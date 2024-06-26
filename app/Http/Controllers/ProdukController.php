<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = Kategori::orderBy('nama')->get()->pluck('nama', 'id_kategori');
        $satuan = Satuan::orderBy('nama')->get()->pluck('nama', 'id_satuan');
        $appname = Setting::first()->value('nama_app');
        return view('produk.index', compact('kategori', 'satuan', 'appname'));
    }

    public function data(Request $request)
    {
        $query = Produk::with('kategori', 'satuan')->select('produk.*')->orderBy('id_produk', 'desc')
            ->when(request()->has('kategori') && $request->kategori != "", function ($query) use ($request) {
                $query->where('id_kategori', $request->kategori);
            })
            ->when(request()->has('satuan') && $request->satuan != "", function ($query) use ($request) {
                $query->where('id_satuan', $request->satuan);
            });

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('harga_beli', function ($produk) {
                return format_uang($produk->harga_beli);
            })
            ->addColumn('harga_jual', function ($produk) {
                return format_uang($produk->harga_jual);
            })
            ->addColumn('stok', function ($produk) {
                return format_uang($produk->stok);
            })
            ->addColumn('kategori', function ($query) {
                return $query->kategori->nama;
            })
            ->addColumn('satuan', function ($query) {
                return $query->satuan->nama;
            })
            ->addColumn('action', function ($query) {
                if (auth()->user()->role_id == 1) {
                    return '';
                }
                return '
            <button onclick="editForm(`' . route('produk.show', $query->id_produk) . '`)" class="btn btn-link text-info"><i
            class="fa fa-edit"></i></button>
            <button class="btn btn-link text-danger" onclick="deleteData(`' . route('produk.destroy', $query->id_produk) . '`)"><i class="fas fa-trash"></i></button>  
            <button class="btn btn-link text-dark" onclick="get_barcode(`' . $query->kode_barang . '`)"><i class="fas fa-barcode"></i></button>';
            })
            ->rawColumns(['action'])
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
        try {
            DB::beginTransaction();
            if ($request->cek_kode == 'manual') {
                $validator = Validator::make($request->all(), [
                    'kode_barang' => 'required|min:2|unique:produk,kode_barang',
                    'nama_barang' => 'required|min:2|unique:produk,nama_barang',
                    'harga_beli' => 'required|min:2',
                    'harga_jual' => 'required|min:2',
                    'id_kategori' => 'required||numeric',
                    'id_satuan' => 'required|numeric',
                ]);



                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }


                $data = $request->all();
                $data['harga_beli'] = str_replace('.', '', $request->harga_beli);
                $data['harga_jual'] = str_replace('.', '', $request->harga_jual);
                $produk = Produk::create([
                    'kode_barang' => $request->kode_barang,
                    'nama_barang' => $request->nama_barang,
                    'harga_beli' => str_replace('.', '', $request->harga_beli),
                    'harga_jual' => str_replace('.', '', $request->harga_jual),
                    'id_kategori' => $request->id_kategori,
                    'id_satuan' => $request->id_satuan,
                ]);
                //$kodeBarang = Kategori::where('id_kategori', $produk->id_kategori)->value('kode_kategori') . $produk->id_produk;
                return response()->json(['data' => $produk, 'message' => 'Produk berhasil ditambahkan!', 'type' => 'success']);
            } elseif ($request->cek_kode == 'otomatis') {
                $validator = Validator::make($request->all(), [
                    //'kode_barang' => 'required|min:2|unique:produk,kode_barang',
                    'nama_barang' => 'required|min:2|unique:produk,nama_barang',
                    'harga_beli' => 'required|min:2',
                    'harga_jual' => 'required|min:2',
                    'id_kategori' => 'required||numeric',
                    'id_satuan' => 'required|numeric',
                ]);



                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }


                $data = $request->all();
                $data['harga_beli'] = str_replace('.', '', $request->harga_beli);
                $data['harga_jual'] = str_replace('.', '', $request->harga_jual);
                $produk = Produk::create([
                    'nama_barang' => $request->nama_barang,
                    'harga_beli' => str_replace('.', '', $request->harga_beli),
                    'harga_jual' => str_replace('.', '', $request->harga_jual),
                    'id_kategori' => $request->id_kategori,
                    'id_satuan' => $request->id_satuan,
                ]);
                //$kodeBarang = Kategori::where('id_kategori', $produk->id_kategori)->value('kode_kategori') . $produk->id_produk;
                $kodelast = Produk::where('id_kategori', $produk->id_kategori)->orderBy('id_produk', 'desc')->skip(1)->value('kode_barang') ?? 0;
                $pisah = preg_replace('/[^0-9]/', '', $kodelast);
                $kodeBarang = Kategori::where('id_kategori', $produk->id_kategori)->value('kode_kategori') . ($pisah + 1);
                $updateKode = Produk::where('id_produk', $produk->id_produk)
                    ->update(['kode_barang' =>  $kodeBarang]);
                return response()->json(['data' => $produk, 'message' => 'Produk berhasil ditambahkan!', 'type' => 'success']);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['data' => null, 'message' => 'Gagal Menyimpan Data!'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function show(Produk $produk)
    {
        return response()->json(['data' => $produk]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Produk $produk)
    {

        $validator = Validator::make($request->all(), [
            'kode_barang' => 'required|min:2|unique:produk,kode_barang,' . $produk->id_produk . ',id_produk',
            'nama_barang' => 'required|min:2|unique:produk,nama_barang,' . $produk->id_produk . ',id_produk',
            'harga_beli' => 'required|min:1',
            'harga_jual' => 'required|min:1',
            'id_kategori' => 'required|numeric',
            'id_satuan' => 'required|numeric',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['harga_beli'] = str_replace('.', '', $data['harga_beli']);
        $data['harga_jual'] = str_replace('.', '', $data['harga_jual']);
        // $data['diskon'] = str_replace('.', '', $data['diskon']);

        $produk->update([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'harga_beli' => str_replace('.', '', $request->harga_beli),
            'harga_jual' => str_replace('.', '', $request->harga_jual),
            'id_kategori' => $request->id_kategori,
            'id_satuan' => $request->id_satuan,
        ]);
        return response()->json(['data' => $produk, 'message' => 'Produk berhasil diedit!', 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produk $produk)
    {
        $produk->delete();
        return response()->json(['data' => null, 'message' => 'Produk Berhasil dihapus!', 'type' => 'success']);
    }

    public function cetak_barcode($kode, $jumlah)
    {
        $jumlah = $jumlah;
        $produk = Produk::where('kode_barang', $kode)->first();
        if ($produk == null) {
            response()->json(['message' => 'Terjadi Kesalahan'], 400);
        }
        $no = 1;
        $pdf = PDF::loadView('produk.barcode', compact('produk', 'no', 'jumlah'));
        $pdf->setPaper('a4', 'portait');
        return $pdf->stream('barcode-' . $produk->nama_barang . '.pdf', array("Attachment" => 0));
    }

    public function datastok()
    {
        $query = Produk::select('nama_barang', 'stok')->orderBy('stok', 'desc')->get();

        return datatables($query)
            ->addIndexColumn()

            ->addColumn('stok', function ($produk) {
                return format_uang($produk->stok);
            })
            ->make(true);
    }

    public function stok()
    {
        $appname = Setting::first()->value('nama_app');
        return view('produk.stok_barang', compact('appname'));
    }
}
