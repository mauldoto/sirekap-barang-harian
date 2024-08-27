<table style="border: 1px black solid;">
    <thead>
        <tr>
            <th colspan="4" style="text-align: center; font-weight:bold; font-size: 16px;">PT Jaringan Putra Nusantara</th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center; font-size: 14px">Report Stok Barang</th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;"></th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">Di report pada: {{Carbon\Carbon::now()->format('d-m-YYYY')}}</th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;"></th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;"></th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;"></th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight:bold;">Kode</th>
            <th style="text-align: center; font-weight:bold;">Nama</th>
            <th style="text-align: center; font-weight:bold;">Kondisi</th>
            <th style="text-align: center; font-weight:bold;">Stok</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stok as $i => $item)
        <tr>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$item->barang->kode}}</td>
            <td style="text-align: center; padding: 0 5px; width: 200px;">{{$item->barang->nama}}</td>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$item->is_new ? 'Baru':'Bekas'}}</td>
            <td style="text-align: center; padding: 0 5px; width: 150px;">{{$item->sumqty}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
