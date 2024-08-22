<!DOCTYPE html>
<html>
<head>
    <title>Report Aktivitas - JPN</title>
    {{-- @include('layouts.head-css') --}}
    <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <style type="text/css">
        body {
            width: 80%;
        }

        #job {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #job td,
        #job th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #job tr:nth-child(even) {
            background-color: #ededed;
        }

        #job tr:hover {
            background-color: #ddd;
        }

        #job th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #3833c6;
            color: white;
        }

        /* table, th, tr, td {
            border: 1px solid grey;
        } */

        .table-detail {
            width: 100%;
        }

        .table-detail .value-table {
            font-weight: bold;
        }

        .table-teknisi, .table-teknisi tr, .table-teknisi th, .table-teknisi td {
            border: 1px solid grey;
        }

        .table-teknisi th, .table-teknisi td {
            text-align: center;
            padding: 5px 8px;
        }

        .p-1 {
            padding: 20px;
        }

        .text-white {
            color: white;
        }

        .bg-secondary {
            background-color: #6c757d;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        .bg-success {
            background-color: #198754;
        }

        .bg-warning {
            background-color: #ffc107;
        }

        .rounded {
            border-radius: 50px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">
            <img style="width: 120px;" src="http://sirekap.test:81/assets/images/logo-jpn.png" alt="">
        </div>
        <h1 class="title" style="font-size:20px; text-align: right; margin-top:-40px;">
            Tiket Aktivitas
        </h1>
    </div>
    
    {{-- <div class="header" style="margin-top: 50px; margin-bottom: 5px;">
        <img style="width: 90px;" src="http://sirekap.test:81/assets/images/logo-jpn.png" alt="">
        <p style="float: right; margin-top:15px;">Tiket Aktivitas</p>
    </div class="header"> --}}

    <div class="no-tiket" style="margin-top:50px; margin-bottom: 30px; text-align: center;">
        <h5 style="color: grey; margin-bottom: -5px;">No Tiket</h4>
        <h1 style="font-size: 35px;">{{$aktivitas->no_referensi}}</h1>
        @if ($aktivitas->status === 'waiting')
            <span class="p-1 text-white rounded bg-secondary">WAITING</span>
        @elseif ($aktivitas->status === 'progress')
            <span class="p-1 text-white rounded bg-warning">PROGRESS</span>
        @elseif ($aktivitas->status === 'done')
            <span class="p-1 text-white rounded bg-success">DONE</span>
        @else
            <span class="p-1 text-white rounded bg-danger">CANCEL</span>
        @endif
    </div>

    <div class="detail" style="margin-top:40px; padding: 0 70px; text-align:left;">
        <h4 style="margin-bottom: -15px; color:grey;">Detail</h4>
        <hr style="color:grey;" />

        <table class="table-detail" style="width: 100%;">
            <tr style="">
                <td style="" width="50%">Tanggal Berangkat</td>
                <td style="" width="50%">Tanggal Pulang (Estimasi)</td>
            </tr>
            <tr>
                <td class="value-table">{{$aktivitas->tanggal_berangkat}}</td>
                <td class="value-table">{{$aktivitas->tanggal_pulang}}</td>
            </tr>
        </table>

        <table class="table-detail" style="margin-top:10px; width: 100%;">
            <tr style="">
                <td style="" width="50%">Lokasi</td>
                <td style="" width="50%">Sublokasi</td>
            </tr>
            <tr>
                <td class="value-table">{{$aktivitas->lokasi->nama}}</td>
                <td class="value-table">{{$aktivitas->sublokasi->nama}}</td>
            </tr>
        </table>
    </div>

    <div class="teknisi" style="margin-top:30px; padding: 0 70px; text-align:left;">
        <h4 style="margin-bottom: -15px; color:grey;">Teknisi</h4>
        <hr style="color:grey;" />

        <table class="table-teknisi" style="width: 100%;">
            <Thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                </tr>
            </Thead>
            <Tbody>
               @foreach ($aktivitas->teknisi as $kei => $teknisi)
               <tr>
                    <td style="padding: 0 0 5px 0;">{{$kei + 1}}</td>
                    <td style="padding: 0 5px;">{{$teknisi->karyawan->nama}}</td>
                </tr>
               @endforeach
            </Tbody>
        </table>
    </div>

    <div class="deskripsi" style="margin-top:30px; padding: 0 70px; text-align:left;">
        <h4 style="margin-bottom: -15px; color:grey;">Deskripsi</h4>
        <hr style="color:grey;" />

        <p>{{$aktivitas->deskripsi}}</p>
    </div>

    <div class="stok" style="margin-top:30px; padding: 0 70px; text-align:left;">
        <h4 style="margin-bottom: -15px; color:grey;">Stok Terpakai</h4>
        <hr style="color:grey;" />

        <table class="table-teknisi" style="width: 100%;">
            <Thead>
                <tr>
                    <th>No</th>
                    <th>Nama barang</th>
                    <th>Jumlah Terpakai</th>
                </tr>
            </Thead>
            <Tbody>
                @foreach ($aktivitas->barang as $kei => $item)
                <tr>
                    <td style="padding: 0 0 5px 0;">{{$kei+1}}</td>
                    <td style="padding: 0 5px;">{{$item->barang->nama}}</td>
                    <td style="padding: 0 5px;">{{$item->qty < 0 ? $item->qty * -1 : $item->qty}}</td>
                    </tr>
                @endforeach
            </Tbody>
        </table>
    </div>

</body>
</html>
