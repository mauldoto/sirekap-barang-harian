@extends('layouts.master')

@section('title') Aktivitas @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Log @endslot
@slot('title') Aktivitas @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap justify-content-between">
                    <h4 class="card-title mb-4">Log Aktivitas/Job</h4>
                    <div class="button-group">
                        <a href="{{route('aktivitas.input')}}" class="btn btn-sm btn-success"><i class='bx bx-archive-in'></i> Input Aktivitas</a>
                    </div>
                </div>

                <table id="datatable-aktivitas" class="table table-bordered dt-responsive w-100 dataTable no-footer dtr-inline" aria-describedby="datatable_info" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                            <th>Sub Lokasi</th>
                            <th>Teknisi</th>
                        </tr>
                    </thead>


                    <tbody>
                        {{-- @foreach($aktivitas as $key => $i)
                        <tr>
                            <td>{{ $i->barang->kode }}</td>
                            <td>{{ $i->barang->nama }}</td>
                            <td>{{ $i->sumqty }} ({{$i->barang->satuan}})</td>
                        </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
<link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
@endsection

@section('script')
<!-- datatables -->
<script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        $("#datatable-aktivitas").dataTable();

        function getDetail(ids) {
            $.get('aktivitas/' + ids + '/detail').done(function(response) {
                let res = response
                if (!res.status) return

                $('.edit-nama').val(res.data.nama)
                $('.edit-deskripsi').val(res.data.deskripsi)

                setTimeout(() => {
                    showModal();
                }, 500);
            })
        }

        function showModal() {
            const myModal = new bootstrap.Modal('#modalaktivitas', {
                show: true
            })
            myModal.show()
        }

        $('#datatable-aktivitas').on('click', '.edit-btn', function() {
            getDetail($(this).data('id'))
            $('.modal-form').attr('action', $(this).data('url'))
        })

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
