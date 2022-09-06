@extends('layouts.app')

@section('title', 'Daftar Retur')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('retur.index') }}">Daftar Retur</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <button type="button" class="btn btn-primary mb-3 p-2" onclick="tambahretur()">
                    <i class="fa fa-plus-circle"> Tambah Retur</i>
                </button>
                <x-table class="tabel-retur">
                    <x-slot name="thead">
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Keterangan</th>
                        <th>Kasir</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </x-slot>

                </x-table>
            </x-card>
        </div>
    </div>

    @includeIf('retur.detail')
    @includeIf('retur.cek_penjualan')

@endsection

@includeIf('includes.datatables')
@includeIf('includes.sweetalert2')
@includeIf('includes.sweetalert2')

@push('script')
    <script>
        let table, table1;
        $(function() {
            table = $('.tabel-retur').DataTable({
                processing: true,
                autoWidth: false,
                "order": [
                    [0, "asc"]
                ],
                ajax: {
                    url: '{{ route('retur.data') }}',
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
                        data: 'kasir',
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
                        data: 'jumlah_lama',
                    },
                    {
                        data: 'jumlah',
                    },
                    {
                        data: 'barang_berkurang',
                    },
                    {
                        data: 'subtotal',
                    },
                ],
            });

        });

        function tambahretur() {
            $('#id_faktur').val('');
            $('#modal-cek_penjualan').modal('show');
        }

        function cekpenjualan() {
            if ($('#id_faktur').val() == '') {
                sweetalertku('Field ID Faktur Tidak Boleh Kosong !!', 'error', 'error');
                return;
            }
            let kode = $('#id_faktur').val();
            $.get(`{{ url('/retur/cekretur') }}/${kode} `)
                .done(response => {
                    if (response.type == 'error') {
                        sweetalertku(response.message, response.type, response.type);
                    } else if (response.type == 'success') {
                        location.href = response.kode;
                    }
                })
                .fail(errors => {
                    sweetalertku('Terjadi Kesalahan', 'error');
                })
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
                            table.ajax.reload();
                            sweetalertku('Data Berhasil DI Hapus', 'success', 'success');
                        })
                        .fail((errors) => {
                            sweetalertku('Tidak dapat menghapus data', 'error', 'error');
                            return;
                        });
                }
            });
        }

        function sweetalertku(message, type, title) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
                confirmButtonText: 'OK'
            })
        }
    </script>
@endpush
