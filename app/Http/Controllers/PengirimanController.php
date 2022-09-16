<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PengirimanController extends Controller
{
    public function index()
    {
        $appname = Setting::first()->value('nama_app');
        return view('pengiriman.index', compact('appname'));
    }

    public function data()
    {
        $query = Pengiriman::orderBy('id_pengiriman', 'desc')->get();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('created_at', function ($query) {
                return tanggal_indonesia($query->created_at, false);
            })
            ->addColumn('status', function ($query) {
                if ($query->status == 'diantar') {
                    return '<span class="badge  badge-warning">' . $query->status . '</span>';
                } else if ($query->status == 'success') {
                    return '<span class="badge  badge-success">' . $query->status . '</span>';
                }
            })
            ->addColumn('updated_at', function ($query) {
                if ($query->updated_at == $query->created_at) {
                    return 'Kosong';
                } else {
                    return tanggal_indonesia($query->updated_at, false);
                }
            })
            ->addColumn('petugas_status', function ($query) {
                return optional($query->user)->name;
            })
            ->addColumn('action', function ($query) {
                return '
            <button onclick="editForm(`' . route('pengiriman.show', $query->id_pengiriman) . '`)" class="btn btn-link text-info"><i
            class="fa fa-edit"></i></button>
            <button class="btn btn-link text-danger" onclick="deleteData(`' . route('pengiriman.destroy', $query->id_pengiriman) . '`)"><i class="fas fa-trash"></i></button>
            <button class="btn btn-link text-info" onclick="detail(`' . route('pengiriman.bio', $query->id_pengiriman) . '`, `' . route('pengiriman.detail', $query->id_pengiriman) . '`)"><i class="fas fa-eye"></i></button>
            <button class="btn btn-link text-dark" href="' . route('pengiriman.printsj', $query->id_pengiriman) . '" onclick="ceknama(' . $query->id_pengiriman . ')" ><i class="fas fa-print"></i></button>
            ';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function bio($id)
    {
        $datakirim = Pengiriman::where('id_pengiriman', $id)->first();
        $pembeli = Penjualan::with('pelanggan')->where('id_penjualan', $datakirim->id_penjualan)->first();
        $pengiriman = [
            'kode_pengiriman' => $datakirim->id_pengiriman,
            'kode_faktur' => $datakirim->id_penjualan,
            'tanggal_transaksi' => tanggal_indonesia($datakirim->created_at),
            'status' => $datakirim->status == 'diantar' ? '<span class="badge badge-warning status-ku">' . $datakirim->status . '</span>' : '<span class="badge badge-success status-ku">' . $datakirim->status . '</span>',
            'penerima' => $datakirim->penerima == null ? 'Status Belum Berubah' : $datakirim->penerima,
            'petugas_pengirim' => $datakirim->petugas_pengiriman == null ? 'Status Belum Berubah' : $datakirim->petugas_pengiriman,
            'tanggal_update' => $datakirim->updated_at == $datakirim->created_at ? 'Status Belum Berubah' : tanggal_indonesia($datakirim->updated_at),
            'pembeli' => optional($pembeli->pelanggan)->nama != null ? optional($pembeli->pelanggan)->nama : 'Kosong',
            'petugas_update' => optional($datakirim->user)->name != null ? optional($datakirim->user)->name : 'Status Belum Berubah',

        ];
        return response()->json(['pengiriman' => $pengiriman]);
    }

    public function detail($id)
    {
        $query = PenjualanDetail::where('id_pengiriman', $id)->get();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('kode_barang', function ($query) {
                return $query->produk->kode_barang;
            })
            ->addColumn('nama_barang', function ($query) {
                return $query->produk->nama_barang;
            })
            ->addColumn('harga', function ($query) {
                return format_uang($query->harga_jual);
            })
            ->addColumn('jumlah', function ($query) {
                return format_uang($query->jumlah);
            })
            ->addColumn('subtotoal', function ($query) {
                return format_uang($query->subtotoal);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show(Pengiriman $pengiriman)
    {
        $pembeli = Penjualan::with('pelanggan')->where('id_penjualan', $pengiriman->id_penjualan)->first();
        $data = [
            'tanggal_data' => tanggal_indonesia($pengiriman->created_at),
            'id_pengiriman' => $pengiriman->id_pengiriman,
            'id_faktur' => $pengiriman->id_penjualan,
            'penerima' => $pengiriman->penerima,
            'petugas_pengiriman' => $pengiriman->petugas_pengiriman,
            'status' => $pengiriman->status,
            'pembeli' => $pembeli->pelanggan->nama,

        ];
        return response()->json(['data' => $data]);
    }

    public function update(Pengiriman $pengiriman, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'penerima' => 'required|min:2',
            // 'petugas_pengiriman' => 'required|min:1',
            'status' => 'required|not_in:diantar',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'penerima' => $request->penerima,
            // 'petugas_pengiriman' => $request->petugas_pengiriman,
            'status' => $request->status,
            'id_user' => Auth::user()->id,
        ];

        $pengiriman->update($data);
        return response()->json(['data' => $pengiriman, 'message' => 'Data Pengiriman berhasil ditambahkan!']);
    }

    public function destroy(Pengiriman $pengiriman)
    {
        $penjualandetail = PenjualanDetail::where('id_pengiriman', $pengiriman->id_pengiriman)
            ->update([
                'id_pengiriman' => null,
                'dikirim' => 'tidak',
            ]);
        $pengiriman->delete();
        return response()->json(['data' => null, 'message' => 'Data Pengiriman Berhasil di Hapus']);
    }

    public function printsj($id)
    {
        $cek_pengiriman = Pengiriman::where('id_pengiriman', $id)->value('petugas_pengiriman');
        if ($cek_pengiriman == null) {
            return 'Silahkan Masukan Nama Petugas Pengiriman terlebih dahulu !!!!';
        }
        $pengiriman = Pengiriman::where('id_pengiriman', $id)->first();
        $penjualan = Penjualan::with('pelanggan')->where('id_penjualan', $pengiriman->id_penjualan)->first();
        $penjualandetail = PenjualanDetail::where('id_pengiriman', $id)->get();
        $pdf = PDF::loadView('pengiriman.surat', compact('pengiriman', 'penjualan', 'penjualandetail'));
        $pdf->setPaper(0, 0, 609, 440, 'potrait');
        return $pdf->stream('Pengiriman-' . date('Y-m-d-His') . '.pdf');
    }

    public function ceknama($kode)
    {
        $cek_pengiriman = Pengiriman::where('id_pengiriman', $kode)->value('petugas_pengiriman');
        if ($cek_pengiriman == null) {
            return response()->json(['cek' => 'tidak']);
        } elseif ($cek_pengiriman != null) {
            return response()->json(['cek' => 'ada', 'hasil' => route('pengiriman.printsj', $kode)]);
        }
    }

    public function simpankirim(Request $request, $kode)
    {
        $cek_pengiriman = Pengiriman::where('id_pengiriman', $kode)->value('petugas_pengiriman');
        if ($cek_pengiriman == null) {
            $pengiriman = Pengiriman::where('id_pengiriman', $kode)->update([
                'petugas_pengiriman' => $request->petugas,
            ]);
            return response()->json(['cek' => 'ada', 'hasil' => route('pengiriman.printsj', $kode)]);
        } elseif ($cek_pengiriman != null) {
            return response()->json(['cek' => 'tidak']);
        }
    }
}
