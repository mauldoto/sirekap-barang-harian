@extends('layouts.master-login')

@section('title')
Register
@endsection

@section('body')

<body>
    @endsection

    @section('content')

    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary bg-soft">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary">Free Register</h5>
                                        <p>Get your free Skote account now.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ URL::asset('assets/images/profile-img.png') }}" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div>
                                <a href="index">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ URL::asset('assets/images/logo/Color-Icon-Logo-8.png') }}" alt="" class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2">

                                @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    @foreach ($errors->all() as $error)
                                    - {{ $error }} <br>
                                    @endforeach
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                @endif

                                <form class="needs-validation" novalidate action="{{route('register-post')}}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="firstname" class="form-label">Nama Depan</label>
                                        <input type="text" class="form-control" id="firstname" placeholder="Masukkan nama depan" name="nama_depan" required>
                                        <div class="invalid-feedback">
                                            Mohon isi Nama Depan
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="lastname" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="lastname" placeholder="Masukkan nama belakang" name="nama_belakang" required>
                                        <div class="invalid-feedback">
                                            Mohon isi Nama Belakang
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Universitas</label>
                                        <select id="sekolah" class="form-control" name="sekolah">
                                            <option value="">-- Pilih Universitas --</option>
                                            @foreach($sekolah as $key => $value)
                                            <option value="{{$value->id}}">{{$value->nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="useremail" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="useremail" placeholder="Masukkan Email" name="email" required>
                                        <div class="invalid-feedback">
                                            Mohon isi Email
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" placeholder="Masukkan username" name="username" required>
                                        <div class="invalid-feedback">
                                            Mohon isi Username
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="userpassword" class="form-label">Password</label>
                                        <br><span class="text-danger">*</span><small class="text-muted">Password minimal 8 karakter</small>
                                        <br><span class="text-danger">*</span><small class="text-muted">Min 1 huruf kapital, min 1 angka, dan min 1 simbol</small>
                                        <input type="password" class="form-control" id="userpassword" placeholder="Masukkan password" name="password" required>
                                        <div class="invalid-feedback">
                                            Mohon isi Password
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="repassword" class="form-label">Ketik Ulang Password</label>
                                        <input type="password" class="form-control" id="repassword" placeholder="Ketik ulang password" name="konfirmasi_password" required>
                                        <div class="invalid-feedback">
                                            Mohon isi Konfirmasi Password
                                        </div>
                                    </div>

                                    <div class="mt-4 d-grid">
                                        <button class="btn btn-primary waves-effect waves-light" type="submit">Daftar</button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <p>Sudah memiliki akun ? <a href="{{route('login')}}" class="fw-medium text-primary">
                                                Login</a> </p>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="mt-5 text-center">

                        <div>

                            <p>© <script>
                                    document.write(new Date().getFullYear())

                                </script> Skote. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @endsection
    @section('script')
    <!-- validation init -->
    <script src="{{ URL::asset('assets/js/pages/validation.init.js') }}"></script>
    @endsection
