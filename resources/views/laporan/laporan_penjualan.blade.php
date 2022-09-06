<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Penjualan</title>

    <link rel="stylesheet" href="{{ public_path('bootstrap-4.6.2/css/bootstrap.css') }}">
</head>

<body>
    <h3 class="text-center">Laporan Penjualan</h3>
    <h4 class="text-center">
        Tanggal {{ tanggal_indonesia($awal, false) }}
        s/d
        Tanggal {{ tanggal_indonesia($akhir, false) }}
    </h4>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>Total Item</th>
                <th>Total Harga</th>
                <th>Diskon</th>
                <th>Total Bayar</th>
                <th>Staff</th>
            </tr>
        </thead>
        <tbody>
            {{-- @foreach ($data as $row)
                <tr>
                    <td>{{ $row->DT_RowIndex }}</td>
            <td>{{ $row->supplier }}</td>
            <td>{{ $row->total_item }}</td>
            <td>{{ $row->total_harga }}</td>
            <td>{{ $row->diskon }}</td>
            <td>{{ $row->total_bayar }}</td>
            <td>{{ $row->staf }}</td>
            </tr>
            @endforeach --}}

            @foreach ($data as $row)
                <tr>
                    @foreach ($row as $col)
                        <td>{{ $col }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <script src="{{ public_path('bootstrap-4.6.2/js/bootstrap.js') }}"></script>
</body>

</html>
