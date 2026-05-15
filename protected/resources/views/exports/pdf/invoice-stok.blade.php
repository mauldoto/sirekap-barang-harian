<!DOCTYPE html>
<html>

<head>
    <title>Invoice Stok - {{ $stok->no_referensi }}</title>
    <style type="text/css">
        /* ===== mPDF compatible styles - NO @media print wrapper ===== */
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            padding: 10px 30px;
        }

        table {
            border-collapse: collapse;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    {{-- ====== HEADER ====== --}}
    <table style="width: 100%; margin-bottom: 10px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <img style="width: 80px; margin-bottom: 5px;" src="assets/images/logo-jpn.png" alt="">
                <div style="font-size: 24px; font-weight: 800; color: #1a3353;">SIREKAP JPN</div>
                <div style="font-size: 13px; color: #64748b; margin-top: 2px;">Invoice Stok {{ ucfirst($stok->type) }}
                </div>
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                {{-- Badge --}}
                @if($stok->type == 'masuk')
                    <span
                        style="display: inline-block; padding: 4px 16px; font-size: 11px; font-weight: 700; letter-spacing: 1px; background-color: #e6f7ee; color: #0d8a4a; border: 1px solid #b8e6cc;">
                        MASUK
                    </span>
                @else
                    <span
                        style="display: inline-block; padding: 4px 16px; font-size: 11px; font-weight: 700; letter-spacing: 1px; background-color: #fde8e8; color: #c53030; border: 1px solid #f5b4b4;">
                        KELUAR
                    </span>
                @endif

                <table style="float: right; margin-top: 8px;">
                    <tr>
                        <td style="font-size: 12px; color: #64748b; padding: 1px 8px 1px 0;">No. Invoice:</td>
                        <td style="font-size: 12px; font-weight: bold; text-align: right;">{{ $stok->no_referensi }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; color: #64748b; padding: 1px 8px 1px 0;">Tanggal:</td>
                        <td style="font-size: 12px; font-weight: bold; text-align: right;">
                            {{ \Carbon\Carbon::parse($stok->tanggal)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; color: #64748b; padding: 1px 8px 1px 0;">Diinput:</td>
                        <td style="font-size: 12px; font-weight: bold; text-align: right;">
                            {{ $stok->user->username ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <hr style="border: none; border-top: 1.5px solid #e2e8f0; margin: 12px 0;" />

    {{-- ====== INFO BOXES ====== --}}
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            @if($stok->type == 'keluar' && $stok->aktivitas)
                <td style="width: 48%; padding-right: 10px; vertical-align: top;">
                    <div style="border: 1.5px solid #e2e8f0; border-left: 4px solid #1a3353; padding: 12px 16px;">
                        <div
                            style="font-size: 10px; font-weight: 700; color: #8492a6; letter-spacing: 1px; margin-bottom: 4px;">
                            AKTIVITAS TERKAIT</div>
                        <div style="font-size: 14px; font-weight: 700; color: #1a1a1a; margin-bottom: 2px;">
                            {{ $stok->aktivitas->no_referensi }}</div>
                        <div style="font-size: 11px; color: #64748b;">{{ $stok->aktivitas->lokasi->nama ?? '-' }} —
                            {{ $stok->aktivitas->sublokasi->nama ?? '-' }}</div>
                    </div>
                </td>
                <td style="width: 48%; padding-left: 10px; vertical-align: top;">
                    <div style="border: 1.5px solid #e2e8f0; border-left: 4px solid #3b82f6; padding: 12px 16px;">
                        <div
                            style="font-size: 10px; font-weight: 700; color: #8492a6; letter-spacing: 1px; margin-bottom: 4px;">
                            LOKASI TUJUAN</div>
                        <div style="font-size: 14px; font-weight: 700; color: #1a1a1a; margin-bottom: 2px;">
                            {{ $stok->aktivitas->lokasi->nama ?? '-' }}</div>
                        <div style="font-size: 11px; color: #64748b;">{{ $stok->aktivitas->sublokasi->nama ?? '-' }}</div>
                    </div>
                </td>
            @else
                <td style="width: 48%; padding-right: 10px; vertical-align: top;">
                    <div style="border: 1.5px solid #e2e8f0; border-left: 4px solid #1a3353; padding: 12px 16px;">
                        <div
                            style="font-size: 10px; font-weight: 700; color: #8492a6; letter-spacing: 1px; margin-bottom: 4px;">
                            KETERANGAN</div>
                        <div style="margin-top: 4px;">{{ $stok->deskripsi ?? '-' }}</div>
                    </div>
                </td>
                <td style="width: 48%; padding-left: 10px; vertical-align: top;">
                    <div style="border: 1.5px solid #e2e8f0; border-left: 4px solid #3b82f6; padding: 12px 16px;">
                        <div
                            style="font-size: 10px; font-weight: 700; color: #8492a6; letter-spacing: 1px; margin-bottom: 4px;">
                            GUDANG</div>
                        @php
                            $gudangNames = $items->pluck('gudang.nama')->unique()->filter()->implode(', ');
                        @endphp
                        <div style="font-size: 14px; font-weight: 700; color: #1a1a1a; margin-bottom: 2px;">
                            {{ $gudangNames ?: '-' }}</div>
                        <div style="font-size: 11px; color: #64748b;">Tujuan penyimpanan</div>
                    </div>
                </td>
            @endif
        </tr>
    </table>

    {{-- ====== TABEL BARANG ====== --}}
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
            <tr>
                <th
                    style="width: 35px; text-align: center; background-color: #f1f5f9; font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; padding: 10px 8px; border-bottom: 2px solid #e2e8f0;">
                    No</th>
                <th
                    style="text-align: left; background-color: #f1f5f9; font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; padding: 10px 8px; border-bottom: 2px solid #e2e8f0;">
                    SKU</th>
                <th
                    style="text-align: left; background-color: #f1f5f9; font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; padding: 10px 8px; border-bottom: 2px solid #e2e8f0;">
                    Nama Barang</th>
                <th
                    style="text-align: center; background-color: #f1f5f9; font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; padding: 10px 8px; border-bottom: 2px solid #e2e8f0;">
                    Kondisi</th>
                <th
                    style="text-align: right; background-color: #f1f5f9; font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; padding: 10px 8px; border-bottom: 2px solid #e2e8f0;">
                    Qty</th>
                <th
                    style="text-align: right; background-color: #f1f5f9; font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; padding: 10px 8px; border-bottom: 2px solid #e2e8f0;">
                    Harga Satuan</th>
                <th
                    style="text-align: right; background-color: #f1f5f9; font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; padding: 10px 8px; border-bottom: 2px solid #e2e8f0;">
                    Total</th>
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
                    $rowBg = $key % 2 == 1 ? 'background-color: #fafbfc;' : '';
                @endphp
                <tr style="{{ $rowBg }}">
                    <td style="text-align: center; padding: 8px; border-bottom: 1px solid #f1f5f9; font-size: 11.5px;">
                        {{ $key + 1 }}</td>
                    <td
                        style="padding: 8px; border-bottom: 1px solid #f1f5f9; font-size: 11.5px; {{ $harga == 0 ? 'color: #0d8a4a;' : '' }}">
                        {{ $item->barang->kode ?? '-' }}
                        @if($harga == 0) (Bonus) @endif
                    </td>
                    <td
                        style="padding: 8px; border-bottom: 1px solid #f1f5f9; font-size: 11.5px; {{ $harga == 0 ? 'color: #0d8a4a;' : '' }}">
                        {{ $item->barang->nama }}</td>
                    <td style="text-align: center; padding: 8px; border-bottom: 1px solid #f1f5f9; font-size: 11.5px;">
                        @if($item->is_new)
                            <span
                                style="display: inline-block; padding: 2px 10px; font-size: 10px; font-weight: 600; background-color: #e0edff; color: #1d4ed8;">Baru</span>
                        @else
                            <span
                                style="display: inline-block; padding: 2px 10px; font-size: 10px; font-weight: 600; background-color: #fef3c7; color: #92400e;">Bekas</span>
                        @endif
                    </td>
                    <td
                        style="text-align: right; padding: 8px; border-bottom: 1px solid #f1f5f9; font-size: 11.5px; {{ $stok->type == 'keluar' ? 'color: #c53030; font-weight: bold;' : '' }}">
                        {{ $stok->type == 'keluar' ? '-' : '' }}{{ $qty }}
                    </td>
                    <td style="text-align: right; padding: 8px; border-bottom: 1px solid #f1f5f9; font-size: 11.5px;">Rp
                        {{ number_format($harga, 0, ',', '.') }}</td>
                    <td
                        style="text-align: right; padding: 8px; border-bottom: 1px solid #f1f5f9; font-size: 11.5px; font-weight: bold; {{ $harga == 0 ? 'color: #0d8a4a;' : '' }}">
                        Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ====== FOOTER ====== --}}
    <table style="width: 100%;">
        <tr>
            {{-- Left: Catatan + Signature --}}
            <td style="width: 50%; vertical-align: top; padding-right: 15px;">
                @if($stok->deskripsi && $stok->type == 'keluar')
                    <div
                        style="border: 1.5px solid #e2e8f0; border-left: 4px solid #f59e0b; padding: 10px 14px; background-color: #fffbeb; margin-bottom: 15px;">
                        <div
                            style="font-size: 10px; font-weight: 700; color: #92400e; letter-spacing: 1px; margin-bottom: 4px;">
                            CATATAN</div>
                        <p style="font-size: 12px;">{{ $stok->deskripsi }}</p>
                    </div>
                @endif

                <table style="width: 100%; margin-top: 10px;">
                    <tr>
                        <td style="width: 50%; vertical-align: top;">
                            <div style="font-size: 10px; font-weight: 700; color: #8492a6; letter-spacing: 0.8px;">
                                {{ $stok->type == 'masuk' ? 'PENERIMA (GUDANG)' : 'DISETUJUI OLEH' }}
                            </div>
                            <div
                                style="border-bottom: 1px solid #cbd5e1; margin-top: 50px; margin-bottom: 5px; width: 75%;">
                            </div>
                            <div style="font-size: 12px; color: #475569;">
                                {{ $stok->user->username ?? '.....................' }}</div>
                        </td>
                        <td style="width: 50%; vertical-align: top;">
                            <div style="font-size: 10px; font-weight: 700; color: #8492a6; letter-spacing: 0.8px;">
                                {{ $stok->type == 'masuk' ? 'PENGIRIM' : 'PENERIMA' }}
                            </div>
                            <div
                                style="border-bottom: 1px solid #cbd5e1; margin-top: 50px; margin-bottom: 5px; width: 75%;">
                            </div>
                            <div style="font-size: 12px; color: #475569;">.......................</div>
                        </td>
                    </tr>
                </table>
            </td>

            {{-- Right: Summary --}}
            <td style="width: 50%; vertical-align: top; padding-left: 15px;">
                <div style="border: 1.5px solid #e2e8f0; padding: 12px 16px; background-color: #f8fafc;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 3px 0; font-size: 12px; color: #64748b;">Subtotal</td>
                            <td style="padding: 3px 0; font-size: 12px; text-align: right;">Rp
                                {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding: 0;">
                                <hr style="border: none; border-top: 1.5px solid #e2e8f0; margin: 6px 0;" />
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; font-size: 14px; font-weight: 700; color: #1a3353;">Total</td>
                            <td
                                style="padding: 3px 0; text-align: right; font-size: 18px; font-weight: 800; color: {{ $stok->type == 'masuk' ? '#0d8a4a' : '#c53030' }};">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div style="margin-top: 20px; font-size: 10px; color: #94a3b8; text-align: right;">
        <p>Dicetak pada: {{ $tanggal }} WIB</p>
    </div>

</body>

</html>