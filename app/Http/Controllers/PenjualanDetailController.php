<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;

class PenjualanDetailController extends Controller
{
    public function index()
    {
        $produk = Produk::orderBy('nama_barang')->get();
        $diskon = Setting::first()->diskon ?? 0;
        $appname = Setting::first()->value('nama_app');
        $pelanggan = Pelanggan::orderBy('created_at', 'desc');

        if ($id_penjualan = session('id_penjualan')) {
            $penjualan = Penjualan::find($id_penjualan);
            return view('penjualan_detail.index', compact('produk', 'diskon', 'penjualan', 'id_penjualan', 'appname', 'pelanggan'));
        } else {
            if (auth()->user()->level == 1) {
                return redirect()->route('transaksi.baru', compact('setting'));
            } else {
                redirect()->route('dashboard.index');
            }
        }
    }

    public function data($id)
    {
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_barang'] = '<span class="label label-success">' . $item->produk['kode_barang'] . '</span>';
            $row['nama_barang'] = $item->produk->nama_barang;
            $row['harga_jual'] = 'Rp. ' . format_uang($item->harga_jual);
            $row['jumlah'] = '<input type="number" class="form-control quantity input-sm" data-id="' . $item->id_penjualan_detail . '" value="' . $item->jumlah . '">';;
            // $row['diskon'] = $item->diskon . ' %';
            if ($item->dikirim == 'ya') {
                $row['dikirim'] = '<div class="custom-control custom-switch">
                <input type="checkbox" name="dikirim" class="custom-control-input dikirim-' . $item->id_penjualan_detail . '" id="dikirim-' . $item->id_penjualan_detail . '" checked onclick="pengiriman(' . $item->id_penjualan_detail . ',`' . route('transaksi.cekkirim', $item->id_penjualan_detail) . '`)">
                <label class="custom-control-label font-weight-normal" for="dikirim-' . $item->id_penjualan_detail . '">Ya</label>
              </div>';
            } elseif ($item->dikirim == 'tidak') {
                $row['dikirim'] = '<div class="custom-control custom-switch">
                <input type="checkbox" name="dikirim" class="custom-control-input dikirim-' . $item->id_penjualan_detail . '" id="dikirim-' . $item->id_penjualan_detail . '" onclick="pengiriman(' . $item->id_penjualan_detail . ',`' . route('transaksi.cekkirim', $item->id_penjualan_detail) . '`)">
                <label class="custom-control-label font-weight-normal" for="dikirim-' . $item->id_penjualan_detail . '">Tidak</label>
              </div>';
            }
            $row['subtotal'] = 'Rp. ' . format_uang($item->subtotal);
            $row['aksi'] = '<div class="btn-group">
                            <button onclick="deleteData(`' . route('transaksi.destroy', $item->id_penjualan_detail) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                            </div>';
            $data[] = $row;

            $total += $item->harga_jual * $item->jumlah;
            $total_item += $item->jumlah;
        }

        $data[] = [

            'kode_barang' => '<div class="total hide">' . $total . '</div> <div class="total_item hide">' . $total_item . ' </div> ',
            'nama_barang' => '',
            'harga_jual' => '',
            'jumlah' => '',
            // 'diskon' => '',
            'dikirim' => '',
            'subtotal' => '',
            'aksi' => '',
        ];


        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_barang', 'jumlah', 'dikirim'])
            ->make(true);
    }

    public function store(Request $request)
    {
        if ($request->cekkondisi == 'pilihbarang') {
            $produk = Produk::where('id_produk', $request->id_produk)->first();
            if (!$produk) {
                return response()->json('Data gagal disimpan', 400);
            }
        } elseif ($request->cekkondisi == 'cekkode') {
            $produk = Produk::where('kode_barang', $request->kode_barang)->first();
            if (!$produk) {
                return response()->json('Data gagal disimpan', 400);
            }
        }
        // return "ff";


        $detail = new PenjualanDetail();
        $detail->id_penjualan = $request->id_penjualan;
        $detail->id_produk = $produk->id_produk;
        $detail->harga_jual = $produk->harga_jual;
        $detail->jumlah = 1;
        $detail->diskon = 0;
        $detail->subtotal = $produk->harga_jual;
        $detail->save();

        return response()->json('Data berhasil di Simpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_jual * $request->jumlah;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon = 0, $total, $diterima)
    {
        $bayar = $total - $diskon;
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        $data = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar) . 'Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' =>  ucwords(terbilang($kembali) . 'Rupiah'),
        ];

        return response()->json($data);
    }

    public function pelanggan()
    {
        $query = Pelanggan::orderBy('id_pelanggan', 'desc')->get();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($query) {
                return '
            <button type="button" onclick="getpelanggan(`' . $query->nama . '`,' . $query->id_pelanggan . ')" class="btn btn-primary btn-sm">Pilih <i class="fas fa-check"></i></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function cekkirim($id, Request $request)
    {
        //@dd($request->dikirim);
        $cekkirim = PenjualanDetail::findOrFail($id);
        $cekkirim->dikirim = $request->dikirim;
        $cekkirim->save();

        //return response()->json(['data' => $kategori, 'message' => 'Kategori berhasil ditambahkan!']);
        return response()->json(['message' => 'Data berhasil di Simpan'], 200);
    }
}
