@extends('layouts.admin')

@section('title', 'Mata Pelajaran')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Mata Pelajaran</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    {{-- <div class="breadcrumb-item"><a href="#">Tag</a></div> --}}
                    <div class="breadcrumb-item">Mata Pelajaran</div>
                </div>
            </div>

            <div class="card shadow card-primary">
                <div class="card-header">
                    <h4>List Mata Pelajaran</h4>

                    <div class="card-header-action">
                        <a href="{{ route('mata_pelajaran/h_tambah') }}" class="btn btn-primary">
                            <i class="fas fa-sm fa-plus"></i> Tambah data
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-borderless dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,
                pageLength: 5,
                autoWidth: false,
                lengthMenu: [
                    [5, 10, 20, -1],
                    [5, 10, 20, "50"]
                ],
                order: [],
                ajax: {
                    url: "{{ route('mata_pelajaran.data') }}",
                },
                columns: [{
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    },
                ]
            });
        });


        $(document).on('click', '.hapus', function(e) {
            let id = $(this).attr('data-id');
            Swal.fire({
                title: 'Hapus Mata Pelajaran?',
                text: "Data telah dihapus tidak bisa di kembalikan!",
                icon: 'warning',
                confirmButton: true,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus!',
                cancelButtonText: 'Batal',

            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('mata_pelajaran.p_hapus') }}",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status == 'success') {
                                Swal.fire({
                                    icon: data.status,
                                    text: data.message,
                                    title: data.title,
                                    toast: true,
                                    position: 'top-end',
                                    timer: 2000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end',
                                });
                                $('#datatable').DataTable().ajax.reload();
                            }
                        },
                    })
                }
            })
        })
    </script>
@endpush
