<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluarDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;

class BarangKeluarDetailController extends Controller
{
    public function index()
    {
        $id_barang_keluar = session('id_barang_keluar');
        $produk = Produk::orderBy('nama_barang')->get();
        if (!$id_barang_keluar) {
            abort(404);
        }
        $appname = Setting::first()->value('nama_app');
        return view('barang_keluar_detail.index', compact('appname', 'id_barang_keluar', 'produk'));
    }
    public function data($id)
    {
        $detail = BarangKeluarDetail::with('produk')
            ->where('id_barang_keluar', $id)
            ->get();
        // return $detail;
        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_barang'] = '<span class="label label-success">' . $item->produk['kode_barang'] . '</span>';
            $row['nama_barang'] = $item->produk->nama_barang;
            $row['harga_beli'] = 'Rp. ' . format_uang($item->harga_beli);
            $row['jumlah'] = '<input type="number" class="form-control quantity input-sm" data-id="' . $item->id_barang_keluar_detail . '" value="' . $item->jumlah . '">';;
            $row['subtotal'] = 'Rp. ' . format_uang($item->subtotal);
            $row['aksi'] = '<div class="btn-group">
                            <button onclick="deleteData(`' . route('barang_keluar_detail.destroy', $item->id_barang_keluar_detail) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                            </div>';
            $data[] = $row;

            $total += $item->harga_beli * $item->jumlah;
            $total_item += $item->jumlah;
        }

        $data[] = [

            'kode_barang' => '<div class="total hide">' . $total . '</div> <div class="total_item hide">' . $total_item . ' </div> ',
            'nama_barang' => '',
            'harga_beli' => '',
            'jumlah' => '',
            'subtotal' => '',
            'aksi' => '',
        ];


        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_barang', 'jumlah'])
            ->make(true);
    }

    public function store(Request $request)
    {
        if ($request->cekkondisi == 'pilihbarang') {
            $produk = Produk::where('id_produk', $request->id_produk)->first();
            if (!$produk) {
                return response()->json(['message' => 'Barang Tidak Di Temukan', 'cek' => 'fail'], 400);
            }
        } elseif ($request->cekkondisi == 'cekkode') {
            $produk = Produk::where('kode_barang', $request->kode_barang)->first();
            if (!$produk) {
                return response()->json(['message' => 'Barang Tidak Di Temukan', 'cek' => 'fail'], 400);
            }
        }

        if ($produk->stok < 1) {
            return response()->json(['message' => 'Stok Tidak Cukup ,Mohon Untuk Cek Stok Kembali !!', 'cek' => 'fail'], 400);
        }

        $detail = new BarangKeluarDetail();
        $detail->id_barang_keluar = $request->id_barang_keluar;
        $detail->id_produk = $produk->id_produk;
        $detail->harga_beli = $produk->harga_beli;
        $detail->jumlah = 1;
        $detail->subtotal = $produk->harga_beli;
        $detail->save();

        return response()->json(['message' => 'Data Berhasil Di Simpan'], 200);
    }

    public function update(Request $request, $id)
    {
        $id_produk = BarangKeluarDetail::where('id_barang_keluar_detail', $id)->value('id_produk');
        $produk = Produk::where('id_produk', $id_produk)->first();
        if ($produk->stok < $request->jumlah) {
            return response()->json(['message' => 'Stok Tidak Cukup ,Mohon Untuk Cek Stok Kembali !!', 'cek' => 'fail'], 400);
        }
        $detail = BarangKeluarDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_beli * $request->jumlah;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = BarangKeluarDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($total)
    {
        $data = [
            'totalrp' => format_uang($total),
            'terbilang' => ucwords(terbilang($total) . 'Rupiah')
        ];

        return response()->json($data);
    }
}
