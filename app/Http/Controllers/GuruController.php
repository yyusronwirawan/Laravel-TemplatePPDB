<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GuruController extends Controller
{
    public function index()
    {
        return view('pages.guru.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $data = Guru::with(['user'])->get();

            return datatables()->of($data)
                ->editColumn('status', function ($data) {
                    return ($data->status == 1 ? 'Aktif' : 'Tidak aktif');
                })
                ->editColumn('nama_lengkap', function ($data) {
                    return Str::ucfirst($data->nama_lengkap);
                })
                ->editColumn('foto', function ($data) {
                    return '<img src="' . Storage::url($data->foto) . '" class="img-thumbnail" width="100">';
                })
                ->addColumn('aksi', function ($data) {
                    $button = '<div class="d-flex justify-content-start align-items-center">
                    <a class="btn btn-sm btn-warning" href="' . route('guru.h_edit', $data->id) . '">
                    <i class="fas fa-sm fa-edit"></i> Edit
                     </a>

                     <a class="btn btn-sm btn-danger mx-1 hapus" href="javascript::void(0)" data-id="' . $data->id . '">
                     <i class="fas fa-sm fa-trash-alt"></i> Hapus
                      </a>
                    </div>';

                    return $button;
                })
                ->rawColumns(['aksi', 'foto', 'nama_lengkap', 'status'])
                ->make(true);
        }
    }

    public function h_tambah(Request $request)
    {
        return view('pages.guru.tambah');
    }

    public function p_tambah(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'email_pribadi' => 'required',
            'alamat' => 'required',
            'foto' => 'required|image|mimes:png,jpg|max:2048',
            'no_telepon' => 'required',
        ], [
            'nama_lengkap.required' => 'tidak boleh kosong',
            'email_pribadi.required' => 'tidak boleh kosong',
            'alamat.required' => 'tidak boleh kosong',
            'foto.required' => 'tidak boleh kosong',
            'foto.image' => 'harus berupa gambar',
            'foto.mimes' => 'tipe gambar harus png atau jpg',
            'foto.max' => 'maksimal ukuran gambar hanya 2MB',
            'no_telepon' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->toArray()
            ]);
        }

        // membuat akun guru
        $user =  User::create([
            'name' => $request->nama_lengkap,
            'email' => $request->nama_lengkap . '@gmail.com',
            'password' => $request->password,
        ]);

        $user->assignRole($request->role);


        $guru = new Guru();
        $guru->nama_lengkap = $request->nama_lengkap;
        $guru->alamat = $request->alamat;
        $guru->email_pribadi = $request->email_pribadi;
        $guru->foto = $request->file('foto')->store('assets/gambar-guru', 'public');
        $guru->no_telepon = $request->no_telepon;
        $guru->masa_awal_bergabung = $request->masa_awal_bergabung;
        $guru->masa_akhir_bergabung = $request->masa_akhir_bergabung;
        $guru->status = $request->status;
        $guru->save();


        if ($guru) {
            return response()->json([
                'status' => 'success',
                'message' => 'Guru ditambah',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function h_edit($id)
    {
        $guru = Guru::find($id);

        return view('pages.guru.edit', [
            'guru' => $guru
        ]);
    }

    public function p_edit(Request $request)
    {
        if ($request->hasFile('foto')) {

            $guru = Guru::find($request->id);
            $guru->alamat = $request->alamat;
            $guru->email_pribadi = $request->email_pribadi;
            $guru->bidang_pelajaran = $request->bidang_pelajaran;
            $guru->foto = $request->file('foto')->store('assets/gambar-guru', 'public');
            $guru->no_telepon = $request->no_telepon;
            $guru->masa_awal_bergabung = $request->masa_awal_bergabung;
            $guru->masa_akhir_bergabung = $request->masa_akhir_bergabung;
            $guru->save();

            if ($guru) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Guru ditambah',
                    'title' => 'Berhasil'
                ]);
            }
        } else {
            $guru = Guru::find($request->id);
            $guru->alamat = $request->alamat;
            $guru->email_pribadi = $request->email_pribadi;
            $guru->bidang_pelajaran = $request->bidang_pelajaran;
            $guru->no_telepon = $request->no_telepon;
            $guru->masa_awal_bergabung = $request->masa_awal_bergabung;
            $guru->masa_akhir_bergabung = $request->masa_akhir_bergabung;
            $guru->save();

            if ($guru) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Guru ditambah',
                    'title' => 'Berhasil'
                ]);
            }
        }
    }

    public function p_hapus(Request $request)
    {
        $guru = Guru::find($request->id);

        $guru->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Guru dihapus',
            'title' => 'Berhasil'
        ]);
    }
}
