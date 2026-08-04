@extends('layouts.master')

@section('title')
    Edit Retur Stok
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Log
        @endslot
        @slot('title')
            Edit Retur Stok
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex flex-wrap justify-content-between">
                        <h4 class="card-title mb-4">Edit Form Retur Stok [{{ $Retur->no_referensi }}]</h4>
                        <div class="button-group">
                            <a href="{{ route('stok.transaksi') }}" class="btn btn-sm btn-warning"><i
                                    class='bx bx-arrow-back'></i> Kembali</a>
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

                    <div class="alert alert-info">
                        <strong>Petunjuk:</strong> Ubah jumlah barang retur sesuai kebutuhan. Untuk <strong>menghapus retur suatu barang</strong>, ubah nilainya menjadi 0 atau tekan tombol <strong>"Batal Retur Item"</strong>.
                    </div>

                    <form action="{{ route('stok.retur.update', $Retur->no_referensi) }}" method="post">
                        @csrf
                        <div class="mb-2 col-lg-5">
                            <label class="form-label">No Referensi Retur</label>
                            <input class="form-control" type="text" name="norefv" value="{{ $Retur->no_referensi }}"
                                disabled required>
                            <input class="form-control" type="hidden" name="noref" value="{{ $Retur->no_referensi }}"
                                required>
                        </div>
                        
                        <div class="mb-2 col-lg-5">
                            <label class="form-label">Referensi Stok Keluar</label>
                            <input class="form-control" type="text" value="{{ $StokOut->no_referensi }}"
                                disabled required>
                        </div>

                        <hr class="border-primary border-2 mt-4">

                        <div class="mb-2">
                            <label class="form-label">Update Jumlah Barang Retur</label>

                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Kondisi</th>
                                        <th>Total Stok Dibawa</th>
                                        <th style="width:22%">Jumlah Stok Retur</th>
                                        <th>Gudang</th>
                                        <th style="width:15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stockLogs as $key => $item)
                                        @php
                                            $itemKey = $item->id_barang . '-' . $item->is_new . '-' . $item->id_gudang;
                                            $currentReturQty = isset($returLogs[$itemKey]) ? abs($returLogs[$itemKey]->qty) : '';
                                        @endphp
                                        <tr class="row-{{ $item->id_barang }}-{{ $item->is_new }}">
                                            <input type="hidden" name="barang[{{ $key }}][item]"
                                                value="{{ $item->id_barang }}">
                                            @if(!$item->is_new)
                                            <input type="hidden" name="barang[{{ $key }}][bekas]"
                                                value="1">
                                            @endif
                                            <input type="hidden" name="barang[{{ $key }}][gudang]"
                                                value="{{ $item->id_gudang }}">

                                            <td>{{ $item->barang->nama }}</td>
                                            <td>
                                                @if($item->is_new)
                                                    <span class="badge bg-primary">Baru</span>
                                                @else
                                                    <span class="badge bg-warning">Bekas</span>
                                                @endif
                                            </td>
                                            <td class="fw-bold">{{ abs($item->sumqty) }}</td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" class="form-control qty-terpakai"
                                                        id="qty_retur_{{ $key }}"
                                                        name="barang[{{ $key }}][qty_retur]" min="0" max="{{ abs($item->sumqty) }}"
                                                        value="{{ $currentReturQty }}" placeholder="0">
                                                    <span class="input-group-text">{{ $item->barang->satuan ?? 'pcs' }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $item->gudang->nama }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="document.getElementById('qty_retur_{{ $key }}').value = 0;" title="Batal / Hapus retur barang ini">
                                                    <i class="bx bx-trash me-1"></i> Batal Retur Item
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <hr>

                            <div class="btn-submit mt-5 d-flex justify-content-end">
                                <button class="btn btn-md btn-primary"><i class="bx bx-save me-1"></i> Simpan Perubahan Retur</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
