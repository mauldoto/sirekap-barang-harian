@extends('layouts.master')

@section('title') Lokasi @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Master Data @endslot
@slot('title') Lokasi @endslot
@endcomponent

<section>
    <div class="row">
        <div class="col-xl-5">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('lokasi.store')}}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Nama Lokasi</label>
                            <input class="form-control" type="text" name="nama_lokasi" placeholder="Masukkan nama lokasi">
                        </div>
                        {{-- <div class="mb-2">
                            <label class="form-label">Nama Pendek</label>
                            <input class="form-control" type="text" name="nama_pendek" placeholder="Contoh: IPS">
                        </div> --}}
                        <div class="mb-2">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi_lokasi" cols="30" rows="5"></textarea>
                        </div>
                        <div class="mb-2 d-flex justify-content-end">
                            <button class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex flex-wrap justify-content-between">
                        <h4 class="card-title mb-4">Daftar Lokasi</h4>

                        <div class="button-group">
                            <a class="btn btn-sm btn-success import-modal-lokasi-btn"><i class='bx bx-archive-in'></i> Import</a>
                        </div>
                    </div>

                    <table id="datatable-lokasi" class="table table-bordered dt-responsive w-100 dataTable no-footer dtr-inline" aria-describedby="datatable_info" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Nama Lokasi</th>
                                {{-- <th>Nama Pendek</th> --}}
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach($lokasi as $key => $i)
                            <tr>
                                <td>{{ $i->nama }}</td>
                                <td>{{ $i->deskripsi }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false">Aksi <i class="mdi mdi-chevron-down"></i></button>
                                        <div class="dropdown-menu" style="">
                                            <a class="dropdown-item edit-btn" href="#" data-url="{{route('lokasi.update', $i->id)}}" data-id="{{$i->id}}">Edit</a>
                                            <div class="dropdown-divider"></div>
                                            {{-- <a class="dropdown-item delete-btn" style="color: red" href="#" data-url="{{route('lokasi.delete', $i->id)}}">Hapus</a> --}}
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

    <form class="form-delete-lokasi" action="" method="post">
        @csrf
    </form>
    <!-- end row -->

    <div id="modalLokasi" class="modal fade" tabindex="-1" aria-labelledby="modalLokasiLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLokasiLabel">Edit Lokasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-form" action="" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Nama Lokasi</label>
                            <input class="form-control edit-nama-lokasi" type="text" name="nama_lokasi" placeholder="Masukkan nama kursus">
                        </div>
                        {{-- <div class="mb-2">
                            <label class="form-label">Nama Pendek</label>
                            <input class="form-control edit-namap" type="text" name="nama_pendek" placeholder="Contoh: IPS">
                        </div> --}}
                        <div class="mb-2">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control edit-deskripsi-lokasi" name="deskripsi_lokasi" cols="30" rows="5"></textarea>
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

    <div id="modalImportLokasi" class="modal fade" tabindex="-1" aria-labelledby="modalImportLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportLokasiLabel">Import Lokasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{route('lokasi.import')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Masukan file import</label>
                            <input type="file" name="import_lokasi" class="form-control" placeholder="Cari file import" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        </div>
                        <div>
                            <button class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

                <div class="modal-footer d-flex justify-content-end">
                    <a href="{{asset('assets/files/import_lokasi_format.xlsx')}}" class="text-success">Download Format Import</a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

</section>

<hr>

<section class="sub-lokasi-section">
    <div class="row">
        <div class="col-xl-5">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('sublokasi.store')}}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Nama Sub Lokasi</label>
                            <input class="form-control" type="text" name="nama_sublokasi" placeholder="Masukkan nama sub lokasi">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Lokasi</label>
                            <select class="form-control lokasi" name="lokasi">
                                <option value="">-- pilih lokasi --</option>
                                @foreach($lokasi as $key => $lok)
                                <option value="{{$lok->id}}">{{$lok->nama . ' (' . $lok->kode . ')'}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="mb-2">
                            <label class="form-label">Nama Pendek</label>
                            <input class="form-control" type="text" name="nama_pendek" placeholder="Contoh: IPS">
                        </div> --}}
                        <div class="mb-2">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi_sublokasi" cols="30" rows="5"></textarea>
                        </div>
                        <div class="mb-2 d-flex justify-content-end">
                            <button class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex flex-wrap justify-content-between">
                        <h4 class="card-title mb-4">Daftar Sub Lokasi</h4>

                        <div class="button-group">
                            <a class="btn btn-sm btn-success import-modal-sub-btn"><i class='bx bx-archive-in'></i> Import</a>
                        </div>
                    </div>

                    <table id="datatable-sublokasi" class="table table-bordered dt-responsive w-100 dataTable no-footer dtr-inline" aria-describedby="datatable_info" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Nama Sub Lokasi</th>
                                <th>Lokasi</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach($sublokasi as $key => $i)
                            <tr>
                                <td>{{ $i->nama }}</td>
                                <td>{{ $i->lokasi->nama }}</td>
                                <td>{{ $i->deskripsi }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false">Aksi <i class="mdi mdi-chevron-down"></i></button>
                                        <div class="dropdown-menu" style="">
                                            <a class="dropdown-item edit-btn-sublokasi" href="#" data-url="{{route('sublokasi.update', $i->id)}}" data-id="{{$i->id}}">Edit</a>
                                            <div class="dropdown-divider"></div>
                                            {{-- <a class="dropdown-item delete-btn-sublokasi" style="color: red" href="#" data-url="{{route('sublokasi.delete', $i->id)}}">Hapus</a> --}}
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

    <form class="form-delete-sublokasi" action="" method="post">
        @csrf
    </form>
    <!-- end row -->

    <div id="modalSubLokasi" class="modal fade" tabindex="-1" aria-labelledby="modalSubLokasiLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSubLokasiLabel">Edit Sub lokasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-form-sublokasi" action="" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Nama Sub Lokasi</label>
                            <input class="form-control edit-nama-sublokasi" type="text" name="nama_sublokasi" placeholder="Masukkan nama kursus">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Lokasi</label>
                            <select class="form-control edit-lokasi" name="lokasi">
                                <option value="">-- pilih lokasi --</option>
                                @foreach($lokasi as $key => $lok)
                                <option value="{{$lok->id}}">{{$lok->nama . ' (' . $lok->kode . ')'}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control edit-deskripsi-sublokasi" name="deskripsi_sublokasi" cols="30" rows="5"></textarea>
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

    <div id="modalImportSubLokasi" class="modal fade" tabindex="-1" aria-labelledby="modalImportLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportSubLokasiLabel">Import Sublokasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{route('sublokasi.import')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Masukan file import</label>
                            <input type="file" name="import_sublokasi" class="form-control" placeholder="Cari file import" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        </div>
                        <div>
                            <button class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

                <div class="modal-footer d-flex justify-content-end">
                    <a href="{{asset('assets/files/import_sublokasi_format.xlsx')}}" class="text-success">Download Format Import</a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</section>

@endsection

@section('css')
<link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('script')
<!-- datatables -->
<script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/select2/js/select2.min.js') }}"></script>
@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        $("#datatable-lokasi").dataTable();
        $(".edit-lokasi").select2();
        $(".lokasi").select2();

        function getDetailLokasi(ids) {
            $.get('lokasi/' + ids + '/detail').done(function(response) {
                let res = response
                if (!res.status) return

                $('.edit-nama-lokasi').val(res.data.nama)
                $('.edit-deskripsi-lokasi').val(res.data.deskripsi)

                setTimeout(() => {
                    showModalLokasi();
                }, 500);
            })
        }

        function showModalLokasi() {
            const myModal = new bootstrap.Modal('#modalLokasi', {
                show: true
            })
            myModal.show()
        }

        $('#datatable-lokasi').on('click', '.edit-btn', function() {
            getDetailLokasi($(this).data('id'))
            $('.modal-form').attr('action', $(this).data('url'))
        })

        $("#datatable-lokasi").on("click", ".delete-btn", function() {
            const url = $(this).data("url");
            const form = $(".form-delete").attr("action", url);

            Swal.fire({
                title: "Apakah anda yakin?"
                , text: "Data akan dialihkan ke folder sampah!"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: "#3085d6"
                , cancelButtonColor: "#d33"
                , confirmButtonText: "Ya, Hapus!"
                , cancelButtonText: "Batalkan"
            , }).then((result) => {
                if (result.isConfirmed) {
                    form[0].submit();
                }
            });
        });

        $('.import-modal-lokasi-btn').on('click', function() {
            const myModal = new bootstrap.Modal('#modalImportLokasi', {
                show: true
            })
            myModal.show()
        })

        $('.import-modal-sub-btn').on('click', function() {
            const myModal = new bootstrap.Modal('#modalImportSubLokasi', {
                show: true
            })
            myModal.show()
        })
    })

</script>
<script>
    $(document).ready(function() {
        $("#datatable-sublokasi").dataTable();

        function getDetailSublokasi(ids) {
            $.get('lokasi/sublokasi/' + ids + '/detail').done(function(response) {
                let res = response
                if (!res.status) return

                $('.edit-nama-sublokasi').val(res.data.nama)
                $('.edit-deskripsi-sublokasi').val(res.data.deskripsi)
                $('.edit-lokasi').val(res.data.id_lokasi)
                $('.edit-lokasi').trigger('change')

                setTimeout(() => {
                    showModalSublokasi();
                }, 500);
            })
        }

        function showModalSublokasi() {
            const myModal = new bootstrap.Modal('#modalSubLokasi', {
                show: true
            })
            myModal.show()
        }

        $('#datatable-sublokasi').on('click', '.edit-btn-sublokasi', function() {
            getDetailSublokasi($(this).data('id'))
            $('.modal-form-sublokasi').attr('action', $(this).data('url'))
        })

        $("#datatable-sublokasi").on("click", ".delete-btn-sublokasi", function() {
            const url = $(this).data("url");
            const form = $(".form-delete-sublokasi").attr("action", url);

            Swal.fire({
                title: "Apakah anda yakin?"
                , text: "Data akan dialihkan ke folder sampah!"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: "#3085d6"
                , cancelButtonColor: "#d33"
                , confirmButtonText: "Ya, Hapus!"
                , cancelButtonText: "Batalkan"
            , }).then((result) => {
                if (result.isConfirmed) {
                    form[0].submit();
                }
            });
        });
    })

</script>
@endpush
