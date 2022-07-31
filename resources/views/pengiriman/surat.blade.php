<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Jalan</title>

    <style>
        .tepi {
            /* margin-top: 50px;
            margin-left: 50px; */
        }

        .font {
            font-size: 14px;
        }

        .tepi-kanan {
            margin-top: 50px;
            margin-right: 50px
        }

        .tebal {
            font-weight: bold;
        }

        table.tablebarang {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .tablebarang th {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .tablebarang td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .text-center {
            text-align: center;
        }

        .bungkus {
            margin-top: 0px;
        }

        .sejajar {
            float: left;
        }
    </style>
</head>

<body>
    <table class="" width="100%">
        <tr>
            <td width="5%">
                <img width="55px" src="{{ public_path('img/logo_cart.png') }}" alt="">

            </td>
            <td style="padding-left: 15px">
                <h2 style="margin: 0">Toko Usaha Baru</h2>
                Jalan Pasar Sebangkau <br>
                Tel. 0988008809 <br>
                Desa Sebatuan, Pemangkat
            </td>
            <td width="10%"></td>
            <td>
                <p style="font-size: 30px">Surat Jalan Barang</p>
            </td>
        </tr>
    </table>


    <hr class="" style="margin-top: 10px; margin-bottom: 0px">

    <table class="">
        <tr>
            <td width="50%">
                <h3 style="font-size: 15px;">Kepada Yth.</h3>
                <div style="font-size: 13px;"><span class="tebal">Nama</span> <span style="margin-left: 45px">:</span>
                    {{ $penjualan->pelanggan->nama }}</div>
            </td>
            <td width="30%">
                <div style="margin-left: 280px"></div>
            </td>
            <td>
                <br> <br>
                <div style="margin-top: 10px;"></div>
                <div style="font-size: 13px;"><span class="tebal">ID Pengiriman</span> <span
                        style="margin-left: 33px">:</span>
                    {{ $pengiriman->id_pengiriman }}</div>
            </td>

        </tr>

        <tr>
            <td>
                <div style="font-size: 13px;"><span class="tebal">No Telepon</span> <span
                        style="margin-left: 15px">:</span> {{ $penjualan->pelanggan->telepon }}
                </div>
            </td>
            <td width="30%">
                <div style="margin-left: 280px"></div>
            </td>
            <td>
                <div style="font-size: 13px;"><span class="tebal">No. Faktur</span> <span
                        style="margin-left: 55px">:</span>
                    {{ $penjualan->id_penjualan }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div style="font-size: 13px;"><span class="tebal">Alamat</span> <span
                        style="margin-left: 37px">:</span> {{ $penjualan->pelanggan->alamat }}</div>
            </td>
            <td width="30%">
                <div style="margin-left: 280px"></div>
            </td>
            <td>
                <div style="font-size: 13px;"><span class="tebal">Tanggal Pemesanan</span> <span
                        style="margin-left:5px">:</span>
                    {{ tanggal_indonesia($pengiriman->created_at, false) }}</div>
            </td>
        </tr>

        {{-- <tr>
            <td></td>
            <td></td>
            <div style="font-size: 13px;"><span class="tebal">Petugas Pengirima</span> <span
                    style="margin-left: 13px">:</span>
                Romin</div>
        </tr> --}}

        {{-- <tr>
            <td></td>
            <td></td>
            <div style="font-size: 13px;"><span class="tebal">Penerima</span> <span style="margin-left: 63px">:</span>
                Dodi</div>
        </tr> --}}
        {{-- <tr>
            <td></td>
            <td></td>
            <div style="font-size: 13px;"><span class="tebal">Tanggal Pengiriman</span> <span
                    style="margin-left: 3px">:</span>
                25-Juni-2022</div>
        </tr> --}}

    </table>

    <hr style="margin-top: 10px; margin-bottom: 10px;">

    <table class="tablebarang text-center" width="100%">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="15%">Kode Barang</th>
                <th width="50%">Nama</th>
                <th width="">Satuan</th>
                <th width="">Jumlah</th>
                <th width="">Dikirim</th>
                <th width="">Keterangan</th>
            </tr>
        </thead>

        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($penjualandetail as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $item->produk->kode_barang }}</td>
                    <td style="text-align: left; padding-left:5px;">{{ $item->produk->nama_barang }}</td>
                    <td>{{ $item->produk->satuan->nama }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ $item->dikirim }}</td>
                    <td></td>
                </tr>
            @endforeach

        </tbody>
    </table>
    <p style="margin: 0; padding-left:20px; margin-top:30px;">Pemangkat, {{ tanggal_indonesia(date('Y-m-d')) }}</p>

    {{-- <div style="margin-top: 30px;" class="ttd">
        <table>
            <tr>
                <td style="padding-left: 30px">Penerima</td>
                <td style="padding-left:400px"> </td>
                <td>Msms</td>
            </tr>
            <tr>
                <td style="padding-top: 40px; padding-left: 15px">( <span
                        style="margin-left: 35px; margin-right: 35px;"> </span> )</td>
            </tr>
        </table>
    </div> --}}

    {{-- <div style="display: flex; margin-top:30px; align-items: stretch;">
        <div style="flex-grow:2">Penerima</div>
        <div style="flex-grow:6">Penerima</div>
        <div style="flex-grow:2">Petugas Pengirim</div>
        <div style="flex-grow:2">Kasir</div>
    </div> --}}


    <div class="bungkus">
        <div class="sejajar">
            <div class="penerima">
                <span style="padding-left: 20px">Penerima</span>
                <br>
                <br>
                <br>
                <br>
                <span style="padding-left: 10px">( </span>
                <span style="padding-left: 65px"> ) </span>
            </div>
        </div>
        <div class="sejajar" style="margin-left: 40%; border: 1px solid black;">
            <div class="penerima">
                <div style="padding-bottom: 5px"> </div>
                <span style="padding-left: 20px; padding-right: 15;">Petugas
                    Pengirim</span>
                <div style="border-bottom: 1px solid black; padding-top:5px;"></div>
                <br>
                <br>
                <br>
                <span style="padding-left: 10px;">( </span>
                <span style="padding-left: 115px;"> ) </span><br>
                <div style="padding-top:10px"></div>
            </div>
        </div>

        <div class="sejajar" style="border: 1px solid black; width: 150px;">
            <div class="penerima" style="text-align: center;">
                <div style="padding-bottom: 5px"> </div>
                <span>Kasir</span>
                <div style="border-bottom: 1px solid black; padding-top:5px;"></div>
                <br>
                <br>
                <br>
                <span style="text-align: center;">( {{ auth()->user()->name }} )</span>
                <div style="padding-top:10px"></div>
            </div>
        </div>
    </div>
    </div>


</body>

</html>
