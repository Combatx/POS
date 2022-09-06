<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Retur;
use App\Models\ReturDetail;
use App\Models\Setting;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class ReturController extends Controller
{
    public function index()
    {
        $appname = Setting::first()->value('nama_app');
        return view('retur.index', compact('appname'));
    }

    public function data()
    {
        $retur = Retur::orderBy('id_retur', 'desc')
            ->where('total_item', '!=', 0)
            ->where('total_harga', '!=', 0)
            ->get();
        // return $retur->user->name;
        return datatables()
            ->of($retur)
            ->addIndexColumn()
            ->addColumn('total_item', function ($retur) {
                return format_uang($retur->total_item);
            })
            ->addColumn('total_harga', function ($retur) {
                return 'Rp. ' . format_uang($retur->total_harga);
            })
            ->addColumn('tanggal', function ($retur) {
                return tanggal_indonesia($retur->created_at, false);
            })
            ->addColumn('kasir', function ($retur) {
                return optional($retur->user)->name ?? '';
            })
            ->addColumn('aksi', function ($retur) {
                return '
                <div class="btn-group">
                <button onclick="showDetail(`' . route('retur.show', $retur->id_retur) . '`)" class="btn btn-xs btn-info btn-flat me-2"><i class="fa fa-eye "></i></button>
                <button onclick="deleteData(`' . route('retur.destroy', $retur->id_retur) . '`)" class="btn btn-xs btn-danger btn-flat me-2"><i class="fa fa-trash "></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create($id)
    {
        $penjualan = Penjualan::where('id_penjualan', $id)->first();
        if ($penjualan == null) {
            return abort(404);
        }
        $retur = new Retur();
        $retur->id_penjualan = $id;
        $retur->total_item = 0;
        $retur->total_harga = 0;
        $retur->total_harga_lama = $penjualan->total_harga;
        $retur->kembalian = 0;
        $retur->id_user = auth()->id();
        $retur->save();

        session(['id_retur' => $retur->id_retur]);
        session(['total_lama' => $penjualan->total_harga]);
        return redirect()->route('retur_detail.create', $id);
    }

    public function store(Request $request)
    {
        // return $request;

        $diskon = str_replace('.', '', $request->diskon);
        $retur = Retur::findOrFail($request->id_retur);
        $retur->total_item = $request->total_item;
        $retur->total_harga = $request->total;
        $retur->kembalian = $request->kembalian;
        $retur->keterangan = $request->keterangan;
        $retur->update();

        // $penjualan_detail = PenjualanDetail::where('id_penjualan', $retur->id_penjualan)->get();
        // foreach ($penjualan_detail as $item) {
        //     $produk = Produk::find($item->id_produk);
        //     $produk->stok -= $item->jumlah;
        //     $produk->update();
        // }

        $detail = ReturDetail::where('id_retur', $retur->id_retur)->get();

        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            $produk->stok += ($item->jumlah_lama - $item->jumlah);
            $produk->update();
        }
        $update_penjualan = Penjualan::where('id_penjualan', $retur->id_penjualan)->update([
            'retur' => 'Ya'
        ]);
        $retur_kosong = Retur::where('total_item', '=', 0)
            ->where('total_harga', '=', 0)
            ->where('total_item', '=', 0)
            ->get();
        if ($retur_kosong->count() != 0) {
            foreach ($retur_kosong as $item) {
                $delete_detail = ReturDetail::where('id_retur', $item->id_retur)->delete();
                $delete_barang_keluar =  Retur::where('id_retur', $item->id_retur)->delete();
            }
        }
        return redirect()->route('retur.index');
    }


    public function show($id)
    {
        $detail = ReturDetail::with('produk')->where('id_retur', $id)->get();

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
            ->addColumn('jumlah_lama', function ($detail) {
                return format_uang($detail->jumlah_lama);
            })
            ->addColumn('barang_berkurang', function ($detail) {
                return format_uang($detail->jumlah_lama - $detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return format_uang($detail->subtotal);;
            })
            ->rawColumns(['kode_barang'])
            ->make(true);
    }

    public function cekretur($kode)
    {
        $penjualancek = Penjualan::where('id_penjualan', $kode)->where('retur', 'Ya')->count();

        if ($penjualancek == 1) {
            return response()->json(['type' => 'error', 'message' => 'Telah Ada Data Retur Dari ID Faktur ' . $kode . ' Yang di Input !!']);
        }
        $penjualan = Penjualan::where('id_penjualan', $kode)->where('retur', 'Tidak')->count();
        //$retur = Retur::where('id_penjualan', $kode)->count();
        if ($penjualan == 0) {
            return response()->json(['type' => 'error', 'message' => 'Tidak di Temukan ID Faktur ' . $kode . ' !!']);
        } elseif ($penjualan >= 1) {
            return response()->json(['type' => 'success', 'message' => 'ID Faktur Ditemukan' . $kode . ' !!', 'kode' => route('retur.create', $kode)]);
        }
    }

    public function destroy($id)
    {
        $retur = Retur::find($id);
        $detail = ReturDetail::where('id_retur', $retur->id_retur)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            $produk->stok -= $item->jumlah;
            $produk->update();
            $item->delete();
        }

        $penjualan_detail = PenjualanDetail::where('id_penjualan', $retur->id_penjualan)->get();
        foreach ($penjualan_detail as $item) {
            $produk = Produk::find($item->id_produk);
            $produk->stok += $item->jumlah;
            $produk->update();
        }
        $penjualan = Penjualan::where('id_penjualan', $retur->id_penjualan)
            ->update([
                'retur' => 'Tidak',
            ]);

        $retur->delete();

        return response(null, 204);
    }
}
