<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class JurusanController extends Controller
{
    public function index()
    {
        return view('pages.jurusan.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $data = Jurusan::all();

            return datatables()->of($data)
                ->editColumn('nama', function ($data) {
                    return Str::ucfirst($data->nama_jurusan);
                })
                ->addColumn('aksi', function ($data) {
                    $button = '<div class="d-flex justify-content-start align-items-center">
                    <a class="btn btn-sm btn-warning" href="' . route('jurusan.h_edit', $data->id) . '">
                    <i class="fas fa-sm fa-edit"></i> Edit
                     </a>

                     <a class="btn btn-sm btn-danger mx-1 hapus" href="javascript::void(0)" data-id="' . $data->id . '">
                     <i class="fas fa-sm fa-trash-alt"></i> Hapus
                      </a>
                    </div>';

                    return $button;
                })
                ->rawColumns(['aksi', 'nama_jurusan'])
                ->make(true);
        }
    }


    public function h_tambah()
    {
        return view('pages.jurusan.tambah');
    }

    public function p_tambah(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jurusan' => 'required',
        ], [
            'nama_jurusan.required' => 'tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }

        $jurusan = new Jurusan();
        $jurusan->nama_jurusan = $request->nama_jurusan;
        $jurusan->save();

        if ($jurusan) {
            return response()->json([
                'status' => 'success',
                'message' => 'Jurusan ditambah',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function h_edit($id)
    {
        $jurusan = Jurusan::find($id);

        return view('pages.jurusan.edit', [
            'jurusan' => $jurusan
        ]);
    }


    public function p_edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jurusan' => 'required',
        ], [
            'nama_jurusan.required' => 'tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }

        $jurusan = Jurusan::find($request->id);
        $jurusan->nama_jurusan = $request->nama_jurusan;
        $jurusan->save();

        if ($jurusan) {
            return response()->json([
                'status' => 'success',
                'message' => 'Jurusan diubah',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $jurusan = Jurusan::find($request->id);

        $jurusan->delete();

        if ($jurusan) {
            return response()->json([
                'status' => 'success',
                'message' => 'Jurusan dihapus',
                'title' => 'Berhasil'
            ]);
        }
    }
}
