<!DOCTYPE html>
<html>
<head>
	<title>Report Aktivitas - JPN</title>
    {{-- @include('layouts.head-css') --}}
    <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />

</head>
<body>
	<style type="text/css">
		#job {
			font-family: Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}

		#job td, #job th {
			border: 1px solid #ddd;
			padding: 8px;
		}

		#job tr:nth-child(even){background-color: #ffa17f;}

		#job tr:hover {background-color: #ddd;}

		#job th {
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
		<h5>Report Aktifitas/Job</h4>
	</div>
 
	<table class='table table-bordered' id="job">
		<thead>
			<tr>
				<th style="text-align: center">Tanggal Berangkat</th>
				<th style="text-align: center">Tanggal Pulang</th>
				<th style="text-align: center">Lokasi</th>
				<th style="text-align: center">Sublokasi</th>
				<th style="text-align: center">Teknisi</th>
				<th style="text-align: center">Deskripsi</th>
			</tr>
		</thead>
		<tbody>
			@foreach($aktivitas as $i => $job)
                @foreach ($job->teknisi as $index => $teknisi)
                <tr>
                    @if ($index == 0)
                    <td rowspan="{{count($job->teknisi)}}" style="text-align: center;">{{$job->tanggal_berangkat}}</td>
                    <td rowspan="{{count($job->teknisi)}}" style="text-align: center;">{{$job->tanggal_pulang}}</td>
                    <td rowspan="{{count($job->teknisi)}}" style="text-align: center;">{{$job->lokasi->nama}}</td>
                    <td rowspan="{{count($job->teknisi)}}" style="text-align: center;">{{$job->sublokasi->nama}}</td>
                    @endif
                    <td>{{$teknisi->karyawan->nama}}</td>
                    @if ($index == 0)
                    <td rowspan="{{count($job->teknisi)}}">{{$job->deskripsi}}</td>
                    @endif
                </tr>                    
                @endforeach
			@endforeach
		</tbody>
	</table>
 
</body>
</html>