@extends('layouts.master')

@section('title') Stok @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Log @endslot
@slot('title') Rencana Pengeluaran Stok @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap justify-content-between">
                    <h4 class="card-title mb-4">Cetak Rencana Pengeluaran Stok</h4>
                    <div class="button-group">
                        <a href="{{route('stok.index')}}" class="btn btn-sm btn-warning"><i class='bx bx-arrow-back'></i> Kembali</a>
                    </div>
                </div>

                <form action="{{route('stok.rencana.cetak')}}" method="post" target="_blank">
                    @csrf
                    {{-- <div class="mb-2 col-lg-5">
                        <label class="form-label">No Referensi</label>
                        <input class="form-control" type="text" name="norefv" value="{{generateReference('SM')}}" disabled required>
                    <input class="form-control" type="hidden" name="noref" value="{{generateReference('SM')}}" required>
            </div> --}}

            <div class="mb-2 col-lg-5">
                <label class="form-label">Tanggal</label>
                <input class="form-control" type="date" name="tanggal" placeholder="Masukkan tanggal" required>
            </div>

            {{-- <div class="mb-3 col-lg-5">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" cols="30" rows="5"></textarea>
                    </div> --}}

            <div class="mb-2 col-lg-5">
                <label class="form-label">Lokasi</label>
                <select class="form-control" name="lokasi" id="lokasi">
                    <option value=""></option>
                    @foreach ($lokasi as $lok)
                    <option value="{{$lok->id}}">{{$lok->nama}}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2 col-lg-5">
                <label class="form-label">Sub Lokasi</label>
                <select class="form-control" name="sublokasi[]" id="sublokasi" multiple>
                    <option value=""></option>
                </select>
            </div>

            <div class="mb-2 col-lg-5">
                <label class="form-label">Teknisi</label>
                <select class="form-control" name="teknisi[]" id="teknisi" multiple>
                    @foreach($karyawan as $key => $p)
                    <option value="{{$p->id}}">{{$p->nama}}</option>
                    @endforeach
                </select>
            </div>


            <div class="mb-2">
                <div class="repeater-heading mb-3 mt-3">
                    <button type="button" class="btn btn-primary pull-right generate-btn">
                        Generate Form
                    </button>
                </div>
               
                <div class="review">
                     <!-- Repeater Html Start -->
                </div>

                <div class="btn-submit mt-5 d-flex justify-content-end">
                    <button class="btn btn-md btn-primary">Cetak</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="repeater-shadow" class="border rounded p-3 repeater-1 mb-2" style="display: none">
    <!-- Repeater Heading -->
    <div class="row">
        <div class="mb-2 col-lg-5">
            <label class="form-label"></label>
            <input id="selectedSublokasi" type="hidden" name="selected_sublokasi[]">
        </div>
    </div>
    <div class="repeater-heading mb-2">
        <button type="button" class="btn btn-primary pull-right repeater-add-btn">
            Add Barang
        </button>
    </div>
    <div class="clearfix"></div>
    <!-- Repeater Items -->
    <div class="items" data-group="">
        <!-- Repeater Content -->
        <div class="item-content">
            <div class="row">
                <div class="col-lg-5">
                    {{-- <input type="text" class="form-control" id="inputName" placeholder="Name" data-name="name"> --}}
                    <select class="form-control input-select2"   data-name="item" required>
                        <option value=""></option>
                        @foreach ($barang as $item)
                        <option value="{{$item->id}}" title="Baru: {{$item->new?$item->new:0}} | Bekas: {{$item->second?$item->second:0}}">
                            {{$item->nama}} ({{$item->kode}}) - {{$item->satuan}} 
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 pt-2">
                    <input class="form-check-input" type="checkbox" data-name="bekas" id="inputCondition">
                    <label class="form-check-label" for="inputCondition">
                        Bekas
                    </label>
                </div>
                <div class="col-lg-3">
                    <input type="text" class="form-control" id="inputQty" placeholder="Qty" data-name="qty" required>
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

@endsection

@section('css')
<link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/select2/css/select2.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
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

        let sublokasi

        $(".generate-btn").click(function() {
            if ($('.barang-select2')) {
                $('.barang-select2').select2('destroy');
            }

            $(".review").empty()
            
            let selectedSublokasi = $("#sublokasi").val()
           
            setTimeout(() => {
                for (const sub of selectedSublokasi) {
                    let shadow = $('#repeater-shadow').clone();
                    let data = sublokasi.find((item) => item.id == sub)
                    shadow.attr('id', 'repeater-' + sub).css('display', '')
                    shadow.find('.form-label').html(data.nama)
                    shadow.find('.items').attr('data-group', 'lokasi_'+sub)
                    shadow.find('#selectedSublokasi').val(sub);
                    shadow.find('.input-select2').addClass(['barang-select2', 'barang-select2-' + sub])
                    // shadow.find('#inputCondition').attr('data-name', 'bekas.'+sub)
                    // shadow.find('#inputQty').attr('data-name', 'qty.'+sub)

                    $(".review").append(shadow)

                    // $('.barang-select2-' + sub).select2({
                    //     placeholder: "-- Pilih Barang --",
                    //     templateResult: formatOption
                    // });

                    $("#repeater-"+sub).createRepeater({
                        showFirstItemToDefault: true
                    , });

                    $(".repeater-add-btn").click(function() {
                        $('.barang-select2').select2({
                            placeholder: "-- Pilih Barang --",
                            templateResult: formatOption
                        });
                    })
                }

                $('.barang-select2').select2({
                    placeholder: "-- Pilih Barang --",
                    templateResult: formatOption
                });
            }, 500);
        })

        function formatOption (option) {
            var $option = $('<div>' + option.text + '</div><small> '+option.title+' </small>');
            return $option;
        };

        $('#teknisi').select2({
            placeholder: "-- Pilih Teknisi --"
        });

        let selectLokasi = $('#lokasi').select2({
            'placeholder': ' -- pilih lokasi --'
        });

        let selectSubLokasi = $('#sublokasi').select2({
            'placeholder': ' -- pilih sublokasi --'
        });

        selectLokasi.on('select2:select', function() {
            selectSubLokasi.html('<option></option');
            $(".review").empty()

            getSubLokasi($(this).val())
        })

        function getSubLokasi(ids) {
            $.get(location.origin + '/aktivitas/lokasi/' + ids).done(function(response) {
                let res = response
                if (!res.status) return

                sublokasi = res.data
                for (const data of res.data) {
                    var newOption = new Option(data.nama, data.id, false, false);
                    // Append it to the select
                    selectSubLokasi.append(newOption).trigger('change');
                }
            })
        }

        $("#datatable-stok").on("click", ".delete-btn", function() {
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
