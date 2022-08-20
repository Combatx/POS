@extends('layouts.app')

@section('title', 'Daftar Penjualan')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Daftar Penjualan</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-table class="table-penjualan">
                    <x-slot name="thead">
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Diskon</th>
                        <th>Total Bayar</th>
                        <th>Kasir</th>
                        <th>Retur</th>
                        <th width="10%"><i class="fa fa-cog"></i></th>
                    </x-slot>

                </x-table>
            </x-card>
        </div>
    </div>

    @includeIf('penjualan.detail')

@endsection

@includeIf('includes.datatables')
@includeIf('includes.sweetalert2')

@push('script')
    <script>
        let table, table1;
        $(function() {
            table = $('.table-penjualan').DataTable({
                processing: true,
                autoWidth: false,
                "order": [
                    [0, "asc"]
                ],
                ajax: {
                    url: '{{ route('penjualan.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                    },
                    {
                        data: 'tanggal',
                    },
                    {
                        data: 'total_item',
                    },
                    {
                        data: 'total_harga',
                    },
                    {
                        data: 'diskon',
                    },
                    {
                        data: 'bayar',
                    },
                    {
                        data: 'kasir',
                    },
                    {
                        data: 'retur',
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ]
            });


            table1 = $('.table-detail').DataTable({
                processing: true,
                bSort: false,
                dom: 'Brt',
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
                        data: 'harga_jual',
                    },
                    {
                        data: 'jumlah',
                    },
                    {
                        data: 'diskon',
                    },
                    {
                        data: 'subtotal',
                    },
                ],
            });

        });

        function showDetail(url) {
            $('#modal-detail').modal('show');

            table1.ajax.url(url);
            table1.ajax.reload();
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
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            sweetalertku('Tidak dapat menghapus data', 'error', 'error');
                            return;
                        });
                }
            });
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
