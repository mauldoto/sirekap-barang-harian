<table style="border: 20px black solid;">
    <thead>
        <tr>
            <th colspan="5" style="text-align: center; font-weight:bold; font-size: 16px;">PT Jaringan Putra Nusantara</th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center; font-size: 14px">Report Akomodasi</th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center; font-size: 14px">Periode: </th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight:bold;">No Akomodasi</th>
            <th style="text-align: center; font-weight:bold;">Tanggal Terbit</th>
            <th style="text-align: center; font-weight:bold;">Nominal Pengajuan</th>
            <th style="text-align: center; font-weight:bold;">Nominal Realisasi</th>
            <th style="text-align: center; font-weight:bold;">Tiket Job</th>
        </tr>
    </thead>
    <tbody>
        @foreach($akomodasi as $i => $akm)
        <tr>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$akm->no_referensi}}</td>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$akm->tanggal_terbit}}</td>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$akm->nominal_pengajuan}}</td>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$akm->nominal_realisasi}}</td>
            <td style="padding: 0 5px; width: 150px; height: 50px;">
                @foreach ($akomodasi->aktivitas as $index => $act)
                - {{$act->no_referensi}} <br />
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
