<table style="border: 20px black solid;">
    <thead>
        <tr>
            <th colspan="5" style="text-align: center; font-weight:bold; font-size: 16px;">PT Jaringan Putra Nusantara</th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center; font-size: 14px">Report Penggunaan Barang</th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center;">Periode: {{$start}} - {{$end}} </th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight:bold;">Lokasi</th>
            <th style="text-align: center; font-weight:bold;">Sublokasi</th>
            <th style="text-align: center; font-weight:bold;">Nama Barang</th>
            <th style="text-align: center; font-weight:bold;">Satuan</th>
            <th style="text-align: center; font-weight:bold;">Kondisi</th>
            <th style="text-align: center; font-weight:bold;">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $i => $item)
        <tr>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$item->nama_lokasi}}</td>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$item->nama_sublokasi}}</td>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$item->nama_barang}}</td>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$item->satuan}}</td>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$item->is_new ? "Baru" : "Bekas"}}</td>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$item->total * -1}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
