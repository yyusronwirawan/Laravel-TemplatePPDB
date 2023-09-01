@extends('layouts.admin')

@section('title', 'Kelas')
@section('content')


    <style>
        .select2-selection {
            overflow: hidden;
        }

        .select2-selection__rendered {
            white-space: normal;
            word-break: break-all;
        }
    </style>


    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kelas</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('kelas') }}">Jurusan</a></div>
                    <div class="breadcrumb-item">Tambah Kelas</div>
                </div>
            </div>


            <div class="d-flex justify-content-start">
                <a href="{{ route('kelas') }}" class="btn btn-sm btn-primary mb-3">
                    <i class="fas fa-sm fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card shadow card-primary">
                <div class="card-header">
                    <div class="d-flex justify-content-start align-items-center">
                        <i class="fas fa-sm fa-edit mx-2"></i> Form Tambah Kelas
                    </div>
                </div>
                <div class="card-body">
                    <form action="#" method="POST" id="form_tambah_kelas">
                        @csrf

                        <div class="form-group">
                            <label for="">Jurusan:</label>
                            <select name="jurusan_id" class="form-control jurusan"></select>
                            <span class="text-danger error-text jurusan_id_error" style="font-size: 12px;"></span>
                        </div>

                        <div class="form-group">
                            <label for="">Kelas:</label>
                            <input type="text" class="form-control" name="kelas">

                            <span class="text-danger error-text kelas_error" style="font-size: 12px;"></span>
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
        $("#form_tambah_kelas").submit(function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            $.ajax({
                url: '{{ route('kelas.p_tambah') }}',
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
                            window.top.location = "{{ route('kelas') }}"
                        }, 2000);
                    } else {
                        $.each(data.error, function(prefix, val) {
                            $('span.' + prefix + '_error').text(val[0]);
                        });

                    }
                }
            });
        });


        $(document).ready(function() {
            $('.jurusan').select2({
                width: '100%',
                placeholder: '--Pilih Jurusan',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{ route('kelas.listJurusan') }}",
                    dataType: 'json',
                    delay: 500,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.text,
                                    id: item.id
                                }
                            })
                        };
                    }
                }
            });
        });
    </script>
@endpush
