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
                    <x-table>
                        <thead>
                            <tr>
                                <td>Supplier</td>
                                <td>: {{ $supplier->nama }}</td>
                            </tr>
                            <tr>
                                <td>Telepon</td>
                                <td>: {{ $supplier->telepon }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>{{ $supplier->alamat }}</td>
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
                                <input type="text" class="form-control" name="kode_barang" id="kode_barang">
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
@endsection

@includeIf('includes.datatables')
@includeIf('includes.jquery-mask')

@push('script')
    <script>
        let table, table2;
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

            $(document).on('input', '.quantity', function() {
                let id = $(this).data('id');
                let jumlah = parseInt($(this).val());

                if (jumlah < 1) {
                    $(this).val(1);
                    alert('Jumlah tidak boleh kurang dari 1');
                    return;
                }
                if (jumlah > 10000) {
                    alert('Jumlah tidak boleh lebih dari 10000');
                    $(this).val(1000);
                    return;
                }

                $.post(`{{ url('/pembelian_detail') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'jumlah': jumlah,
                    })
                    .done(response => {
                        $(this).on('mouseout', function() {
                            table.ajax.reload(() => loadForm($('#diskon').cleanVal()));
                        })
                    }).fail(errors => {
                        alert('Tidak dapat menyimpan data?');
                        return;
                    });
            });

            $(document).on('input', '#diskon', function() {
                if ($(this).val() == "") {
                    $(this).val().select();
                }
                loadForm($(this).cleanVal());
            });

            $('.btn-simpan').on('click', function() {
                $('.form-pembelian').submit();
            })

            inputMask();
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
            $('#kode_barang').val(kode);
            hideProduk();
            tambahProduk();
        }

        function tambahProduk() {
            $.post('{{ route('pembelian_detail.store') }}', $('.form-produk').serialize())
                .done(response => {
                    $('#kode_barang').focus();
                    table.ajax.reload(() => loadForm($('#diskon').cleanVal()));
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        }

        function deleteData(url) {
            if (confirm('Apakah anda yakin ingin menghapus data?')) {
                // event.preventDefault();
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload(() => loadForm($('#diskon').cleanVal()));
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
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
                    alert('Tidak dapat menampikan data');
                    return;
                })
        }
    </script>
@endpush
