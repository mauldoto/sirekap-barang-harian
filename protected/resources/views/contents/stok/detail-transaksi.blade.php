@extends('layouts.master')

@section('title')
    Detail Transaksi Stok
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Log
        @endslot
        @slot('title')
            Detail Transaksi Stok
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex flex-wrap justify-content-between mb-4">
                        <h4 class="card-title">Detail Transaksi: <span class="text-primary">{{ $stok->no_referensi }}</span></h4>
                        <div class="button-group">
                            <a href="{{ route('stok.transaksi') }}" class="btn btn-sm btn-warning"><i class='bx bx-arrow-back'></i> Kembali</a>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <td style="width: 150px;" class="fw-bold text-muted">No. Referensi</td>
                                        <td>: {{ $stok->no_referensi }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted">Tanggal</td>
                                        <td>: {{ \Carbon\Carbon::parse($stok->tanggal)->format('d F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted">Jenis Transaksi</td>
                                        <td>: 
                                            <span class="badge rounded-pill {{ $stok->type == 'masuk' ? 'bg-success' : ($stok->type == 'koreksi' ? 'bg-info' : ($stok->type == 'retur' ? 'bg-secondary' : 'bg-danger')) }}">
                                                {{ ucfirst($stok->type) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted">Diinput Oleh</td>
                                        <td>: {{ $stok->user->username ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    @if($stok->aktivitas)
                                        <tr>
                                            <td style="width: 150px;" class="fw-bold text-muted">Aktivitas Terkait</td>
                                            <td>: {{ $stok->aktivitas->no_referensi }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Lokasi</td>
                                            <td>: {{ $stok->aktivitas->lokasi->nama ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Sublokasi</td>
                                            <td>: {{ $stok->aktivitas->sublokasi->nama ?? '-' }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td style="width: 150px;" class="fw-bold text-muted">Keterangan</td>
                                            <td>: {{ $stok->deskripsi ?? '-' }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <h5 class="font-size-14 mb-3"><i class="bx bx-list-ul me-1 text-primary"></i> Daftar Barang</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Gudang</th>
                                    <th class="text-center">Kondisi</th>
                                    <th class="text-center">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $item->barang->kode ?? '-' }}</td>
                                        <td>{{ $item->barang->nama ?? '-' }}</td>
                                        <td>{{ $item->gudang->nama ?? '-' }}</td>
                                        <td class="text-center">
                                            @if($item->is_new)
                                                <span class="badge bg-primary">Baru</span>
                                            @else
                                                <span class="badge bg-warning">Bekas</span>
                                            @endif
                                        </td>
                                        <td class="text-center fw-bold">
                                            @if($item->qty > 0)
                                                <span class="text-success">+{{ $item->qty }}</span>
                                            @elseif($item->qty < 0)
                                                <span class="text-danger">{{ $item->qty }}</span>
                                            @else
                                                {{ $item->qty }}
                                            @endif
                                            <span class="text-muted fw-normal font-size-12 ms-1">({{ $item->barang->satuan ?? '-' }})</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3 text-muted">Tidak ada data barang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
