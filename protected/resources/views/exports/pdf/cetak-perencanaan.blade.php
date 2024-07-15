<!DOCTYPE html>
<html>
<head>
	<title>Report Aktivitas - JPN</title>
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

		#stok td, #stok th {
			border: 1px solid #ddd;
			padding: 8px;
		}

		#stok .td-empty {
			padding: 16px;
		}

		#stok tr:nth-child(even){background-color: #ededed;}

		#stok tr:hover {background-color: #ddd;}

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

	</style>

    <div class="header" style="margin-top: -20px; margin-bottom: 5px;">
        <img style="width: 90px;" src="assets/images/logo-jpn.png" alt="">
    </div class="header">

	<div style="margin-bottom: 30px; text-align: center;">
		<h4>Lembar Pengeluaran Stok</h4>
        {{-- <p>Di print pada: {{$tanggal}}</p> --}}
	</div>

	<div style="margin-bottom: 30px; width: 100%">
		<table>
			<tr>
				<td >Tanggal</td>
				<td style="padding-left: 10px">:</td>
				<td style="padding-left: 10px">{{$tanggal}}</td>
			</tr>
			<tr>
				<td>Lokasi</td>
				<td style="padding-left: 10px">:</td>
				<td style="padding-left: 10px">{{ $lokasi->nama }}</td>
			</tr>
			<tr>
				<td>Sub Lokasi</td>
				<td style="padding-left: 10px">:</td>
				<td style="padding-left: 10px">{{ $sublokasi->nama }}</td>
			</tr>
		</table>
        {{-- <p>Di print pada: {{$tanggal}}</p> --}}
	</div>
 
	<table class='table table-bordered' id="stok">
		<thead>
			<tr>
				<th style="text-align: center">Kode</th>
				<th style="text-align: center">Nama</th>
				<th style="text-align: center">Kondisi</th>
				<th style="text-align: center">Jumlah Dibawa</th>
				<th style="text-align: center">Jumlah Terpakai</th>
			</tr>
		</thead>
		<tbody>
			@foreach($barang as $i => $item)
                <tr>
                    <td style="text-align: center">{{$item->kode}}</td>
                    <td style="text-align: center">{{$item->nama}}</td>
                    <td style="text-align: center">{{$item->kondisi}}</td>
                    <td style="text-align: center">{{$item->qty}}</td>
					<td></td>
                </tr>
			@endforeach
			@for ($i = 0; $i <= 2; $i++)
				<tr>
					<td class="td-empty"></td>
					<td class="td-empty"></td>
					<td class="td-empty"></td>
					<td class="td-empty"></td>
					<td class="td-empty"></td>
				</tr>
			@endfor
		</tbody>
	</table>
 
</body>
</html>