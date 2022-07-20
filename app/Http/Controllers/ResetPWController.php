<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ResetPWController extends Controller
{
    public function index()
    {
        $appname = Setting::first()->value('nama_app');
        return view('change_password.index', compact('appname'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'password_lama' => 'required|min:8',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|min:8|same:password',
        ];
        $validatedData = $request->validate($rules);

        $old_user = auth()->user();
        if ($request->has('password') && $request->password != '') {
            if (Hash::check($request->password_lama, $old_user->password)) {
                if ($request->password == $request->password_confirmation) {
                    $user = User::find($old_user->id);
                    $user->password = bcrypt($request->password);
                    $user->save();

                    return redirect(route('reset.index'))->with('success', 'Password berhasil Di edit!');
                }
            }
            return redirect(route('reset.index'))->with('gagal', 'Password gagal');
        }
    }
}
