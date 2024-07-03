@extends('layouts.master')

@section('title') Stok @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Log @endslot
@slot('title') Stok @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap justify-content-between">
                    <h4 class="card-title mb-4">Log Stok Masuk/Keluar</h4>
                    <div class="button-group">
                        <a href="{{route('stok.index')}}" class="btn btn-sm btn-warning"><i class='bx bx-arrow-back'></i> Kembali</a>
                    </div>
                </div>

                <table id="datatable-logstok" class="table table-bordered dt-responsive w-100 dataTable no-footer dtr-inline" aria-describedby="datatable_info" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>No Ref</th>
                            <th>Jenis Stok</th>
                            <th>Aktivitas</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach($stok as $key => $i)
                        <tr>
                            <td>{{ $i->stok->tanggal }}</td>
                            <td>{{ $i->stok->no_referensi }}</td>
                            <td class="text-white"><span class="rounded p-1 {{ $i->stok->type == 'masuk' ? 'bg-success' : 'bg-danger'}}">{{ $i->stok->type }}</span></td>
                            <td>
                                @if ($i->stok->aktivitas)
                                - {{$i->stok->aktivitas->no_referensi}} </br>
                                - {{$i->stok->aktivitas->lokasi->nama}} </br>
                                - {{$i->stok->aktivitas->sublokasi->nama}}
                                @endif
                                
                            </td>
                            <td>{{ $i->barang->nama }}</td>
                            <td><span class="{{$i->qty < 0 ? 'text-danger' : 'text-success'}}">{{ $i->qty < 0 ? -1 * $i->qty : $i->qty }}</span> ({{$i->barang->satuan}})</td>
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
        $("#datatable-logstok").dataTable();

        $("#datatable-logstok").on("click", ".delete-btn", function() {
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
