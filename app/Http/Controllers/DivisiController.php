<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class DivisiController extends Controller
{

    public function index(Request $request)
    {
        $query = Divisi::query();
        $data['divisi'] = $query->get();
        return view('datamaster.divisi.index', $data);
    }

    public function create()
    {
        return view('datamaster.divisi.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'kode_div' => 'required',
            'nama_div' => 'required'
        ]);
        try {
            //Simpan Data Departemen
            Divisi::create([
                'kode_div' => $request->kode_div,
                'nama_div' => $request->nama_div,
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_div)
    {
        $kode_div = Crypt::decrypt($kode_div);
        $data['divisi'] = Divisi::where('kode_div', $kode_div)->first();
        return view('datamaster.divisi.edit', $data);
    }

    public function update($kode_div, Request $request)
    {
        $kode_div = Crypt::decrypt($kode_div);

        $request->validate([
            'kode_div' => 'required',
            'nama_div' => 'required'
        ]);
        try {
            //Simpan Data Divisi
            Divisi::where('kode_div', $kode_div)->update([
                'kode_div' => $request->kode_div,
                'nama_div' => $request->nama_div
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_div)
    {
        $kode_div = Crypt::decrypt($kode_div);
        try {
            Divisi::where('kode_div', $kode_div)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
