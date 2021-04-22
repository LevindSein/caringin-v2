<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Pemakaian Diskon | BP3C</title>
        <link rel="stylesheet" href="{{asset('css/laporan/pemakaian/style-pemakaian1.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>
    <style type="text/css">
    table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    </style>
    <body onload="window.print()">  
        @for($i=1;$i<=2;$i++)
        @if($i == 1)
        <main>
            <table class="tg">
                <thead>
                    <tr>
                        <th colspan="6" style="border-style:none;">
                            <h3 style="text-align:center;">REKAP DISKON KEAMANAN & IPK<br>TAGIHAN {{$bln}}</h3>
                        </th>
                    </tr>
                    <tr>
                        <th class="tg-r8fv">No.</th>
                        <th class="tg-r8fv">Blok</th>
                        <th class="tg-r8fv">Jml.Unit</th>
                        <th class="tg-r8fv">Tagihan</th>
                        <th class="tg-r8fv">Diskon</th>
                        <th class="tg-r8fv">Jml Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach($rekapKeamananIpk as $d)
                    <tr>
                        <td class="tg-cegc">{{$no}}</td>
                        <td class="tg-cegc">{{$d->blok}}</td>
                        <td class="tg-cegc">{{$d->pengguna}}</td>
                        <td class="tg-g25h">{{number_format($d->subtotal)}}</td>
                        <td class="tg-g25h">{{number_format($d->diskon)}}</td>
                        <td class="tg-g25h">{{number_format($d->tagihan)}}</td>
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                    <tr>
                        <td class="tg-vbo4" style="text-align:center;" colspan="2">Total</td>
                        <td class="tg-vbo4" style="text-align:center;">{{number_format($ttlRekapKeamananIpk[0])}}</td>
                        <td class="tg-8m6k">{{number_format($ttlRekapKeamananIpk[1])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekapKeamananIpk[2])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekapKeamananIpk[3])}}</td>
                    </tr>
                </tbody>
            </table>
        </main>
        @else
        <div style="page-break-before:always"></div>
        @foreach($rincianKeamananIpk as $data)
        <div>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th colspan="8" style="border-style:none;">
                                <h3 style="text-align:center;">RINCIAN DISKON KEAMANAN & IPK<br>TAGIHAN {{$bln}}<br>{{$data[0]}}</h3>
                            </th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv">No.</th>
                            <th class="tg-r8fv">Kontrol</th>
                            <th class="tg-r8fv">Pengguna</th>
                            <th class="tg-r8fv" style="width:18%">Alamat</th>
                            <th class="tg-r8fv">Jml.Unit</th>
                            <th class="tg-r8fv">Tagihan</th>
                            <th class="tg-r8fv">Diskon</th>
                            <th class="tg-r8fv">Jml Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach($data[1] as $d)
                        <tr>
                            <td class="tg-cegc">{{$no}}</td>
                            <td class="tg-cegc">{{$d->kontrol}}</td>
                            <td class="tg-cegc" style="text-align:left;white-space: normal;">{{$d->pengguna}}</td>
                            <td class="tg-cegc" style="white-space:normal; word-break:break-word;">{{$d->nomor}}</td>
                            <td class="tg-cegc">{{number_format($d->jumlah)}}</td>
                            <td class="tg-cegc">{{number_format($d->subtotal)}}</td>
                            <td class="tg-cegc">{{number_format($d->diskon)}}</td>
                            <td class="tg-cegc">{{number_format($d->tagihan)}}</td>
                        </tr>
                        <?php $no++; ?>
                        @endforeach
                        @foreach($data[2] as $d)
                        <tr>
                            <td class="tg-vbo4" style="text-align:center;" colspan="4">Total</td>
                            <td class="tg-8m6k">{{number_format($d->jumlah)}}</td>
                            <td class="tg-8m6k">{{number_format($d->subtotal)}}</td>
                            <td class="tg-8m6k">{{number_format($d->diskon)}}</td>
                            <td class="tg-8m6k">{{number_format($d->tagihan)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </main>
            <!-- <div style="page-break-after:always"></div> -->
        </div>
        @endforeach
        @endif
        @endfor
        <div style="page-break-before:always"></div>
        @for($i=1;$i<=2;$i++)
        @if($i == 1)
        <main>
            <table class="tg">
                <thead>
                    <tr>
                        <th colspan="6" style="border-style:none;">
                            <h3 style="text-align:center;">REKAP DISKON KEBERSIHAN<br>TAGIHAN {{$bln}}</h3>
                        </th>
                    </tr>
                    <tr>
                        <th class="tg-r8fv">No.</th>
                        <th class="tg-r8fv">Blok</th>
                        <th class="tg-r8fv">Jml.Unit</th>
                        <th class="tg-r8fv">Tagihan</th>
                        <th class="tg-r8fv">Diskon</th>
                        <th class="tg-r8fv">Jml Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach($rekapKebersihan as $d)
                    <tr>
                        <td class="tg-cegc">{{$no}}</td>
                        <td class="tg-cegc">{{$d->blok}}</td>
                        <td class="tg-cegc">{{$d->pengguna}}</td>
                        <td class="tg-g25h">{{number_format($d->subtotal)}}</td>
                        <td class="tg-g25h">{{number_format($d->diskon)}}</td>
                        <td class="tg-g25h">{{number_format($d->tagihan)}}</td>
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                    <tr>
                        <td class="tg-vbo4" style="text-align:center;" colspan="2">Total</td>
                        <td class="tg-vbo4" style="text-align:center;">{{number_format($ttlRekapKebersihan[0])}}</td>
                        <td class="tg-8m6k">{{number_format($ttlRekapKebersihan[1])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekapKebersihan[2])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekapKebersihan[3])}}</td>
                    </tr>
                </tbody>
            </table>
        </main>
        @else
        <div style="page-break-before:always"></div>
        @foreach($rincianKebersihan as $data)
        <div>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th colspan="8" style="border-style:none;">
                                <h3 style="text-align:center;">RINCIAN DISKON KEBERSIHAN<br>TAGIHAN {{$bln}}<br>{{$data[0]}}</h3>
                            </th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv">No.</th>
                            <th class="tg-r8fv">Kontrol</th>
                            <th class="tg-r8fv">Pengguna</th>
                            <th class="tg-r8fv" style="width:18%">Alamat</th>
                            <th class="tg-r8fv">Jml.Unit</th>
                            <th class="tg-r8fv">Tagihan</th>
                            <th class="tg-r8fv">Diskon</th>
                            <th class="tg-r8fv">Jml Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach($data[1] as $d)
                        <tr>
                            <td class="tg-cegc">{{$no}}</td>
                            <td class="tg-cegc">{{$d->kontrol}}</td>
                            <td class="tg-cegc" style="text-align:left;white-space: normal;">{{$d->pengguna}}</td>
                            <td class="tg-cegc" style="white-space:normal; word-break:break-word;">{{$d->nomor}}</td>
                            <td class="tg-cegc">{{number_format($d->jumlah)}}</td>
                            <td class="tg-cegc">{{number_format($d->subtotal)}}</td>
                            <td class="tg-cegc">{{number_format($d->diskon)}}</td>
                            <td class="tg-cegc">{{number_format($d->tagihan)}}</td>
                        </tr>
                        <?php $no++; ?>
                        @endforeach
                        @foreach($data[2] as $d)
                        <tr>
                            <td class="tg-vbo4" style="text-align:center;" colspan="4">Total</td>
                            <td class="tg-8m6k">{{number_format($d->jumlah)}}</td>
                            <td class="tg-8m6k">{{number_format($d->subtotal)}}</td>
                            <td class="tg-8m6k">{{number_format($d->diskon)}}</td>
                            <td class="tg-8m6k">{{number_format($d->tagihan)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </main>
            <!-- <div style="page-break-after:always"></div> -->
        </div>
        @endforeach
        @endif
        @endfor
        <div style="page-break-before:always"></div>
        @for($i=1;$i<=2;$i++)
        @if($i == 1)
        <main>
            <table class="tg">
                <thead>
                    <tr>
                        <th colspan="6" style="border-style:none;">
                            <h3 style="text-align:center;">REKAP DISKON LISTRIK<br>PEMAKAIAN {{$bulan}}</h3>
                        </th>
                    </tr>
                    <tr>
                        <th class="tg-r8fv">No.</th>
                        <th class="tg-r8fv">Blok</th>
                        <th class="tg-r8fv">Pengguna</th>
                        <th class="tg-r8fv">Tagihan</th>
                        <th class="tg-r8fv">Diskon</th>
                        <th class="tg-r8fv">Jml Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach($rekapListrik as $d)
                    <tr>
                        <td class="tg-cegc">{{$no}}</td>
                        <td class="tg-cegc">{{$d->blok}}</td>
                        <td class="tg-cegc">{{$d->pengguna}}</td>
                        <td class="tg-cegc">{{number_format($d->tagihan + $d->diskon)}}</td>
                        <td class="tg-cegc">{{number_format($d->diskon)}}</td>
                        <td class="tg-cegc">{{number_format($d->tagihan)}}</td>
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                    <tr>
                        <td class="tg-vbo4" style="text-align:center;" colspan="2">Total</td>
                        <td class="tg-vbo4" style="text-align:center;">{{number_format($ttlRekapListrik[0])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekapListrik[5] + $ttlRekapListrik[8])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekapListrik[8])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekapListrik[5])}}</td>
                    </tr>
                </tbody>
            </table>
        </main>
        @else
        <div style="page-break-before:always"></div>
        @foreach($rincianListrik as $data)
        <div>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th colspan="8" style="border-style:none;">
                                <h3 style="text-align:center;">RINCIAN DISKON LISTRIK<br>PEMAKAIAN {{$bulan}}<br>{{$data[0]}}</h3>
                            </th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv">No.</th>
                            <th class="tg-r8fv">Kontrol</th>
                            <th class="tg-r8fv">Pengguna</th>
                            <th class="tg-r8fv">Tagihan</th>
                            <th class="tg-r8fv">Diskon</th>
                            <th class="tg-r8fv">Jml Bayar</th>
                            <th class="tg-r8fv">Ket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach($data[1] as $d)
                        <tr>
                            <td class="tg-cegc">{{$no}}</td>
                            <td class="tg-cegc">{{$d->kontrol}}</td>
                            <td class="tg-cegc" style="text-align:left;">{{$d->pengguna}}</td>
                            <td class="tg-cegc">{{number_format($d->tagihan + $d->diskon)}}</td>
                            <td class="tg-cegc">{{number_format($d->diskon)}}</td>
                            <td class="tg-cegc">{{number_format($d->tagihan)}}</td>
                            <td class="tg-cegc" style="white-space:normal; word-break:break-word;">{{$d->lokasi}}</td>
                        </tr>
                        <?php $no++; ?>
                        @endforeach
                        @foreach($data[2] as $d)
                        <tr>
                            <td class="tg-vbo4" style="text-align:center;" colspan="3">Total</td>
                            <td class="tg-8m6k">{{number_format($d->tagihan + $d->diskon)}}</td>
                            <td class="tg-8m6k">{{number_format($d->diskon)}}</td>
                            <td class="tg-8m6k">{{number_format($d->tagihan)}}</td>
                            <td class="tg-8m6k"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </main>
            <!-- <div style="page-break-after:always"></div> -->
        </div>
        @endforeach
        @endif
        @endfor
        <div style="page-break-before:always"></div>
        @for($i=1;$i<=2;$i++)
        @if($i == 1)
        <main>
            <table class="tg">
                <thead>
                    <tr>
                        <th colspan="6" style="border-style:none;">
                            <h3 style="text-align:center;">REKAP DISKON AIR BERSIH<br>PEMAKAIAN {{$bulan}}</h3>
                        </th>
                    </tr>
                    <tr>
                        <th class="tg-r8fv">No.</th>
                        <th class="tg-r8fv">Blok</th>
                        <th class="tg-r8fv">Pengguna</th>
                        <th class="tg-r8fv">Tagihan</th>
                        <th class="tg-r8fv">Diskon</th>
                        <th class="tg-r8fv">Jml Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach($rekapAirBersih as $d)
                    <tr>
                        <td class="tg-cegc">{{$no}}</td>
                        <td class="tg-cegc">{{$d->blok}}</td>
                        <td class="tg-cegc">{{$d->pengguna}}</td>
                        <td class="tg-cegc">{{number_format($d->tagihan + $d->diskon)}}</td>
                        <td class="tg-cegc">{{number_format($d->diskon)}}</td>
                        <td class="tg-cegc">{{number_format($d->tagihan)}}</td>
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                    <tr>
                        <td class="tg-vbo4" style="text-align:center;" colspan="2">Total</td>
                        <td class="tg-vbo4" style="text-align:center;">{{number_format($ttlRekapAirBersih[0])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekapAirBersih[5] + $ttlRekapAirBersih[8])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekapAirBersih[8])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekapAirBersih[5])}}</td>
                    </tr>
                </tbody>
            </table>
        </main>
        @else
        <div style="page-break-before:always"></div>
        @foreach($rincianAirBersih as $data)
        <div>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th colspan="8" style="border-style:none;">
                                <h3 style="text-align:center;">RINCIAN DISKON AIR BERSIH<br>PEMAKAIAN {{$bulan}}<br>{{$data[0]}}</h3>
                            </th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv">No.</th>
                            <th class="tg-r8fv">Kontrol</th>
                            <th class="tg-r8fv">Pengguna</th>
                            <th class="tg-r8fv">Tagihan</th>
                            <th class="tg-r8fv">Diskon</th>
                            <th class="tg-r8fv">Jml Bayar</th>
                            <th class="tg-r8fv">Ket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach($data[1] as $d)
                        <tr>
                            <td class="tg-cegc">{{$no}}</td>
                            <td class="tg-cegc">{{$d->kontrol}}</td>
                            <td class="tg-cegc" style="text-align:left;">{{$d->pengguna}}</td>
                            <td class="tg-cegc">{{number_format($d->tagihan + $d->diskon)}}</td>
                            <td class="tg-cegc">{{number_format($d->diskon)}}</td>
                            <td class="tg-cegc">{{number_format($d->tagihan)}}</td>
                            <td class="tg-cegc" style="white-space:normal; word-break:break-word;">{{$d->lokasi}}</td>
                        </tr>
                        <?php $no++; ?>
                        @endforeach
                        @foreach($data[2] as $d)
                        <tr>
                            <td class="tg-vbo4" style="text-align:center;" colspan="3">Total</td>
                            <td class="tg-8m6k">{{number_format($d->tagihan + $d->diskon)}}</td>
                            <td class="tg-8m6k">{{number_format($d->diskon)}}</td>
                            <td class="tg-8m6k">{{number_format($d->tagihan)}}</td>
                            <td class="tg-8m6k"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </main>
            <!-- <div style="page-break-after:always"></div> -->
        </div>
        @endforeach
        @endif
        @endfor
    </body>
</html>