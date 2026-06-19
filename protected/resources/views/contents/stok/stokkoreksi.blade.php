@extends('layouts.master')

@section('title')
    Koreksi Stok
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Log
        @endslot
        @slot('title')
            Koreksi Stok
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex flex-wrap justify-content-between">
                        <h4 class="card-title mb-4">Input Koreksi Stok</h4>
                        <div class="button-group">
                            <a href="{{ route('stok.index') }}" class="btn btn-sm btn-warning"><i class='bx bx-arrow-back'></i>
                                Kembali</a>
                        </div>
                    </div>

                    {{-- Alert errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('stok.koreksi.store') }}" method="post">
                        @csrf

                        {{-- Header fields --}}
                        <div class="row mb-3">
                            <div class="col-lg-5 mb-2">
                                <label class="form-label">No Referensi</label>
                                <input class="form-control" type="text" name="norefv"
                                    value="{{ generateReference('KS') }}" disabled required>
                                <input class="form-control" type="hidden" name="noref"
                                    value="{{ generateReference('KS') }}" required>
                            </div>

                            <div class="col-lg-5 mb-2">
                                <label class="form-label">Tanggal</label>
                                <input class="form-control" type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                                    required>
                            </div>

                            <div class="col-lg-5 mb-2">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan" cols="30" rows="3" placeholder="Alasan koreksi stok...">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>

                        {{-- Legend --}}
                        <div class="alert alert-info p-2 mb-3" style="font-size: 0.85rem;">
                            <i class='bx bx-info-circle me-1'></i>
                            Pilih <strong>Tambah</strong> untuk menambah stok, atau <strong>Kurangi</strong> untuk
                            mengurangi stok.
                            Pengurangan stok tidak boleh melebihi stok yang tersedia di gudang.
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Barang</label>

                            {{-- Repeater --}}
                            <div id="repeater">
                                {{-- Heading --}}
                                <div class="repeater-heading mb-2">
                                    <button type="button" class="btn btn-primary pull-right repeater-add-btn">
                                        <i class='bx bx-plus me-1'></i> Add
                                    </button>
                                </div>
                                <div class="clearfix"></div>

                                {{-- Items --}}
                                <div class="items" data-group="barang">

                                    {{-- Table-like header row (visible on desktop) --}}
                                    <div class="row repeater-col-header text-muted mb-1 d-none d-lg-flex"
                                        style="font-size:0.78rem;">
                                        <div class="col-lg-2">Gudang</div>
                                        <div class="col-lg-3">Barang</div>
                                        <div class="col-lg-1 text-center">Kondisi</div>
                                        <div class="col-lg-1">Qty</div>
                                        <div class="col-lg-2 text-center">Aksi Koreksi</div>
                                        <div class="col-lg-1"></div>
                                    </div>

                                    {{-- Repeater content --}}
                                    <div class="item-content">
                                        <div class="row align-items-center mb-2">

                                            {{-- Gudang --}}
                                            <div class="col-lg-2 mb-2">
                                                <select class="form-control select2-gudang" data-name="gudang">
                                                    <option value=""></option>
                                                    @foreach ($gudang as $ws)
                                                        <option value="{{ $ws->id }}">{{ $ws->nama }}
                                                            ({{ $ws->kode }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Barang --}}
                                            <div class="col-lg-3 mb-2">
                                                <select class="form-control select2-item" data-name="item">
                                                    <option value=""></option>
                                                    @foreach ($barang as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama }}
                                                            ({{ $item->kode }})
                                                            - {{ $item->satuan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Bekas checkbox --}}
                                            <div class="col-lg-1 mb-2 pt-1 text-center">
                                                <input class="form-check-input" type="checkbox" data-name="bekas"
                                                    value="bekas" data-value="bekas">
                                                <label class="form-check-label">Bekas</label>
                                            </div>

                                            {{-- Qty --}}
                                            <div class="col-lg-1 mb-2">
                                                <input type="number" class="form-control" placeholder="Qty" min="1"
                                                    data-name="qty">
                                            </div>

                                            {{-- Aksi Koreksi (Tambah / Kurangi) --}}
                                            <div class="col-lg-2 mb-2">
                                                <div class="aksi-koreksi-toggle d-flex rounded overflow-hidden border"
                                                    style="height:38px;">
                                                    <label
                                                        class="flex-fill d-flex align-items-center justify-content-center m-0 px-1 toggle-label-tambah"
                                                        style="cursor:pointer; font-size:0.82rem; gap:4px;">
                                                        <input type="radio" data-name="aksi" value="tambah"
                                                            data-value="tambah" checked class="radio-aksi"
                                                            style="display:none;">
                                                        <i class='bx bx-plus-circle'></i> Tambah
                                                    </label>
                                                    <label
                                                        class="flex-fill d-flex align-items-center justify-content-center m-0 px-1 toggle-label-kurangi border-start"
                                                        style="cursor:pointer; font-size:0.82rem; gap:4px;">
                                                        <input type="radio" data-name="aksi" value="kurangi"
                                                            data-value="kurangi" class="radio-aksi"
                                                            style="display:none;">
                                                        <i class='bx bx-minus-circle'></i> Kurang
                                                    </label>
                                                </div>
                                            </div>

                                            {{-- Remove --}}
                                            <div class="col-lg-1 mb-2 repeater-remove-btn">
                                                <button class="btn btn-danger remove-btn btn-sm w-100">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                {{-- Repeater End --}}
                            </div>

                            <div class="btn-submit mt-4 d-flex justify-content-end">
                                <button class="btn btn-md btn-primary">
                                    <i class='bx bx-save me-1'></i> Simpan Koreksi
                                </button>
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

        /* Toggle button styling */
        .aksi-koreksi-toggle {
            background: #f8f9fa;
            user-select: none;
        }

        .toggle-label-tambah,
        .toggle-label-kurangi {
            transition: background 0.15s, color 0.15s;
            color: #6c757d;
        }

        .toggle-label-tambah.active {
            background: #198754;
            color: #fff;
            font-weight: 600;
        }

        .toggle-label-kurangi.active {
            background: #dc3545;
            color: #fff;
            font-weight: 600;
        }

        /* Dark mode compatibility */
        [data-bs-theme="dark"] .aksi-koreksi-toggle {
            background: #343a40;
        }
    </style>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/repeater.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/imask.js') }}"></script>
