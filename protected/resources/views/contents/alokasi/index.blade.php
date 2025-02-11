@extends('layouts.master')

@section('title') Alokasi Perangkat @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Alokasi Perangkat @endslot
@slot('title') Alokasi Perangkat @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap justify-content-between">
                    <h4 class="card-title mb-2">Pilih Alokasi Perangkat</h4>
                </div>

                <form action="" method="GET">
                    <div class="row">
                        <div class="col-lg-5">
                            <label class="form-label">Lokasi</label>
                            <select class="form-control" name="lokasi" id="lokasi">
                                <option value=""></option>
                                @foreach ($daftarLokasi as $lok)
                                <option value="{{$lok->id}}" {{$lokasi && $lokasi == $lok->id ? 'selected' : ''}}>{{$lok->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-5">
                            <label class="form-label">Sub Lokasi</label>
                            <select class="form-control" name="sublokasi" id="sublokasi">
                                <option value=""></option>
                                @foreach ($daftarSublokasi as $sublok)
                                <option value="{{$sublok->id}}" {{$sublokasi && $sublokasi == $sublok->id ? 'selected' : ''}}>{{$sublok->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-10 mt-2">
                            <button class="btn btn-primary col-lg-12">Pilih</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($sublokasi)
    @include('contents.alokasi.pages.setting')
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
<script src="{{ URL::asset('assets/libs/repeater.js') }}"></script>
@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        var DTalokasi;
        
        renderTable();
        function renderTable() {
            var checkDT = $('.table-here').find('.table-alokasi')
            console.log(checkDT)
            if (checkDT.length > 0) {
                DTalokasi.destroy()
            }
            var DTSalokasi = $('.table-alokasi').clone()
            $('.table-here').append(DTSalokasi)
            DTSalokasi.css('display', '')
            DTSalokasi.attr('id', 'datatable-alokasi')

            DTalokasi = $('.table-here').find('.table-alokasi').dataTable({
                "aaSorting": [
                    [0, "asc"]
                ]
            });
        }

        $("#repeater").createRepeater({
            showFirstItemToDefault: true
        , });

        $(".repeater-add-btn").click(function() {
            let select2Arr = $('.barang-select2')
            select2Arr.each(function(index, el) {
                $(el).select2({
                    placeholder: "-- Pilih Barang --"
                , });
            })
        })

        $(".barang-select2").select2({
            placeholder: "-- Pilih Barang --"
        , });

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

        handleDeleteItem()
        function handleDeleteItem() {
            $("#datatable-alokasi").on('click', ".dlt-data", function() {
                const keyy = $(this).data('key')
                $('.shadow-table').find('.row-' + keyy).remove()
                $('.table-here').html('')
                renderTable()

                handleDeleteItem()
            })
        }
    })

</script>
@endpush
