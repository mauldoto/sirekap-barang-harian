<!DOCTYPE html>
<html>

<head>
    <title>Nota Invoice Stok - {{ $stok->no_referensi }}</title>
    <style type="text/css">
        @page {
            size: landscape;
            margin: 8mm 10mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.3;
            padding: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .text-center {
            text-align: center;
        }

        .text-end,
        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .fw-bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        /* Dashed border utilities */
        .border-dashed-all {
            border: 1px dashed #000;
        }

        .border-dashed-top {
            border-top: 1px dashed #000;
        }

        .border-dashed-bottom {
            border-bottom: 1px dashed #000;
        }

        .border-dashed-tb {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }

        /* Header section */
        .header-title {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .header-subtitle {
            font-size: 12px;
            font-weight: bold;
            margin-top: 2px;
        }

        .meta-table td {
            padding: 2px 4px;
            font-size: 11px;
        }

        /* Info section */
        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
            font-size: 11px;
        }

        .info-label {
            font-size: 9px;
            font-weight: bold;
            color: #444;
            text-transform: uppercase;
        }

        .info-value {
            font-size: 11px;
            font-weight: bold;
        }

        /* Items table */
        .items-table {
            margin-top: 8px;
            margin-bottom: 10px;
        }

        .items-table th {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 6px 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .items-table td {
            border-bottom: 1px dashed #999;
            padding: 5px 4px;
            font-size: 10.5px;
        }

        /* Signature area */
        .sig-box {
            text-align: center;
            vertical-align: top;
            padding: 5px 10px;
        }

        .sig-line {
            border-bottom: 1px dashed #000;
            margin-top: 40px;
            margin-bottom: 4px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        /* Summary box */
        .summary-table td {
            padding: 3px 6px;
            font-size: 11px;
        }

        .summary-total {
            font-size: 13px;
            font-weight: bold;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }
    </style>
</head>

<body>

    {{-- ====== HEADER ====== --}}
    <table style="width: 100%; margin-bottom: 6px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 70px; vertical-align: top;">
                            <img style="width: 60px;" src="assets/images/logo-jpn.png" alt="JPN Logo">
                        </td>
                        <td style="vertical-align: top;">
                            <div class="header-title">SIREKAP JPN</div>
                            <div class="header-subtitle">NOTA INVOICE STOK {{ strtoupper($stok->type) }}</div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <table style="float: right;" class="meta-table">
                    <tr>
                        <td class="text-right" style="color: #444;">No. Invoice:</td>
                        <td class="text-right fw-bold">{{ $stok->no_referensi }}</td>
                    </tr>
                    <tr>
                        <td class="text-right" style="color: #444;">Tanggal:</td>
                        <td class="text-right fw-bold">{{ \Carbon\Carbon::parse($stok->tanggal)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-right" style="color: #444;">Diinput Oleh:</td>
                        <td class="text-right fw-bold">{{ $stok->user->username ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ====== DASHED DIVIDER ====== --}}
    <div style="border-top: 1px dashed #000; margin: 4px 0 8px 0;"></div>

    {{-- ====== INFO SECTION ====== --}}
    @php
        $gudangNames = $items->pluck('gudang.nama')->unique()->filter()->implode(', ');
    @endphp
    <table class="info-table border-dashed-tb" style="margin-bottom: 8px;">
        <tr>
            @if ($stok->type == 'keluar' && $stok->aktivitas)
                <td style="width: 30%; border-right: 1px dashed #ccc;">
                    <div class="info-label">Aktivitas Terkait</div>
                    <div class="info-value">{{ $stok->aktivitas->no_referensi }}</div>
                </td>
                <td style="width: 35%; border-right: 1px dashed #ccc;">
                    <div class="info-label">Lokasi / Sublokasi Tujuan</div>
                    <div class="info-value">{{ $stok->aktivitas->lokasi->nama ?? '-' }} —
                        {{ $stok->aktivitas->sublokasi->nama ?? '-' }}</div>
                </td>
                <td style="width: 35%;">
                    <div class="info-label">Gudang / Keterangan</div>
                    <div class="info-value">{{ $gudangNames ?: '-' }} @if ($stok->deskripsi)
                            ({{ $stok->deskripsi }})
                        @endif
                    </div>
                </td>
            @else
                <td style="width: 50%; border-right: 1px dashed #ccc;">
                    <div class="info-label">Gudang</div>
                    <div class="info-value">{{ $gudangNames ?: '-' }}</div>
                </td>
                <td style="width: 50%;">
                    <div class="info-label">Keterangan</div>
                    <div class="info-value">{{ $stok->deskripsi ?? '-' }}</div>
                </td>
            @endif
        </tr>
    </table>

    {{-- ====== TABEL BARANG ====== --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">No</th>
                <th style="width: 110px;" class="text-left">SKU</th>
                <th class="text-left">Nama Barang</th>
                <th style="width: 80px;" class="text-center">Kondisi</th>
                <th style="width: 70px;" class="text-right">Qty</th>
                <th style="width: 110px;" class="text-right">Harga Satuan</th>
                <th style="width: 120px;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal = 0; @endphp
            @foreach ($items as $key => $item)
                @php
                    $harga = $item->harga;
                    $qty = abs($item->qty);
                    $total = $harga * $qty;
                    $subtotal += $total;
                @endphp
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td class="text-left">
                        {{ $item->barang->kode ?? '-' }}
                        @if ($harga == 0)
                            <span style="font-size: 9.5px;">(Bonus)</span>
                        @endif
                    </td>
                    <td class="text-left">{{ $item->barang->nama }}</td>
                    <td class="text-center">{{ $item->is_new ? 'Baru' : 'Bekas' }}</td>
                    <td class="text-right">
                        {{ $stok->type == 'keluar' ? '-' : '' }}{{ $qty }}
                    </td>
                    <td class="text-right">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                    <td class="text-right fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ====== FOOTER & SUMMARY ====== --}}
    <table style="width: 100%; margin-top: 10px;">
        <tr>
            {{-- Signatures --}}
            <td style="width: 60%; vertical-align: top; padding-right: 15px;">
                <table style="width: 100%;">
                    <tr>
                        <td class="sig-box" style="width: 50%;">
                            <div class="info-label">
                                {{ $stok->type == 'masuk' ? 'Penerima (Gudang)' : 'Disetujui Oleh' }}</div>
                            <div class="sig-line"></div>
                            <div style="font-size: 11px;">{{ $stok->user->username ?? '.....................' }}</div>
                        </td>
                        <td class="sig-box" style="width: 50%;">
                            <div class="info-label">{{ $stok->type == 'masuk' ? 'Pengirim' : 'Penerima' }}</div>
                            <div class="sig-line"></div>
                            <div style="font-size: 11px;">.......................</div>
                        </td>
                    </tr>
                </table>
            </td>

            {{-- Summary Table --}}
            <td style="width: 40%; vertical-align: top;">
                <div style="border: 1px dashed #000; padding: 6px 10px;">
                    <table class="summary-table" style="width: 100%;">
                        <tr>
                            <td class="text-left">Subtotal:</td>
                            <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="summary-total">
                            <td class="text-left fw-bold">Total:</td>
                            <td class="text-right fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div style="margin-top: 15px; font-size: 9px; color: #555; text-align: right;">
        Dicetak pada: {{ $tanggal }} WIB
    </div>

</body>

</html>
