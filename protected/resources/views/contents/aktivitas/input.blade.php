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
                        <a href="{{route('aktivitas.index')}}" class="btn btn-sm btn-warning"><i class='bx bx-arrow-back'></i> Kembali</a>
                    </div>
                </div>

                <form action="{{route('aktivitas.store')}}" method="post">
                    @csrf
                    <div class="mb-2 col-lg-10">
                        <div class="row">
                            <div class="col-lg-5">
                                <label class="form-label">Tanggal Berangkat</label>
                                <input class="form-control" type="date" name="tanggal_berangkat" placeholder="Masukkan tanggal berangkat" required>
                            </div>
                            <div class="col-lg-5">
                                <label class="form-label">Tanggal Pulang</label>
                                <input class="form-control" type="date" name="tanggal_pulang" placeholder="Masukkan tanggal pulang" required>
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
                                <select class="form-control" name="sublokasi[]" id="sublokasi" multiple>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2 col-lg-8">
                        <label class="form-label">Teknisi</label>
                        <select class="form-control select2-teknisi" name="teknisi[]" id="tek" multiple>
                            @foreach ($karyawan as $teknisi)
                                <option value="{{$teknisi->id}}">{{$teknisi->nama}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <div class="repeater-heading mb-3 mt-3">
                            <button type="button" class="btn btn-primary pull-right generate-btn">
                                Generate Detail
                            </button>
                        </div>
                       
                        <div class="review">
                            {{-- content here --}}
                        </div>
        
                        <div class="btn-submit mt-5 d-flex justify-content-end">
                            <button class="btn btn-md btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="repeater-shadow" class="border rounded p-3 repeater-1 mb-2" style="display: none">
    <div class="row">
        <div class="mb-2 col-lg-5">
            <label class="form-label fw-bold detail-title"></label>
        </div>
    </div>

    <div class="mb-2 col-lg-8 border border-warning rounded p-3">
        <label class="form-label col-12">Jenis Pekerjaan</label>
        <div class="ms-2 row">
            @foreach ($jenisKerja as $jenis)
            <div class="form-check mb-1 col-3">
                <input class="form-check-input opt-job" type="checkbox" name="" id="" value="{{$jenis->id}}">
                <label class="form-check-label opt-job-label" for="">
                  {{$jenis->nama}}
                </label>
            </div>
            @endforeach
        </div>
    </div>

    <div class="mb-2 col-lg-8">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control desc" name="" cols="20" rows="5"></textarea>
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

        let sublokasi;

        $('.select2-teknisi').select2({
            'placeholder': ' -- pilih teknisi --'
        });

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

                sublokasi = res.data
                for (const data of res.data) {
                    var newOption = new Option(data.nama, data.id, false, false);
                    // Append it to the select
                    selectSubLokasi.append(newOption).trigger('change');
                }
            })
        }

        $(".generate-btn").click(function() {
            $(".review").empty()
            let selectedSublokasi = $("#sublokasi").val()
            console.log(selectedSublokasi)
           
            setTimeout(() => {
                for (const sub of selectedSublokasi) {
                    let shadow = $('#repeater-shadow').clone();
                    let data = sublokasi.find((item) => item.id == sub)
                    shadow.find('.detail-title').html(data.nama)
                    let optJob = shadow.find('.opt-job')
                    let optJobLabel = shadow.find('.opt-job-label')
                    shadow.find('.desc').attr('name', 'deskripsi['+ data.id + "]")

                    let iteration = 1
                    for (const opt of optJob) {
                        $(opt).attr('name', 'opt['+ data.id +']').attr('id', 'optJob-' + data.kode + '-' + iteration)
                        iteration++
                    }

                    let iteration2 = 1
                    for (const opt of optJobLabel) {
                        $(opt).attr('for', 'optJob-' + data.kode + '-' + iteration2)
                        iteration2++
                    }

                    shadow.css('display', '')
                    // shadow.find('#inputCondition').attr('data-name', 'bekas.'+sub)
                    // shadow.find('#inputQty').attr('data-name', 'qty.'+sub)

                    $(".review").append(shadow)

                    // $('.barang-select2-' + sub).select2({
                    //     placeholder: "-- Pilih Barang --",
                    //     templateResult: formatOption
                    // });

                }

            }, 500);
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
