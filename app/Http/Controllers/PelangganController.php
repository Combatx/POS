<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    public function index()
    {
        $appname = Setting::first()->value('nama_app');
        return view('pelanggan.index', compact('appname'));
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

    public function data()
    {
        $query = Pelanggan::orderBy('id_pelanggan', 'desc')->get();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($query) {
                return '
            <button onclick="editForm(`' . route('pelanggan.show', $query->id_pelanggan) . '`)" class="btn btn-link text-info"><i
            class="fa fa-edit"></i></button>
            <button class="btn btn-link text-danger" onclick="deleteData(`' . route('pelanggan.destroy', $query->id_pelanggan) . '`)"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
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
            'nama' => 'required|min:2|unique:pelanggan,nama',
            'alamat' => 'required|min:2',
            'telepon' => 'min:2|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $satuan = Pelanggan::create($data);
        return response()->json(['data' => $satuan, 'message' => 'pelanggan berhasil ditambahkan!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pelanggan  $pelangan
     * @return \Illuminate\Http\Response
     */
    public function show(Pelanggan $pelanggan)
    {
        return response()->json(['data' => $pelanggan]);
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
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:2|unique:pelanggan,nama,' . $pelanggan->id_pelanggan . ',id_pelanggan',
            'alamat' => 'required|min:2',
            'telepon' => 'min:2|numeric',
        ]);



        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        $pelanggan->update($data);
        return response()->json(['data' => $pelanggan, 'message' => 'Pelanggan berhasil diedit!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return response()->json(['data' => null, 'message' => 'pelanggan Berhasil dihapus!']);;
    }
}
