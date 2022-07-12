<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('kategori.index');
    }


    public function data()
    {
        $query = Kategori::orderBy('id_kategori', 'desc')->get();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($query) {
                return '
            <button onclick="editForm(`' . route('kategori.show', $query->id_kategori) . '`)" class="btn btn-link text-info"><i
            class="fa fa-edit"></i></button>
            <button class="btn btn-link text-danger" onclick="deleteData(`' . route('kategori.destroy', $query->id_kategori) . '`)"><i class="fas fa-trash"></i></button>';
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
            'nama' => 'required|min:2|unique:kategori,nama',
            'kode_kategori' => 'required|alpha|min:1|unique:kategori,kode_kategori',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $kategori = Kategori::create($data);
        return response()->json(['data' => $kategori, 'message' => 'Kategori berhasil ditambahkan!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function show(Kategori $kategori)
    {
        return response()->json(['data' => $kategori]);
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
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:2|unique:kategori,nama,' . $kategori->id_kategori . ',id_kategori',
            'kode_kategori' => 'required|alpha|min:1|unique:kategori,kode_kategori,' . $kategori->id_kategori . ',id_kategori',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        $kategori->update($data);
        return response()->json(['data' => $kategori, 'message' => 'Kategori berhasil diedit!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return response()->json(['data' => null, 'message' => 'Kategori Berhasil dihapus!']);
    }
}
