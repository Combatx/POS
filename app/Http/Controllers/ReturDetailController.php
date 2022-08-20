<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\ReturDetail;
use App\Models\Setting;
use Illuminate\Http\Request;

class ReturDetailController extends Controller
{
    public function index()
    {
        $id_retur = session('id_retur');
        $total_lama = session('total_lama');
        if (!$id_retur || !$total_lama) {
            return abort(404);
        }
        $appname = Setting::first()->value('nama_app');
        return view('retur_detail.index', compact('id_retur', 'appname', 'total_lama'));
    }

    public function data($id)
    {
        $detail = ReturDetail::with('produk')
            ->where('id_retur', $id)
            ->get();
        // return $detail;
        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_barang'] = '<span class="label label-success">' . $item->produk['kode_barang'] . '</span>';
            $row['nama_barang'] = $item->produk->nama_barang;
            $row['harga_jual'] = 'Rp. ' . format_uang($item->harga_jual);
            $row['jumlah'] = '<input type="number" class="form-control quantity input-sm" data-id="' . $item->id_retur_detail . '" value="' . $item->jumlah . '">';;
            $row['subtotal'] = 'Rp. ' . format_uang($item->subtotal);
            // $row['dikirim'] = $item->dikirim;
            // $row['aksi'] = '<div class="btn-group">
            //                 <button onclick="deleteData(`' . route('retur_detail.destroy', $item->id_retur_detail) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
            //                 </div>';
            $data[] = $row;

            $total += $item->harga_jual * $item->jumlah;
            $total_item += $item->jumlah;
        }

        $data[] = [

            'kode_barang' => '<div class="total hide">' . $total . '</div> <div class="total_item hide">' . $total_item . ' </div> ',
            'nama_barang' => '',
            'harga_jual' => '',
            'jumlah' => '',
            'subtotal' => '',
            // 'aksi' => '',
        ];


        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['kode_barang', 'jumlah'])
            ->make(true);
    }

    public function create($kode)
    {
        $id_retur = session('id_retur');
        if (!$id_retur) {
            return abort(404);
        }

        $penjualan = PenjualanDetail::where('id_penjualan', $kode)->get();

        foreach ($penjualan as $item) {
            $retur_detai = ReturDetail::create([
                'id_retur' => $id_retur,
                'id_produk' => $item->id_produk,
                'harga_jual' => $item->harga_jual,
                'jumlah' => $item->jumlah,
                'jumlah_lama' => $item->jumlah,
                'subtotal' => $item->subtotal,
            ]);
        }
        return redirect()->route('retur_detail.index');
    }

    public function update(Request $request, $id)
    {
        $get_penjualan = ReturDetail::where('id_retur_detail', $id)->value('jumlah_lama');
        if ($request->jumlah > $get_penjualan) {
            return response()->json(['type' => 'error', 'message' => 'Jumlah Melebihi Dari Batas Jumlah Pembelian !!', 'angka' => $get_penjualan]);
        }
        $detail = ReturDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_jual * $request->jumlah;
        $detail->update();
    }

    public function destroy()
    {
    }

    public function loadForm($total, $total_lama)
    {
        $data = [
            'totalrp' => format_uang($total),
            'total_lamarp' => format_uang($total_lama),
            'terbilang' => ucwords(terbilang($total) . 'Rupiah'),
            'kembalianrp' => format_uang($total_lama - $total),
            'kembalian' => $total_lama - $total,
            'kembalian_terbilang' => ucwords(terbilang($total_lama - $total)),
        ];

        return response()->json($data);
    }
}
