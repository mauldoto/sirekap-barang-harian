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
            Stok
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex flex-wrap justify-content-between">
                        <h4 class="card-title mb-4">Daftar Stok Masuk/Keluar</h4>
                        <div class="button-group">
                            <a href="{{ route('stok.index') }}" class="btn btn-sm btn-warning"><i class='bx bx-arrow-back'></i>
                                Kembali</a>
                        </div>
                    </div>

                    <hr>

                    <form action="" method="GET">
                        <div class="row">
                            <div class="mb-2 col-lg-2">
                                <label class="form-label">Dari</label>
                                <input class="form-control" type="date" name="dari" placeholder="Masukkan tanggal"
                                    value="{{ $startDate }}" required>
                            </div>
                            <div class="mb-2 col-lg-2">
                                <label class="form-label">Sampai</label>
                                <input class="form-control" type="date" name="ke" placeholder="Masukkan tanggal"
                                    value="{{ $endDate }}" required>
                            </div>
                            <div class="mb-2 col-lg-2">
                                <label class="form-label">Jenis</label>
                                <select class="form-control" name="filter_type" id="">
                                    <option value=""> -- filter jenis -- </option>
                                    <option value="masuk" {{ $type == 'masuk' ? 'selected' : '' }}> Masuk </option>
                                    <option value="keluar" {{ $type == 'keluar' ? 'selected' : '' }}> Keluar </option>
                                    <option value="koreksi" {{ $type == 'koreksi' ? 'selected' : '' }}> Koreksi </option>
                                    <option value="retur" {{ $type == 'retur' ? 'selected' : '' }}> Retur </option>
                                </select>
                            </div>
                            <div class="mb-2 col-lg-2 d-flex align-items-end">
                                <button class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>


                    <hr>

                    <table id="datatable-logstok"
                        class="table table-bordered dt-responsive w-100 dataTable no-footer dtr-inline"
                        aria-describedby="datatable_info" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No Ref</th>
                                <th>Jenis Stok</th>
                                <th>Aktivitas</th>
                                <th>Diinput Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($stok as $key => $i)
                                <tr>
                                    <td>{{ $i->tanggal }}</td>
                                    <td>
                                        @if (in_array($i->type, ['masuk', 'keluar', 'retur']))
                                            <a href="{{ route('stok.invoice', $i->no_referensi) }}"
                                                class="text-primary fw-bold">
                                                {{ $i->no_referensi }}
                                            </a>
                                        @else
                                            {{ $i->no_referensi }}
                                        @endif
                                    </td>
                                    <td class="text-white"><span
                                            class="rounded p-1 {{ $i->type == 'masuk' ? 'bg-success' : ($i->type == 'koreksi' ? 'bg-info' : ($i->type == 'retur' ? 'bg-secondary' : 'bg-danger')) }}">{{ $i->type }}</span>
                                    </td>
                                    <td>
                                        @if ($i->aktivitas)
                                            - {{ $i->aktivitas->no_referensi }} </br>
                                            - {{ $i->aktivitas->lokasi->nama }} </br>
                                            - {{ $i->aktivitas->sublokasi->nama }}
                                        @endif

                                    </td>
                                    <td>{{ $i->user->username }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info dropdown-toggle btn-sm"
                                                data-bs-toggle="dropdown" aria-expanded="false">Aksi <i
                                                    class="mdi mdi-chevron-down"></i></button>
                                            <div class="dropdown-menu" style="">
                                                <a class="dropdown-item detail-btn d-flex align-items-center"
                                                    href="{{ route('stok.transaksi.detail', $i->no_referensi) }}"
                                                    data-url="" data-id="{{ $i->id }}"><i
                                                        class='bx bx-search-alt-2 me-1'></i>
                                                    Detail</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ route('aktivitas.print.tiket', $i->no_referensi) }}"
                                                    target="_blank" data-url=""><i class='bx bxs-discount me-1'></i>
                                                    Print Nota</a>
                                                @if (auth()->user()->username == 'superadmin' || !in_array($i->status, ['done', 'cancel']))
                                                    {{-- <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item d-flex align-items-center"
                                                        href="{{ route('aktivitas.edit', $i->no_referensi) }}"
                                                        data-url=""><i class='bx bxs-edit me-1'></i> Edit Tiket</a> --}}

                                                    @if (
                                                        $i->type == 'keluar' &&
                                                            (auth()->user()->username == 'superadmin' ||
                                                                !in_array($i->status, ['stock_out_verification', 'stock_out_verified'])))
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="{{ $i->has_retur ? route('stok.retur.edit', $i->retur->no_referensi) : route('stok.retur.view', $i->no_referensi) }}"
                                                            data-url=""><i class='bx bx-rotate-left me-1'></i>
                                                            {{ $i->has_retur ? 'Edit Retur' : 'Input Retur' }}</a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
        .edit-btn {
            cursor: pointer;
        }

        .edit-btn:hover {
            color: orange;
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
            let selectBarang = $('.select2').select2({
                placeholder: "-- Pilih Barang --"
            });

            $("#datatable-logstok").dataTable();

            $('#datatable-logstok').on('click', '.edit-btn', function() {
                let data = $(this)
                selectBarang.val(data.data('item')).trigger('change');
                $('.edit-qty').val(data.data('qty'))
                $('.edit-stok').val(data.data('stok'))
                $('.edit-item').val(data.data('item'))

                if (data.data('new') == 'Bekas') {
                    $('.edit-new').attr('checked', true)
                } else {
                    $('.edit-new').attr('checked', false)
                }

                setTimeout(() => {
                    showModal();
                }, 500);
            })

            function showModal() {
                const myModal = new bootstrap.Modal('#modalEditStok', {
                    show: true
                })
                myModal.show()
            }

            $(".update-btn").on("click", function() {
                const form = $("#updateForm");

                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Mohon periksa dengan teliti data yang akan anda update!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Update!",
                    cancelButtonText: "Batalkan",
                }).then((result) => {
                    if (result.isConfirmed) {
                        form[0].submit();
                    }
                });
            });

            $(".delete-btn").on("click", function() {
                $('.delete-stok').val($('.edit-stok').val())
                $('.delete-item').val($('.edit-item').val())
                const form = $("#deleteForm");

                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Mohon periksa dengan teliti data yang akan anda HAPUS!",
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
