@extends('layouts.admin')

@section('title', 'Guru')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Guru</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    {{-- <div class="breadcrumb-item"><a href="#">Tag</a></div> --}}
                    <div class="breadcrumb-item">Guru</div>
                </div>
            </div>

            <div class="card shadow card-primary">
                <div class="card-header">
                    <h4>List Guru</h4>

                    <div class="card-header-action">
                        <a href="{{ route('guru.h_tambah') }}" class="btn btn-primary">
                            <i class="fas fa-sm fa-plus"></i> Tambah data
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-borderless dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <th>Alamat</th>
                                    <th>Email Pribadi</th>
                                    <th>No Telepon</th>
                                    <th>Bidang Pelajaran</th>
                                    <th>Masa Awal Bergabung</th>
                                    <th>Masa Akhir Bergabung</th>
                                    <th>Status</th>
                                    <th>Foto</th>
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
                    url: "{{ route('guru.data') }}",
                },
                columns: [{
                        data: 'nama_lengkap',
                        name: 'nama_lengkap'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'email_pribadi',
                        name: 'email_pribadi'
                    },
                    {
                        data: 'no_telepon',
                        name: 'no_telepon'
                    },
                    {
                        data: 'bidang_pelajaran',
                        name: 'bidang_pelajaran'
                    },

                    {
                        data: 'masa_awal_bergabung',
                        name: 'masa_awal_bergabung'
                    },

                    {
                        data: 'masa_akhir_bergabung',
                        name: 'masa_akhir_bergabung'
                    },

                    {
                        data: 'status',
                        name: 'status'
                    },

                    {
                        data: 'foto',
                        name: 'foto'
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
                title: 'Hapus Guru?',
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
                        url: "{{ route('guru.p_hapus') }}",
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
