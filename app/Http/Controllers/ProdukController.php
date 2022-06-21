<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        return view('produk.index', compact('kategori', 'satuan'));
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
                return '
            <button onclick="editForm(`' . route('produk.show', $query->id_produk) . '`)" class="btn btn-link text-info"><i
            class="fa fa-edit"></i></button>
            <button class="btn btn-link text-danger" onclick="deleteData(`' . route('produk.destroy', $query->id_produk) . '`)"><i class="fas fa-trash"></i></button>';
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
        $validator = Validator::make($request->all(), [
            'kode_barang' => 'required|min:2|unique:produk,kode_barang',
            'nama_barang' => 'required|min:2|unique:produk,nama_barang',
            'harga_beli' => 'required|min:2|numeric',
            'harga_jual' => 'required|min:2|numeric',
            'id_kategori' => 'required||numeric',
            'id_satuan' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $produk = Produk::create($data);
        return response()->json(['data' => $produk, 'message' => 'Produk berhasil ditambahkan!']);
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
        //
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
            'kode_barang' => 'required|min:2|unique:produk,kode_barang',
            'nama_barang' => 'required|min:2|unique:produk,nama_barang',
            'harga_beli' => 'required|min:2|numeric',
            'harga_jual' => 'required|min:2|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        $produk->update($data);
        return response()->json(['data' => $produk, 'message' => 'Produk berhasil diedit!']);
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
        return response()->json(['data' => null, 'message' => 'Produk Berhasil dihapus!']);
    }
}