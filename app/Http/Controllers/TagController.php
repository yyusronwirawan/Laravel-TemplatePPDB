<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TagController extends Controller
{

    public function index()
    {
        return view('pages.tag.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $data = Tag::all();

            return datatables()->of($data)
                ->addColumn('aksi', function ($data) {
                    $button = '<div class="d-flex justify-content-start align-items-center">
                    <a class="btn btn-sm btn-warning" href="' . route('tag.edit', $data->id) . '">
                    <i class="fas fa-sm fa-edit"></i> Edit
                     </a>

                     <a class="btn btn-sm btn-danger mx-1 hapus" href="javascript::void(0)" data-id="'.$data->id.'">
                     <i class="fas fa-sm fa-trash-alt"></i> Hapus
                      </a>
                    </div>';

                    return $button;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function h_tambah()
    {
        return view('pages.tag.tambah');
    }


    public function p_tambah(Request $request)
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

        $tags = new Tag();
        $tags->name = $request->name;
        $tags->slug = Str::slug($request->name);
        $tags->save();


        if ($tags) {
            return response()->json([
                'status' => 'success',
                'message' => 'Tag ditambah',
                'title' => 'Behasil'
            ]);
        }
    }

    public function h_edit($id)
    {
        $tag = Tag::find($id);

        return view('pages.tag.edit', [
            'tag' => $tag
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

        $tags = Tag::find($request->id);
        $tags->name = $request->name;
        $tags->slug = Str::slug($request->name);
        $tags->save();


        if ($tags) {
            return response()->json([
                'status' => 'success',
                'message' => 'Tag diubah',
                'title' => 'Behasil'
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $tag = Tag::find($request->id);

        $tag->delete();

        if ($tag) {
            return response()->json([
                'status' => 'success',
                'message' => 'Tag dihapus',
                'title' => 'Behasil'
            ]);
        }
    }
}
