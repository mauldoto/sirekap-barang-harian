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
                        {{-- <a class="btn btn-sm btn-danger exportpdf-modal-btn"><i class='bx bx-archive-out'></i> Export Aktivitas</a> --}}
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
                            <th>Status</th>
                            <th>Diinput Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach($aktivitas as $key => $i)
                        <tr>
                            <td>{{ $i->tanggal_berangkat }} s/d {{ $i->tanggal_pulang }}</td>
                            <td>{{ $i->no_referensi }}</td>
                            <td>{{ $i->lokasi->nama }}</td>
                            <td>{{ $i->sublokasi->nama }}</td>
                            <td>
                                @if ($i->status === 'waiting')
                                <span class="p-1 text-white rounded bg-secondary">Waiting</span>
                                @elseif ($i->status === 'progress')
                                <span class="p-1 text-white rounded bg-warning">Progress</span>
                                @elseif ($i->status === 'done')
                                <span class="p-1 text-white rounded bg-success">Done</span>
                                @else
                                <span class="p-1 text-white rounded bg-danger">Cancel</span>
                                @endif
                            </td>
                            <td>{{ $i->user->username }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false">Aksi <i class="mdi mdi-chevron-down"></i></button>
                                    <div class="dropdown-menu" style="">
                                        <a class="dropdown-item detail-btn d-flex align-items-center" href="#" data-url="" data-id="{{$i->id}}"><i class='bx bx-search-alt-2 me-1'></i> Detail</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item d-flex align-items-center" href="{{route('aktivitas.print.tiket', $i->no_referensi)}}" target="_blank" data-url=""><i class='bx bxs-discount me-1'></i> Print Tiket</a>
                                        @if (!in_array($i->status, ['done', 'cancel']))
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item d-flex align-items-center update-status-btn" href="#" data-url="{{route('aktivitas.update.status', $i->no_referensi)}}" data-status="{{$i->status}}"><i class='bx bx-task me-1'></i> Update Status</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item d-flex align-items-center" href="{{route('aktivitas.edit', $i->no_referensi)}}" data-url=""><i class='bx bxs-edit me-1'></i> Edit</a>

                                        @if ($i->stok)
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item d-flex align-items-center" href="{{route('aktivitas.stokout.view', $i->no_referensi)}}" data-url=""><i class='bx bx-archive-out me-1'></i> Edit Stok Keluar</a>
                                        @endif
                                        @endif

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
                            <td>Lokasi</td>
                            <td>:</td>
                            <td class="lokasi"></td>
                        </tr>
                        <tr>
                            <td>Sublokasi</td>
                            <td>:</td>
                            <td class="sublokasi"></td>
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
                        <tr>
                            <td>Barang Terpakai</td>
                            <td>:</td>
                            <td class="perangkat"></td>
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
                <h5 class="modal-title" id="modalExportPdfLabel">Export Aktivitas</h5>
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

<div id="modalUpdateStatus" class="modal fade" tabindex="-1" aria-labelledby="modalUpdateStatusLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUpdateStatusLabel">Update Status Aktivitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="modal-form" id="formUpdateStatus" action="" method="post">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-3">
                            <input class="form-check-input" type="radio" name="status" id="waiting" value="waiting">
                            <label class="form-check-label" for="waiting">
                                <span class="p-1 rounded text-white bg-secondary">Waiting</span>
                            </label>
                        </div>
                        <div class="mb-3 col-3">
                            <input class="form-check-input" type="radio" name="status" id="progress" value="progress">
                            <label class="form-check-label" for="progress">
                                <span class="p-1 rounded text-white bg-warning">Progress</span>
                            </label>
                        </div>
                        <div class="mb-3 col-3">
                            <input class="form-check-input" type="radio" name="status" id="done" value="done">
                            <label class="form-check-label" for="done">
                                <span class="p-1 rounded text-white bg-success">Done</span>
                            </label>
                        </div>
                        <div class="mb-3 col-3">
                            <input class="form-check-input" type="radio" name="status" id="cancel" value="cancel">
                            <label class="form-check-label" for="cancel">
                                <span class="p-1 rounded text-white bg-danger">Cancel</span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control edit-deskripsi" name="deskripsi" cols="30" rows="5" disabled></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light btn-submit-u">Simpan</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

@endsection

@section('css')
<link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
{{-- <link href="{{ URL::asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" /> --}}
@endsection

@section('script')
<!-- datatables -->
<script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
{{-- <script src="{{ URL::asset('assets/libs/select2/js/select2.min.js') }}"></script> --}}
@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        $("#datatable-aktivitas").dataTable({
            "aaSorting": [
                [0, "desc"]
            ]
        });

        // $('.job-select2').select2({
        //     placeholder: "-- Pilih Aktivitas/Job --"
        // });

        function getDetailAktivitas(ids) {
            $.get('aktivitas/' + ids + '/detail').done(function(response) {
                let res = response
                if (!res.status) return

                $('.ka').html(res.data.no_referensi)
                $('.lokasi').html(res.data.lokasi.nama)
                $('.sublokasi').html(res.data.sublokasi.nama)
                $('.ka').html(res.data.no_referensi)
                $('.tanggal').html(res.data.tanggal_berangkat + ' / ' + res.data.tanggal_pulang)

                let tempTeknisi = ''
                for (const teknisi of res.data.teknisi) {
                    tempTeknisi += '- ' + teknisi.karyawan.nama + '<br/>'
                }

                let tempBarang = ''
                for (const item of res.data.barang) {
                    tempBarang += '- ' + item.barang.nama + ' (' + (item.qty < 1 ? item.qty * -1 : item.qty) + " " + item.barang.satuan + ')' + ' [' + (item.is_new ? 'Baru' : 'Bekas') + ']' + '<br />'
                }

                $('.teknisi').html(tempTeknisi)
                $('.perangkat').html(tempBarang)
                $('.deskripsi').html(res.data.deskripsi)

                setTimeout(() => {
                    showModalAktivitas();
                }, 500);
            })
        }

        document.querySelectorAll('input[type="radio"][name="status"]').forEach(element => {
            element.addEventListener('change', function(e) {
                if (e.target.checked && (e.target.value == 'cancel' || e.target.value == 'done')) {
                    document.querySelector('.edit-deskripsi').disabled = false;
                } else {
                    document.querySelector('.edit-deskripsi').disabled = true;
                }
            })
        });

        function getDetailStatus(status) {

            let radioStatus = document.querySelectorAll('input[type="radio"][name="status"]');
            radioStatus.forEach(elm => {
                elm.checked = false
            });

            document.getElementById(status).checked = true

            setTimeout(() => {
                showModalUpdate();
            }, 500);
        }

        function showModalAktivitas() {
            const myModal = new bootstrap.Modal('#modalDetail', {
                show: true
            })
            myModal.show()
        }

        function showModalUpdate() {
            const myModal = new bootstrap.Modal('#modalUpdateStatus', {
                show: true
            })
            myModal.show()
        }

        $('#datatable-aktivitas').on('click', '.detail-btn', function() {
            getDetailAktivitas($(this).data('id'))
        })

        $('#datatable-aktivitas').on('click', '.update-status-btn', function() {
            getDetailStatus($(this).data('status'))

            const url = $(this).data("url");
            const form = $("#formUpdateStatus").attr("action", url);
        })

        $('.btn-submit-u').on('click', function() {
            Swal.fire({
                title: "Apakah anda yakin?"
                , text: "Status aktivitas akan diupdate!"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: "#3085d6"
                , cancelButtonColor: "#d33"
                , confirmButtonText: "Ya, Update!"
                , cancelButtonText: "Batalkan"
            , }).then((result) => {
                if (result.isConfirmed) {
                    $('#formUpdateStatus')[0].submit();
                }
            });
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
