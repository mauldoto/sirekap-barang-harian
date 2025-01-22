@extends('layouts.master')

@section('title') Akomodasi @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Log @endslot
@slot('title') Input Akomodasi @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap justify-content-between">
                    <h4 class="card-title mb-4">Input Akomodasi</h4>
                    <div class="button-group">
                        <a href="{{route('akomodasi.index')}}" class="btn btn-sm btn-warning"><i class='bx bx-arrow-back'></i> Kembali</a>
                    </div>
                </div>

                <form action="{{route('stok.masuk.store')}}" method="post">
                    @csrf
                    <div class="mb-2 col-lg-5">
                        <label class="form-label">No Referensi</label>
                        <input class="form-control" type="text" name="norefv" value="{{generateReference('AKM')}}" disabled required>
                        <input class="form-control" type="hidden" name="noref" value="{{generateReference('AKM')}}" required>
                    </div>

                    <div class="mb-2 col-lg-5">
                        <label class="form-label">Tanggal Terbit</label>
                        <input class="form-control" type="date" name="tanggal" placeholder="Masukkan tanggal" required>
                    </div>

                    <div class="mb-3 col-lg-5">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" cols="30" rows="5"></textarea>
                    </div>

                    <div class="mb-2 col-lg-5">
                        <label class="form-label">Aktivitas/Job</label>
                        {{-- <input class="form-control" type="date" name="tanggal" placeholder="Masukkan tanggal" required> --}}
                        <select class="form-control job-select2" name="aktivitas" id="ak">
                            <option></option>
                            {{-- @foreach ($aktivitas as $act)
                            <option value="{{$act->id}}">[{{ $act->no_referensi }}] {{ $act->lokasi->nama }} - {{ $act->sublokasi->nama }}</option>
                            @endforeach --}}
                        </select>
                    </div>

                    <div class="btn-submit mt-5 d-flex justify-content-end">
                        <button class="btn btn-md btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
<link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/select2/css/select2.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
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
        $("#repeater").createRepeater({
            showFirstItemToDefault: true
        , });

        $(".repeater-add-btn").click(function() {
            let select2Arr = $('.select2')
            select2Arr.each(function(index, el) {
                $(el).select2({
                    placeholder: "-- Pilih Barang --"
                });
            })
        })

        $('.select2').select2({
            placeholder: "-- Pilih Barang --"
        });

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
