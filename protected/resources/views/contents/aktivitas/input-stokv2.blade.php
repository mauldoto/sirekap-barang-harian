@extends('layouts.master')

@section('title')
    Aktivitas
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Log
        @endslot
        @slot('title')
            Input Stok Keluar
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex flex-wrap justify-content-between">
                        <h4 class="card-title mb-4">Form Pengajuan Stok Keluar Tiket [{{ $aktivitas->no_referensi }}]</h4>
                        <div class="button-group">
                            <a href="{{ route('aktivitas.index') }}" class="btn btn-sm btn-warning"><i
                                    class='bx bx-arrow-back'></i> Kembali</a>
                        </div>
                    </div>

                    <form action="{{ route('aktivitas.inputstokout.post', $aktivitas->no_referensi) }}" method="post">
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
                                value="{{ $aktivitas->tanggal_berangkat }}" disabled required>
                        </div>

                        <div class="mb-2 col-lg-5">
                            <label class="form-label">Aktivitas/Job</label>
                            {{-- <input class="form-control" type="date" name="tanggal" placeholder="Masukkan tanggal"
                                required> --}}
                            <input class="form-control" name="aktivitas" placeholder=""
                                value="{{ '[' . $aktivitas->no_referensi . '] ' . $aktivitas->lokasi->nama . ' - ' . $aktivitas->sublokasi->nama }}"
                                disabled required>
                        </div>

                        <hr class="border-primary border-2">

                        <div class="mb-2">
                            <label class="form-label">Input Jumlah Barang Berdasarkan Pengajuan Stok Keluar</label>

                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Kondisi</th>
                                        <th>Jumlah Stok Dibawa</th>
                                        <th style="width:15%">Jumlah Stok Terpakai</th>
                                        <th>Gudang</th>
                                        <!-- <th></th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengajuanStok as $key => $item)
                                        <tr class="row-{{ $item->id_barang }}-{{ $item->is_new }}">
                                            <input type="hidden" name="input[{{ $key }}][barang]"
                                                value="{{ $item->id_barang }}">
                                            <input type="hidden" name="input[{{ $key }}][kondisi]"
                                                value="{{ $item->is_new }}">
                                            <input type="hidden" name="input[{{ $key }}][qty]"
                                                value="{{ $item->sumqty }}">
                                            <input type="hidden" name="input[{{ $key }}][qty_used]"
                                                value="{{ $item->sumqty_used }}">
                                            <input type="hidden" name="input[{{ $key }}][gudang]"
                                                value="{{ $item->id_gudang }}">

                                            <td>{{ $item->barang->nama }}</td>
                                            <td>{{ $item->is_new ? 'Baru' : 'Bekas' }}</td>
                                            <td>{{ $item->sumqty }}</td>
                                            <td>
                                                <input type="number" class="col-2 form-control qty-terpakai"
                                                    name="input[{{ $key }}][qty_used]" min="0"
                                                    id="" value="{{ $item->sumqty_used }}">
                                            </td>
                                            <td>{{ $item->gudang->nama }}</td>
                                            <!-- <td>
                                                                                                    <button type="button" class="btn btn-sm btn-danger dlt-data"
                                                                                                        data-key="{{ $item->id_barang }}-{{ $item->is_new }}">Remove</button>
                                                                                                </td> -->
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <hr>

                            <label class="form-label">Input Barang Tambahan <small class="text-warning"> (Barang yang tidak
                                    ada di pengajuan)</small></label>

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
                                            <div class="col-lg-3">
                                                {{-- <input type="text" class="form-control" id="inputName"
                                                    placeholder="Name" data-name="name"> --}}
                                                <select class="form-control gudang-select2" id="inputWs"
                                                    data-name="gudang">
                                                    <option value=""></option>
                                                    @foreach ($gudang as $ws)
                                                        <option value="{{ $ws->id }}">{{ $ws->nama }}
                                                            ({{ $ws->kode }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                {{-- <input type="text" class="form-control" id="inputName"
                                                    placeholder="Name" data-name="name"> --}}
                                                <select class="form-control select2 barang-select2" id="inputItem"
                                                    data-name="item" disabled>
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                            <div class="col-lg-1 pt-2">
                                                <input class="form-check-input" type="checkbox" data-name="bekas"
                                                    id="inputCondition" value="bekas">
                                                <label class="form-check-label" for="inputCondition">
                                                    Bekas
                                                </label>
                                            </div>
                                            <div class="col-lg-2">
                                                <input type="text" class="form-control" id="inputQty" placeholder="Qty"
                                                    data-name="qty">
                                            </div>
                                            <div class="col-lg-1 repeater-remove-btn">
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
                                <button class="btn btn-md btn-primary">Simpan dan Ajukan Pengeluaran Barang</button>
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
                regenerateS2()
            })

            $('.gudang-select2').select2({
                placeholder: "-- Pilih Gudang --",
            });

            $('.barang-select2').select2({
                placeholder: "-- Pilih Barang --",
                templateResult: formatOption
            });

            $('.gudang-select2').on('select2:select', function(e) {
                showLoadingScreen()
                let select2this = $(this).parent().parent().find(".barang-select2")
                getData(e.params.data.id, select2this)
            })

            async function getData(idGudang, s2element) {
                const metaTag = document.querySelector(`meta[name="baseURL"]`);
                const url = metaTag.content;
                s2element.attr('disabled', true)
                s2element.html('')

                try {
                    const response = await fetch(url + '/stok/gudang/' + idGudang);

                    if (!response.ok) {
                        throw new Error(`Gagal mengambil data, status: ${response.status}`);
                    }

                    const result = await response.json();
                    let newOption = `<option></option>`
                    for (const item of result.data) {
                        newOption +=
                            `<option value="${item.id}" title="Baru: ${item.new ? item.new : 0} | Bekas: ${item.second ? item.second : 0}">${item.nama} (${item.kode}) - ${item.satuan} </option>`
                    }

                    s2element.html(newOption)

                    s2element.select2({
                        placeholder: "-- Pilih Barang --",
                        templateResult: formatOption
                    });

                    s2element.attr('disabled', false)
                    hideLoadingScreen()
                } catch (error) {
                    // Tangani kesalahan jaringan ATAU kesalahan HTTP/konversi data
                    console.error('Proses Fetch Gagal:', error);
                    hideLoadingScreen()
                }
            }

            function formatOption(option) {
                var $option = $('<div>' + option.text + '</div><small> ' + option.title + ' </small>');
                return $option;
            };

            $('.job-select2').select2({
                placeholder: "-- Pilih Aktivitas/Job --"
            });

            function regenerateS2() {
                let select2Gudang = $('.gudang-select2')
                select2Gudang.each(function(index, el) {
                    $(el).select2({
                        placeholder: "-- Pilih Gudang --"
                    });
                })

                let select2Arr = $('.barang-select2')
                select2Arr.each(function(index, el) {
                    $(el).select2({
                        placeholder: "-- Pilih Barang --"
                    });
                })

                select2Gudang.on('select2:select', function(e) {
                    showLoadingScreen()
                    let select2this = $(this).parent().parent().find(".barang-select2")
                    console.log(select2this)

                    getData(e.params.data.id, select2this)
                })
            }

            $(".dlt-data").on('click', function() {
                const keyy = $(this).data('key')
                console.log(keyy)
                $('.row-' + keyy).remove()
            })

            $("#datatable-stok").on("click", ".delete-btn", function() {
                const url = $(this).data("url");
                const form = $(".form-delete").attr("action", url);

                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Data akan dialihkan ke folder sampah!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batalkan",
                }).then((result) => {
                    if (result.isConfirmed) {
                        form[0].submit();
                    }
                });
            });

        })
    </script>
@endpush
