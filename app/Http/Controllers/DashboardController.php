<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Pembelian;
use App\Models\Pengiriman;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Retur;
use App\Models\Satuan;
use App\Models\Setting;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tanggal = date('Y-m-d');
        $kategori = Kategori::count();
        $satuan = Satuan::count();
        $barang = Produk::count();
        $supplier = Supplier::count();
        $pelanggan = Pelanggan::count();
        $pembelian = Pembelian::count();
        $penjualan = Penjualan::count();
        $pengiriman = Pengiriman::count();
        // $barang_keluar = BarangKeluar::count();
        // $retur = Retur::count();

        $harian_penjualan_angka = Penjualan::whereDate('created_at', date('Y-m-d'))->count();
        $total_pendapatan = 0;
        $pendapatan = 0;
        $bulan = 1;
        $array_penjualan = array();

        // $tanggal = date('Y-m-d');
        // $total_penjualan = Penjualan::whereDate('created_at', 'like', "%$tanggal%")->where('retur', 'Tidak')->sum('bayar');
        // $total_pembelian = Pembelian::whereDate('created_at', 'like', "%$tanggal%")->sum('bayar');
        // $total_barang_keluar = BarangKeluar::whereDate('created_at', 'like', "%$tanggal%")->sum('total_harga');
        // $total_retur = Retur::whereDate('created_at', 'like', "%$tanggal%")->sum('total_harga');

        // $pendapatan = $total_pembelian - (($total_penjualan + $total_retur)  - $total_barang_keluar);
        // $total_pendapatan += $pendapatan;

        $harian_penjualan = Penjualan::whereDate('created_at', 'like', "%$tanggal%")->where('retur', 'Tidak')->sum('total_harga');
        $harian_pembelian = Pembelian::whereDate('created_at', 'like', "%$tanggal%")->sum('total_harga');
        $harian_barang_keluar = BarangKeluar::whereDate('created_at', 'like', "%$tanggal%")->sum('total_harga');
        while ($bulan <= 12) {
            $data_penjualan = Penjualan::whereBetween('created_at', [date('Y-m-d', mktime(0, 0, 0, $bulan, 1, date('Y'))),  date('Y-m-d', mktime(0, 0, 0, $bulan, 31, date('Y')))])->sum('total_harga');
            $array_penjualan[] = $data_penjualan;
            $bulan++;
        }
        $data_penjualan_cart = '';
        for ($i = 0; $i < count($array_penjualan); $i++) {

            $data_penjualan_cart .= $array_penjualan[$i] . ', ';
        }
        // dd($array_penjualan, $bulan, $data_penjualan_cart);

        $barang_data = array();
        $databarang = Produk::get();
        foreach ($databarang as $item) {
            $barang_penjualan = PenjualanDetail::where('id_produk', $item->id_produk)->whereBetween('created_at', [date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y'))),  date('Y-m-d', mktime(0, 0, 0, date('m'), 31, date('Y')))])->sum('jumlah');
            $barang_data[$item->nama_barang] = $barang_penjualan;
        }
        $index = 1;
        arsort($barang_data);
        $appname = Setting::first()->value('nama_app');

        if (auth()->user()->role_id == 1) {
            return view('dashboard.index', compact(
                'appname',
                // 'total_pendapatan',
                'harian_penjualan',
                'harian_pembelian',
                'harian_barang_keluar',
                'data_penjualan_cart',
                'penjualan',
                'barang_data',
                'index',
                'kategori',
                'satuan',
                'barang',
                'supplier',
                'pelanggan',
                'harian_penjualan_angka',
                'pembelian',
                'pengiriman',
                // 'retur',
            ));
        } else if (auth()->user()->role_id == 2) {
            return view('dashboard.dashboard_gudang', compact(
                'appname',
                // 'total_pendapatan',
                'barang_data',
                'index',
                'kategori',
                'satuan',
                'barang',
                'supplier',
                // 'retur',
            ));
        } else if (auth()->user()->role_id == 3) {
            return view('dashboard.dashboard_kasir', compact('appname'));
        }
    }

    public function cekstok()
    {
        $produk = Produk::where('stok', '<=', 10)->get();
        // return $produk->user->name;
        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('stok', function ($produk) {
                return format_uang($produk->stok);
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
