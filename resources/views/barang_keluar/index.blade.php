@extends('layouts.app')

@section('title', 'Daftar Barang Keluar')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('barangkeluar.index') }}">Daftar Barang Keluar</a></li>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    {{-- <button onclick="addForm()" class="btn btn-primary"><i class="fa fa-plus-circle">
                            Transasksi Baru</i></button> --}}
                    @if (auth()->user()->role_id == 2)
                        <a href="{{ route('barangkeluar.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus-circle"> Tambah Barang Keluar</i>
                        </a>
                    @endif
                    {{-- @empty(!session('id_barang_keluar'))
                        <a href="{{ route('barang_keluar_detail.index') }}" class="btn btn-info "><i class="fa fa-pencil">
                                Transasksi Aktif</i></a>
                    @endempty --}}
                </x-slot>

                <x-table class="table-barang_keluar">
                    <x-slot name="thead">
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="15%">Total Item</th>
                        <th width="15%">Total Harga</th>
                        <th>Keterangan</th>
                        <th width="15%">Staf</th>
                        <th width="10%"><i class="fa fa-cog"></i></th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>

    {{-- @includeIf('barang_keluar.supplier') --}}
    @includeIf('barang_keluar.detail')
@endsection

@includeIf('includes.datatables')
@includeIf('includes.sweetalert2')

@push('script')
    <script>
        let table, table1;
        $(function() {
            table = $('.table-barang_keluar').DataTable({
                processing: true,
                autoWidth: false,
                "order": [
                    [0, "asc"]
                ],
                ajax: {
                    url: '{{ route('barangkeluar.data') }}',
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
                        data: 'keterangan',
                    },
                    {
                        data: 'user',
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
            //$('#modal-supplier').modal('show');
            //get
        }

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
                            sweetalertku('Data Berhasil DI Hapus', 'success', 'success');
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            sweetalertku('Tidak dapat menghapus data', 'error', 'error');
                            // alert('Tidak dapat menyimpan data');
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
