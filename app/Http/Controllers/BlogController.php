<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Kategori;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        return view('pages.post.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $data = Blog::with(['kategori', 'tag'])->get();

            return datatables()->of($data)
                ->editColumn('image', function ($data) {
                    return '<img src="' . Storage::url($data->image) . '" class="img-thumbnail" width="100" alt="">';
                })
                ->addColumn('category', function ($data) {

                    return $data->category->name;
                })
                ->addColumn('tag', function ($data) {

                    $html = '<ul">';
                    foreach ($data->tag as $t) {
                        $html .= '<li>' .  $t->name . '</li>';
                    }
                    $html .= '</ul>';

                    return $html;
                })

                ->addColumn('aksi', function ($data) {
                    $button = '<div class="d-flex justify-content-start align-items-center">
                    <a class="btn btn-sm btn-warning" href="' . route('berita.edit', $data->id) . '">
                    <i class="fas fa-sm fa-edit"></i> Edit
                     </a>

                     <a class="btn btn-sm btn-danger mx-1 hapus" href="javascript::void(0)" data-id="' . $data->id . '">
                     <i class="fas fa-sm fa-trash-alt"></i> Hapus
                      </a>
                    </div>';

                    return $button;
                })
                ->rawColumns(['aksi', 'image', 'category', 'tag'])
                ->make(true);
        }
    }


    public function create()
    {
        return view('pages.post.create');
    }

    public function store(Request $request)
    {

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|image|mimes:png,jpg|max:2048',
            'kategori_id' => 'required',
            'konten' => 'required',
            'judul' => 'required',
            'tag' => 'required'
        ], [
            'title.required' => 'tidak boleh kosong',
            'content.required' => 'tidak boleh kosong',
            'tag.required' => 'tidak boleh kosong',
            'category_id.required' => 'tidak boleh kosong',
            'image.required' => 'tidak boleh kosong',
            'image.image' => 'harus berupa gambar',
            'image.mimes' => 'gambar harus bertipekan png, jpg',
            'image.max' => 'maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error'  => $validator->errors()->toArray()
            ]);
        }


        if ($request->hasFile('image')) {
            $gambar = $request->file('gambar')->store('assets/gambar-berita', 'public');
        }

        $blog = new Blog();
        $blog->gambar = $gambar;
        $blog->judul = $request->judul;
        $blog->slug = Str::slug($request->judul);
        $blog->konten = $request->konten;
        $blog->kategori_id = $request->category_id;
        $blog->save();

        $blog->tag()->attach($request->tag);


        if ($blog) {
            return response()->json([
                'status' => 'success',
                'message' => 'Berita ditambah',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function edit($id)
    {
        $blog = Blog::find($id);

        return view('pages.post.edit', [
            'blog' => $blog
        ]);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|image|mimes:png,jpg|max:2048',
            'kategori_id' => 'required',
            'konten' => 'required',
            'judul' => 'required',
            'tag' => 'required'
        ], [
            'title.required' => 'tidak boleh kosong',
            'content.required' => 'tidak boleh kosong',
            'tag.required' => 'tidak boleh kosong',
            'category_id.required' => 'tidak boleh kosong',
            'image.required' => 'tidak boleh kosong',
            'image.image' => 'harus berupa gambar',
            'image.mimes' => 'gambar harus bertipekan png, jpg',
            'image.max' => 'maksimal 2MB',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error'  => $validator->errors()->toArray()
            ]);
        }





        if ($request->hasFile('gambar')) {


            $gambar = $request->file('gambar')->store('assets/gambar-berita', 'public');

            $blog = Blog::find($request->id);
            $blog->gambar = $gambar;
            $blog->judul = $request->judul;
            $blog->slug = Str::slug($request->judul);
            $blog->konten = $request->konten;
            $blog->kategori_id = $request->category_id;
            $blog->save();

            $blog->tag()->sync($request->tag);

            if ($blog) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berita diubah',
                    'title' => 'Berhasil'
                ]);
            }
        } else {
            $blog = Blog::find($request->id);
            $blog->judul = $request->judul;
            $blog->slug = Str::slug($request->judul);
            $blog->konten = $request->konten;
            $blog->kategori_id = $request->category_id;
            $blog->save();

            $blog->tag()->sync($request->tag);

            if ($blog) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berita diubah',
                    'title' => 'Berhasil'
                ]);
            }
        }
    }

    public function destroy(Request $request)
    {
        $blog = Blog::find($request->id);
        $blog->delete();

        if ($blog) {
            return response()->json([
                'status' => 'success',
                'message' => 'Berita dihapus',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function listTag(Request $request)
    {
        $search = $request->q;

        if ($search == '') {
            $tag = Tag::orderBy('nama')->select('id', 'nama')->get();
        }

        $response = array();

        foreach ($tag as $t) {
            $response[] = array(
                'id' => $t->id,
                'text' => $t->nama
            );
        }

        return response()->json($response);
    }

    public function listKategori(Request $request)
    {
        $search = $request->q;

        if ($search == '') {
            $kategori = Kategori::orderBy('nama')->select('id', 'nama')->get();
        }

        $response = array();

        foreach ($kategori as $k) {
            $response[] = array(
                'id' => $k->id,
                'text' => $k->nama
            );
        }

        return response()->json($response);
    }


    public function tagPunyaBlog(Request $request)
    {
        $tag = DB::table('blog')
            ->select('tag.id as tag_id', 'tag.name as tag_name')
            ->join('blog_tag', 'blog_tag.blog_id', '=', 'blog.id')
            ->join('tag', 'post_tag.tag_id', '=', 'tag.id')
            ->where('posts.id', $request->id)
            ->get();

        $result = array();

        foreach ($tag as $t) {
            $result[] = array(
                'id' => $t->tag_id,
                'text' => $t->tag_name
            );
        }

        return response()->json($result);
    }

    public function kategoriPunyaBlog(Request $request)
    {
        $kategori = DB::table('blog')
            ->select('kategori.*')
            ->join('kategori', 'kategori.id', '=', 'blog.kategori_id')
            ->where('blog.id', $request->id)
            ->get();

        $result = array();

        foreach ($kategori as $c) {
            $result[] = array(
                'id' => $c->id,
                'text' => $c->nama
            );
        }

        return response()->json($result);
    }
}
