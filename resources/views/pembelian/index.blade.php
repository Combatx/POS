@extends('layouts.app')

@section('title', 'Daftar Pembelian')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('pembelian.index') }}">Daftar Pembelian</a></li>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <button onclick="addForm()" class="btn btn-primary"><i class="fa fa-plus-circle">
                            Transasksi Baru</i></button>
                    @empty(!session('id_pembelian'))
                        <a href="{{ route('pembelian_detail.index') }}" class="btn btn-info "><i class="fa fa-pencil">
                                Transasksi Aktif</i></a>
                    @endempty
                </x-slot>

                <x-table class="table-pembelian">
                    <x-slot name="thead">
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Diskon</th>
                        <th>Total Bayar</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>

    @includeIf('pembelian.supplier')
    @includeIf('pembelian.detail')
@endsection

@includeIf('includes.datatables')
@push('script')
    <script>
        let table, table1;
        $(function() {
            table = $('.table-pembelian').DataTable({
                processing: true,
                autoWidth: false,
                "order": [
                    [2, "asc"]
                ],
                ajax: {
                    url: '{{ route('pembelian.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                    },
                    {
                        data: 'tanggal',
                    },
                    {
                        data: 'supplier',
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
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

            $('.table-supplier').DataTable();

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
                        data: 'harga_beli',
                    },
                    {
                        data: 'jumlah',
                    },
                    {
                        data: 'subtotal',
                    },
                ],
            });

        });

        function addForm(url) {
            $('#modal-supplier').modal('show');
        }

        function showDetail(url) {
            $('#modal-detail').modal('show');

            table1.ajax.url(url);
            table1.ajax.reload();
        }

        function deleteData(url) {
            if (confirm('Apakah anda yakin ingin menghapus data?')) {
                // event.preventDefault();
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        }
    </script>
@endpush
