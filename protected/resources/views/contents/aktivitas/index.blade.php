@extends('layouts.master')

@section('title') Aktivitas @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Log @endslot
@slot('title') Aktivitas @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap justify-content-between">
                    <h4 class="card-title mb-4">Log Aktivitas/Job</h4>
                    <div class="button-group">
                        <a href="{{route('aktivitas.input')}}" class="btn btn-sm btn-success"><i class='bx bx-archive-in'></i> Input Aktivitas</a>
                        <a class="btn btn-sm btn-danger exportpdf-modal-btn"><i class='bx bx-archive-out'></i> Export PDF</a>
                    </div>
                </div>

                <hr>

                <form action="" method="GET">
                    <div class="row">
                            <div class="mb-2 col-lg-2">
                                <label class="form-label">Dari</label>
                                <input class="form-control" type="date" name="dari" placeholder="Masukkan tanggal" value="{{$startDate}}" required>
                            </div>
                            <div class="mb-2 col-lg-2">
                                <label class="form-label">Sampai</label>
                                <input class="form-control" type="date" name="ke" placeholder="Masukkan tanggal" value="{{$endDate}}" required>
                            </div>
                            <div class="mb-2 col-lg-2 d-flex align-items-end">
                                <button class="btn btn-primary">Filter</button>
                            </div>
                    </div>
                </form>


                <hr>

                <table id="datatable-aktivitas" class="table table-bordered dt-responsive w-100 dataTable no-footer dtr-inline" aria-describedby="datatable_info" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode</th>
                            <th>Lokasi</th>
                            <th>Sub Lokasi</th>
                            <th>Diinput Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach($aktivitas as $key => $i)
                        <tr>
                            <td>{{ $i->tanggal_berangkat }} s/d {{ $i->tanggal_pulang }}</td>
                            <td>{{ $i->kode }}</td>
                            <td>{{ $i->lokasi->nama }}</td>
                            <td>{{ $i->sublokasi->nama }}</td>
                            <td>{{ $i->user->username }}</td>
                            <td class="text-center">
                                <button class="btn btn-secondary detail-btn" data-id="{{$i->id}}">
                                    <i class='bx bx-search-alt-2'></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modalDetail" class="modal fade" tabindex="-1" aria-labelledby="detailLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailLabel">Detail </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <table class="table table-bordered dt-responsive w-100 dataTable no-footer dtr-inline" aria-describedby="datatable_info" style="width: 100%;">
                    <tbody>
                        <tr>
                            <td>Kode Aktivitas</td>
                            <td>:</td>
                            <td class="ka"></td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td class="tanggal"></td>
                        </tr>
                        <tr>
                            <td>Teknisi</td>
                            <td>:</td>
                            <td class="teknisi"></td>
                        </tr>
                        <tr>
                            <td>Deskripsi</td>
                            <td>:</td>
                            <td class="deskripsi"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
                <form action="{{route('aktivitas.export.pdf')}}" method="GET" target="_blank">
                    <div class="row">
                        <div class="mb-2 col-lg-6">
                            <label class="form-label">Dari</label>
                            <input class="form-control" type="date" name="dari" placeholder="Masukkan tanggal" value="{{$startDate}}" required>
                        </div>
                        <div class="mb-2 col-lg-6">
                            <label class="form-label">Sampai</label>
                            <input class="form-control" type="date" name="ke" placeholder="Masukkan tanggal" value="{{$endDate}}" required>
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
        $("#datatable-aktivitas").dataTable();

        function getDetailAktivitas(ids) {
            $.get('aktivitas/' + ids + '/detail').done(function(response) {
                let res = response
                if (!res.status) return

                console.log(res);
                $('.ka').html(res.data.no_referensi)
                $('.tanggal').html(res.data.tanggal_berangkat + ' - ' +res.data.tanggal_pulang)

                let tempTeknisi = ''
                for (const teknisi of res.data.teknisi) {
                    tempTeknisi += '- ' + teknisi.karyawan.nama + '<br/>'
                }
                $('.teknisi').html(tempTeknisi)
                $('.deskripsi').html(res.data.deskripsi)

                setTimeout(() => {
                    showModalAktivitas();
                }, 500);
            })
        }

        function showModalAktivitas() {
            const myModal = new bootstrap.Modal('#modalDetail', {
                show: true
            })
            myModal.show()
        }

        $('#datatable-aktivitas').on('click', '.detail-btn', function() {
            getDetailAktivitas($(this).data('id'))
        })

        $("#datatable-aktivitas").on("click", ".delete-btn", function() {
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
