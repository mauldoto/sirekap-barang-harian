<div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <div class="d-sm-flex flex-wrap justify-content-between">
                <h4 class="card-title mb-4">Alokasi Perangkat [{{$selectedSublokasi->nama}}]</h4>
                {{-- <div class="button-group">
                    <a href="{{route('aktivitas.index')}}" class="btn btn-sm btn-warning"><i class='bx bx-arrow-back'></i> Kembali</a>
            </div> --}}
        </div>

        <form action="{{route('alokasi.process')}}" method="post">
            @csrf
            <input type="hidden" name="sublokasi" value="{{$sublokasi}}">
            <div class="mb-2">
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
                                <div class="col-lg-5 pt-2">
                                    {{-- <input type="text" class="form-control" id="inputName" placeholder="Name" data-name="name"> --}}
                                    <select class="form-control select2 barang-select2" id="inputItem" data-name="item">
                                        <option value=""></option>
                                        @foreach ($devices as $device)
                                        <option value="{{$device->id}}">
                                            {{$device->nama}} ({{$device->kode}}) - {{$device->satuan}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 pt-2">
                                    <input type="text" class="form-control" id="inputQty" placeholder="Qty" data-name="qty">
                                </div>
                                <div class="col-lg-2 repeater-remove-btn pt-2">
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

                <hr>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alokasiDevice as $key => $item)
                        <tr class="row-{{$item->id_sublokasi}}-{{$item->id_barang}}">
                            <input type="hidden" name="input[{{$key}}][barang]" value="{{$item->id_barang}}">

                            <td>{{$item->barang->nama}}</td>
                            <td>{{$item->sumqty}}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger dlt-data" data-key="{{$item->id_sublokasi}}-{{$item->id_barang}}">Remove</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="btn-submit mt-5 d-flex justify-content-end">
                    <button class="btn btn-md btn-primary">Submit</button>
                </div>
        </form>
    </div>
</div>
</div>
