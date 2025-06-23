@extends('layouts.master')

@section('title')
    User
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            User
        @endslot
        @slot('title')
            User
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Nama</label>
                            <input class="form-control" type="text" name="nama" placeholder="Masukkan nama user">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Username</label>
                            <input class="form-control" type="text" name="username" placeholder="Masukan username">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="email" name="email"
                                placeholder="Contoh: john.doe@mail.com">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Role</label>
                            <select name="role" id="" class="form-control">
                                <option value="admin">Admin JPN</option>
                                <option value="finance">Finance JPN</option>
                                <option value="jpn">User JPN</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Password</label>
                            <div class="input-group auth-pass-inputgroup">
                                <input type="password" class="form-control" placeholder="Masukkan password"
                                    aria-label="Password" aria-describedby="password-addon" name="password">
                                <button class="btn btn-light " type="button" id="password-addon">
                                    <i class="mdi mdi-eye-outline"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Ulangi Password</label>
                            <div class="input-group auth-pass-inputgroup">
                                <input type="password" class="form-control" placeholder="Masukkan password"
                                    aria-label="Password" aria-describedby="repassword-addon" name="ulangi_password">
                                <button class="btn btn-light " type="button" id="repassword-addon">
                                    <i class="mdi mdi-eye-outline"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-2 d-flex justify-content-end">
                            <button class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex flex-wrap justify-content-between">
                        <h4 class="card-title mb-4">Daftar User</h4>

                        {{-- <div class="button-group">
                            <a class="btn btn-sm btn-success import-modal-btn"><i class='bx bx-archive-in'></i> Import</a>
                        </div> --}}
                    </div>

                    <table id="datatable-user"
                        class="table table-bordered dt-responsive w-100 dataTable no-footer dtr-inline"
                        aria-describedby="datatable_info" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($users as $key => $i)
                                <tr>
                                    <td>{{ $i->name }}</td>
                                    <td>{{ $i->username }}</td>
                                    <td>{{ $i->email }}</td>
                                    <td>{{ $i->role }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info dropdown-toggle btn-sm"
                                                data-bs-toggle="dropdown" aria-expanded="false">Aksi <i
                                                    class="mdi mdi-chevron-down"></i></button>
                                            <div class="dropdown-menu" style="">
                                                <a class="dropdown-item edit-btn" href="#"
                                                    data-url="{{ route('user.update', $i->id) }}"
                                                    data-id="{{ $i->id }}">Edit</a>
                                                {{-- <div class="dropdown-divider"></div>
                                                <a class="dropdown-item delete-btn" style="color: red" href="#"
                                                    data-url="{{ route('user.delete', $i->id) }}">Hapus</a> --}}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form class="form-delete" action="" method="post">
        @csrf
    </form>
    <!-- end row -->

    <div id="modalUser" class="modal fade" tabindex="-1" aria-labelledby="modalUserLabel" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUserLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-form" action="" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Nama</label>
                            <input class="form-control edit-nama" type="text" name="nama"
                                placeholder="Masukkan nama user">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Username</label>
                            <input class="form-control edit-username" type="text" name="username"
                                placeholder="Masukan username">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email</label>
                            <input class="form-control edit-email" type="email" name="email"
                                placeholder="Contoh: john.doe@mail.com">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Role</label>
                            <select name="role" id="" class="form-control edit-role">
                                <option value="admin">Admin JPN</option>
                                <option value="finance">Finance JPN</option>
                                <option value="jpn">User JPN</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Password</label>
                            <div class="input-group auth-pass-inputgroup">
                                <input type="password" class="form-control" placeholder="Masukkan password"
                                    aria-label="Password" aria-describedby="password-addon-modal" name="password">
                                <button class="btn btn-light " type="button" id="password-addon-modal">
                                    <i class="mdi mdi-eye-outline"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Ulangi Password</label>
                            <div class="input-group auth-pass-inputgroup">
                                <input type="password" class="form-control" placeholder="Masukkan password"
                                    aria-label="Password" aria-describedby="repassword-addon-modal"
                                    name="ulangi_password">
                                <button class="btn btn-light " type="button" id="repassword-addon-modal">
                                    <i class="mdi mdi-eye-outline"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect"
                            data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div id="modalImport" class="modal fade" tabindex="-1" aria-labelledby="modalImportLabel" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportLabel">Import Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('barang.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Masukan file import</label>
                            <input type="file" name="import_barang" class="form-control"
                                placeholder="Cari file import"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        </div>
                        <div>
                            <button class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

                <div class="modal-footer d-flex justify-content-end">
                    <a href="{{ asset('assets/files/import_barang_format.xlsx') }}" class="text-success">Download Format
                        Import</a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
@endsection

@section('css')
    <link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" id="app-style"
        rel="stylesheet" type="text/css" />
@endsection

@section('script')
    <!-- datatables -->
    <script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@push('page-js')
    <script>
        $(document).ready(function() {
            $("#repassword-addon").on("click", function() {
                if ($(this).siblings("input").length > 0) {
                    $(this).siblings("input").attr("type") == "password" ?
                        $(this).siblings("input").attr("type", "input") :
                        $(this).siblings("input").attr("type", "password");
                }
            });
            $("#password-addon-modal").on("click", function() {
                if ($(this).siblings("input").length > 0) {
                    $(this).siblings("input").attr("type") == "password" ?
                        $(this).siblings("input").attr("type", "input") :
                        $(this).siblings("input").attr("type", "password");
                }
            });
            $("#repassword-addon-modal").on("click", function() {
                if ($(this).siblings("input").length > 0) {
                    $(this).siblings("input").attr("type") == "password" ?
                        $(this).siblings("input").attr("type", "input") :
                        $(this).siblings("input").attr("type", "password");
                }
            });
            $("#datatable-user").dataTable();

            function getDetail(ids) {
                $.get('user/' + ids + '/detail').done(function(response) {
                    let res = response
                    if (!res.status) return

                    $('.edit-nama').val(res.data.name)
                    $('.edit-username').val(res.data.username)
                    $('.edit-email').val(res.data.email)
                    $('.edit-role').val(res.data.role).trigger('change');

                    setTimeout(() => {
                        showModal();
                    }, 500);
                })
            }

            function showModal() {
                const myModal = new bootstrap.Modal('#modalUser', {
                    show: true
                })
                myModal.show()
            }

            $('#datatable-user').on('click', '.edit-btn', function() {
                getDetail($(this).data('id'))
                $('.modal-form').attr('action', $(this).data('url'))
            })

            $("#datatable-user").on("click", ".delete-btn", function() {
                const url = $(this).data("url");
                const form = $(".form-delete").attr("action", url);

                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Data akan dialihkan ke folder sampah!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batalkan",
                }).then((result) => {
                    if (result.isConfirmed) {
                        form[0].submit();
                    }
                });
            });

            $('.import-modal-btn').on('click', function() {
                const myModal = new bootstrap.Modal('#modalImport', {
                    show: true
                })
                myModal.show()
            })
        })
    </script>
@endpush
