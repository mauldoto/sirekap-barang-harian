@extends('layouts.master')

@section('title') Report @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Report @endslot
@slot('title') Report @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap justify-content-between">
                    <h4 class="card-title mb-2">Pilih Report</h4>
                </div>

                <form action="" method="GET">
                    <div class="row">
                        <div class="mb-2 col-lg-4">
                            <select class="form-control select2-report" name="report" id="">
                                <option value=""></option>
                                <option value="stok-barang">Stok</option>
                                <option value="penggunaan-barang">Penggunaan Stok</option>
                                <option value="aktivitas">Aktivitas</option>
                                <option value="detail-aktivitas-karyawan">Detail Aktivitas Karyawan</option>
                            </select>
                        </div>
                        <div class="mb-2 col-lg-2 d-flex align-items-end">
                            <button class="btn btn-primary">Pilih</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($report)
    @include('contents.report.pages.' . $report)
    @endif

</div>

@endsection

@section('css')
<link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
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
        $(".select2-report").select2({
            'placeholder': '-- Pilih Report --'
        });

        $(".select2-barang").select2({});

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
            console.log(location)
            $.get(location.origin + '/aktivitas/lokasi/' + ids).done(function(response) {
                let res = response
                if (!res.status) return

                for (const data of res.data) {
                    var newOption = new Option(data.nama, data.id, false, false);
                    // Append it to the select
                    selectSubLokasi.append(newOption).trigger('change');
                }
            })
        }

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
    })

</script>
@endpush
