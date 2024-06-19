@extends('layouts.master')

@section('title') Data Personal @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Data Personal @endslot
@slot('title') Data Personal @endslot
@endcomponent

<div class="row">
    <div class="col-xxl-6">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap">
                    <h4 class="card-title mb-4">Informasi Dasar</h4>
                </div>

                <form action="{{url('data-personal/update')}}" method="post">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                @csrf
                                <div class="mb-3 col-6">
                                    <label class="form-label">Nama Depan</label>
                                    <input class="form-control" type="text" name="firstname" placeholder="Masukkan nama depan" value="{{$userdata->firstname}}">
                                </div>
                                <div class="mb-3 col-6">
                                    <label class="form-label">Nama Belakang</label>
                                    <input class="form-control" type="text" name="lastname" placeholder="Masukkan nama belakang" value="{{$userdata->lastname}}">
                                </div>
                                <div class="mb-3 col-6">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input class="form-control" type="text" name="tempat_lahir" placeholder="Masukkan tempat lahir" value="{{$userdata->tempat_lahir}}">
                                </div>
                                <div class="mb-3 col-6">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input class="form-control" type="date" name="tanggal_lahir" placeholder="Masukkan tanggal lahir" value="{{$userdata->tanggal_lahir}}">
                                </div>
                                <div class="mb-3 col-6">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select class="form-control" name="jenis_kelamin">
                                        <option value="">-- Pilih jenis kelamin --</option>
                                        <option value="L" {{$userdata->jenis_kelamin == "L" ? 'selected' : ''}}>Laki-laki</option>
                                        <option value="P" {{$userdata->firstname == "P" ? 'selected' : ''}}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="col-xxl-6">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap">
                    {{-- <h4 class="card-title mb-4">Informasi Dasar</h4> --}}
                </div>

                <div class="border border-2 rounded p-3">
                    <h4>Informasi Kontak</h4>
                    <p>Kumpulan Informasi Kontak Valid yang Anda Miliki</p>
                    <hr class="border border-1">
                    <div class="d-flex justify-content-between">
                        <p>ALAMAT SUREL</p>
                        <div>
                            <p>{{auth()->user()->email}}</p>
                        </div>
                    </div>
                    <hr class="border border-1">
                    <div class="d-flex justify-content-between">
                        <p>NO. HANDPHONE</p>
                        <div class="d-flex">
                            <p class="">{{$userdata->hp}}</p>
                            <span class="ms-2" data-bs-toggle="modal" data-bs-target="#modalPhone"><i class='bx bxs-pencil text-primary'></i></span>
                        </div>
                    </div>

                    <div id="modalPhone" class="modal fade" tabindex="-1" aria-labelledby="modalPhoneLabel" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalPhoneLabel">Update No HP</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class="modal-form" action="{{url('data-personal/phone/update')}}" method="post">
                                    @csrf
                                    @method('post')
                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label class="form-label">No HP Baru</label>
                                            <div class="input-group auth-pass-inputgroup">
                                                <input class="form-control" type="text" name="phone" placeholder="Masukkan nomor baru">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                                    </div>
                                </form>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xxl-6">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap">
                    {{-- <h4 class="card-title mb-4">Informasi Dasar</h4> --}}
                </div>

                <div class="border border-2 rounded p-3">
                    <h4>Kata Sandi</h4>
                    <p>Kata sandi anda bersifat rahasia</p>
                    <hr class="border border-1">
                    <div class="d-flex justify-content-between">
                        <p>KATA SANDI</p>
                        <div class="d-flex">
                            <p class="">*******</p>
                            <span class="ms-2" data-bs-toggle="modal" data-bs-target="#modalPassword"><i class='bx bxs-pencil text-primary'></i></span>
                        </div>
                    </div>
                </div>

                <div id="modalPassword" class="modal fade" tabindex="-1" aria-labelledby="modalPasswordLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalPasswordLabel">Update Password</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form class="modal-form" action="{{url('data-personal/password/update')}}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-2">
                                        <label class="form-label">Password Baru</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password" class="form-control" placeholder="Masukkan password" aria-label="Password" aria-describedby="password-addon" name="password">
                                            <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Ketik Ulang Password</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password" class="form-control" placeholder="Ketik ulang password" aria-label="Password" aria-describedby="confirm-password-addon" name="konfirmasi_password">
                                            <button class="btn btn-light " type="button" id="confirm-password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                                </div>
                            </form>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

@endsection

@section('css')
<link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
@endsection

@section('script')
<!-- datatables -->
<script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        $("#datatable-siswa").dataTable();

        function getDetail(ids) {
            $.get('siswa/' + ids + '/detail').done(function(response) {
                let res = response
                if (!res.status) return

                $('.edit-nama-depan').val(res.data.detail.firstname)
                $('.edit-nama-belakang').val(res.data.detail.lastname)
                $('.edit-username').val(res.data.username)
                $('.edit-email').val(res.data.email)

                setTimeout(() => {
                    showModal();
                }, 300);
            })
        }

        function showModal() {
            const myModal = new bootstrap.Modal('#modalTahunAjaran', {
                show: true
            })
            myModal.show()
        }

        $('#datatable-siswa').on('click', '.edit-btn', function() {
            getDetail($(this).data('id'))
            $('.modal-form').attr('action', $(this).data('url'))
        })
    })

</script>
@endpush
