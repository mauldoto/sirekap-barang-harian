@extends('layouts.master')

@section('title')
    Invoice Stok
@endsection

@section('content')
    @component('components.breadcrumb')
    @slot('li_1')
    Invoice
    @endslot
    @slot('title')
    Stok
    @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card invoice-card">
                <div class="card-body p-4">

                    {{-- ====== HEADER ====== --}}
                    <div class="invoice-header">
                        <div class="row align-items-start">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ URL::asset('assets/images/logo-jpn.png') }}" alt="Logo"
                                        class="invoice-logo me-3">
                                    <div>
                                        <h2 class="invoice-company-name mb-0">SIREKAP JPN</h2>
                                        <p class="text-muted mb-0 font-size-13">
                                            <i class='bx bx-archive-in me-1'></i>
                                            Invoice Stok {{ ucfirst($stok->type) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="invoice-meta">
                                    <span
                                        class="invoice-badge {{ $stok->type == 'masuk' ? 'badge-masuk' : 'badge-keluar' }}">
                                        {{ strtoupper($stok->type) }}
                                    </span>
                                    <table class="invoice-meta-table ms-auto mt-2">
                                        <tr>
                                            <td class="text-muted pe-2">No. Invoice:</td>
                                            <td class="fw-bold">{{ $stok->no_referensi }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted pe-2">Tanggal:</td>
                                            <td class="fw-bold">{{ \Carbon\Carbon::parse($stok->tanggal)->format('d M Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted pe-2">Diinput:</td>
                                            <td class="fw-bold">{{ $stok->user->username ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="invoice-divider">

                    {{-- ====== INFO BOXES ====== --}}
                    <div class="row mb-4">
                        @if($stok->type == 'keluar' && $stok->aktivitas)
                            <div class="col-md-6 mb-3">
                                <div class="invoice-info-box">
                                    <div class="info-box-label">AKTIVITAS TERKAIT</div>
                                    <h5 class="fw-bold mb-1">{{ $stok->aktivitas->no_referensi }}</h5>
                                    <p class="mb-0 text-muted font-size-13">
                                        {{ $stok->aktivitas->lokasi->nama ?? '-' }} —
                                        {{ $stok->aktivitas->sublokasi->nama ?? '-' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="invoice-info-box info-box-alt">
                                    <div class="info-box-label">LOKASI TUJUAN</div>
                                    <h5 class="fw-bold mb-1">{{ $stok->aktivitas->lokasi->nama ?? '-' }}</h5>
                                    <p class="mb-0 text-muted font-size-13">
                                        {{ $stok->aktivitas->sublokasi->nama ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="col-md-6 mb-3">
                                <div class="invoice-info-box">
                                    <div class="info-box-label">KETERANGAN</div>
                                    <p class="mb-0 mt-1">{{ $stok->deskripsi ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="invoice-info-box info-box-alt">
                                    <div class="info-box-label">GUDANG</div>
                                    @php
                                        $gudangNames = $items->pluck('gudang.nama')->unique()->filter()->implode(', ');
                                    @endphp
                                    <h5 class="fw-bold mb-1">{{ $gudangNames ?: '-' }}</h5>
                                    <p class="mb-0 text-muted font-size-13">Tujuan penyimpanan</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- ====== TABEL BARANG ====== --}}
                    <div class="table-responsive invoice-table-wrapper">
                        <table class="table invoice-table mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 45px;">NO</th>
                                    <th>SKU</th>
                                    <th>NAMA BARANG</th>
                                    <th class="text-center">KONDISI</th>
                                    <th class="text-end">QTY</th>
                                    <th class="text-end">HARGA SATUAN</th>
                                    <th class="text-end">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $subtotal = 0; @endphp
                                @foreach ($items as $key => $item)
                                    @php
                                        $harga = $item->is_new ? ($item->barang->h_new ?? 0) : ($item->barang->h_second ?? 0);
                                        $qty = abs($item->qty);
                                        $total = $harga * $qty;
                                        $subtotal += $total;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>
                                            <span class="fw-medium {{ $harga == 0 ? 'text-success' : '' }}">
                                                {{ $item->barang->kode ?? '-' }}
                                                @if($harga == 0)
                                                    (Bonus)
                                                @endif
                                            </span>
                                        </td>
                                        <td class="{{ $harga == 0 ? 'text-success' : '' }}">
                                            {{ $item->barang->nama }}
                                        </td>
                                        <td class="text-center">
                                            <span class="kondisi-badge {{ $item->is_new ? 'kondisi-baru' : 'kondisi-bekas' }}">
                                                {{ $item->is_new ? 'Baru' : 'Bekas' }}
                                            </span>
                                        </td>
                                        <td class="text-end {{ $stok->type == 'keluar' ? 'text-danger fw-bold' : '' }}">
                                            {{ $stok->type == 'keluar' ? '-' : '' }}{{ $qty }}
                                            <small class="text-muted">{{ $item->barang->satuan }}</small>
                                        </td>
                                        <td class="text-end">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold {{ $harga == 0 ? 'text-success' : '' }}">
                                            Rp {{ number_format($total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- ====== FOOTER: CATATAN + TOTAL ====== --}}
                    <div class="row mt-4">
                        {{-- Catatan --}}
                        <div class="col-md-6 mb-3">
                            @if($stok->deskripsi && $stok->type == 'keluar')
                                <div class="invoice-catatan">
                                    <div class="catatan-label">CATATAN</div>
                                    <p class="mb-0">{{ $stok->deskripsi }}</p>
                                </div>
                            @endif

                            <!-- {{-- Tanda Tangan --}}
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <p class="sign-label">{{ $stok->type == 'masuk' ? 'PENERIMA (GUDANG)' : 'DISETUJUI OLEH' }}</p>
                                        <div class="sign-line"></div>
                                        <p class="sign-name">{{ $stok->user->username ?? '.....................' }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="sign-label">{{ $stok->type == 'masuk' ? 'PENGIRIM' : 'PENERIMA' }}</p>
                                        <div class="sign-line"></div>
                                        <p class="sign-name">.......................</p>
                                    </div>
                                </div> -->
                        </div>

                        {{-- Subtotal & Total --}}
                        <div class="col-md-6">
                            <div class="invoice-summary">
                                <table class="summary-table">
                                    <tr>
                                        <td class="text-muted">Subtotal</td>
                                        <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="summary-total-row">
                                        <td>
                                            <span class="total-label">Total</span>
                                        </td>
                                        <td class="text-end">
                                            <span
                                                class="total-value {{ $stok->type == 'masuk' ? 'text-success-dark' : 'text-danger' }}">
                                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('stok.log') }}" class="btn btn-outline-secondary">
                                    <i class='bx bx-arrow-back me-1'></i> Kembali
                                </a>
                                <a href="{{ route('stok.invoice.print', $stok->no_referensi) }}" target="_blank"
                                    class="btn btn-invoice-print">
                                    <i class='bx bx-printer me-1'></i> Cetak
                                    {{ $stok->type == 'masuk' ? 'Bukti Masuk' : 'Invoice' }}
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        /* ===== Invoice Card ===== */
        .invoice-card {
            border: none;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
        }

        .invoice-logo {
            height: 48px;
            width: auto;
        }

        .invoice-company-name {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1a3353;
            letter-spacing: -0.5px;
        }

        /* ===== Badge ===== */
        .invoice-badge {
            display: inline-block;
            padding: 5px 18px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .badge-masuk {
            background-color: #e6f7ee;
            color: #0d8a4a;
            border: 1.5px solid #b8e6cc;
        }

        .badge-keluar {
            background-color: #fde8e8;
            color: #c53030;
            border: 1.5px solid #f5b4b4;
        }

        /* ===== Meta Table ===== */
        .invoice-meta-table {
            border-collapse: separate;
            border-spacing: 0 2px;
        }

        .invoice-meta-table td {
            font-size: 13px;
            padding: 1px 0;
        }

        .invoice-divider {
            border-color: #e8ecf1;
            margin: 1rem 0;
        }

        /* ===== Info Boxes ===== */
        .invoice-info-box {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px 20px;
            height: 100%;
            border-left: 4px solid #1a3353;
        }

        .invoice-info-box.info-box-alt {
            border-left-color: #3b82f6;
        }

        .info-box-label {
            font-size: 11px;
            font-weight: 700;
            color: #8492a6;
            letter-spacing: 1.2px;
            margin-bottom: 6px;
        }

        /* ===== Table ===== */
        .invoice-table-wrapper {
            border: 1px solid #e8ecf1;
            border-radius: 8px;
            overflow: hidden;
        }

        .invoice-table thead {
            background-color: #f1f5f9;
        }

        .invoice-table thead th {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 12px 16px;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        .invoice-table tbody td {
            padding: 12px 16px;
            font-size: 13.5px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .invoice-table tbody tr:last-child td {
            border-bottom: none;
        }

        .invoice-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .kondisi-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 12px;
            font-size: 11.5px;
            font-weight: 600;
        }

        .kondisi-baru {
            background-color: #e0edff;
            color: #1d4ed8;
        }

        .kondisi-bekas {
            background-color: #fef3c7;
            color: #92400e;
        }

        /* ===== Catatan ===== */
        .invoice-catatan {
            border: 1.5px solid #e2e8f0;
            border-left: 4px solid #f59e0b;
            border-radius: 8px;
            padding: 14px 18px;
            background-color: #fffbeb;
        }

        .catatan-label {
            font-size: 11px;
            font-weight: 700;
            color: #92400e;
            letter-spacing: 1.2px;
            margin-bottom: 6px;
        }

        /* ===== Signature ===== */
        .sign-label {
            font-size: 11px;
            font-weight: 700;
            color: #8492a6;
            letter-spacing: 1px;
            margin-bottom: 0;
        }

        .sign-line {
            border-bottom: 1.5px solid #cbd5e1;
            margin: 40px 0 8px 0;
            width: 80%;
        }

        .sign-name {
            font-size: 13px;
            color: #475569;
        }

        /* ===== Summary ===== */
        .invoice-summary {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px 20px;
            background: #f8fafc;
        }

        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 4px;
        }

        .summary-table td {
            padding: 4px 0;
            font-size: 14px;
        }

        .summary-total-row td {
            padding-top: 12px;
            border-top: 1.5px solid #e2e8f0;
        }

        .total-label {
            font-size: 16px;
            font-weight: 700;
            color: #1a3353;
        }

        .total-value {
            font-size: 22px;
            font-weight: 800;
        }

        .text-success-dark {
            color: #0d8a4a !important;
        }

        /* ===== Print Button ===== */
        .btn-invoice-print {
            background-color: #1a3353;
            color: white;
            border: none;
            padding: 8px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-invoice-print:hover {
            background-color: #0f2440;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26, 51, 83, 0.3);
        }

        /* ===== Print Media ===== */
        @media print {

            .btn-invoice-print,
            .btn-outline-secondary,
            .page-title-box,
            #layout-wrapper>.header,
            .vertical-menu,
            .footer {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
            }

            .invoice-card {
                box-shadow: none !important;
            }
        }
    </style>
@endsection