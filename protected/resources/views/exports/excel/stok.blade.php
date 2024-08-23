<table style="border: 1px black solid;">
    <thead>
        <tr>
            <th colspan="4" style="text-align: center; font-weight:bold;">REPORT STOK PT JPN</th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
            <th style="text-align: center; font-weight:bold;"></th>
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
            <td style="text-align: center">{{$item->barang->kode}}</td>
            <td style="text-align: center">{{$item->barang->nama}}</td>
            <td style="text-align: center">{{$item->is_new ? 'Baru':'Bekas'}}</td>
            <td style="text-align: center">{{$item->sumqty}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
