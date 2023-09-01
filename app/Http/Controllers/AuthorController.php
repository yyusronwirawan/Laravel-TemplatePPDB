<?php

namespace App\Http\Controllers;

use App\Models\Authors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    public function index()
    {
        return view('pages.author.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $data = Authors::all();

            return datatables()->of($data)
                ->addColumn('aksi', function ($data) {
                    $button = '<div class="d-flex justify-content-start align-items-center">
                    <a class="btn btn-sm btn-warning" href="' . route('penulis.edit', $data->id) . '">
                    <i class="fas fa-sm fa-edit"></i> Edit
                     </a>

                     <a class="btn btn-sm btn-danger mx-1 hapus" href="javascript::void(0)" data-id="' . $data->id . '">
                     <i class="fas fa-sm fa-trash-alt"></i> Hapus
                      </a>
                    </div>';

                    return $button;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('pages.author.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ], [
            'name.required' => 'tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }

        $authors = new Authors();
        $authors->name = $request->name;
        $authors->save();

        if ($authors) {
            return response()->json([
                'status' => 'success',
                'message' => 'Penulis ditambah',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function edit($id)
    {
        $authors = Authors::find($id);

        return view('pages.author.edit', [
            'authors' => $authors
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ], [
            'name.required' => 'tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }

        $authors = Authors::find($request->id);

        $authors->name = $request->name;
        $authors->save();


        if ($authors) {
            return response()->json([
                'status' => 'success',
                'message' => 'Penulis diubah',
                'title' => 'Berhasil'
            ]);
        }
    }


    public function destroy(Request $request)
    {
        $authors = Authors::find($request->id);

        $authors->delete();

        if ($authors) {
            return response()->json([
                'status' => 'success',
                'message' => 'Penulis dihapus',
                'title' => 'Berhasil'
            ]);
        }
    }
}
