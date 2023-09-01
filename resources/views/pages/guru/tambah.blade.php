@extends('layouts.admin')

@section('title', 'Guru')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Guru</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('guru') }}">Guru</a></div>
                    <div class="breadcrumb-item">Tambah Guru</div>
                </div>
            </div>


            <div class="d-flex justify-content-start">
                <a href="{{ route('jurusan') }}" class="btn btn-sm btn-primary mb-3">
                    <i class="fas fa-sm fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card shadow card-primary">
                <div class="card-header">
                    <div class="d-flex justify-content-start align-items-center">
                        <i class="fas fa-sm fa-edit mx-2"></i> Form Tambah Guru
                    </div>
                </div>
                <div class="card-body">
                    <form action="#" method="POST" id="form_tambah_guru">
                        @csrf

                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="form-group">
                                    <label for="">Nama Lengkap:</label>
                                    <input type="text" class="form-control" name="nama_lengkap">
                                    <span class="text-danger error-text nama_lengkap_error" style="font-size: 12px;"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="form-group">
                                    <label for="">Email Pribadi:</label>
                                    <input type="email" class="form-control" name="email_pribadi">
                                    <span class="text-danger error-text email_pribadi_error"
                                        style="font-size: 12px;"></span>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="form-group">
                                    <label for="">Alamat</label>
                                    <textarea name="alamat" class="form-control" style="height: 230px !important;" cols="30" rows="10"></textarea>
                                    <span class="text-danger error-text alamat_error" style="font-size: 12px;"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="form-group">
                                    <label for="">Bidang Pelajaran:</label>

                                    <span class="text-danger error-text bidang_pelajaran_error" style="font-size: 12px;"></span>
                                </div>

                                <div class="form-group">
                                    <label for="">Foto:</label>
                                    <input type="file" class="form-control" name="foto">
                                    <span class="text-danger error-text foto_error" style="font-size: 12px;"></span>
                                </div>

                                <div class="form-group">
                                    <label for="">No Telepon:</label>
                                    <input type="text" class="form-control" name="no_telepon">
                                    <span class="text-danger error-text no_telepon_error" style="font-size: 12px;"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="">Masa Awal Bergabung:</label>
                                    <input type="date" class="form-control" name="masa_awal_bergabung">

                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="">Masa Akhir Bergabung:</label>
                                    <input type="date" class="form-control" name="masa_akhir_bergabung">

                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="">Status:</label>
                                    <select name="status" class="form-control">
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak aktif</option>
                                    </select>

                                </div>
                            </div>
                        </div>

                        <button class="btn btn-sm btn-primary" type="submit">
                            <i class="fas fa-sm fa-save mx-1"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('script')
    <script>
        $("#form_tambah_guru").submit(function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            $.ajax({
                url: '{{ route('guru.p_tambah') }}',
                method: 'post',
                data: fd,
                cache: false,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $(document).find('span.error-text').text('');
                },
                success: function(data) {
                    if (data.status == 'success') {
                        Swal.fire({
                            icon: data.status,
                            text: data.message,
                            title: data.title,
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end',
                        });
                        setTimeout(function() {
                            window.top.location = "{{ route('guru') }}"
                        }, 2000);
                    } else {
                        $.each(data.error, function(prefix, val) {
                            $('span.' + prefix + '_error').text(val[0]);
                        });

                    }
                }
            });
        });
    </script>
@endpush
