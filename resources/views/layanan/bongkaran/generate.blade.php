<?php
use App\Models\Terbilang;
?>
<table>
    <thead>
        <tr>
            <th colspan="8">
                <h3><b>DATA KONTROL BONGKARAN {{$time}}</b></h3>
            </th>
        </tr>
        <tr>
            <th><b>No.</b></th>
            <th><b>Kontrol</b></th>
            <th><b>Pengguna</b></th>
            <th><b>Bulan</b></th>
            <th><b>Banyak Tagihan</b></th>
            <th><b>Tagihan</b></th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; $total = 0; ?>
        @foreach($dataset as $d)
        <tr>
            <td>{{$i}}.</td>
            <td>{{$d->kd_kontrol}}</td>
            <td>{{$d->nama}}</td>
            <td>{{$d->bulan}} Bulan</td>
            <td>{{$d->banyak}}</td>
            <td>{{number_format($d->tagihan,2,",",".")}}</td>
        </tr>
        <?php $i++; $total = $total + $d->tagihan; ?>
        @endforeach
        <tr>
            <td colspan="5"><b>T O T A L</b></td>
            <td><b>{{number_format($total,2,",",".")}}</b></td>
        </tr>
        <tr>
            <td><b>Terbilang</b></td>
            <td colspan="7">{{Terbilang::convert($total)}}</td>
        </tr>
    </tbody>
</table>
