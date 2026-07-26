<!DOCTYPE html>
<html>

<head>
    <title>Rencana Pengeluaran Stok - JPN</title>
    {{-- @include('layouts.head-css') --}}
    <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />

</head>

<body>
    <style type="text/css">
        #stok {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #stok td,
        #stok th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #stok .td-empty {
            padding: 16px;
        }

        #stok tr:nth-child(even) {
            background-color: #ededed;
        }

        #stok tr:hover {
            background-color: #ddd;
        }

        #stok th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #3833c6;
            color: white;
        }

        div.header {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>

    <div class="header" style="margin-top: -20px; margin-bottom: 5px;">
        <img style="width: 90px;" src="assets/images/logo-jpn.png" alt="">
    </div class="header">

    <div style="margin-bottom: 30px; text-align: center;">
        <h4>Lembar Pengeluaran Stok</h4>
        {{-- <p>Di print pada: {{$tanggal}}</p> --}}
    </div>

    @foreach ($barang as $lokasi)
        <div style="margin-bottom: 30px; width: 100%">
            <table class='table table-bordered' id="stok">
                <thead>
                    <tr>
                        <td colspan="7" style="text-center" style="text-align: center">
                            {{ $lokasi['no_referensi'] . ' | ' . $lokasi['lokasi']['nama'] . ' | ' . $lokasi['sublokasi']['nama'] }}
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: center">Kode</th>
                        <th style="text-align: center">Nama</th>
                        <th style="text-align: center">Kondisi</th>
                        <th style="text-align: center">Jumlah Dibawa</th>
                        <th style="text-align: center">Jumlah Terpakai</th>
                        <th style="text-align: center">Satuan</th>
                        <th style="text-align: center">Gudang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lokasi['barang'] as $i => $item)
                        <tr>
                            <td style="text-align: center">{{ $item['kode'] }}</td>
                            <td style="text-align: center">{{ $item['nama'] }}</td>
                            <td style="text-align: center">{{ $item['kondisi'] }}</td>
                            <td style="text-align: center">{{ $item['qty'] }}</td>
                            <td style="text-align: center">{{ $item['qty_used'] }}</td>
                            <td style="text-align: center">{{ $item['satuan'] }}</td>
                            <td style="text-align: center">{{ $item['gudang'] }}</td>
                        </tr>
                    @endforeach
                    @for ($i = 0; $i <= 2; $i++)
                        <tr>
                            <td class="td-empty"></td>
                            <td class="td-empty"></td>
                            <td class="td-empty"></td>
                            <td class="td-empty"></td>
                            <td class="td-empty"></td>
                            <td class="td-empty"></td>
                            <td class="td-empty"></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    @endforeach


</body>

</html>
