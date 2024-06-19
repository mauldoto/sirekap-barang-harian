@extends('layouts.master')

@section('title') Dashboard @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Dashboard @endslot
@slot('title') Dashboard @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">{{ __('app.table.dashboard.saying') }}</h4>
                <div class="">
                    <div class="alert alert-primary" role="alert">
                        {{ __('app.table.dashboard.instruction') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

@endsection

@section('css')
<link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
@endsection

@section('script')
<!-- datatables -->
<script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        $("#datatable-siswa").dataTable();

        function getDetail(ids) {
            $.get('siswa/' + ids + '/detail').done(function(response) {
                let res = response
                if (!res.status) return

                $('.edit-nama-depan').val(res.data.detail.firstname)
                $('.edit-nama-belakang').val(res.data.detail.lastname)
                $('.edit-username').val(res.data.username)
                $('.edit-email').val(res.data.email)

                setTimeout(() => {
                    showModal();
                }, 300);
            })
        }

        function showModal() {
            const myModal = new bootstrap.Modal('#modalTahunAjaran', {
                show: true
            })
            myModal.show()
        }

        $('#datatable-siswa').on('click', '.edit-btn', function() {
            getDetail($(this).data('id'))
            $('.modal-form').attr('action', $(this).data('url'))
        })
    })

</script>
@endpush
