<?php

namespace App\Http\Controllers;

use App\Models\KalenderAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KalenderAkademikController extends Controller
{
    public function index()
    {
        return view('pages.kalender-akademik.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $data = KalenderAkademik::all();

            return datatables()->of($data)
                ->addColumn('kalender_akademik', function ($data) {
                    return $data->tahun .'/'. $data->semester;
                })
                ->addColumn('aksi', function ($data) {
                    $button = '<div class="d-flex justify-content-start align-items-center">
                    <a class="btn btn-sm btn-warning" href="' . route('kalender_akademik.h_edit', $data->id) . '">
                    <i class="fas fa-sm fa-edit"></i> Edit
                     </a>

                     <a class="btn btn-sm btn-danger mx-1 hapus" href="javascript::void(0)" data-id="' . $data->id . '">
                     <i class="fas fa-sm fa-trash-alt"></i> Hapus
                      </a>
                    </div>';

                    return $button;
                })
                ->rawColumns(['aksi', 'kalender_akademik'])
                ->make(true);
        }
    }

    public function h_tambah(Request $request)
    {
        return view('pages.kalender-akademik.tambah');
    }

    public function p_tambah(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'semester' => 'required'
        ], [
            'tahun.required' => 'tidak boleh kosong',
            'semester' => 'tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }

        $kalender_akademik = new KalenderAkademik();
        $kalender_akademik->tahun = $request->tahun;
        $kalender_akademik->semester = $request->semester;
        $kalender_akademik->save();

        if ($kalender_akademik) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kalender akademik ditambah',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function h_edit($id)
    {
        $kalender_akademik = KalenderAkademik::find($id);

        return view('pages.kalender-akademik.edit', [
            'kalender_akademik' => $kalender_akademik
        ]);
    }

    public function p_edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'semester' => 'required'
        ], [
            'tahun.required' => 'tidak boleh kosong',
            'semester' => 'tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }

        $kalender_akademik = KalenderAkademik::find($request->id);
        $kalender_akademik->tahun = $request->tahun;
        $kalender_akademik->semester = $request->semester;
        $kalender_akademik->save();

        if ($kalender_akademik) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kalender akademik diubah',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function p_hapus(Request $request)
    {
        $kalender_akademik = KalenderAkademik::find($request->id);
        $kalender_akademik->delete();

        if ($kalender_akademik) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kalender akademik dihapus',
                'title' => 'Berhasil'
            ]);
        }
    }
}
