@extends('layouts.master')

@section('title') Stok @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Log @endslot
@slot('title') Stok @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap justify-content-between">
                    <h4 class="card-title mb-4">Log Stok</h4>
                    <div class="button-group">
                        <a class="btn btn-sm btn-success"><i class='bx bx-archive-in'></i> Stok Masuk</a>
                        <a class="btn btn-sm btn-warning"><i class='bx bx-archive-out'></i> Stok Keluar</a>
                        <a class="btn btn-sm btn-primary"><i class='bx bx-search-alt-2'></i> Cek Log</a>
                    </div>
                </div>

                <table id="datatable-stok" class="table table-bordered dt-responsive w-100 dataTable no-footer dtr-inline" aria-describedby="datatable_info" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Stok</th>
                        </tr>
                    </thead>


                    <tbody>
                        {{-- @foreach($stok as $key => $i)
                        <tr>
                            <td>{{ $i->kode }}</td>
                            <td>{{ $i->nama }}</td>
                            <td>{{ $i->deskripsi }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false">Aksi <i class="mdi mdi-chevron-down"></i></button>
                                    <div class="dropdown-menu" style="">
                                        <a class="dropdown-item edit-btn" href="#" data-url="{{route('stok.update', $i->id)}}" data-id="{{$i->id}}">Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete-btn" style="color: red" href="#" data-url="{{route('stok.delete', $i->id)}}">Hapus</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach --}}
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

<div id="modalstok" class="modal fade" tabindex="-1" aria-labelledby="modalstokLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalstokLabel">Edit Kursus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="modal-form" action="" method="post">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Nama stok</label>
                        <input class="form-control edit-nama" type="text" name="nama" placeholder="Masukkan nama stok">
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
        $("#datatable-stok").dataTable();

        function getDetail(ids) {
            $.get('stok/' + ids + '/detail').done(function(response) {
                let res = response
                if (!res.status) return

                $('.edit-nama').val(res.data.nama)
                $('.edit-deskripsi').val(res.data.deskripsi)

                setTimeout(() => {
                    showModal();
                }, 500);
            })
        }

        function showModal() {
            const myModal = new bootstrap.Modal('#modalstok', {
                show: true
            })
            myModal.show()
        }

        $('#datatable-stok').on('click', '.edit-btn', function() {
            getDetail($(this).data('id'))
            $('.modal-form').attr('action', $(this).data('url'))
        })

        $("#datatable-stok").on("click", ".delete-btn", function() {
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
    })

</script>
@endpush
