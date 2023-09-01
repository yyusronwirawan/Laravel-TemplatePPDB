<?php

namespace App\Http\Controllers;

use App\Models\Ekstrakurikuler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EkstrakurikulerController extends Controller
{
    public function index()
    {
        return view('pages.ekstrakurikuler.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $data = Ekstrakurikuler::all();

            return datatables()->of($data)
                ->editColumn('logo', function ($data) {
                    return '<img src="' . Storage::url($data->logo) . '" class="img-thumbnail" width="100">';
                })
                ->addColumn('aksi', function ($data) {
                    $button = '<div class="d-flex justify-content-start align-items-center">
                    <a class="btn btn-sm btn-warning" href="' . route('ekstrakurikuler.h_edit', $data->id) . '">
                    <i class="fas fa-sm fa-edit"></i> Edit
                     </a>

                     <a class="btn btn-sm btn-danger mx-1 hapus" href="javascript::void(0)" data-id="' . $data->id . '">
                     <i class="fas fa-sm fa-trash-alt"></i> Hapus
                      </a>
                    </div>';

                    return $button;
                })
                ->rawColumns(['aksi', 'logo'])
                ->make(true);
        }
    }

    public function h_tambah()
    {
        return view('pages.ekstrakurikuler.tambah');
    }

    public function p_tambah(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'logo' => 'required',
            'deskripsi' => 'required'
        ], [
            'nama.required' => 'tidak boleh kosong',
            'logo.required' => 'tidak boleh kosong',
            'deskripsi.required' => 'tidak boleh kosong',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }

        $ekstrakurikuler = new Ekstrakurikuler();
        $ekstrakurikuler->nama = $request->nama;
        $ekstrakurikuler->logo = $request->file('logo')
            ->store('assets/gambar-ekstrakurikuler', 'public');
        $ekstrakurikuler->deskripsi = $request->deskripsi;
        $ekstrakurikuler->save();

        if ($ekstrakurikuler) {
            return response()->json([
                'status' => 'success',
                'message' => 'Ekstrakurikuler ditambah',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function h_edit($id)
    {
        $ekstrakurikuler = Ekstrakurikuler::find($id);

        return view('pages.ekstrakurikuler.edit', [
            'ekstrakurikuler' => $ekstrakurikuler
        ]);
    }

    public function p_edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'deskripsi' => 'required'
        ], [
            'nama.required' => 'tidak boleh kosong',
            'deskripsi.required' => 'tidak boleh kosong',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }

        if ($request->file('logo')) {

            $ekstrakurikuler = Ekstrakurikuler::find($request->id);
            $ekstrakurikuler->nama = $request->nama;
            $ekstrakurikuler->logo = $request->file('logo')
                ->store('assets/gambar-ekstrakurikuler', 'public');
            $ekstrakurikuler->deskripsi = $request->deskripsi;
            $ekstrakurikuler->save();

            if ($ekstrakurikuler) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Ekstrakurikuler ditambah',
                    'title' => 'Berhasil'
                ]);
            }
        } else {

            $ekstrakurikuler = Ekstrakurikuler::find($request->id);
            $ekstrakurikuler->nama = $request->nama;
            $ekstrakurikuler->deskripsi = $request->deskripsi;
            $ekstrakurikuler->save();

            if ($ekstrakurikuler) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Ekstrakurikuler diubah',
                    'title' => 'Berhasil'
                ]);
            }
        }
    }

    public function p_hapus(Request $request)
    {
        $ekstrakurikuler = Ekstrakurikuler::find($request->id);
        $ekstrakurikuler->delete();

        if ($ekstrakurikuler) {
            return response()->json([
                'status' => 'success',
                'message' => 'Ekstrakurikuler dihapus',
                'title' => 'Berhasil'
            ]);
        }
    }
}
