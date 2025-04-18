@extends('layouts.master')

@section('title') Aktivitas @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Log @endslot
@slot('title') Edit Aktivitas @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap justify-content-between">
                    <h4 class="card-title mb-4">Edit Aktivitas</h4>
                    <div class="button-group">
                        <a href="{{route('aktivitas.index')}}" class="btn btn-sm btn-warning"><i class='bx bx-arrow-back'></i> Kembali</a>
                    </div>
                </div>

                <form action="{{route('aktivitas.update', $aktivitas->no_referensi)}}" method="post">
                    @csrf
                    <div class="mb-4 col-lg-5">
                        <label class="form-label">No Referensi</label>
                        <input class="form-control" type="text" name="norefv" value="{{$aktivitas->no_referensi}}" disabled required>
                        <input class="form-control" type="hidden" name="noref" value="{{$aktivitas->no_referensi}}" required>
                    </div>

                    <div class="mb-2 col-lg-10">
                        <div class="row">
                            <div class="col-lg-5">
                                <label class="form-label">Tanggal Berangkat</label>
                                <input class="form-control" type="date" name="tanggal_berangkat" placeholder="Masukkan tanggal berangkat" value="{{$aktivitas->tanggal_berangkat}}" required>
                            </div>
                            <div class="col-lg-5">
                                <label class="form-label">Tanggal Pulang</label>
                                <input class="form-control" type="date" name="tanggal_pulang" placeholder="Masukkan tanggal pulang" value="{{$aktivitas->tanggal_pulang}}" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 col-lg-10">
                        <div class="row">
                            <div class="col-lg-5">
                                <label class="form-label">Lokasi</label>
                                <select class="form-control" name="lokasi" id="lokasi">
                                    <option value=""></option>
                                    @foreach ($lokasi as $lok)
                                    <option value="{{$lok->id}}" {{$lok->id == $aktivitas->lokasi->id ? 'selected': ''}}>{{$lok->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-5">
                                <label class="form-label">Sub Lokasi</label>
                                <select class="form-control" name="sublokasi" id="sublokasi">
                                    @foreach ($sublokasi as $sub)
                                        <option value="{{$sub->id}}" {{$aktivitas->sublokasi->id == $sub->id ? "selected" : ''}}>{{ $sub->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2 col-lg-8">
                        <label class="form-label">Teknisi</label>
                        <select class="form-control select2-teknisi" name="teknisi[]" id="tek" multiple>
                            @foreach ($karyawan as $teknisi)
                            <option value="{{$teknisi->id}}" {{in_array($teknisi->id, $teknisiArr) ? 'selected' : ''}}>{{$teknisi->nama}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2 col-lg-8">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" id="desc" cols="30" rows="10">{{$aktivitas->deskripsi}}</textarea>
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
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
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
@endsection

@push('page-js')
<script>
    $(document).ready(function() {

        $('.select2-teknisi').select2({
            'placeholder': ' -- pilih teknisi --'
        });

        let selectLokasi = $('#lokasi').select2({
            'placeholder': ' -- pilih lokasi --'
        });

        let selectSubLokasi = $('#sublokasi').select2({
            'placeholder': ' -- pilih sublokasi --'
        });

        selectLokasi.on('select2:select', function() {
            selectSubLokasi.html('<option></option');
            getSubLokasi($(this).val())
        })

        function getSubLokasi(ids) {
            let urlA = window.location.origin;
            $.get(urlA + '/aktivitas/lokasi/' + ids).done(function(response) {
                let res = response
                if (!res.status) return

                for (const data of res.data) {
                    var newOption = new Option(data.nama, data.id, false, false);
                    // Append it to the select
                    selectSubLokasi.append(newOption).trigger('change');
                }
            })
        }

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
