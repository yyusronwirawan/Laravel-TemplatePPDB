<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MataPelajaranController extends Controller
{
    public function index()
    {
        return view('pages.mata-pelajaran.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $data = MataPelajaran::all();

            return datatables()->of($data)
                ->editColumn('nama', function ($data) {
                    return Str::ucfirst($data->nama);
                })
                ->addColumn('aksi', function ($data) {
                    $button = '<div class="d-flex justify-content-start align-items-center">
                    <a class="btn btn-sm btn-warning" href="' . route('mata_pelajaran.h_edit', $data->id) . '">
                    <i class="fas fa-sm fa-edit"></i> Edit
                     </a>

                     <a class="btn btn-sm btn-danger mx-1 hapus" href="javascript::void(0)" data-id="' . $data->id . '">
                     <i class="fas fa-sm fa-trash-alt"></i> Hapus
                      </a>
                    </div>';

                    return $button;
                })
                ->rawColumns(['aksi', 'nama'])
                ->make(true);
        }
    }

    public function h_tambah()
    {
        return view('pages.mata-pelajaran.tambah');
    }

    public function p_tambah(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required'
        ], [
            'nama.required' => 'tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }

        $mata_pelajaran = new MataPelajaran();
        $mata_pelajaran->nama = $request->nama;
        $mata_pelajaran->save();

        if ($mata_pelajaran) {
            return response()->json([
                'status' => 'success',
                'message' => 'Mata pelajaran ditambah',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function h_edit($id)
    {
        $mata_pelajaran = MataPelajaran::find($id);

        return view('pages.mata-pelajaran.edit', [
            'mata_pelajaran' => $mata_pelajaran
        ]);
    }

    public function p_edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required'
        ], [
            'nama.required' => 'tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }

        $mata_pelajaran = MataPelajaran::find($request->id);
        $mata_pelajaran->nama = $request->nama;
        $mata_pelajaran->save();

        if ($mata_pelajaran) {
            return response()->json([
                'status' => 'success',
                'message' => 'Mata pelajaran diubah',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function p_hapus(Request $request)
    {
        $mata_pelajaran = MataPelajaran::find($request->id);
        $mata_pelajaran->delete();


        if ($mata_pelajaran) {
            return response()->json([
                'status' => 'success',
                'message' => 'Mata pelajaran dihapus',
                'title' => 'Berhasil'
            ]);
        }
    }
}
