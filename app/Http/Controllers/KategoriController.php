<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index()
    {
        return view('pages.kategori.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $data = Kategori::all();

            return datatables()->of($data)
                ->editColumn('nama', function ($data) {
                    return Str::ucfirst($data->nama);
                })
                ->addColumn('aksi', function ($data) {
                    $button = '<div class="d-flex justify-content-start align-items-center">
                    <a class="btn btn-sm btn-warning" href="' . route('kategori.h_edit', $data->id) . '">
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
        return view('pages.kategori.tambah');
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

        $kategori = new Kategori();
        $kategori->nama = $request->nama;
        $kategori->slug = Str::slug($request->name);
        $kategori->save();


        if ($kategori) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kategori ditambah',
                'title' => 'Behasil'
            ]);
        }
    }

    public function h_edit($id)
    {
        $kategori = Kategori::find($id);

        return view('pages.kategori.edit', [
            'kategori' => $kategori
        ]);
    }

    public function p_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required'
        ], [
            'name.required' => 'tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }

        $kategori = Kategori::find($request->id);
        $kategori->nama = $request->name;
        $kategori->slug = Str::slug($request->name);
        $kategori->save();


        if ($kategori) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kategori diubah',
                'title' => 'Behasil'
            ]);
        }
    }

    public function p_hapus(Request $request)
    {
        $kategori = Kategori::find($request->id);

        $kategori->delete();

        if ($kategori) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kategori dihapus',
                'title' => 'Behasil'
            ]);
        }
    }
}
