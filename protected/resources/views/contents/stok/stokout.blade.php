@extends('layouts.master')

@section('title')
    Stok
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Log
        @endslot
        @slot('title')
            Stok Keluar
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex flex-wrap justify-content-between">
                        <h4 class="card-title mb-4">Input Stok Keluar</h4>
                        <div class="button-group">
                            <a href="{{ route('stok.index') }}" class="btn btn-sm btn-warning"><i class='bx bx-arrow-back'></i>
                                Kembali</a>
                        </div>
                    </div>

                    <form action="{{ route('stok.keluar.store') }}" method="post">
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
                                required>
                        </div>

                        <div class="mb-2 col-lg-5">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" cols="30" rows="5"></textarea>
                        </div>

                        {{-- <div class="mb-2 col-lg-5">
                        <label class="form-label">Aktivitas/Job</label>
                        <input class="form-control" type="date" name="tanggal" placeholder="Masukkan tanggal" required>
                        <select class="form-control job-select2" name="aktivitas" id="ak">
                            <option></option>
                            @foreach ($aktivitas as $act)
                            <option value="{{$act->id}}">[{{ $act->no_referensi }}] {{ $act->lokasi->nama }} - {{ $act->sublokasi->nama }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                        <div class="mb-2">
                            <label class="form-label">Barang</label>

                            <!-- Repeater Html Start -->
                            <div id="repeater">
                                <!-- Repeater Heading -->
                                <div class="repeater-heading mb-2">
                                    <button type="button" class="btn btn-primary pull-right repeater-add-btn">
                                        Add
                                    </button>
                                </div>
                                <div class="clearfix"></div>
                                <!-- Repeater Items -->
                                <div class="items" data-group="barang">
                                    <!-- Repeater Content -->
                                    <div class="item-content">
                                        <div class="row">
                                            <div class="col-lg-5">
                                                {{-- <input type="text" class="form-control" id="inputName" placeholder="Name" data-name="name"> --}}
                                                <select class="form-control select2 barang-select2" id="inputItem"
                                                    data-name="item">
                                                    <option value=""></option>
                                                    @foreach ($barang as $item)
                                                        <option value="{{ $item->id }}"
                                                            title="Baru: {{ $item->new ? $item->new : 0 }} | Bekas: {{ $item->second ? $item->second : 0 }}">
                                                            {{ $item->nama }} ({{ $item->kode }}) - {{ $item->satuan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2 pt-2">
                                                <input class="form-check-input" type="checkbox" data-name="bekas"
                                                    id="inputCondition" value="bekas">
                                                <label class="form-check-label" for="inputCondition">
                                                    Bekas
                                                </label>
                                            </div>
                                            <div class="col-lg-3">
                                                <input type="text" class="form-control" id="inputQty" placeholder="Qty"
                                                    data-name="qty">
                                            </div>
                                            <div class="col-lg-2 repeater-remove-btn">
                                                <button class="btn btn-danger remove-btn">
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <!-- Repeater Remove Btn -->
                                <div class="pull-right repeater-remove-btn">
                                    <button class="btn btn-danger remove-btn">
                                        Remove
                                    </button>
                                </div> --}}
                                    <div class="clearfix"></div>
                                </div>
                                <!-- Repeater End -->
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
            $("#repeater").createRepeater({
                showFirstItemToDefault: true,
            });

            $(".repeater-add-btn").click(function() {
                let select2Arr = $('.barang-select2')
                select2Arr.each(function(index, el) {
                    $(el).select2({
                        placeholder: "-- Pilih Barang --",
                        templateResult: formatOption
                    });
                })
            })

            $('.barang-select2').select2({
                placeholder: "-- Pilih Barang --",
                templateResult: formatOption
            });

            function formatOption(option) {
                var $option = $('<div>' + option.text + '</div><small> ' + option.title + ' </small>');
                return $option;
            };

            $('.job-select2').select2({
                placeholder: "-- Pilih Aktivitas/Job --"
            });
        })
    </script>
@endpush
