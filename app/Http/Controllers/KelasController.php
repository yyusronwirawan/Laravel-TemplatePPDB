<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KelasController extends Controller
{
    public function index()
    {
        return view('pages.kelas.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $data = Kelas::with(['jurusan'])->get();

            return datatables()->of($data)
                ->editColumn('kelas', function ($data) {
                    return Str::ucfirst($data->kelas);
                })
                ->addColumn('jurusan', function ($data) {
                    return $data->jurusan->nama_jurusan;
                })
                ->addColumn('aksi', function ($data) {
                    $button = '<div class="d-flex justify-content-start align-items-center">
                    <a class="btn btn-sm btn-warning" href="' . route('kelas.h_edit', $data->id) . '">
                    <i class="fas fa-sm fa-edit"></i> Edit
                     </a>

                     <a class="btn btn-sm btn-danger mx-1 hapus" href="javascript::void(0)" data-id="' . $data->id . '">
                     <i class="fas fa-sm fa-trash-alt"></i> Hapus
                      </a>
                    </div>';

                    return $button;
                })
                ->rawColumns(['aksi', 'kelas', 'jurusan'])
                ->make(true);
        }
    }

    public function h_tambah()
    {
        return view('pages.kelas.tambah');
    }

    public function p_tambah(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kelas' => 'required',
            'jurusan_id' => 'required'
        ], [
            'kelas.required' => 'tidak boleh kosong',
            'jurusan_id.required' => 'tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }

        $kelas = new Kelas();
        $kelas->kelas = $request->kelas;
        $kelas->jurusan_id = $request->jurusan_id;
        $kelas->save();

        if ($kelas) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kelas ditambah',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function h_edit($id)
    {
        $kelas = Kelas::find($id);

        return view('pages.kelas.edit', [
            'kelas' => $kelas
        ]);
    }

    public function p_edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kelas' => 'required'
        ], [
            'kelas.required' => 'tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }

        $kelas = Kelas::find($request->id);
        $kelas->kelas = $request->kelas;
        $kelas->jurusan_id = $request->jurusan_id;
        $kelas->save();

        if ($kelas) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kelas diubah',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function p_hapus(Request $request)
    {
        $kelas = Kelas::find($request->id);

        $kelas->delete();

        if ($kelas) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kelas dihapus',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function listJurusan(Request $request)
    {
        if ($request->has('q')) {
            $search = $request->q;

            $jurusan = Jurusan::select("id", "nama_jurusan")
                ->Where('nama_jurusan', 'LIKE', "%$search%")
                ->get();

            $response = array();
            foreach ($jurusan as $j) {
                $response[] = array(
                    'id' => $j->id,
                    'text' => $j->nama_jurusan
                );
            }
            return response()->json($response);
        } else {
            $jurusan = Jurusan::select("id", "nama_jurusan")
                ->get();

            $response = array();
            foreach ($jurusan as $j) {
                $response[] = array(
                    'id' => $j->id,
                    'text' => $j->nama_jurusan
                );
            }
            return response()->json($response);
        }
    }


    public function jurusanByKelas($id)
    {
        $jurusan = DB::table('kelas')
            ->select('jurusan.id as id_jurusan', 'jurusan.nama_jurusan as nama_jurusan')
            ->join('jurusan', 'jurusan.id', '=', 'kelas.jurusan_id')
            ->where('kelas.id', $id)
            ->get();

        $response = array();
        foreach ($jurusan as $j) {
            $response[] = array(
                'id' => $j->id_jurusan,
                'text' => $j->nama_jurusan
            );
        }

        return response()->json($response);
    }
}
