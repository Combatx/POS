<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluarDetail;
use App\Models\PembelianDetail;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\ReturDetail;
use App\Models\Setting;
use Illuminate\Http\Request;

class KartuStokController extends Controller
{
    public function index($kode)
    {
        $appname = Setting::first()->value('nama_app');
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        $barang = Produk::with(['kategori', 'satuan'])->where('kode_barang', $kode)->first();
        return view('kartu_stok.index', compact('appname', 'tanggalAwal', 'tanggalAkhir', 'barang'));
    }

    public function getData($kode, $awal, $akhir)
    {
        $no = 1;
        $data = array();
        $masuk = 0;
        $keluar = 0;
        $sisa = 0;
        $total_sisa = 0;


        while (strtotime($awal) <= strtotime($akhir)) {
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

            $total_penjualan = PenjualanDetail::with('produk')->where('id_produk', $kode)->whereDate('created_at', 'like', "%$tanggal%")->where('retur', 'Tidak')->sum('jumlah');
            $total_pembelian = PembelianDetail::with('produk')->where('id_produk', $kode)->whereDate('created_at', 'like', "%$tanggal%")->sum('jumlah');
            $total_barang_keluar = BarangKeluarDetail::with('produk')->where('id_produk', $kode)->whereDate('created_at', 'like', "%$tanggal%")->sum('jumlah');
            $total_retur = ReturDetail::with('produk')->where('id_produk', $kode)->whereDate('created_at', 'like', "%$tanggal%")->sum('jumlah');

            $penjualan = PenjualanDetail::with('produk')->where('id_produk', $kode)->whereDate('created_at', 'like', "%$tanggal%")->where('retur', 'Tidak')->first();
            $pembelian = PembelianDetail::with('produk')->where('id_produk', $kode)->whereDate('created_at', 'like', "%$tanggal%")->first();
            $barang_keluar = BarangKeluarDetail::with('produk')->where('id_produk', $kode)->whereDate('created_at', 'like', "%$tanggal%")->first();
            $retur = ReturDetail::with('produk')->where('id_produk', $kode)->whereDate('created_at', 'like', "%$tanggal%")->first();

            $masuk += $total_pembelian;
            $keluar += ($total_penjualan + $total_barang_keluar + $total_retur);
            $sisa = $masuk - $keluar;
            $total_sisa += $sisa;

            if ($total_penjualan != 0) {
                // $row = array();
                // $row['DT_RowIndex'] = $no++;
                // $row['tanggal'] = tanggal_indonesia($tanggal, false);
                // $row['no_bukti'] = format_uang($total_penjualan);
                // $row['keterangan'] = format_uang($total_pembelian);
                // $row['masuk'] = format_uang($total_barang_keluar);
                // $row['keluar'] = format_uang($total_retur);
                // $row['sisa'] = format_uang($pendapatan);
                // $row['bagian'] = format_uang($pendapatan);
                // $data[] = $row;
            }
            $row = array();
            // $row['DT_RowIndex'] = $no++;
            // $row['tanggal'] = tanggal_indonesia($tanggal, false);
            // $row['no_bukti'] = format_uang($total_penjualan);
            // $row['keterangan'] = format_uang($total_pembelian);
            // $row['masuk'] = format_uang($total_barang_keluar);
            // $row['keluar'] = format_uang($total_retur);
            // $row['sisa'] = format_uang($pendapatan);
            // $row['bagian'] = format_uang($pendapatan);

            // if ($row['penjualan'] == 0 && $row['pembelian'] == 0 && $row['barang_keluar'] == 0  && $row['retur'] == 0  && $row['pendapatan'] == 0) {
            // } else {
            //     $data[] = $row;
            // }
        }

        // $data[] = [
        //     'DT_RowIndex' => '',
        //     'tanggal' => '',
        //     'penjualan' => '',
        //     'pembelian' => '',
        //     'barang_keluar' => '',
        //     'retur' => 'Total Pendapatan',
        //     'pendapatan' => format_uang($total_pendapatan),
        // ];

        // return $data;
    }
    // public function data($awal, $akhir)
    // {
    //     $data = $this->getData($awal, $akhir);

    //     return datatables()
    //         ->of($data)
    //         ->make(true);;
    // }
}
