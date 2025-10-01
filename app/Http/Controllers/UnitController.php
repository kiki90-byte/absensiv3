<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class UnitController extends Controller
{
    public function index(Request $request)
    {

        $query = Unit::query();
        if (!empty($request->nama_unit)) {
            $query->where('nama_unit', 'like', '%' . $request->nama_unit . '%');
        }
        $query->orderBy('kode_unit');
        $unit = $query->paginate(10);
        $unit->appends(request()->all());
        return view('datamaster.unit.index', compact('unit'));
    }

    public function create()
    {
        return view('datamaster.unit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_unit' => 'required|max:3|unique:unit,kode_unit',
            'nama_unit' => 'required',
            'alamat_unit' => 'required',
            'telepon_unit' => 'required|numeric',
            'lokasi_unit' => 'required',
            'radius_unit' => 'required',
        ]);


        try {
            Unit::create([
                'kode_unit' => $request->kode_unit,
                'nama_unit' => $request->nama_unit,
                'alamat_unit' => $request->alamat_unit,
                'telepon_unit' => $request->telepon_unit,
                'lokasi_unit' => $request->lokasi_unit,
                'radius_unit' => $request->radius_unit,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_unit)
    {
        $kode_unit = Crypt::decrypt($kode_unit);
        $unit = Unit::where('kode_unit', $kode_unit)->first();
        return view('datamaster.unit.edit', compact('unit'));
    }


    public function update(Request $request, $kode_unit)
    {
        $kode_unit = Crypt::decrypt($kode_unit);
        $request->validate([
            'nama_unit' => 'required',
            'alamat_unit' => 'required',
            'telepon_unit' => 'required|numeric',
            'lokasi_unit' => 'required',
            'radius_unit' => 'required',
        ]);


        try {
            Unit::where('kode_unit', $kode_unit)->update([
                'nama_unit' => $request->nama_unit,
                'alamat_unit' => $request->alamat_unit,
                'telepon_unit' => $request->telepon_unit,
                'lokasi_unit' => $request->lokasi_unit,
                'radius_unit' => $request->radius_unit,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_unit)
    {
        $kode_unit = Crypt::decrypt($kode_unit);
        try {
            Unit::where('kode_unit', $kode_unit)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