@endsection

@push('page-js')
    <script>
        $(document).ready(function() {

            // ── Initialize repeater ─────────────────────────────────────
            $("#repeater").createRepeater({
                showFirstItemToDefault: true,
            });

            // ── Helpers ─────────────────────────────────────────────────
            function initSelect2(context) {
                $(context).find('.select2-gudang').select2({
                    placeholder: "-- Pilih Gudang --",
                    dropdownParent: $(context)
                });
                $(context).find('.select2-item').select2({
                    placeholder: "-- Pilih Barang --",
                    dropdownParent: $(context)
                });
            }

            function applyNumberMask(element) {
                IMask(element, {
                    mask: Number,
                    min: 0,
                    max: 100000000,
                    thousandsSeparator: '.'
                });
            }

            // ── Toggle Tambah / Kurangi ──────────────────────────────────
            function syncToggleUI(itemContent) {
                var checked = itemContent.find('.radio-aksi:checked').val();
                var labelT = itemContent.find('.toggle-label-tambah');
                var labelK = itemContent.find('.toggle-label-kurangi');
                if (checked === 'tambah') {
                    labelT.addClass('active');
                    labelK.removeClass('active');
                } else if (checked === 'kurangi') {
                    labelK.addClass('active');
                    labelT.removeClass('active');
                }
            }

            function fixInputValues(context) {
                // Repeater clears input values, restore them for checkboxes/radios using data-value
                $(context).find('input[data-value]').each(function() {
                    $(this).val($(this).attr('data-value'));
                });
            }

            // Event handler for toggling (delegated)
            $(document).on('change', '.radio-aksi', function() {
                syncToggleUI($(this).closest('.item-content'));
            });

            // First row init
            fixInputValues(document);
            initSelect2('.items');
            // applyNumberMask(document.querySelector('.input-price'));
            $('.item-content').each(function() {
                syncToggleUI($(this));
            });

            // ── After repeater adds a new row ────────────────────────────
            $(".repeater-add-btn").on('click', function() {
                setTimeout(function() {
                    var newItem = $('.item-content').last();
                    fixInputValues(newItem);
                    initSelect2(newItem.parent());
                    newItem.find('.input-price').each(function() {
                        applyNumberMask(this);
                    });

                    // Reset toggle for new row
                    newItem.find('.radio-aksi[value="tambah"]').prop('checked', true);
                    syncToggleUI(newItem);
                }, 300);
            });

        });
    </script>
@endpush
