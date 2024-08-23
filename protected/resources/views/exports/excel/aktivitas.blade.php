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
        <tr>
            <td style="text-align: center;">{{$job->tanggal_berangkat}}</td>
            <td style="text-align: center;">{{$job->tanggal_pulang}}</td>
            <td style="text-align: center;">{{$job->lokasi->nama}}</td>
            <td style="text-align: center;">{{$job->sublokasi->nama}}</td>
            <td>
                @foreach ($job->teknisi as $index => $teknisi)
                - {{$teknisi->karyawan->nama}} <br />
                @endforeach
            </td>
            <td>{{$job->deskripsi}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
