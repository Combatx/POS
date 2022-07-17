<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        //$user = User::first();
        $appname = Setting::first()->value('nama_app');
        return view('Profil.index', compact('appname'));
    }

    public function show(User $user)
    {
        //return response()->json(['data' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //return $request;
        $rules = [
            'name' => "min:1|max:191|required",
            'email' => "required|email|min:1|max:191",
            'telepon' => "required|numeric|min:5",
            'foto' => "image|mimes:jpg,bmp,jpeg,png",
            'alamat' => "required|min:2",
        ];
        $validatedData = $request->validate($rules);
        if ($request->deleteimg == 'delete') {
            Storage::delete($request->oldImage);
            $validatedData['foto'] = '/img/user1.png';
        } elseif ($request->cekubah == 'default') {
            //$validatedData['foto'] = '/img/user1.png';
        } elseif ($request->cekubah == 'berubah') {
            if ($request->file('foto')) {
                if ($request->old_image == '/img/user1.png') {
                    $validatedData['foto'] = $request->file('foto')->store('profil');
                } else {
                    Storage::delete($request->oldImage);
                    $validatedData['foto'] = $request->file('foto')->store('profil');
                }
            }
        }

        User::where('id', Auth::user()->id)->update($validatedData);
        return redirect(route('profil.index'))->with('success', 'Profil berhasil Di edit!');
        // return $validateku;
    }
}
