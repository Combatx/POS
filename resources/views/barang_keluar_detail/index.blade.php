@extends('layouts.app')

@section('title', 'Transaksi Barang Keluar')

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

        .table-barang_keluar_detail tbody tr:last-child {
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
    <li class="breadcrumb-item"><a href="#">Transakasi Barang Keluar</a></li>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <x-card>
                <x-slot name="header">
                    <h2>Barang Keluar</h2>
                </x-slot>

                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_barang" class="col-lg-2">Kode Produk</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_barang_keluar" id="id_barang_keluar"
                                    value="{{ $id_barang_keluar }}">
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

                <x-table class="table-barang_keluar_detail mb-3">
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
                        <form action="{{ route('barangkeluar.store') }}" class="form-barang_keluar" method="post">
                            @csrf
                            <input type="hidden" name="id_barang_keluar" value="{{ $id_barang_keluar }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            {{-- <input type="hidden" name="bayar" id="bayar"> --}}

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-3 control-label">Total</label>
                                <div class="col-lg-9">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keterangan" class="col-lg-3">Keterangan</label>
                                <div class="col-lg-9">
                                    <textarea name="keterangan" class="form-control" id="keterangan" rows="3" required></textarea>
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
    @includeIf('barang_keluar_detail.produk')

@endsection

@includeIf('includes.datatables')
@includeIf('includes.jquery-mask')
@includeIf('includes.select2')
@includeIf('includes.sweetalert2')

@push('script')
    <script>
        let table, table2;
        let modal = '#modal-form';
        $(function() {
            $('body').addClass('sidebar-closed sidebar-collapse');
            table = $('.table-barang_keluar_detail').DataTable({
                    processing: true,
                    autoWidth: false,
                    "order": [
                        [0, "asc"]
                    ],
                    ajax: {
                        url: '{{ route('barang_keluar_detail.data', $id_barang_keluar) }}',
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
                    loadForm();
                });

            table2 = $('.table-produk').DataTable();

            $(document).on('click', '.quantity', function() {
                $(this).select();
            });

            $(document).on('input', '.quantity', function() {
                let id = $(this).data('id');
                let jumlah = parseInt($(this).val());

                if (jumlah < 1) {
                    $(this).val(1);
                    sweetalertku('Jumlah tidak boleh kurang dari 1', 'error', 'error');
                    return;
                }
                if (jumlah > 10000) {
                    sweetalertku('Jumlah tidak boleh lebih dari 10000', 'error', 'error');
                    $(this).val(800);
                    return;
                }

                setTimeout(function() {
                    $.post(`{{ url('/barang_keluar_detail') }}/${id}`, {
                            '_token': $('[name=csrf-token]').attr('content'),
                            '_method': 'put',
                            'jumlah': jumlah,
                        })
                        .done(response => {
                            // $(this).on('mouseout', function() {
                            setTimeout(table.ajax.reload(() => loadForm()), 3000);;
                            $('#kode_barang').focus();
                            // table.ajax.reload();
                            // })
                        }).fail(errors => {
                            if (errors.responseJSON.cek == 'fail') {
                                sweetalertku(errors.responseJSON.message, 'error', 'error');
                                table.ajax.reload(() => loadForm());
                                $('#kode_barang').focus();
                            } else {
                                sweetalertku('Terjadi Kesalahan, Tidak dapat Menyimpan Data',
                                    'error',
                                    'error');
                                $('#kode_barang').focus();
                            }

                            return;
                        });
                }, 1000);
            });

            // $(document).on('input', '#diskon', function() {
            //     if ($(this).val() == "") {
            //         $(this).val().select();
            //     }
            //     loadForm($(this).cleanVal());
            // });

            $('.btn-simpan').on('click', function() {
                $('.form-barang_keluar').submit();
            })

            $('.suppliertest').select2();
            inputMask();
            $('#kode_barang').focus();
        });


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

        function pilihProduk(id, kode) {
            $('#id_produk').val(id);
            // $('#kode_barang').val(kode);
            $('#cekkondisi').val('pilihbarang');
            hideProduk();
            tambahProduk();
        }

        function tambahProduk() {
            $.post('{{ route('barang_keluar_detail.store') }}', $('.form-produk').serialize())
                .done(response => {
                    $('#kode_barang').focus();
                    // sweetalertku('Data Berhasil Di Tambahkan', 'success', 'success');
                    table.ajax.reload(() => loadForm());
                    $('#kode_barang').val('');
                    $('#kode_barang').focus();
                    // table.ajax.reload();
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
                            table.ajax.reload(() => loadForm());
                            $('#kode_barang').focus();
                            // table.ajax.reload();
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

        function loadForm() {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/barang_keluar_detail/loadform/') }}/${$('.total').text()}`)
                .done(response => {
                    $('#totalrp').val('Rp. ' + response.totalrp);
                    $('.tampil-bayar').text('Rp. ' + response.totalrp);
                    $('.tampil-terbilang').text(response.terbilang);

                })
                .fail(errors => {
                    sweetalertku('Tidak dapat Menampilkan Data', 'error', 'error');
                    return;
                })
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
