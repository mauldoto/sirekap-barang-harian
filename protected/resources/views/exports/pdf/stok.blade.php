<!DOCTYPE html>
<html>
<head>
	<title>Report Aktivitas - JPN</title>
    {{-- @include('layouts.head-css') --}}
    <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />

</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}

        table, thead, th, tr, td {
            border: 1px solid black;
        }

        div.header {
            text-align: right;
        }

	</style>

    <div class="header" style="margin-top: -20px; margin-bottom: 5px;">
        <img style="width: 90px;" src="{{asset('assets/images/logo-jpn.png')}}" alt="">
    </div class="header">

	<center style="margin-bottom: 30px;">
		<h5>Report Stok</h4>
        <p>Di print pada: {{$tanggal}}</p>
	</center>
 
	<table class='table table-bordered'>
		<thead>
			<tr>
				<th style="text-align: center">Kode</th>
				<th style="text-align: center">Nama</th>
				<th style="text-align: center">Stok</th>
			</tr>
		</thead>
		<tbody>
			@foreach($stok as $i => $item)
                <tr>
                    <td style="text-align: center">{{$item->barang->kode}}</td>
                    <td style="text-align: center">{{$item->barang->nama}}</td>
                    <td style="text-align: center">{{$item->sumqty}}</td>
                </tr>
			@endforeach
		</tbody>
	</table>
 
</body>
</html>