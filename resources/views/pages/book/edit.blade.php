@extends('layouts.admin')

@section('title', 'Buku')
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
                <h1>Buku</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('buku') }}">Buku</a></div>
                    <div class="breadcrumb-item">Tambah Buku</div>
                </div>
            </div>


            <div class="d-flex justify-content-start">
                <a href="{{ route('buku') }}" class="btn btn-sm btn-primary mb-3">
                    <i class="fas fa-sm fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card shadow card-primary">
                <div class="card-header">
                    <div class="d-flex justify-content-start align-items-center">
                        <i class="fas fa-sm fa-edit mx-2"></i> Form Tambah Buku
                    </div>
                </div>
                <div class="card-body">
                    <form action="#" method="POST" id="form_ubah_buku" enctype="multipart/form-data">
                        @csrf

                        <input type="text" name="id" class="form-control" value="{{ $books->id }}">

                        <div class="form-group">
                            <label for="">Penulis:</label>
                            <select name="author[]" class="form-control author"></select>
                            <span class="text-danger error-text author_error" style="font-size: 12px;"></span>
                        </div>

                        <div class="form-group">
                            <label for="">Judul:</label>
                            <input type="text" class="form-control" name="title" value="{{ $books->title }}">
                            <span class="text-danger error-text title_error" style="font-size: 12px;"></span>
                        </div>

                        <div class="form-group">
                            <label for="">Cover</label>
                            <input type="file" name="cover" class="form-control">
                            <span class="text-danger error-text cover_error" style="font-size: 12px;"></span>
                        </div>

                        <div class="form-group">
                            <label for="">Deskripsi</label>
                            <textarea name="description" class="form-control" cols="30" rows="10" style="height: 200px !important;">{{ $books->description }}</textarea>
                            <span class="text-danger error-text description_error" style="font-size: 12px;"></span>
                        </div>

                        <div class="form-group">
                            <label for="">Ketersediaan</label>
                            <input type="text" name="stock" class="form-control" value="{{ $books->stock }}">
                            <span class="text-danger error-text stock_error" style="font-size: 12px;"></span>
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
        $("#form_ubah_buku").submit(function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            $.ajax({
                url: '{{ route('buku.update') }}',
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
                            window.top.location = "{{ route('buku') }}"
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
            $('.author').select2({
                width: '100%',
                placeholder: '--Pilih Author',
                multiple: true,
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{ route('buku.listAuthor') }}",
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
                url: '{{ route('buku.authorByBook') }}',
                method: 'get',
                data: {
                    books_id: {{ $books->id }},
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    console.log(data);
                    for (let i = 0; i < data.length; i++) {
                        var newOption = new Option(data[i].text, data[i].id, true,
                            true);
                        $('.author').append(newOption).trigger('change');
                    }
                }
            });
        });
    </script>
@endpush
