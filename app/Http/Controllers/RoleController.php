<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        $role = Role::orderBy('nama', 'asc')->get()->pluck('nama', 'id_roles');
        $appname = Setting::first()->value('nama_app');
        return view('role.index', compact('appname', 'role'));
    }
    public function data()
    {
        $query = User::orderBy('id', 'desc')->get();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('role', function ($query) {
                return $query->role->nama;
            })
            ->addColumn('action', function ($query) {
                return '
            <button onclick="editForm(`' . route('role.show', $query->id) . '`)" class="btn btn-link text-info"><i
            class="fa fa-edit"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show(User $role)
    {
        return response()->json(['data' => $role]);
    }

    public function update(Request $request, User $role)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = User::find($role->id);
        $role->role_id = $request->role_id;
        $role->save();
        return response()->json(['data' => $role, 'message' => 'Role berhasil diedit!']);
    }
}
