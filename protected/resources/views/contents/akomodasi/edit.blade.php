@extends('layouts.master')

@section('title') Akomodasi @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Log @endslot
@slot('title') Edit Akomodasi @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap justify-content-between">
                    <h4 class="card-title mb-4">Edit Akomodasi</h4>
                    <div class="button-group">
                        <a href="{{route('akomodasi.index')}}" class="btn btn-sm btn-warning"><i class='bx bx-arrow-back'></i> Kembali</a>
                    </div>
                </div>

                <form action="{{route('akomodasi.update', $akomodasi->no_referensi)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="mb-2 col-lg-5">
                            <label class="form-label">No Referensi</label>
                            <input class="form-control" type="text" name="norefv" value="{{$akomodasi->no_referensi}}" disabled required>
                            {{-- <input class="form-control" type="hidden" name="noref" value="{{generateReference('AKM')}}" required> --}}
                        </div>

                        <div class="mb-2 col-lg-5">
                            <label class="form-label">Tanggal Terbit</label>
                            <input class="form-control" type="date" name="tanggal" placeholder="Masukkan tanggal" value="{{$akomodasi->tanggal_terbit}}" required>
                        </div>

                        <div class="mb-2 col-lg-5">
                            <label class="form-label">Nominal Pengajuan</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp.</span>
                                <input class="form-control" id="np" type="text" name="nominal_pengajuan" value="{{$akomodasi->nominal_pengajuan}}" required>
                            </div>
                        </div>

                        <div class="mb-2 col-lg-5">
                            <label class="form-label">Nominal Realisasi</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp.</span>
                                <input class="form-control" id="nr" type="text" name="nominal_realisasi" value="{{$akomodasi->nominal_realisasi}}" required>
                            </div>
                        </div>

                        <div class="mb-2 col-lg-5">
                            <label class="form-label">Aktivitas/Job</label>
                            {{-- <input class="form-control" type="date" name="tanggal" placeholder="Masukkan tanggal" required> --}}
                            <select class="form-control job-select2" name="aktivitas[]" id="ak" multiple>
                                @foreach ($akomodasi->aktivitas as $selectedAct)
                                <option value="{{$selectedAct->id}}" selected>[{{ $selectedAct->no_referensi }}] {{ $selectedAct->lokasi->nama }} - {{ $selectedAct->sublokasi->nama }}</option>
                                @endforeach
                                @foreach ($aktivitas as $act)
                                <option value="{{$act->id}}">[{{ $act->no_referensi }}] {{ $act->lokasi->nama }} - {{ $act->sublokasi->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-lg-5">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan" cols="30" rows="5">{!! $akomodasi->deskripsi !!}</textarea>
                        </div>

                        <div class="mb-3 col-lg-5">
                            <label class="form-label">Upload Dokumen <small>(Tidak Wajib)</small></label>
                            <input type="file" class="form-control" name="dokumen" id="" accept=".pdf">
                            @if($akomodasi->dokumen)
                            <br>
                            <div style="padding: 5px;" class="bg-light d-flex justify-content-between align-items-center">
                                <a href="{{route('akomodasi.open', $akomodasi->no_referensi)}}" target="_blank">{{ $akomodasi->dokumen->nama }}</a>
                                <button type="button" class="btn btn-sm btn-danger delete-file" data-url="{{route('akomodasi.delete.file', $akomodasi->no_referensi)}}">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="btn-submit mt-5 d-flex justify-content-end">
                        <button class="btn btn-md btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<form class="hidden" id="hapusFile" action="" method="POST">
    @csrf
</form>

@endsection

@section('css')
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
<script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/select2/js/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/imask.js') }}"></script>
@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        $('.job-select2').select2({
            placeholder: "-- Pilih Job --"
        });

        IMask(
            document.getElementById('np'), {
                mask: Number
                , min: 0
                , max: 10000000
                , thousandsSeparator: '.'
            }
        )

        IMask(
            document.getElementById('nr'), {
                mask: Number
                , min: 0
                , max: 10000000
                , thousandsSeparator: '.'
            }
        )

        $(".delete-file").on("click", function() {
            const url = $(this).data("url");
            const form = $("#hapusFile").attr("action", url);

            Swal.fire({
                title: "Apakah anda yakin?"
                , text: "Dokumen akan dihapus dari sistem!"
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
