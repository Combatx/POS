@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
@endsection


@section('content')
    <!-- Small boxes (Stat box) -->

    {{-- Baris 2 --}}
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-cube"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Kategori</span>
                    <span class="info-box-number">{{ $kategori }}</span>
                </div>

            </div>

        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-balance-scale"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Satuan</span>
                    <span class="info-box-number">{{ $satuan }}</span>
                </div>

            </div>

        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-boxes"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Barang</span>
                    <span class="info-box-number">{{ $barang }}</span>
                </div>

            </div>

        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-truck"></i></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Supplier</span>
                    <span class="info-box-number">{{ $supplier }}</span>
                </div>

            </div>

        </div>

    </div>


    <!-- Main row -->
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 connectedSortable">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between mb-3">
                                <h3 style="font-weight: bold">Stok Produk Limit</h3>

                                <button onclick="refreshstok()"
                                    style="border: 0; text-decoration: none; background: none;"><i
                                        class="fas fa-sync"></i></button>
                            </div>

                            <table class="table table-striped table-cekstok">
                                <thead class="bg-dark">
                                    <th width="10%">No</th>
                                    <th>Nama Barang</th>
                                    <th width="20%">Stok</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 style="font-weight: bold">Penjualan Produk Tertinggi Bulan {{ date('F') }}</h3>
                        </div>

                        <div class="card-body">
                            <table class="table table-striped table-penjualan">
                                <thead class="bg-success">
                                    <th width="10%">No</th>
                                    <th>Nama Barang</th>
                                    <th width="20%">Jumlah</th>
                                </thead>
                                <tbody>
                                    @foreach (array_slice($barang_data, 0, 10) as $key => $item)
                                        <tr>
                                            <td>{{ $index++ }}</td>
                                            <td>{{ $key }}</td>
                                            <td>{{ $item }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.Left col -->
    </div>
    <!-- /.row (main row) -->
@endsection

@includeIf('includes.datatables')


{{-- @push('script_vendor')
    <script src="{{ asset('javascript/chartjs/chartjs.js') }}"></script>
@endpush --}}

@push('script')
    {{-- <script>
        const labels = [
            'Januari',
            'Febuari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agutus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        const data = {
            labels: labels,
            datasets: [{
                label: 'Grafik Penjualan',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: [{{ $data_penjualan_cart }}],
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {}
        };
    </script>

    <script>
        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script> --}}

    <script>
        let table1;
        $(function() {
            table1 = $('.table-cekstok').DataTable({
                processing: true,
                autoWidth: false,
                "order": [
                    [0, "asc"]
                ],
                ajax: {
                    url: '{{ route('dashboard.cekstok') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                    },

                    {
                        data: 'nama_barang',
                    },
                    {
                        data: 'stok',
                    },
                ]
            })
        });

        function refreshstok() {
            table1.ajax.reload();
        }
    </script>

    {{-- <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('AdminLTE/dist/js/demo.js') }}"></script> --}}
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('AdminLTE/dist/js/pages/dashboard.js') }}"></script>
@endpush
