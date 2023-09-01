@extends('layouts.admin')

@section('title', 'Kalender Akademik')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kalender Akademikk</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('kalender_akademik') }}">Kalender Akademik</a></div>
                    <div class="breadcrumb-item">Edit Kalender Akademik</div>
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
                        <i class="fas fa-sm fa-edit mx-2"></i> Form Edit Jurusan
                    </div>
                </div>
                <div class="card-body">
                    <form action="#" method="POST" id="form_edit_kalender_akademik">
                        @csrf

                        <input type="hidden" name="id" class="form-control" value="{{ $kalender_akademik->id }}">

                        <div class="form-group">
                            <label for="">Tahun:</label>
                            <input type="text" class="form-control" name="tahun"
                                value="{{ $kalender_akademik->tahun }}">
                            <span class="text-danger error-text nama_jurusan_error" style="font-size: 12px;"></span>
                        </div>


                        <div class="form-group">
                            <label for="">Semester</label>
                            <select name="semester" class="form-control">
                                <option value="ganjil" @if ($kalender_akademik->semester == 'ganjil') selected @endif>Ganjil
                                </option>
                                <option value="genap" @if ($kalender_akademik->semester == 'genap') selected @endif>Genap
                                </option>
                            </select>
                            <span class="text-danger error-text semester_error" style="font-size: 12px;"></span>
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
        $("#form_edit_kalender_akademik").submit(function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            $.ajax({
                url: '{{ route('kalender_akademik.p_edit') }}',
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
                            window.top.location = "{{ route('kalender_akademik') }}"
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
