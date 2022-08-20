@extends('layouts.app')

@section('title', 'Transaksi Pembelian')

@push('css')
    <style>
        .tampil-bayar {
            font-size: 5em;
            text-align: center;
            height: 100px;
        }

        .tampil-terbilang {
            padding: 10px;
            background: #f0f0f0;

        }

        .table-pembelian tbody tr:last-child {
            display: none;
        }

        @media(max-width: 768px) {
            .tampil-bayar {
                font-size: 3em;
                height: 70px;
                padding-top: 5px;

            }
        }
    </style>
@endpush

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="#">Transakasi Pembelian</a></li>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <x-card>
                <x-slot name="header">
                    <p><span class="text-danger font-weight-bold" style="font-size: 25px;">Mohon untuk memilih Supplier
                            !!!</span></p>
                    <button onclick="showsupplier()" class="btn btn-success"><i class="fas fa-search"></i> Pilih
                        Supplier</button>
                    <br>
                    {{-- <label for="suppliertest"><span class="text-danger">Mohon untuk memilih Supplier !</span></label>
                    <select style="width: 400px;" name="suppliertest" id="suppliertest" class="suppliertest"
                        onchange="getsupplier(this.selectedOptions[0].value)">
                        <option selected disabled>Pilih Supplier</option>
                        @foreach ($suppliershow as $item)
                            <option value="{{ $item->id_supplier }}">{{ $item->nama }}
                            </option>
                        @endforeach
                    </select> --}}
                    <x-table>
                        <thead>
                            <tr>
                                <td width=30%>Supplier</td>
                                <td width=70%>: <span id="supplier_nama">-</span>
                                </td>
                            </tr>
                            <tr>
                                <td width=30%>Telepon</td>
                                <td width=70%>: <span id="supplier_telepon">-</span></td>
                            </tr>
                            <tr>
                                <td width=30%>Alamat</td>
                                <td width=70%>: <span id="supplier_alamat">-</span></td>
                            </tr>
                        </thead>
                    </x-table>
                </x-slot>

                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_barang" class="col-lg-2">Kode Produk</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_pembelian" id="id_pembelian" value="{{ $id_pembelian }}">
                                <input type="hidden" name="id_produk" id="id_produk">
                                <input type="text" class="form-control" name="kode_barang" id="kode_barang"
                                    oninput="cekkodebarang()">
                                <input type="hidden" name="cekkondisi" id="cekkondisi">
                                <span class="input-group-btn">
                                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i
                                            class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>

                    </div>
                </form>

                <x-table class="table-pembelian mb-3">
                    <x-slot name="thead">
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th width="15%">Jumlah</th>
                        <th>Sub Total</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </x-slot>
                </x-table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('pembelian.store') }}" class="form-pembelian" method="post">
                            @csrf
                            <input type="hidden" name="id_pembelian" value="{{ $id_pembelian }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="bayar" id="bayar">
                            <input type="hidden" name="id_supplier" id="id_supplier">

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                                <div class="col-lg-8">
                                    <input type="text" name="diskon" id="diskon" class="form-control"
                                        value="{{ $diskon }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                                <div class="col-lg-8">
                                    <input type="text" id="bayarrp" class="form-control" readonly>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <x-slot name="footer">
                    <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i
                            class="fa fa-floppy-o"> Simpan Transaksi</i></button>
                </x-slot>
            </x-card>
        </div>
    </div>
    @includeIf('pembelian_detail.produk')
    @includeIf('pembelian_detail.supplier')

@endsection

@includeIf('includes.datatables')
@includeIf('includes.jquery-mask')
@includeIf('includes.select2')
@includeIf('includes.sweetalert2')

