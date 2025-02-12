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
                                <option value="stok-barang" {{$report && $report == 'stok-barang' ? 'selected' : ''}}>Stok Barang</option>
                                <option value="penggunaan-barang" {{$report && $report == 'penggunaan-barang' ? 'selected' : ''}}>Penggunaan Barang</option>
                                <option value="aktivitas" {{$report && $report == 'aktivitas' ? 'selected' : ''}}>Aktivitas</option>
                                <option value="aktivitas-karyawan" {{$report && $report == 'aktivitas-karyawan' ? 'selected' : ''}}>Detail Aktivitas Karyawan</option>
                                <option value="alokasi" {{$report && $report == 'alokasi' ? 'selected' : ''}}>Alokasi Perangkat</option>
                                <option value="akomodasi" {{$report && $report == 'akomodasi' ? 'selected' : ''}}>Akomodasi</option>
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

@push('page-css')
<style>
    .select2-selection__clear {
        padding-right: 20px;
    }
</style>
@endpush

@push('page-js')
<script>
    $(document).ready(function() {
        $(".select2-report").select2({
            'placeholder': '-- Pilih Report --'
        });

        $(".select2-barang").select2({});

        let selectLokasi = $('#lokasi').select2({
            'placeholder': ' -- pilih lokasi --',
            allowClear: true
        });

        let selectSubLokasi = $('#sublokasi').select2({
            'placeholder': ' -- pilih sublokasi --'
        });

        $('.select2-teknisi').select2({
            'placeholder': ' -- pilih karyawan --'
        });

        selectLokasi.on('select2:select', function() {
            selectSubLokasi.html('<option></option');
            getSubLokasi($(this).val())
        })

        selectLokasi.on('select2:clear', function() {
            selectSubLokasi.html('<option></option');
            selectSubLokasi.val(null).trigger('change.select2');
        })

        $('.btn-export').on('click', function(){
            $('input[name="export"]').val($(this).data('export'));

            $('#reportForm')[0].submit();
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
