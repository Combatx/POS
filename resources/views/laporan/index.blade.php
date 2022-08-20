@extends('layouts.app')

@section('title')
    Laporan Pendapatan {{ tanggal_indonesia($tanggalAwal, false) }} s/d {{ tanggal_indonesia($tanggalAkhir, false) }}
@endsection


@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="#">Laporan</a></li>
@endsection

@section('content')
    <!-- Main row -->
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="updatePeriode()" class="btn btn-info"><i class="fa fa-plus-circle">
                            Ubah Periode</i></button>
                    <a href="{{ route('laporan.export_pdf', [$tanggalAwal, $tanggalAkhir]) }}" target="_blank"
                        onclick="updatePeriode()" class="btn btn-danger"><i class="fa fa-file-pdf-o">
                            Export PDF</i></a>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Penjualan</th>
                            <th>Pembelian</th>
                            <th>Barang Keluar</th>
                            <th>Retur</th>
                            <th>Pendapatan</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('laporan.form')
    @includeIf('includes.datatables')
    @includeIf('includes.datetimepicker')
@endsection
@push('script')
    <script>
        let table;
        $(function() {
            table = $('.table').DataTable({
                processing: true,
                autoWidth: false,
                "order": [
                    [0, "asc"]
                ],
                ajax: {
                    url: '{{ route('laporan.data', [$tanggalAwal, $tanggalAkhir]) }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                    },
                    {
                        data: 'tanggal',
                    },
                    {
                        data: 'penjualan',
                    },
                    {
                        data: 'pembelian',
                    },
                    {
                        data: 'barang_keluar',
                    },
                    {
                        data: 'retur',
                    },
                    {
                        data: 'pendapatan',
                    },
                ],
                dom: 'Brt',
                bSort: false,
                bPaginate: false,
            });


        });

        // $('.datetimepicker-input').datetimepicker({
        //     format: 'yyyy-mm-dd',
        //     autoclose: true,
        // });

        // function aa() {
        //     console.log($('#tanggal_awal').val());
        //     console.log($('#tanggal_akhir').val());
        // }

        function updatePeriode() {
            $('#modal-form').modal('show');
        }
    </script>
@endpush
