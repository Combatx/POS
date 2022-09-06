@extends('layouts.app')

@section('title', 'Stok Produk')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="#">Stok Produk</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>

                <x-table>
                    <x-slot name="thead">
                        <th width="5%">No</th>
                        {{-- <th>Kode Barang</th> --}}
                        <th>Nama Barang</th>
                        {{-- <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th> --}}
                        {{-- <th>Diskon</th> --}}
                        <th>Stok</th>
                        {{-- <th width="10%"><i class="fas fa-cog"></i></th> --}}
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>


@endsection

@includeIf('includes.datatables')
@includeIf('includes.jquery-mask')

@push('script')
    <script>
        let table;

        $(document).ready(function() {

            inputMask();

        });

        table = $('.table').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('datastok') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false,
                },
                // {
                //     data: 'kode_barang'
                // },
                {
                    data: 'nama_barang'
                },
                // {
                //     data: 'kategori'
                // },
                // {
                //     data: 'satuan'
                // },
                // {
                //     data: 'harga_beli'
                // },
                // {
                //     data: 'harga_jual'
                // },
                // {
                //     data: 'diskon'
                // },
                {
                    data: 'stok'
                },
                // {
                //     data: 'action',
                //     searchable: false,
                //     sortable: false,
                // },
            ]
        });


        function inputMask() {
            $('#harga_beli').mask('#.##0', {
                reverse: true
            });
            $('#harga_jual').mask('#.##0', {
                reverse: true
            });
            $('#diskon').mask('#.##0', {
                reverse: true
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
