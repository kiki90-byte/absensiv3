<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Divisi;
use App\Models\Detailsetjamkerjabydiv;
use App\Models\Jamkerja;
use App\Models\Setjamkerjabydiv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class JamkerjabydivController extends Controller
{
    public function index(Request $request)
    {
        $query = Setjamkerjabydiv::query();
        $query->join('unit', 'presensi_jamkerja_bydiv.kode_unit', '=', 'unit.kode_unit');
        $query->join('divisi', 'presensi_jamkerja_bydiv.kode_div', '=', 'divisi.kode_div');
        $query->select('presensi_jamkerja_bydiv.*', 'unit.nama_unit', 'divisi.nama_div');

        if (!empty($request->kode_unit)) {
            $query->where('presensi_jamkerja_bydiv.kode_unit', $request->kode_unit);
        }
        $data['jamkerjabydiv'] = $query->paginate(15);
        $data['unit'] = Unit::orderBy('kode_unit')->get();
        return view('jamkerjabydiv.index', $data);
    }

    public function create()
    {
        $data['unit'] = Unit::orderby('kode_unit')->get();
        $data['divisi'] = Divisi::orderBy('kode_div')->get();
        $data['jamkerja'] = Jamkerja::orderBy('kode_jam_kerja')->get();
        return view('jamkerjabydiv.create', $data);
    }


    public function store(Request $request)
    {
        $request->validate([
            'kode_unit' => 'required',
            'kode_div' => 'required',
        ]);
        $kode_unit = $request->kode_unit;
        $kode_div = $request->kode_div;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;
        // dd($kode_jam_kerja);
        $cekjamkerjabydiv = Setjamkerjabydiv::where('kode_unit', $kode_unit)
            ->where('kode_div', $kode_div)
            ->first();

        if ($cekjamkerjabydiv) {
            return Redirect::back()->with(messageError('Data Jam Kerja Sudah Ada'));
        }
        DB::beginTransaction();
        try {

            Setjamkerjabydiv::create([
                'kode_jk_div' => 'J' . $kode_unit . $kode_div,
                'kode_unit' => $kode_unit,
                'kode_div' => $kode_div
            ]);

            for ($i = 0; $i < count($hari); $i++) {
                if (!empty($kode_jam_kerja[$i])) {
                    Detailsetjamkerjabydiv::create([
                        'kode_jk_div' => 'J' . $kode_unit . $kode_div,
                        'hari' => $hari[$i],
                        'kode_jam_kerja' => $kode_jam_kerja[$i]
                    ]);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_jk_div)
    {
        $kode_jk_div = Crypt::decrypt($kode_jk_div);

        $data['jamkerjabydiv'] = Setjamkerjabydiv::where('kode_jk_div', $kode_jk_div)
            ->join('unit', 'presensi_jamkerja_bydiv.kode_unit', '=', 'unit.kode_unit')
            ->join('divisi', 'presensi_jamkerja_bydiv.kode_div', '=', 'divisi.kode_div')
            ->first();
        $data['jamkerja'] = Jamkerja::orderBy('kode_jam_kerja')->get();
        $data['detailjamkerjabydiv'] = Detailsetjamkerjabydiv::where('kode_jk_div', $kode_jk_div)->pluck('kode_jam_kerja', 'hari')->toArray();
        return view('jamkerjabydiv.edit', $data);
    }

    public function update($kode_jk_div, Request $request)
    {
        $kode_jk_div = Crypt::decrypt($kode_jk_div);
        $jamkerjabydiv = Setjamkerjabydiv::where('kode_jk_div', $kode_jk_div)->first();
        $kode_unit = $jamkerjabydiv->kode_unit;
        $kode_div = $jamkerjabydiv->kode_div;

        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;

        DB::beginTransaction();
        try {

            Detailsetjamkerjabydiv::where('kode_jk_div', $kode_jk_div)->delete();
            for ($i = 0; $i < count($hari); $i++) {
                if (!empty($kode_jam_kerja[$i])) {
                    Detailsetjamkerjabydiv::create([
                        'kode_jk_div' => 'J' . $kode_unit . $kode_div,
                        'hari' => $hari[$i],
                        'kode_jam_kerja' => $kode_jam_kerja[$i]
                    ]);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_jk_div)
    {
        $kode_jk_div = Crypt::decrypt($kode_jk_div);
        try {
            Setjamkerjabydiv::where('kode_jk_div', $kode_jk_div)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
