<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $appname = Setting::first()->value('nama_app');
        return view('setting.index', compact('setting', 'appname'));
    }

    public function show(Setting $setting)
    {
        //return response()->json(['data' => $setting]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        //@dd($request->path_logo);
        $rules = [
            'nama_app' => "min:1|max:191|required",
            'nama_perusahaan' => "min:1|max:191|required",
            'telepon' => "required|numeric|min:5",
            'tipe_nota' => "numeric|required|max:2",
            'path_logo' => "image|mimes:jpg,bmp,jpeg,png",
            'alamat' => "required|min:2",
            //'tipe_nota' => "image|file|max:2048"
        ];
        $validatedData = $request->validate($rules);

        if ($request->deleteimg == 'delete') {
            Storage::delete($request->oldImage);
            $validatedData['path_logo'] = '/img/logo_cart.png';
        } elseif ($request->cekubah == 'default') {
            //$validatedData['path_logo'] = '/img/logo_cart.png';
        } elseif ($request->cekubah == 'berubah') {
            if ($request->file('path_logo')) {
                if ($request->old_image == '/img/logo_cart.png') {
                    $validatedData['path_logo'] = $request->file('path_logo')->store('toko');
                } else {
                    Storage::delete($request->oldImage);
                    $validatedData['path_logo'] = $request->file('path_logo')->store('toko');
                }
            }
        }

        $setting->update($validatedData);
        return redirect(route('setting.index'))->with('success', 'Profil berhasil Di edit!');
        // return $validateku;
    }
}
