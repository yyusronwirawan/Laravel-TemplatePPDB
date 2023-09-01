@extends('layouts.admin')

@section('title', 'Berita')
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
                <h1>Berita</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('berita') }}">Berita</a></div>
                    <div class="breadcrumb-item">Tambah Berita</div>
                </div>
            </div>


            <div class="d-flex justify-content-start">
                <a href="{{ route('berita') }}" class="btn btn-sm btn-primary mb-3">
                    <i class="fas fa-sm fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card shadow card-primary">
                <div class="card-header">
                    <div class="d-flex justify-content-start align-items-center">
                        <i class="fas fa-sm fa-edit mx-2"></i> Form Tambah Berita
                    </div>
                </div>
                <div class="card-body">
                    <form action="#" method="POST" id="form_edit_berita" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="id" value="{{ $post->id }}">

                        <div class="form-group">
                            <label for="">Thumbnail:</label>
                            <input type="file" class="form-control" name="image">
                            <span class="text-danger error-text image_error" style="font-size: 12px;"></span>
                        </div>

                        <div class="form-group">
                            <label for="">Tag:</label>
                            <select name="tag[]" class="form-control tag"></select>
                            <span class="text-danger error-text tag_error" style="font-size: 12px;"></span>
                        </div>

                        <div class="form-group">
                            <label for="">Category:</label>
                            <select name="category_id" class="form-control category"></select>
                            <span class="text-danger error-text category_id_error" style="font-size: 12px;"></span>
                        </div>


                        <div class="form-group">
                            <label for="">Judul:</label>
                            <input type="text" class="form-control" name="title" value="{{ $post->title }}">

                            <span class="text-danger error-text title_error" style="font-size: 12px;"></span>
                        </div>

                        <div class="form-group">
                            <label>Konten:</label>
                            <textarea class="form-control" name="content" id="editor">{{ $post->content }}</textarea>
                            <span class="text-danger error-text content_error" style="font-size: 12px;"></span>
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
    <script src="{{ asset('be/ckeditor/ckeditor.js') }}"></script>
    <script>
        var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
        };
    </script>


    <script>
        CKEDITOR.replace('editor', options);
    </script>


    <script>
        $("#form_edit_berita").submit(function(e) {
            e.preventDefault();


            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }


            const fd = new FormData(this);
            $.ajax({
                url: '{{ route('berita.update') }}',
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
                            window.top.location = "{{ route('berita') }}"
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
            $('.tag').select2({
                width: '100%',
                placeholder: '--Pilih Tag',
                multiple: true,
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{ route('berita.listTag') }}",
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

        $(document).ready(function() {
            $('.category').select2({
                width: '100%',
                placeholder: '--Pilih Category',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{ route('berita.listCategory') }}",
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



        $(document).ready(function() {
            $.ajax({
                url: '{{ route('berita.categoryByPost') }}',
                method: 'get',
                data: {
                    id: {{ $post->id }},
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    console.log(data);
                    for (let i = 0; i < data.length; i++) {
                        var newOption = new Option(data[i].text, data[i].id, true,
                            true);
                        $('.category').append(newOption).trigger('change');
                    }
                }
            });
        });


        $(document).ready(function() {
            $.ajax({
                url: '{{ route('berita.tagByPost') }}',
                method: 'get',
                data: {
                    id: {{ $post->id }},
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    console.log(data);
                    for (let i = 0; i < data.length; i++) {
                        var newOption = new Option(data[i].text, data[i].id, true,
                            true);
                        $('.tag').append(newOption).trigger('change');
                    }
                }
            });
        });
    </script>
@endpush
