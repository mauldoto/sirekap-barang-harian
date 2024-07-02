@extends('layouts.master')

@section('title') Aktivitas @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Log @endslot
@slot('title') Input Aktivitas  @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap justify-content-between">
                    <h4 class="card-title mb-4">Input Aktivitas</h4>
                    <div class="button-group">
                        <a class="btn btn-sm btn-warning"><i class='bx bx-search-alt-2'></i> Kembali</a>
                    </div>
                </div>

                <form action="{{route('karyawan.store')}}" method="post">
                    @csrf
                    <div class="mb-4 col-lg-5">
                        <label class="form-label">No Referensi</label>
                        <input class="form-control" type="text" name="norefv" value="{{generateReference('JOB')}}" disabled required>
                        <input class="form-control" type="hidden" name="noref" value="{{generateReference('JOB')}}" required>
                    </div>

                    <div class="mb-2 col-lg-10">
                        <div class="row">
                            <div class="col-lg-5">
                                <label class="form-label">Tanggal Berangkat</label>
                                <input class="form-control" type="date" name="tanggal" placeholder="Masukkan tanggal" required>
                            </div>
                            <div class="col-lg-5">
                                <label class="form-label">Tanggal Pulang</label>
                                <input class="form-control" type="date" name="tanggal" placeholder="Masukkan tanggal" required>
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
                                        <option value="{{$lok->id}}">{{$lok->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-5">
                                <label class="form-label">Sub Lokasi</label>
                                <select class="form-control" name="sublokasi" id="sublokasi">
                                    <option value=""></option>
                                    <option value="sdsd">sdsd</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2 col-lg-8">
                        <label class="form-label">Teknisi</label>
                        <select class="form-control select2-teknisi" name="teknisi[]" id="tek" multiple>
                            <option value=""></option>
                            @foreach ($karyawan as $teknisi)
                                <option value="{{$teknisi->id}}">{{$teknisi->nama}}</option>
                            @endforeach
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
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}"  rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/select2/css/select2.min.css') }}"  rel="stylesheet" type="text/css" />
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

        $('.select2-teknisi').select2();

        let selectLokasi = $('#lokasi').select2({
            'placeholder': ' -- pilih lokasi --'
        });

        let selectSubLokasi = $('#sublokasi').select2({
            'placeholder': ' -- pilih sublokasi --'
        });

        selectLokasi.on('select2:select', function(){
            selectSubLokasi.html('<option></option');
            getSubLokasi($(this).val())
        })

        function getSubLokasi(ids) {
            $.get('lokasi/' + ids).done(function(response) {
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
