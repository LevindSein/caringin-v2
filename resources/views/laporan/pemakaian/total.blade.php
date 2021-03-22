<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Total Pemakaian | BP3C</title>
        <link rel="stylesheet" href="{{asset('css/laporan/pemakaian/style-pemakaian.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>
    <body onload="window.print()">
        @for($i=1;$i<=2;$i++)
        @if($i == 1)
        <main>
            <table class="tg">
                <thead>
                    <tr>
                        <th colspan="8" style="border-style:none;">
                            <h3 style="text-align:center;">REKAP TOTAL PEMAKAIAN FASILITAS<br>{{$bln}}</h3>
                        </th>
                    </tr>
                    <tr>
                        <th class="tg-r8fv">No.</th>
                        <th class="tg-r8fv">Blok</th>
                        <th class="tg-r8fv">Pengguna</th>
                        <th class="tg-r8fv">Listrik</th>
                        <th class="tg-r8fv">Air Bersih</th>
                        <th class="tg-r8fv">Keamanan IPK</th>
                        <th class="tg-r8fv">Kebersihan</th>
                        <th class="tg-r8fv">Tagihan</th>
                        <!-- <th class="tg-r8fv">Realisasi</th> -->
                        <!-- <th class="tg-r8fv">Selisih</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach($rekap as $d)
                    <tr>
                        <td class="tg-cegc">{{$no}}</td>
                        <td class="tg-cegc">{{$d->blok}}</td>
                        <td class="tg-cegc">{{$d->pengguna}}</td>
                        <td class="tg-g25h">{{number_format($d->listrik)}}</td>
                        <td class="tg-g25h">{{number_format($d->airbersih)}}</td>
                        <td class="tg-g25h">{{number_format($d->keamananipk)}}</td>
                        <td class="tg-g25h">{{number_format($d->kebersihan)}}</td>
                        <td class="tg-g25h">{{number_format($d->tagihan)}}</td>
                        <!-- <td class="tg-g25h">{{number_format($d->realisasi)}}</td> -->
                        <!-- <td class="tg-g25h">{{number_format($d->selisih)}}</td> -->
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                </tbody>
                <tr>
                    <td class="tg-vbo4" style="text-align:center;" colspan="2">Total</td>
                    <td class="tg-vbo4" style="text-align:center;">{{number_format($ttlRekap[0])}}</td>
                    <td class="tg-8m6k">Rp. {{number_format($ttlRekap[1])}}</td>
                    <td class="tg-8m6k">Rp. {{number_format($ttlRekap[2])}}</td>
                    <td class="tg-8m6k">Rp. {{number_format($ttlRekap[3])}}</td>
                    <td class="tg-8m6k">Rp. {{number_format($ttlRekap[4])}}</td>
                    <td class="tg-8m6k">Rp. {{number_format($ttlRekap[5])}}</td>
                    <!-- <td class="tg-8m6k">Rp. {{number_format($ttlRekap[6])}}</td> -->
                    <!-- <td class="tg-8m6k">Rp. {{number_format($ttlRekap[7])}}</td> -->
                </tr>
            </table>
        </main>
        @else
        <div style="page-break-before:always"></div>
        @foreach($rincian as $data)
        <div>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th colspan="10" style="border-style:none;">
                                <h3 style="text-align:center;">RINCIAN PEMAKAIAN FASILITAS<br>{{$bln}}<br>{{$data[0]}}</h3>
                            </th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv">No.</th>
                            <th class="tg-r8fv">Kontrol</th>
                            <th class="tg-r8fv">Pengguna</th>
                            <th class="tg-r8fv" style="width:10%">Alamat</th>
                            <th class="tg-r8fv">Jml.Unit</th>
                            <th class="tg-r8fv">Listrik</th>
                            <th class="tg-r8fv">Air Bersih</th>
                            <th class="tg-r8fv">Keamanan IPK</th>
                            <th class="tg-r8fv">Kebersihan</th>
                            <th class="tg-r8fv">Tagihan</th>
                            <!-- <th class="tg-r8fv">Realisasi</th> -->
                            <!-- <th class="tg-r8fv">Selisih</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach($data[1] as $d)
                        <tr>
                            <td class="tg-cegc">{{$no}}</td>
                            <td class="tg-cegc">{{$d->kontrol}}</td>
                            <td class="tg-g25h" style="text-align:left;">{{$d->pengguna}}</td>
                            <td class="tg-cegc" style="white-space:normal; word-break:break-word;">{{$d->nomor}}</td>
                            <td class="tg-g25h">{{number_format($d->alamat)}}</td>
                            <td class="tg-g25h">{{number_format($d->listrik)}}</td>
                            <td class="tg-g25h">{{number_format($d->airbersih)}}</td>
                            <td class="tg-g25h">{{number_format($d->keamananipk)}}</td>
                            <td class="tg-g25h">{{number_format($d->kebersihan)}}</td>
                            <td class="tg-g25h">{{number_format($d->tagihan)}}</td>
                            <!-- <td class="tg-g25h">{{number_format($d->realisasi)}}</td> -->
                            <!-- <td class="tg-g25h">{{number_format($d->selisih)}}</td> -->
                        </tr>
                        <?php $no++; ?>
                        @endforeach
                    </tbody>
                    @foreach($data[2] as $d)
                    <tr>
                        <td class="tg-vbo4" style="text-align:center;" colspan="4">Total</td>
                        <td class="tg-8m6k">{{number_format($d->alamat)}}</td>
                        <td class="tg-8m6k">{{number_format($d->listrik)}}</td>
                        <td class="tg-8m6k">{{number_format($d->airbersih)}}</td>
                        <td class="tg-8m6k">{{number_format($d->keamananipk)}}</td>
                        <td class="tg-8m6k">{{number_format($d->kebersihan)}}</td>
                        <td class="tg-8m6k">{{number_format($d->tagihan)}}</td>
                        <!-- <td class="tg-8m6k">{{number_format($d->realisasi)}}</td> -->
                        <!-- <td class="tg-8m6k">{{number_format($d->selisih)}}</td> -->
                    </tr>
                    @endforeach
                </table>
            </main>
            <div style="page-break-after:always"></div>
        </div>
        @endforeach
        @endif
        @endfor
    </body>
</html>