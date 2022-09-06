<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangKeluarDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $appname = Setting::first()->value('nama_app');
        return view('barang_keluar.index', compact('appname'));
    }

    public function data()
    {
        $barangkeluar = BarangKeluar::orderBy('id_barang_keluar', 'desc')
            ->where('total_item', '!=', 0)
            ->where('total_harga', '!=', 0)
            ->get();

        return datatables()
            ->of($barangkeluar)
            ->addIndexColumn()
            ->addColumn('total_item', function ($barangkeluar) {
                return format_uang($barangkeluar->total_item);
            })
            ->addColumn('total_harga', function ($barangkeluar) {
                return 'Rp. ' . format_uang($barangkeluar->total_harga);
            })
            ->addColumn('tanggal', function ($barangkeluar) {
                return tanggal_indonesia($barangkeluar->created_at, false);
            })
            ->addColumn('user', function ($barangkeluar) {
                return $barangkeluar->user->name;
            })
            ->addColumn('aksi', function ($barangkeluar) {
                if (auth()->user()->role_id == 1) {
                    return '
                <div class="btn-group">
                <button onclick="showDetail(`' . route('barangkeluar.show', $barangkeluar->id_barang_keluar) . '`)" class="btn btn-xs btn-info btn-flat me-2"><i class="fa fa-eye "></i></button>
                <button onclick="deleteData(`' . route('barangkeluar.destroy', $barangkeluar->id_barang_keluar) . '`)" class="btn btn-xs btn-danger btn-flat me-2"><i class="fa fa-trash "></i></button>
                </div>
                ';
                } elseif (auth()->user()->role_id == 2) {
                    return '
                <div class="btn-group">
                <button onclick="showDetail(`' . route('barangkeluar.show', $barangkeluar->id_barang_keluar) . '`)" class="btn btn-xs btn-info btn-flat me-2"><i class="fa fa-eye "></i></button>
                </div>
                ';
                }
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $barangkeluar = new BarangKeluar();
        $barangkeluar->total_item = 0;
        $barangkeluar->total_harga = 0;
        $barangkeluar->id_user = auth()->user()->id;
        $barangkeluar->save();

        session(['id_barang_keluar' => $barangkeluar->id_barang_keluar]);
        //session(['id_supplier' => $barangkeluar->id_supplier]);

        return redirect()->route('barang_keluar_detail.index');
    }

    public function store(Request $request)
    {

        $diskon = str_replace('.', '', $request->diskon);
        $barang_keluar = BarangKeluar::findOrFail($request->id_barang_keluar);
        $barang_keluar->total_item = $request->total_item;
        $barang_keluar->total_harga = $request->total;
        $barang_keluar->keterangan = $request->keterangan;
        $barang_keluar->update();

        $detail = BarangKeluarDetail::where('id_barang_keluar', $barang_keluar->id_barang_keluar)->get();

        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            $produk->stok -= $item->jumlah;
            $produk->update();
        }

        $barang_keluar_kosong = BarangKeluar::where('total_item', '=', 0)
            ->where('total_harga', '=', 0)
            ->where('total_item', '=', 0)
            ->get();
        if ($barang_keluar_kosong->count() != 0) {
            foreach ($barang_keluar_kosong as $item) {
                $delete_detail = BarangKeluarDetail::where('id_barang_keluar', $item->id_barang_keluar)->delete();
                $delete_barang_keluar =  BarangKeluar::where('id_barang_keluar', $item->id_barang_keluar)->delete();
            }
        }
        return redirect()->route('barangkeluar.index');
    }

    public function show($id)
    {
        $detail = BarangKeluarDetail::with('produk')->where('id_barang_keluar', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_barang', function ($detail) {
                return '<span class="label label-success">' . $detail->produk->kode_barang . '</span>';
            })
            ->addColumn('nama_barang', function ($detail) {
                return $detail->produk->nama_barang;
            })
            ->addColumn('harga', function ($detail) {
                return 'Rp. ' . format_uang($detail->harga_beli);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return format_uang($detail->subtotal);;
            })
            ->rawColumns(['kode_barang'])
            ->make(true);
    }

    public function destroy($id)
    {
        $barangkeluar = BarangKeluar::find($id);
        $detail = BarangKeluarDetail::where('id_barang_keluar', $barangkeluar->id_barang_keluar)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            $produk->stok += $item->jumlah;
            $produk->update();
            $item->delete();
        }

        $barangkeluar->delete();

        return response(null, 204);
    }
}
