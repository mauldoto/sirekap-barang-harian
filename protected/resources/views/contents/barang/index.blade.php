@extends('layouts.master')

@section('title') Barang @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Master Data @endslot
@slot('title') Barang @endslot
@endcomponent

<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <form action="{{route('barang.store')}}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Nama Barang</label>
                        <input class="form-control" type="text" name="nama" placeholder="Masukkan nama barang">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Kode Barang</label>
                        <input class="form-control" type="text" name="kode" placeholder="Kosongkan jika ingin terisi otomatis">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Satuan</label>
                        <input class="form-control" type="text" name="satuan" placeholder="Contoh: pcs, meter, kg">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" cols="30" rows="5"></textarea>
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
                    <h4 class="card-title mb-4">Daftar Barang</h4>

                    <div class="button-group">
                        <a class="btn btn-sm btn-success import-modal-btn"><i class='bx bx-archive-in'></i> Import</a>
                    </div>
                </div>

                <table id="datatable-barang" class="table table-bordered dt-responsive w-100 dataTable no-footer dtr-inline" aria-describedby="datatable_info" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach($barang as $key => $i)
                        <tr>
                            <td>{{ $i->kode }}</td>
                            <td>{{ $i->nama }}</td>
                            <td>{{ $i->satuan }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false">Aksi <i class="mdi mdi-chevron-down"></i></button>
                                    <div class="dropdown-menu" style="">
                                        <a class="dropdown-item edit-btn" href="#" data-url="{{route('barang.update', $i->id)}}" data-id="{{$i->id}}">Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete-btn" style="color: red" href="#" data-url="{{route('barang.delete', $i->id)}}">Hapus</a>
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

<div id="modalbarang" class="modal fade" tabindex="-1" aria-labelledby="modalbarangLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalbarangLabel">Edit Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="modal-form" action="" method="post">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Nama Barang</label>
                        <input class="form-control edit-nama" type="text" name="nama" placeholder="Masukkan nama barang">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Satuan</label>
                        <input class="form-control edit-satuan" type="text" name="satuan" placeholder="Contoh: pcs, meter, kg">
                    </div>
                    {{-- <div class="mb-2">
                        <label class="form-label">Nama Pendek</label>
                        <input class="form-control edit-namap" type="text" name="nama_pendek" placeholder="Contoh: IPS">
                    </div> --}}
                    <div class="mb-2">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control edit-deskripsi" name="deskripsi" cols="30" rows="5"></textarea>
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

<div id="modalImport" class="modal fade" tabindex="-1" aria-labelledby="modalImportLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImportLabel">Import Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{route('barang.import')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Masukan file import</label>
                        <input type="file" name="import_barang" class="form-control" placeholder="Cari file import" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                    </div>
                    <div>
                        <button class="btn btn-sm btn-primary">Submit</button>
                    </div>
                </form>
            </div>

            <div class="modal-footer d-flex justify-content-end">
                <a href="" class="text-success">Download Format Import</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


@endsection

@section('css')
<link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
@endsection

@section('script')
<!-- datatables -->
<script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        $("#datatable-barang").dataTable();

        function getDetail(ids) {
            $.get('barang/' + ids + '/detail').done(function(response) {
                let res = response
                if (!res.status) return

                $('.edit-nama').val(res.data.nama)
                $('.edit-satuan').val(res.data.satuan)
                $('.edit-deskripsi').val(res.data.deskripsi)

                setTimeout(() => {
                    showModal();
                }, 500);
            })
        }

        function showModal() {
            const myModal = new bootstrap.Modal('#modalbarang', {
                show: true
            })
            myModal.show()
        }

        $('#datatable-barang').on('click', '.edit-btn', function() {
            getDetail($(this).data('id'))
            $('.modal-form').attr('action', $(this).data('url'))
        })

        $("#datatable-barang").on("click", ".delete-btn", function() {
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

        $('.import-modal-btn').on('click', function() {
            const myModal = new bootstrap.Modal('#modalImport', {
                show: true
            })
            myModal.show()
        })
    })

</script>
@endpush
