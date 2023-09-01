<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EkstrakurikulerController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KalenderAkademikController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});


Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::prefix('dashboard')
    // ->middleware('auth')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        // bagian tag
        Route::get('/tag', [TagController::class, 'index'])
            ->name('tag.index');
        Route::get('tag.data', [TagController::class, 'data'])
            ->name('tag.data');
        Route::get('/tag/tambah', [TagController::class, 'create'])
            ->name('tag.create');
        Route::post('tag.ptambah', [TagController::class, 'store'])
            ->name('tag.store');
        Route::get('/tag/edit/{id}', [TagController::class, 'edit'])
            ->name('tag.edit');
        Route::post('tag.update', [TagController::class, 'update'])
            ->name('tag.update');
        Route::post('tag.hapus', [TagController::class, 'destroy'])
            ->name('tag.hapus');

        // bagian kategori
        Route::get('/kategori', [KategoriController::class, 'index'])
            ->name('kategori.index');
        Route::get('kategori.data', [KategoriController::class, 'data'])
            ->name('kategori.data');
        Route::get('/kategori/tambah', [KategoriController::class, 'h_tambah'])
            ->name('kategori.h_tambah');
        Route::post('kategori.ptambah', [KategoriController::class, 'p_tambah'])
            ->name('kategori.p_tambah');
        Route::get('/kategori/h_edit/{id}', [KategoriController::class, 'h_edit'])
            ->name('kategori.h_edit');
        Route::post('kategori.p_update', [KategoriController::class, 'p_update'])
            ->name('kategori.p_update');
        Route::post('kategori.p_hapus', [KategoriController::class, 'p_hapus'])
            ->name('kategori.p_hapus');

        Route::get('/berita', [PostController::class, 'index'])
            ->name('berita');
        Route::get('berita.data', [PostController::class, 'data'])
            ->name('berita.data');
        Route::get('/berita/h_tambah', [PostController::class, 'create'])
            ->name('berita.create');
        Route::post('berita.ptambah', [PostController::class, 'store'])
            ->name('berita.store');
        Route::get('/berita/edit/{id}', [PostController::class, 'edit'])
            ->name('berita.edit');
        Route::post('berita.update', [PostController::class, 'update'])
            ->name('berita.update');
        Route::post('berita.hapus', [PostController::class, 'destroy'])
            ->name('berita.hapus');
        Route::get('berita.lisTag', [PostController::class, 'listTag'])
            ->name('berita.listTag');
        Route::get('berita.listCategory', [PostController::class, 'listCategory'])
            ->name('berita.listCategory');
        Route::get('berita/tagByPost', [PostController::class, 'tagByPost'])
            ->name('berita.tagByPost');
        Route::get('berita/categoryByPost', [PostController::class, 'categoryByPost'])
            ->name('berita.categoryByPost');



        // bagian perpustkaan

        // penulis buku
        Route::get('penulis', [AuthorController::class, 'index'])
            ->name('penulis');
        Route::get('penulis.data', [AuthorController::class, 'data'])
            ->name('penulis.data');
        Route::get('penulis/h_tambah', [AuthorController::class, 'create'])
            ->name('penulis.create');
        Route::post('penulis.store', [AuthorController::class, 'store'])
            ->name('penulis.store');
        Route::get('penulis/h_edit/{id}', [AuthorController::class, 'edit'])
            ->name('penulis.edit');
        Route::post('penulis.update', [AuthorController::class, 'update'])
            ->name('penulis.update');
        Route::post('penulis.destroy', [AuthorController::class, 'destroy'])
            ->name('penulis.destroy');


        // buku
        Route::get('buku', [BookController::class, 'index'])
            ->name('buku');
        Route::get('buku.data', [BookController::class, 'data'])
            ->name('buku.data');
        Route::get('buku/h_tambah', [BookController::class, 'create'])
            ->name('buku.create');
        Route::post('buku.store', [BookController::class, 'store'])
            ->name('buku.store');
        Route::get('buku/h_edit/{id}', [BookController::class, 'edit'])
            ->name('buku.edit');
        Route::post('buku.update', [BookController::class, 'update'])
            ->name('buku.update');
        Route::post('buku.destroy', [BookController::class, 'destroy'])
            ->name('buku.destroy');
        Route::get('buku.listAuthor', [BookController::class, 'listAuthor'])
            ->name('buku.listAuthor');
        Route::get('buku.authorByBook', [BookController::class, 'authorByBook'])
            ->name('buku.authorByBook');

        // jurusan
        Route::get('jurusan', [JurusanController::class, 'index'])
            ->name('jurusan');
        Route::get('jurusan.data', [JurusanController::class, 'data'])
            ->name('jurusan.data');
        Route::get('jurusan/h_tambah', [JurusanController::class, 'h_tambah'])
            ->name('jurusan.h_tambah');
        Route::post('jurusan.p_tambah', [JurusanController::class, 'p_tambah'])
            ->name('jurusan.p_tambah');
        Route::get('jurusan/h_edit/{id}', [JurusanController::class, 'h_edit'])
            ->name('jurusan.h_edit');
        Route::post('jurusan/p_edit', [JurusanController::class, 'p_edit'])
            ->name('jurusan.p_edit');
        Route::post('jurusan/p_hapus', [JurusanController::class, 'p_hapus'])
            ->name('jurusan.p_hapus');

        // kalender akademik
        Route::get('kalender_akademik', [KalenderAkademikController::class, 'index'])
            ->name('kalender_akademik');
        Route::get('kalender_akademik.data', [KalenderAkademikController::class, 'data'])
            ->name('kalender_akademik.data');
        Route::get('kalender_akademik/h_tambah', [KalenderAkademikController::class, 'h_tambah'])
            ->name('kalender_akademik.h_tambah');
        Route::post('kalender_akademik.p_tambah', [KalenderAkademikController::class, 'p_tambah'])
            ->name('kalender_akademik.p_tambah');
        Route::get('kalender_akademik/h_edit/{id}', [KalenderAkademikController::class, 'h_edit'])
            ->name('kalender_akademik.h_edit');
        Route::post('kalender_akademik.p_edit', [KalenderAkademikController::class, 'p_edit'])
            ->name('kalender_akademik.p_edit');
        Route::post('kalender_akademik.p_hapus', [KalenderAkademikController::class, 'p_hapus'])
            ->name('kalender_akademik.p_hapus');

        // ekstrakurikuler
        Route::get('ekstrakurikuler', [EkstrakurikulerController::class, 'index'])
            ->name('ekstrakurikuler');
        Route::get('ekstrakurikuler.data', [EkstrakurikulerController::class, 'data'])
            ->name('ekstrakurikuler.data');
        Route::get('ekstrakurikuler/h_tambah', [EkstrakurikulerController::class, 'h_tambah'])
            ->name('ekstrakurikuler.h_tambah');
        Route::post('ekstrakurikuler.p_tambah', [EkstrakurikulerController::class, 'p_tambah'])
            ->name('ekstrakurikuler.p_tambah');
        Route::get('ekstrakurikuler/h_edit/{id}', [EkstrakurikulerController::class, 'h_edit'])
            ->name('ekstrakurikuler.h_edit');
        Route::post('ekstrakurikuler/p_edit', [EkstrakurikulerController::class, 'p_edit'])
            ->name('ekstrakurikuler.p_edit');
        Route::post('ekstrakurikuler.p_hapus', [EkstrakurikulerController::class, 'p_hapus'])
            ->name('ekstrakurikuler.p_hapus');

        // kelas
        Route::get('kelas', [KelasController::class, 'index'])
            ->name('kelas');
        Route::get('kelas.data', [KelasController::class, 'data'])
            ->name('kelas.data');
        Route::get('kelas/h_tambah', [KelasController::class, 'h_tambah'])
            ->name('kelas.h_tambah');
        Route::post('kelas.p_tambah', [KelasController::class, 'p_tambah'])
            ->name('kelas.p_tambah');
        Route::get('kelas/h_edit/{id}', [KelasController::class, 'h_edit'])
            ->name('kelas.h_edit');
        Route::post('kelas.p_edit', [KelasController::class, 'p_edit'])
            ->name('kelas.p_edit');
        Route::post('kelas.p_hapus', [KelasController::class, 'p_hapus'])
            ->name('kelas.p_hapus');
        Route::get('kelas.listJurusan', [KelasController::class, 'listJurusan'])
            ->name('kelas.listJurusan');
        Route::get('kelas/listJurusan/{id}', [KelasController::class, 'jurusanByKelas'])
            ->name('kelas.jurusanByKelas');


        // guru
        Route::get('guru', [GuruController::class, 'index'])
            ->name('guru');
        Route::get('guru.data', [GuruController::class, 'data'])
            ->name('guru.data');
        Route::get('guru.h_tambah', [GuruController::class, 'h_tambah'])
            ->name('guru.h_tambah');
        Route::post('guru.p_tambah', [GuruController::class, 'p_tambah'])
            ->name('guru.p_tambah');
        Route::get('guru.h_edit', [GuruController::class, 'h_edit'])
            ->name('guru.h_edit');
        Route::post('guru.p_hapus', [GuruController::class, 'p_hapus'])
            ->name('guru.p_hapus');


        // mata pelajaran
        Route::get('mata_pelajaran', [MataPelajaranController::class, 'index'])
            ->name('mata_pelajaran');
        Route::get('mata_pelajaran.data', [MataPelajaranController::class, 'data'])
            ->name('mata_pelajaran.data');
        Route::get('mata_pelajaran/h_tambah', [MataPelajaranController::class, 'h_tambah'])
            ->name('mata_pelajaran/h_tambah');
        Route::post('mata_pelajaran.p_tambah', [MataPelajaranController::class, 'p_tambah'])
            ->name('mata_pelajaran.p_tambah');
        Route::get('mata_pelajaran/h_edit/{id}', [MataPelajaranController::class, 'h_edit'])
            ->name('mata_pelajaran.h_edit');
        Route::post('mata_pelajaran.p_edit', [MataPelajaranController::class, 'p_edit'])
            ->name('mata_pelajaran.p_edit');
        Route::post('mata_pelajaran.p_hapus', [MataPelajaranController::class, 'p_hapus'])
            ->name('mata_pelajaran.p_hapus');
    });


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
