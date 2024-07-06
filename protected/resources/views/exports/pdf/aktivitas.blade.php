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
            text-align: end;
        }
	</style>

    <div class="header">
        <img style="width: 100px;" src="{{asset('assets/images/clients/6.png')}}" alt="">
    </div class="header">
	<center>
		<h5>Report Aktifitas/Job</h4>
	</center>
 
	<table class='table table-bordered'>
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