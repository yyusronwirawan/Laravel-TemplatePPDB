@extends('layouts.admin')

@section('title', 'Ekstrakurikuler')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Ekstrakurikuler</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('ekstrakurikuler') }}">Ekstrakurikuler</a></div>
                    <div class="breadcrumb-item">Edit Ekstrakurikuler</div>
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
                        <i class="fas fa-sm fa-edit mx-2"></i> Form Edit Ekstrakurikuler
                    </div>
                </div>
                <div class="card-body">
                    <form action="#" method="POST" id="form_edit_ekstrakurikuler" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="id" value="{{ $ekstrakurikuler->id }}">

                        <div class="form-group">
                            <label for="">Nama:</label>
                            <input type="text" class="form-control" name="nama" value="{{ $ekstrakurikuler->nama }}">

                            <span class="text-danger error-text nama_error" style="font-size: 12px;"></span>
                        </div>

                        <div class="form-group">
                            <label for="">Logo:</label>
                            <input type="file" name="logo" class="form-control">
                            <span class="text-danger error-text logo_error" style="font-size: 12px;"></span>
                        </div>


                        <div class="form-group">
                            <label for="">Deskripsi:</label>
                            <textarea name="deskripsi" class="form-control" cols="30" rows="10" style="height: 200px;">{{ $ekstrakurikuler->deskripsi }}</textarea>
                            <span class="text-danger error-text deskripsi_error" style="font-size: 12px;"></span>
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
        $("#form_edit_ekstrakurikuler").submit(function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            $.ajax({
                url: '{{ route('ekstrakurikuler.p_edit') }}',
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
                            window.top.location = "{{ route('ekstrakurikuler') }}"
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
