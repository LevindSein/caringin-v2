<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Tagihan Keamanan IPK | BP3C</title>
        <link rel="stylesheet" href="{{asset('css/style-pemakaian.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>
    <style type="text/css">
    table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    </style>
    <body onload="window.print()">  
        @for($i=1;$i<=2;$i++)
        @if($i == 1)
        <h2 style="text-align:center;">REKAP TAGIHAN KEAMANAN & IPK<br>{{$bln}}</h2>
        <main>
            <table class="tg">
                <thead>
                    <tr>
                        <th class="tg-r8fv">No.</th>
                        <th class="tg-r8fv">Blok</th>
                        <th class="tg-r8fv">Jml.Unit</th>
                        <th class="tg-r8fv">Tagihan</th>
                        <th class="tg-r8fv">Diskon</th>
                        <th class="tg-r8fv">Jumlah</th>
                        <th class="tg-r8fv">Realisasi</th>
                        <th class="tg-r8fv">Selisih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach($rekap as $d)
                    <tr>
                        <td class="tg-cegc">{{$no}}</td>
                        <td class="tg-cegc">{{$d->blok}}</td>
                        <td class="tg-cegc">{{$d->pengguna}}</td>
                        <td class="tg-g25h">{{number_format($d->subtotal)}}</td>
                        <td class="tg-g25h">{{number_format($d->diskon)}}</td>
                        <td class="tg-g25h">{{number_format($d->tagihan)}}</td>
                        <td class="tg-g25h">{{number_format($d->realisasi)}}</td>
                        <td class="tg-g25h">{{number_format($d->selisih)}}</td>
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                    <tr>
                        <td class="tg-vbo4" style="text-align:center;" colspan="2">Total</td>
                        <td class="tg-vbo4" style="text-align:center;">{{number_format($ttlRekap[0])}}</td>
                        <td class="tg-8m6k">{{number_format($ttlRekap[1])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekap[2])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekap[3])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekap[4])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekap[5])}}</td>
                    </tr>
                </tbody>
            </table>
        </main>
        @else
        <h2 style="text-align:center;page-break-before:always">RINCIAN TAGIHAN KEAMANAN & IPK<br>{{$bln}}</h2>
        @foreach($rincian as $data)
        <div>
            <h3>{{$data[0]}}</h3>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th class="tg-r8fv">No.</th>
                            <th class="tg-r8fv">Kontrol</th>
                            <th class="tg-r8fv">Pengguna</th>
                            <th class="tg-r8fv" style="width:18%">Alamat</th>
                            <th class="tg-r8fv">Jml.Unit</th>
                            <th class="tg-r8fv">Tagihan</th>
                            <th class="tg-r8fv">Diskon</th>
                            <th class="tg-r8fv">Jumlah</th>
                            <th class="tg-r8fv">Realisasi</th>
                            <th class="tg-r8fv">Selisih</th>
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
                            <td class="tg-cegc">{{number_format($d->realisasi)}}</td>
                            <td class="tg-cegc">{{number_format($d->selisih)}}</td>
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
                            <td class="tg-8m6k">{{number_format($d->realisasi)}}</td>
                            <td class="tg-8m6k">{{number_format($d->selisih)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </main>
            <div style="page-break-after:always"></div>
        </div>
        @endforeach
        @endif
        @endfor
    </body>
</html>