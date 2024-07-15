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
                        <a href="{{route('stok.masuk.view')}}" class="btn btn-sm btn-success"><i class='bx bx-archive-in'></i> Stok Masuk</a>
                        <a href="{{route('stok.keluar.view')}}" class="btn btn-sm btn-warning"><i class='bx bx-archive-out'></i> Stok Keluar</a>
                        <a href="{{route('stok.log')}}" class="btn btn-sm btn-primary"><i class='bx bx-search-alt-2'></i> Cek Log</a>
                        <a href="{{route('stok.rencana')}}" class="btn btn-sm btn-secondary"><i class='bx bx-print-alt-2'></i> Lembar Stok</a>
                        <a class="btn btn-sm btn-danger exportpdf-modal-btn"><i class='bx bx-archive-out'></i> Export PDF</a>
                    </div>
                </div>

                <table id="datatable-stok" class="table table-bordered dt-responsive w-100 dataTable no-footer dtr-inline" aria-describedby="datatable_info" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kondisi</th>
                            <th>Stok</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($stok as $key => $i)
                        <tr>
                            <td>{{ $i->barang->kode }}</td>
                            <td>{{ $i->barang->nama }}</td>
                            <td><span class="badge rounded-pill {{ $i->is_new ? 'bg-primary' : 'bg-warning' }}">{{ $i->is_new ? 'Baru' : 'Bekas' }}</span></td>
                            <td>{{ $i->sumqty }} ({{$i->barang->satuan}})</td>
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

<div id="modalExportPdf" class="modal fade" tabindex="-1" aria-labelledby="modalExportPdfLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExportPdfLabel">Export Aktivitas - PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{route('stok.export.pdf')}}" method="GET" target="_blank">
                    <div class="row">
                        <div class="mb-2 col-lg-12">
                            <label class="form-label">Pilih Barang</label>
                            <select class="form-control" name="barang[]" id="pilihBarang" multiple>
                                @foreach ($barang as $item)
                                <option value="{{$item->id}}">{{$item->nama . '('. $item->kode .')'}}</option>
                                @endforeach
                            </select>
                            <small class="text-danger">*Kosongkan kolom jika ingin mengambil semua</small>
                        </div>
                        <div class="mb-2 col-lg-2 d-flex align-items-end">
                            <button class="btn btn-primary">Export</button>
                        </div>
                    </div>
                </form>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

@endsection

@section('css')
<link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/select2/css/select2.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
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
        $("#datatable-stok").dataTable();
        $("#pilihBarang").select2();

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

        $('.exportpdf-modal-btn').on('click', function() {
            const myModal = new bootstrap.Modal('#modalExportPdf', {
                show: true
            })
            myModal.show()
        })
    })

</script>
@endpush
