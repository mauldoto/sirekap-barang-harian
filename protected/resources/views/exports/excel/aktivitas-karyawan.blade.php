<table class='table table-bordered' id="job">
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; font-weight:bold; font-size: 16px;">PT Jaringan Putra Nusantara</th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; font-size: 14px">Report Aktivitas Karyawan</th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">Periode: {{$start}} - {{$end}}</th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
        <tr>
            <th style="text-align: center;font-weight: bold">No Tiket</th>
            <th style="text-align: center;font-weight: bold">Tanggal Berangkat</th>
            <th style="text-align: center;font-weight: bold">Tanggal Pulang</th>
            <th style="text-align: center;font-weight: bold">Lokasi</th>
            <th style="text-align: center;font-weight: bold">Sublokasi</th>
            <th style="text-align: center;font-weight: bold">Teknisi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($aktivitas as $i => $job)
        <tr>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$job->no_referensi}}</td>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$job->tanggal_berangkat}}</td>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$job->tanggal_pulang}}</td>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$job->nama_lokasi}}</td>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$job->nama_sublokasi}}</td>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$job->nama_karyawan}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
