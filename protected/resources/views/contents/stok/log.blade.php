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
                        <h4 class="card-title mb-4">Log Stok Masuk/Keluar</h4>
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
                                <th>Barang</th>
                                <th>Kondisi</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($stok as $key => $i)
                                <tr>
                                    <td>{{ $i->stok->tanggal }}</td>
                                    <td>
                                        <span class="{{ $i->stok->type == 'masuk' ? 'edit-btn' : '' }}"
                                            data-stok="{{ $i->stok->id }}" data-item="{{ $i->barang->id }}"
                                            data-new="{{ $i->is_new ? 'Baru' : 'Bekas' }}"
                                            data-name="{{ $i->barang->nama }}" data-qty="{{ $i->qty }}">
                                            {{ $i->stok->no_referensi }} {{-- // value to be renderer --}}
                                        </span>
                                    </td>
                                    <td class="text-white"><span
                                            class="rounded p-1 {{ $i->stok->type == 'masuk' ? 'bg-success' : 'bg-danger' }}">{{ $i->stok->type }}</span>
                                    </td>
                                    <td>
                                        @if ($i->stok->aktivitas)
                                            - {{ $i->stok->aktivitas->no_referensi }} </br>
                                            - {{ $i->stok->aktivitas->lokasi->nama }} </br>
                                            - {{ $i->stok->aktivitas->sublokasi->nama }}
                                        @endif

                                    </td>
                                    <td>{{ $i->stok->user->username }}</td>
                                    <td>{{ $i->barang->nama }}</td>
                                    <td><span
                                            class="badge rounded-pill {{ $i->is_new ? 'bg-primary' : 'bg-warning' }}">{{ $i->is_new ? 'Baru' : 'Bekas' }}</span>
                                    </td>
                                    <td><span
                                            class="{{ $i->qty < 0 ? 'text-danger' : 'text-success' }}">{{ $i->qty < 0 ? -1 * $i->qty : $i->qty }}</span>
                                        ({{ $i->barang->satuan }})
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modalEditStok" class="modal fade" tabindex="-1" aria-labelledby="modalEditStokLabel" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditStokLabel">Edit Log Stok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" class="modal-form" action="{{ route('stok.log.update') }}" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <input type="hidden" class="edit-stok" name="stok">
                        <input type="hidden" class="edit-item" name="item">
                        <div class="mb-2">
                            <label class="form-label">Barang</label>
                            <select class="form-control select2" id="inputItem" name="new_item">
                                <option value=""></option>
                                @foreach ($barang as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }} ({{ $item->kode }}) -
                                        {{ $item->satuan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Kondisi</label>
                            <div class="form-group">
                                <input class="form-check-input edit-new" type="checkbox" name="bekas" id="inputCondition"
                                    value="bekas">
                                <label class="form-check-label" for="inputCondition">
                                    Bekas
                                </label>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control edit-qty" id="inputQty" placeholder="Qty"
                                name="qty">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect delete-btn">
                            Hapus Record
                        </button>
                        <button type="button" class="btn btn-secondary waves-effect"
                            data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary waves-effect waves-light update-btn">Simpan</button>
                    </div>
                </form>
                <form id="deleteForm" class="hidden" action="{{ route('stok.log.delete') }}" method="post">
                    @csrf
                    @method('delete')
                    <input class="delete-stok" type="hidden" name="delete_stok">
                    <input class="delete-item" type="hidden" name="delete_item">
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
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
