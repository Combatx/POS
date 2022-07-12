<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PembelianDetailController extends Controller
{
    public function index()
    {
        $id_pembelian = session('id_pembelian');
        $produk = Produk::orderBy('nama_barang')->get();
        //$supplier = Supplier::find(session('id_supplier'));
        $diskon = Pembelian::find($id_pembelian)->diskon ?? 0;

        $suppliershow = Supplier::get();
        // if (!$supplier) {
        //     abort(404);
        // }
        return view('pembelian_detail.index', compact('id_pembelian', 'produk', 'diskon', 'suppliershow'));
    }

    public function data($id)
    {
        $detail = PembelianDetail::with('produk')
            ->where('id_pembelian', $id)
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
            $row['jumlah'] = '<input type="number" class="form-control quantity input-sm" data-id="' . $item->id_pembelian_detail . '" value="' . $item->jumlah . '">';;
            $row['subtotal'] = 'Rp. ' . format_uang($item->subtotal);
            $row['aksi'] = '<div class="btn-group">
                            <button onclick="deleteData(`' . route('pembelian_detail.destroy', $item->id_pembelian_detail) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
        $produk = Produk::where('id_produk', $request->id_produk)->first();
        if (!$produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new PembelianDetail();
        $detail->id_pembelian = $request->id_pembelian;
        $detail->id_produk = $produk->id_produk;
        $detail->harga_beli = $produk->harga_beli;
        $detail->jumlah = 1;
        $detail->subtotal = $produk->harga_beli;
        $detail->save();

        return response()->json('Data berhasil di Simpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = PembelianDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_beli * $request->jumlah;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = PembelianDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon, $total)
    {
        $bayar = $total - $diskon;
        $data = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar) . 'Rupiah')
        ];

        return response()->json($data);
    }

    public function getsupplier($id)
    {
        $supplier = Supplier::where('id_supplier', $id)->first();
        return response()->json($supplier);
    }

    public function data_supplier()
    {
        $query = Supplier::orderBy('id_supplier', 'desc')->get();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($query) {
                return '
            <button type="button" onclick="getsupplier(' . $query->id_supplier . ')" class="btn btn-primary btn-sm">Pilih <i class="fas fa-check"></i></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
