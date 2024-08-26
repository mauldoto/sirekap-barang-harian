<table class='table table-bordered' id="job">
    <thead>
        <tr>
            <th colspan="4" style="text-align: center; font-weight:bold; font-size: 16px;">PT Jaringan Putra Nusantara</th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center; font-size: 14px">Report Aktivitas</th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;"></th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">Periode: </th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
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
        <tr>
            <td style="text-align: center; padding: 0 5px; width: 100px;">{{$job->tanggal_berangkat}}</td>
            <td style="text-align: center; padding: 0 5px; width: 100px;">{{$job->tanggal_pulang}}</td>
            <td style="text-align: center; padding: 0 5px; width: 100px;">{{$job->lokasi->nama}}</td>
            <td style="text-align: center; padding: 0 5px; width: 100px;">{{$job->sublokasi->nama}}</td>
            <td style="padding: 0 5px; width: 100px; height: 50px;">
                @foreach ($job->teknisi as $index => $teknisi)
                - {{$teknisi->karyawan->nama}} <br />
                @endforeach
            </td>
            <td style="padding: 0 5px; width: 100px;">{{$job->deskripsi}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
