<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserCrudController extends Controller
{
    public function index()
    {
        $appname = Setting::first()->value('nama_app');
        return view('user_crud.index', compact('appname'));
    }

    public function data()
    {
        $query = User::orderBy('id', 'desc')->get();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('status', function ($query) {
                if ($query->status == "Aktif") {
                    return '<div class="custom-control custom-switch">
                <input type="checkbox" name="status" class="custom-control-input status-' . $query->id . '" id="status" checked onclick="checkswitch(' . $query->id . ',`' . route('user.status', $query->id) . '`)">
                <label class="custom-control-label font-weight-normal" for="status">Aktif</label>
              </div>';
                } else if ($query->status == "NonAktif") {
                    return '<div class="custom-control custom-switch">
                <input type="checkbox" name="status" class="custom-control-input status-' . $query->id . '" id="status" onclick="checkswitch(' . $query->id . ',`' . route('user.status', $query->id) . '`)">
                <label class="custom-control-label font-weight-normal" for="status">Non Aktif</label>
              </div>';
                }
            })
            ->addColumn('foto', function ($query) {
                if ($query->foto == 'img/user1.png') {
                    return '<img src="' . asset($query->foto)  . '" alt=""  width="80px">';
                } else {
                    return '<img src="' . asset('storage/' . $query->foto)  . '" alt=""  width="80px">';
                }
            })
            ->addColumn('action', function ($query) {
                return '
            <button onclick="editForm(`' . route('user.show', $query->id) . '`)" class="btn btn-link text-info"><i
            class="fa fa-edit"></i></button>
            <button class="btn btn-link text-danger" onclick="deleteData(`' . route('user.destroy', $query->id) . '`)"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['action', 'status', 'foto'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'email' => 'required|email|min:2|unique:users,email',
            'telepon' => 'required|min:2',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|min:8|same:password',
            'alamat' => '',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->telepon = $request->telepon;
        $user->password = bcrypt($request->password);
        $user->alamat = $request->alamat;
        $user->role_id = 1;
        $user->foto = 'img/user1.png';
        $user->save();

        return response()->json(['data' => $user, 'message' => 'User berhasil ditambahkan!']);
    }

    public function show(User $user)
    {
        return response()->json(['data' => $user]);
    }

    public function update(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'email' => 'required|email|min:2|unique:users,email,' . $user->id . ',id',
            'telepon' => 'required|min:2',
            'alamat' => '',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($user->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->telepon = $request->telepon;
        //$user->password = bcrypt($request->password);
        $user->alamat = $request->alamat;
        //$user->role_id = 1;
        //$user->foto = 'img/user1.png';
        $user->save();

        return response()->json(['data' => $user, 'message' => 'User berhasil diubah!']);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['data' => null, 'message' => 'User Berhasil dihapus!']);;
    }

    public function status(Request $request, $id)
    {
        $status = User::where('id', $id)->update([
            'status' => $request->status,
        ]);
        return response()->json(['message' => 'Status berhasil diedit!']);
    }
}
