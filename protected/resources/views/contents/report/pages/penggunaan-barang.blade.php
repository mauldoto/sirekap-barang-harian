<div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <div class="d-sm-flex flex-wrap justify-content-between">
                <h4 class="card-title mb-3">Report {{ucfirst(str_replace('-', ' ', $report))}}</h4>
            </div>

            <hr>

            <form action="{{route('report.process')}}" method="GET" target="_blank">
                <input type="hidden" value="{{$report}}">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="mb-2 col-lg-5">
                                <label class="form-label">Dari</label>
                                <input class="form-control" type="date" name="dari" placeholder="Masukkan tanggal" value="{{$startDate}}" required>
                            </div>
                            <div class="mb-2 col-lg-5">
                                <label class="form-label">Sampai</label>
                                <input class="form-control" type="date" name="ke" placeholder="Masukkan tanggal" value="{{$endDate}}" required>
                            </div>

                        </div>
                        <div class="row">
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
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-2 col-lg-10">
                                <label class="form-label">Pilih Barang</label>
                                <select class="form-control select2-barang" name="barang[]" id="pilihBarang" multiple>
                                    @foreach ($barang as $item)
                                    <option value="{{$item->id}}">{{$item->nama . '('. $item->kode .')'}}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger">*Kosongkan kolom jika ingin mengambil semua</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="mb-4 col-lg-10">
                                {{-- <label class="form-label">Pilih Jenis Export</label> --}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-2 col-lg-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-success col-12 d-flex align-items-center justify-content-center btn-export" data-export="excel">
                                    <i class='bx bxs-spreadsheet me-1'></i> Export Excel
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-2 col-lg-4 d-flex align-items-end">
                                <button type="button" class="btn btn-danger col-12 d-flex align-items-center justify-content-center btn-export" data-export="pdf">
                                    <i class='bx bxs-file-pdf me-1'></i> Export PDF
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="mb-2 col-lg-2 d-flex align-items-end">
                        <button class="btn btn-primary">Filter</button>
                    </div> --}}
                </div>
            </form>
        </div>
    </div>
</div>
