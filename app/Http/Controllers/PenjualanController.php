<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Retur;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $appname = Setting::first()->value('nama_app');
        return view('penjualan.index', compact('appname'));
    }

    public function data()
    {
        $penjualan = Penjualan::orderBy('id_penjualan', 'desc')
            ->where('total_item', '!=', 0)
            ->where('total_harga', '!=', 0)
            ->where('bayar', '!=', 0)
            ->get();
        // return $penjualan->user->name;
        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('id_faktur', function ($penjualan) {
                return $penjualan->id_penjualan;
            })
            ->addColumn('total_item', function ($penjualan) {
                return format_uang($penjualan->total_item);
            })
            ->addColumn('total_harga', function ($penjualan) {
                return 'Rp. ' . format_uang($penjualan->total_harga);
            })
            ->addColumn('bayar', function ($penjualan) {
                return 'Rp. ' . format_uang($penjualan->bayar);
            })
            ->addColumn('diskon', function ($penjualan) {
                return format_uang($penjualan->diskon);
            })
            ->addColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->addColumn('kasir', function ($penjualan) {
                return optional($penjualan->user)->name ?? '';
            })
            ->addColumn('aksi', function ($penjualan) {
                if (auth()->user()->role_id == 1) {
                    return '
                <div class="btn-group">
                <button onclick="showDetail(`' . route('penjualan.show', $penjualan->id_penjualan) . '`)" class="btn btn-xs btn-info btn-flat me-2"><i class="fa fa-eye "></i></button>
                <button onclick="deleteData(`' . route('penjualan.destroy', $penjualan->id_penjualan) . '`)" class="btn btn-xs btn-danger btn-flat me-2"><i class="fa fa-trash "></i></button>
                </div>
                ';
                } elseif (auth()->user()->role_id == 3) {
                    return '
                <div class="btn-group">
                <button onclick="showDetail(`' . route('penjualan.show', $penjualan->id_penjualan) . '`)" class="btn btn-xs btn-info btn-flat me-2"><i class="fa fa-eye "></i></button>
                </div>
                ';
                }
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_barang', function ($detail) {
                return '<span class="label label-success">' . $detail->produk->kode_barang . '</span>';
            })
            ->addColumn('nama_barang', function ($detail) {
                return $detail->produk->nama_barang;
            })
            ->addColumn('harga_jual', function ($detail) {
                return 'Rp. ' . format_uang($detail->harga_jual);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('diskon', function ($detail) {
                return format_uang($detail->diskon);
            })
            ->addColumn('subtotal', function ($detail) {
                return format_uang($detail->subtotal);;
            })
            ->rawColumns(['kode_barang'])
            ->make(true);
    }

    public function create()
    {
        $penjualan = new Penjualan();
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->bayar = 0;
        $penjualan->id_user = auth()->id();
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $diskon = str_replace('.', '', $request->diskon);
            $diterima = str_replace('.', '', $request->diterima);
            $penjualan = Penjualan::findOrFail($request->id_penjualan);
            $penjualan->total_item = $request->total_item;
            $penjualan->total_harga = $request->total;
            $penjualan->diskon = $diskon;
            $penjualan->bayar = $request->bayar;
            $penjualan->diterima = $diterima;
            $penjualan->id_pelanggan = $request->id_pelanggan;
            $penjualan->update();

            $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();

            foreach ($detail as $item) {
                $produk = Produk::find($item->id_produk);
                $produk->stok -= $item->jumlah;
                $produk->update();
            }

            $penjualan_pengiriman = PenjualanDetail::where('id_penjualan', $request->id_penjualan)->where('dikirim', 'ya')->get();

            if ($penjualan_pengiriman->count() != 0) {
                $pengiriman =  Pengiriman::create([
                    'id_penjualan' => $request->id_penjualan,
                    'status' => 'diantar',
                ]);
                // foreach ($detail as $item) {
                //     $detailkirim = 
                //     $produk->update();
                // }

                $detailkirim = PenjualanDetail::where('id_penjualan', $request->id_penjualan)
                    ->update([
                        'id_pengiriman' => $pengiriman->id_pengiriman
                    ]);
            }
            $penjualankosong = Penjualan::where('total_item', '=', 0)
                ->where('total_harga', '=', 0)
                ->where('bayar', '=', 0)
                ->get();

            if ($penjualankosong->count() != 0) {
                foreach ($penjualankosong as $item) {
                    $delete_detail = PenjualanDetail::where('id_penjualan', $item->id_penjualan)->delete();
                    $delete_penjualan =  Penjualan::where('id_penjualan', $item->id_penjualan)->delete();
                }
            }

            DB::commit();
            return redirect()->route('transaksi.selesai');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['data' => null, 'message' => 'Gagal Menyimpan Data!'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $cek_kirim = Pengiriman::where('id_penjualan', $id)->count();
            $cek_retur = Retur::where('id_penjualan', $id)->count();
            if ($cek_kirim >= 1) {
                return response()->json(['message' => 'Mohon Hapus Data Pengiriman Terlebih Dahulu Sesuai Dengan ID Faktur ' . $id, 'type' => 'error', 'cek' => 'fail'], 400);
            }
            if ($cek_retur >= 1) {
                return response()->json(['message' => 'Mohon Hapus Data Retur Terlebih Dahulu Sesuai Dengan ID Faktur ' . $id, 'type' => 'error', 'cek' => 'fail'], 400);
            }
            $penjualan = Penjualan::find($id);
            $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
            foreach ($detail as $item) {
                $produk = Produk::find($item->id_produk);
                $produk->stok += $item->jumlah;
                $produk->update();
                $item->delete();
            }

            $penjualan->delete();

            return response(null, 204);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['data' => null, 'message' => 'Gagal Menyimpan Data!'], 500);
        }
    }

    public function selesai()
    {
        $setting = Setting::first();
        $appname = Setting::first()->value('nama_app');
        return  view('penjualan.selesai', compact('setting', 'appname'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (!$penjualan) {
            abort(404);
        }
        $jam = date_format($penjualan->created_at, "H:i:s");
        $tanggal = date_format($penjualan->created_at, "d-m-Y");
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail', 'jam', 'tanggal'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (!$penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));

        $pdf->setPaper(0, 0, 609, 440, 'potrait');
        return $pdf->stream('Transaksi-' . date('Y-m-d-His') . '.pdf');
    }
}