@push('script')
    <script>
        let table, table2, tablesupplier;
        let modal = '#modal-form';
        $(function() {
            $('body').addClass('sidebar-closed sidebar-collapse');
            table = $('.table-pembelian').DataTable({
                    processing: true,
                    autoWidth: false,
                    "order": [
                        [0, "asc"]
                    ],
                    ajax: {
                        url: '{{ route('pembelian_detail.data', $id_pembelian) }}',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            searchable: false,
                        },
                        {
                            data: 'kode_barang',
                        },
                        {
                            data: 'nama_barang',
                        },
                        {
                            data: 'harga_beli',
                        },
                        {
                            data: 'jumlah',
                        },
                        {
                            data: 'subtotal',
                        },
                        {
                            data: 'aksi',
                            searchable: false,
                            sortable: false
                        },
                    ],
                    dom: 'Brt',
                    bSort: false,
                })
                .on('draw.dt', function() {
                    loadForm($('#diskon').cleanVal());
                });

            table2 = $('.table-produk').DataTable();


            $(document).on('click', '.quantity', function() {
                $(this).select();
            });

            $(document).on('input', '.quantity', function() {
                // console.log($(this).val());
                let jumlah = parseInt($(this).val());
                // jumlah.select();
                let id = $(this).data('id');

                if (jumlah < 1) {
                    $(this).val(1);
                    sweetalertku('Jumlah tidak boleh kurang dari 1', 'error', 'error');
                    // alert('Jumlah tidak boleh kurang dari 1');
                    return;
                }
                if (jumlah > 10000) {
                    sweetalertku('Jumlah tidak boleh lebih dari 10000', 'error', 'error');
                    // alert('Jumlah tidak boleh lebih dari 10000');
                    $(this).val(1000);
                    return;
                }
                // setTimeout(function() {
                $.post(`{{ url('/pembelian_detail') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'jumlah': jumlah,
                    })
                    .done(response => {
                        // $(this).on('mouseout', function() {
                        table.ajax.reload(() => loadForm($('#diskon')
                            .cleanVal()));
                        $('#kode_barang').focus();
                    }).fail(errors => {
                        sweetalertku('Terjadi Kesalahan, Tidak dapat Menyimpan Data',
                            'error',
                            'error');
                        $('#kode_barang').focus();
                        return;
                    });
                // }, 1000);
            });

            $(document).on('click', '#diskon', function() {
                $(this).select();
            });

            $(document).on('input', '#diskon', function() {
                if ($(this).val() == "") {
                    $(this).val().select();
                }
                loadForm($(this).cleanVal());
            });

            $('.btn-simpan').on('click', function() {
                ceksupplier();
                $('.form-pembelian').submit();
            })

            $('.suppliertest').select2();
            inputMask();
        });



        tablesupplier = $('.table-supplier').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('pembelian_detail.data_supplier') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false,
                },
                {
                    data: 'nama'
                },
                {
                    data: 'alamat'
                },
                {
                    data: 'telepon'
                },
                {
                    data: 'action',
                    searchable: false,
                    sortable: false,
                },
            ]
        });


        function getsupplier(id) {
            $.get(`{{ url('/pembelian_detail/getsupplier') }}/${id} `)
                .done(response => {
                    console.log(response.nama);
                    document.getElementById("supplier_nama").innerHTML = response.nama;
                    document.getElementById("supplier_telepon").innerHTML = response.telepon;
                    document.getElementById("supplier_alamat").innerHTML = response.alamat;
                    $('#id_supplier').val(response.id_supplier);
                    $('#modal-supplier').modal('hide');
                    $('#kode_barang').focus();
                })
                .fail(errors => {
                    sweetalertku('Tidak Dapat Mendapatkan Data Supplier', 'error', 'error');
                })
        }

        function inputMask() {
            $('#diskon').mask('#.##0', {
                reverse: true
            });
        }

        function tampilProduk(url) {
            $('#modal-produk').modal('show');
        }

        function hideProduk() {
            $('#modal-produk').modal('hide');
        }

        // function blokall() {
        //     // $('.quantity').on('click', function() {
        //     console.log('fff');
        //     $(this).select();
        //     // });
        // }


        function pilihProduk(id, kode) {
            $('#id_produk').val(id);
            // $('#kode_barang').val(kode);
            $('#cekkondisi').val('pilihbarang');
            hideProduk();
            tambahProduk();
        }

        function tambahProduk() {
            $.post('{{ route('pembelian_detail.store') }}', $('.form-produk').serialize())
                .done(response => {
                    $('#kode_barang').focus();
                    table.ajax.reload(() => loadForm($('#diskon').cleanVal()));
                    $('#kode_barang').val('');
                    $('#kode_barang').focus();
                })
                .fail(errors => {
                    if (errors.responseJSON.cek == 'fail') {
                        sweetalertku(errors.responseJSON.message, 'error', 'error');
                        // $('#kode_barang').val('');
                        $('#kode_barang').focus();
                    } else {
                        sweetalertku('Tidak dapat menyimpan data', 'error', 'error');
                        // $('#kode_barang').val('');
                        $('#kode_barang').focus();
                    }
                    return;
                });
        }

        function deleteData(url) {
            Swal.fire({
                title: 'Delete',
                text: "Apakah Kamu Ingin Menghapus Data Ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    // event.preventDefault();
                    $.post(url, {
                            '_token': $('[name=csrf-token]').attr('content'),
                            '_method': 'delete'
                        })
                        .done((response) => {
                            sweetalertku('Data Berhasil Di Hapus', 'success', 'success');
                            table.ajax.reload(() => loadForm($('#diskon').cleanVal()));
                            $('#kode_barang').focus();
                        })
                        .fail((errors) => {
                            sweetalertku('Tidak dapat Menyimpan Data', 'error', 'error');
                            $('#kode_barang').focus();
                            return;
                        });
                }
            });
        }

        function cekkodebarang() {
            $('#id_produk').val(null);
            $('#cekkondisi').val('cekkode');
            if ($('#kode_barang').val() == '') {
                return;
            } else {
                tambahProduk();
            }
            // preventDefault();
        }

        function loadForm(diskon = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/pembelian_detail/loadform') }}/${diskon}/${$('.total').text()}`)
                .done(response => {
                    $('#totalrp').val('Rp. ' + response.totalrp);
                    $('#bayarrp').val('Rp. ' + response.bayarrp);
                    $('#bayar').val(response.bayar);
                    $('.tampil-bayar').text('Rp. ' + response.bayarrp);
                    $('.tampil-terbilang').text(response.terbilang);

                })
                .fail(errors => {
                    sweetalertku('Tidak dapat Menampilkan Data', 'error', 'error');
                    return;
                })
        }

        function ceksupplier() {
            if ($('#id_supplier').val() == '' || $('#id_supplier').val() == null) {
                sweetalertku('Pilih Supplier Terlibih Dahulu !!!', 'error', 'error');
                preventDefault();
            }
            return;
        }

        function showsupplier() {
            //console.log('fsdf');
            $('#modal-supplier').modal('show');
        }

        function refreshsupplier() {
            tablesupplier.ajax.reload();
        }

        function sweetalertku(message, title, type) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
                confirmButtonText: 'OK'
            })
        }
    </script>
@endpush
