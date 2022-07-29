<table>
    <tr>
        <td colspan="6" align="center" style="border: 1px solid #000000;"><b>Data Tempat Usaha</b></td>
    </tr>
    <tr>
        <td align="center" style="border: 1px solid #000000; border-bottom: 1px double #000000;"><b>No.</b></td>
        <td align="center" style="border: 1px solid #000000; border-bottom: 1px double #000000;"><b>Kontrol</b></td>
        <td align="center" style="border: 1px solid #000000; border-bottom: 1px double #000000;"><b>Los</b></td>
        <td align="center" style="border: 1px solid #000000; border-bottom: 1px double #000000;"><b>Pengguna</b></td>
        <td align="center" style="border: 1px solid #000000; border-bottom: 1px double #000000;"><b>Pemilik</b></td>
        <td align="center" style="border: 1px solid #000000; border-bottom: 1px double #000000;"><b>Status</b></td>
    </tr>
    @php
        $i = 1;
    @endphp
    @foreach ($data as $d)
    <tr>
        <td style="border: 1px solid #000000;">{{$i}}</td>
        <td style="border: 1px solid #000000;">{{$d->kd_kontrol}}</td>
        <td style="border: 1px solid #000000;">{{$d->no_alamat}}</td>
        <td style="border: 1px solid #000000;">{{$d->pengguna->nama}}</td>
        <td style="border: 1px solid #000000;">{{$d->pemilik->nama}}</td>
        <td align="center" style="border: 1px solid #000000;">{{\App\Models\TempatUsaha::status($d->stt_tempat)}}</td>
    </tr>
    @php
        $i++;
    @endphp
    @endforeach
    <tr></tr>
    <tr>
        <td colspan="6" align="right"><b>Bandung, {{\Carbon\Carbon::now()}}</b></td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr>
        <td colspan="6" align="right"><b>{{Session::get('username')}}</b></td>
    </tr>
</table>
