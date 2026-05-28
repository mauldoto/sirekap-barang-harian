@extends('layouts.master')

@section('title')
    Aktivitas
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Log
        @endslot
        @slot('title')
            Review Stok Keluar
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex flex-wrap justify-content-between">
                        <h4 class="card-title mb-4">Form Pengajuan Stok Keluar Tiket [{{ $aktivitas->no_referensi }}]</h4>
                        <div class="button-group">
                            <a href="{{ route('aktivitas.index') }}" class="btn btn-sm btn-warning"><i
                                    class='bx bx-arrow-back'></i> Kembali</a>
                        </div>
                    </div>

                    <form class="form-approve"
                        action="{{ route('aktivitas.reviewstokout.post', $aktivitas->no_referensi) }}" method="post">
                        @csrf
                        <div class="mb-2 col-lg-5">
                            <label class="form-label">No Referensi</label>
                            <input class="form-control" type="text" name="norefv" value="{{ generateReference('SK') }}"
                                disabled required>
                            <input class="form-control" type="hidden" name="noref" value="{{ generateReference('SK') }}"
                                required>
                        </div>

                        <div class="mb-2 col-lg-5">
                            <label class="form-label">Tanggal</label>
                            <input class="form-control" type="date" name="tanggal" placeholder="Masukkan tanggal"
                                value="{{ $aktivitas->tanggal_berangkat }}" disabled required>
                        </div>

                        <div class="mb-2 col-lg-5">
                            <label class="form-label">Aktivitas/Job</label>
                            {{-- <input class="form-control" type="date" name="tanggal" placeholder="Masukkan tanggal"
                                required> --}}
                            <input class="form-control" name="aktivitas" placeholder=""
                                value="{{ '[' . $aktivitas->no_referensi . '] ' . $aktivitas->lokasi->nama . ' - ' . $aktivitas->sublokasi->nama }}"
                                disabled required>
                        </div>

                        <hr class="border-primary border-2">

                        <div class="mb-2">
                            <label class="form-label">Input Jumlah Barang Berdasarkan Pengajuan Stok Keluar</label>

                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Kondisi</th>
                                        <th>Jumlah Stok Dibawa</th>
                                        <th style="width:15%">Jumlah Stok Terpakai</th>
                                        <th>Gudang</th>
                                        <!-- <th></th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengajuanStok as $key => $item)
                                        <tr class="row-{{ $item->id_barang }}-{{ $item->is_new }}">
                                            <input type="hidden" name="input[{{ $key }}][barang]"
                                                value="{{ $item->id_barang }}">
                                            <input type="hidden" name="input[{{ $key }}][kondisi]"
                                                value="{{ $item->is_new }}">
                                            <input type="hidden" name="input[{{ $key }}][qty]"
                                                value="{{ $item->sumqty }}">
                                            <input type="hidden" name="input[{{ $key }}][gudang]"
                                                value="{{ $item->id_gudang }}">

                                            <td>{{ $item->barang->nama }}</td>
                                            <td>{{ $item->is_new ? 'Baru' : 'Bekas' }}</td>
                                            <td>{{ $item->sumqty }}</td>
                                            <td>
                                                <input type="number" class="col-2 form-control qty-terpakai"
                                                    name="input[{{ $key }}][qty_used]" max="{{ $item->sumqty }}"
                                                    min="0" value="{{ $item->sumqty_used }}" disabled>
                                            </td>
                                            <td>{{ $item->gudang->nama }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <hr>

                            <div class="btn-submit mt-5 d-flex justify-content-end">
                                <button type="button" class="btn btn-md btn-danger me-2 btn-decline">
                                    <i class="mdi mdi-thumb-down"></i> Tolak
                                </button>
                                <button type="button" class="btn btn-md btn-success btn-approve">
                                    <i class="mdi mdi-thumb-up"></i>
                                    Setujui
                                </button>
                            </div>
                    </form>

                    <form class="hidden form-decline"
                        action="{{ route('aktivitas.declinereviewstokout.post', $aktivitas->no_referensi) }}"
                        method="post">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" id="app-style"
        rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" id="app-style" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('assets/libs/select2/css/select2.min.css') }}" id="app-style" rel="stylesheet"
        type="text/css" />
    <style>
        .items {
            margin-bottom: 4px !important;
        }
    </style>
@endsection

@section('script')
    <!-- datatables -->
    <script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/repeater.js') }}"></script>
@endsection

@push('page-js')
    <script>
        $(document).ready(function() {

            $(".btn-approve").on("click", function() {
                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Pengajuan stok keluar akan disetujui!",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Setujui!",
                    cancelButtonText: "Batalkan"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(".form-approve").submit();
                    }
                });
            });

            $(".btn-decline").on("click", function() {
                const form = $(".form-decline").attr("action", $(this).data("url"));

                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Pengajuan stok keluar akan ditolak!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Tolak!",
                    cancelButtonText: "Batalkan"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form[0].submit();
                    }
                });
            });
        })
    </script>
@endpush
