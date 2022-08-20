<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Retur;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanPendapatanController extends Controller
{
    public function index(Request $request)
    {
        $appname = Setting::first()->value('nama_app');
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir != "") {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('laporan.index', compact('tanggalAwal', 'tanggalAkhir', 'appname'));
    }


    public function getData($awal, $akhir)
    {
        $no = 1;
        $data = array();
        $pendapatan = 0;
        $total_pendapatan = 0;


        while (strtotime($awal) <= strtotime($akhir)) {
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

            $total_penjualan = Penjualan::whereDate('created_at', 'like', "%$tanggal%")->where('retur', 'Tidak')->sum('bayar');
            $total_pembelian = Pembelian::whereDate('created_at', 'like', "%$tanggal%")->sum('bayar');
            $total_barang_keluar = BarangKeluar::whereDate('created_at', 'like', "%$tanggal%")->sum('total_harga');
            $total_retur = Retur::whereDate('created_at', 'like', "%$tanggal%")->sum('total_harga');

            $pendapatan = $total_pembelian - (($total_penjualan + $total_retur) - $total_barang_keluar);
            $total_pendapatan += $pendapatan;

            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = tanggal_indonesia($tanggal, false);
            $row['penjualan'] = format_uang($total_penjualan);
            $row['pembelian'] = format_uang($total_pembelian);
            $row['barang_keluar'] = format_uang($total_barang_keluar);
            $row['retur'] = format_uang($total_retur);
            $row['pendapatan'] = format_uang($pendapatan);

            if ($row['penjualan'] == 0 && $row['pembelian'] == 0 && $row['barang_keluar'] == 0  && $row['retur'] == 0  && $row['pendapatan'] == 0) {
            } else {
                $data[] = $row;
            }
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'penjualan' => '',
            'pembelian' => '',
            'barang_keluar' => '',
            'retur' => 'Total Pendapatan',
            'pendapatan' => format_uang($total_pendapatan),
        ];

        return $data;
    }
    public function data($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);

        return datatables()
            ->of($data)
            ->make(true);;
    }

    public function exportPDF($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);
        $pdf = Pdf::loadView('laporan.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('Laporan-Pendapatan-' . date('Y-m-d-His') . '.pdf');
    }
}
