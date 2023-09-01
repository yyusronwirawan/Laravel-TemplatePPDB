<?php

namespace App\Http\Controllers;

use App\Models\Authors;
use App\Models\Books;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index()
    {
        return view('pages.book.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $data = Books::with(['author'])->get();


            return datatables()->of($data)
                ->addColumn('author', function ($data) {
                    $html = '<ul">';
                    foreach ($data->author as $at) {
                        $html .= '<li>' .  $at->name . '</li>';
                    }
                    $html .= '</ul>';

                    return $html;
                })
                ->editColumn('cover', function ($data) {
                    return '<img src="' . Storage::url($data->cover) . '" width="100" class="img-thumbnail"/>';
                })
                ->addColumn('aksi', function ($data) {
                    $button = '<div class="d-flex justify-content-start align-items-center">
                    <a class="btn btn-sm btn-warning" href="' . route('buku.edit', $data->id) . '">
                    <i class="fas fa-sm fa-edit"></i> Edit
                     </a>

                     <a class="btn btn-sm btn-danger mx-1 hapus" href="javascript::void(0)" data-id="' . $data->id . '">
                     <i class="fas fa-sm fa-trash-alt"></i> Hapus
                      </a>
                    </div>';

                    return $button;
                })
                ->rawColumns(['aksi', 'cover', 'author'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('pages.book.create');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'cover' => 'required|image|mimes:png,jpg|max:2048',
            'description' => 'required',
            'stock' => 'required',
        ], [
            'title.required' => 'tidak boleh kosong',
            'cover.required' => 'tidak boleh kosong',
            'cover.image' => 'harus berupa gambar',
            'cover.mimes' => 'gambar harus png atau jpg',
            'cover.max' => 'maksimal 2MB',
            'description.required' => 'tidak boleh kosong',
            'stock.required' => 'tidak boleh kosong',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }


        $books = new Books();
        $books->title = $request->title;
        $books->cover = $request->file('cover')->store('assets/gambar-buku', 'public');
        $books->description = $request->description;
        $books->stock = $request->stock;
        $books->save();

        $books->author()->attach($request->author);

        if ($books) {
            return response()->json([
                'status' => 'success',
                'message' => 'Buku ditambah',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function edit($id)
    {
        $books = Books::find($id);

        return view('pages.book.edit', [
            'books' => $books
        ]);
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'cover' => 'image|mimes:png,jpg|max:2048',
            'description' => 'required',
            'stock' => 'required'
        ], [
            'title.requred' => 'tidak boleh kosong',

            'cover.image' => 'harus berupa gambar',
            'cover.mimes' => 'gambar harus png,jpg',
            'cover.max' => 'maksimal 2MB',
            'description.required' => 'tidak boleh kosong',
            'stock.required' => 'tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }



        if ($request->hasFile('cover')) {
            $books = Books::find($request->id);
            $books->title = $request->title;
            $books->cover = $request->file('stock')->store('assets/gambar-buku', 'public');
            $books->description = $request->description;
            $books->stock = $request->stock;
            $books->save();


            if ($books) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Buku diubah',
                    'title' => 'Berhasil'
                ]);
            }
        } else {
            $books = Books::find($request->id);
            $books->title = $request->title;
            $books->description = $request->description;
            $books->stock = $request->stock;
            $books->save();
            if ($books) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Buku ditambah',
                    'title' => 'Berhasil'
                ]);
            }
        }
    }


    public function listAuthor(Request $request)
    {
        $author = Authors::orderBy('name', 'ASC')->select('id', 'name')->get();

        // dd($author);

        $response = array();

        foreach ($author as $a) {
            $response[] = array(
                'id' => $a->id,
                'text' => $a->name
            );
        }

        return response()->json($response);
    }


    public function authorByBook(Request $request)
    {
        $author = DB::table('books')
            ->select('authors.*')
            ->join('book_auhtors', 'book_auhtors.books_id', '=', 'books.id')
            ->join('authors', 'book_auhtors.authors_id', '=', 'authors.id')
            ->where('books.id', $request->books_id)
            ->get();

        $response = array();

        foreach ($author as $a) {
            $response[] = array(
                'id' => $a->id,
                'text' => $a->name
            );
        }

        return response()->json($response);
    }

    public function destroy(Request $request)
    {
        $books = Books::find($request->id);

        $books->delete();

        if ($books) {
            return response()->json([
                'status' => 'success',
                'message' => 'Buku dihapus',
                'title' => 'Berhasil'
            ]);
        }
    }
}
