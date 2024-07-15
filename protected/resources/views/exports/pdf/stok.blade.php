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

		#stok tr:nth-child(even){background-color: #ffa17f;}

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
		<h5>Report Stok</h4>
        <p>Di print pada: {{$tanggal}}</p>
	</div>
 
	<table class='table table-bordered' id="stok">
		<thead>
			<tr>
				<th style="text-align: center">Kode</th>
				<th style="text-align: center">Nama</th>
				<th style="text-align: center">Kondisi</th>
				<th style="text-align: center">Stok</th>
			</tr>
		</thead>
		<tbody>
			@foreach($stok as $i => $item)
                <tr>
                    <td style="text-align: center">{{$item->barang->kode}}</td>
                    <td style="text-align: center">{{$item->barang->nama}}</td>
                    <td style="text-align: center">{{$item->is_new ? 'Baru':'Bekas'}}</td>
                    <td style="text-align: center">{{$item->sumqty}}</td>
                </tr>
			@endforeach
		</tbody>
	</table>
 
</body>
</html>