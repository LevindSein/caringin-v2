<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Pemakaian Listrik | BP3C</title>
        <link rel="stylesheet" href="{{asset('css/laporan/pemakaian/style-pemakaian.css')}}" media="all"/>
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
                        <th colspan="8" style="border-style:none;">
                            <h3 style="text-align:center;">REKAP PEMAKAIAN LISTRIK<br>{{$bln}}</h3>
                        </th>
                    </tr>
                    <tr>
                        <th class="tg-r8fv">No.</th>
                        <th class="tg-r8fv">Blok</th>
                        <th class="tg-r8fv">Pengguna</th>
                        <th class="tg-r8fv">Daya</th>
                        <th class="tg-r8fv">Pakai</th>
                        <th class="tg-r8fv">Beban</th>
                        <th class="tg-r8fv">BPJU</th>
                        <th class="tg-r8fv">Tagihan</th>
                        <!-- <th class="tg-r8fv">Realisasi</th>
                        <th class="tg-r8fv">Selisih</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach($rekap as $d)
                    <tr>
                        <td class="tg-cegc">{{$no}}</td>
                        <td class="tg-cegc">{{$d->blok}}</td>
                        <td class="tg-cegc">{{$d->pengguna}}</td>
                        <td class="tg-cegc">{{number_format($d->daya)}}</td>
                        <td class="tg-cegc">{{number_format($d->pakai)}}</td>
                        <td class="tg-cegc">{{number_format($d->beban)}}</td>
                        <td class="tg-cegc">{{number_format($d->bpju)}}</td>
                        <td class="tg-cegc">{{number_format($d->tagihan)}}</td>
                        <!-- <td class="tg-cegc">{{number_format($d->realisasi)}}</td>
                        <td class="tg-cegc">{{number_format($d->selisih)}}</td> -->
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                    <tr>
                        <td class="tg-vbo4" style="text-align:center;" colspan="2">Total</td>
                        <td class="tg-vbo4" style="text-align:center;">{{number_format($ttlRekap[0])}}</td>
                        <td class="tg-8m6k">{{number_format($ttlRekap[1])}} W</td>
                        <td class="tg-8m6k">{{number_format($ttlRekap[2])}} kWh</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekap[3])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekap[4])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekap[5])}}</td>
                        <!-- <td class="tg-8m6k">Rp. {{number_format($ttlRekap[6])}}</td>
                        <td class="tg-8m6k">Rp. {{number_format($ttlRekap[7])}}</td> -->
                    </tr>
                </tbody>
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
                            <th colspan="14" style="border-style:none;">
                                <h3 style="text-align:center;">RINCIAN PEMAKAIAN LISTRIK<br>{{$bln}}<br>{{$data[0]}}</h3>
                            </th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv">No.</th>
                            <th class="tg-r8fv">Kontrol</th>
                            <th class="tg-r8fv">Pengguna</th>
                            <th class="tg-r8fv">Daya</th>
                            <th class="tg-r8fv">M.Lalu</th>
                            <th class="tg-r8fv">M.Baru</th>
                            <th class="tg-r8fv">Pakai</th>
                            <th class="tg-r8fv">Rek.Min</th>
                            <th class="tg-r8fv">B.Blok1</th>
                            <th class="tg-r8fv">B.Blok2</th>
                            <th class="tg-r8fv">B.Beban</th>
                            <th class="tg-r8fv">BPJU</th>
                            <th class="tg-r8fv">Tagihan</th>
                            <th class="tg-r8fv" style="width:10%">Ket</th>
                            <!-- <th class="tg-r8fv">Realisasi</th>
                            <th class="tg-r8fv">Selisih</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach($data[1] as $d)
                        <tr>
                            <td class="tg-cegc">{{$no}}</td>
                            <td class="tg-cegc">{{$d->kontrol}}</td>
                            <td class="tg-cegc" style="text-align:left;">{{$d->pengguna}}</td>
                            <td class="tg-cegc">{{number_format($d->daya)}}</td>
                            <td class="tg-cegc">{{number_format($d->lalu)}}</td>
                            <td class="tg-cegc">{{number_format($d->baru)}}</td>
                            <td class="tg-cegc">{{number_format($d->pakai)}}</td>
                            <td class="tg-cegc">{{number_format($d->rekmin)}}</td>
                            <td class="tg-cegc">{{number_format($d->blok1)}}</td>
                            <td class="tg-cegc">{{number_format($d->blok2)}}</td>
                            <td class="tg-cegc">{{number_format($d->beban)}}</td>
                            <td class="tg-cegc">{{number_format($d->bpju)}}</td>
                            <td class="tg-cegc">{{number_format($d->tagihan)}}</td>
                            <td class="tg-cegc" style="white-space:normal; word-break:break-word;">{{$d->lokasi}}</td>
                            <!-- <td class="tg-cegc">{{number_format($d->realisasi)}}</td>
                            <td class="tg-cegc">{{number_format($d->selisih)}}</td> -->
                        </tr>
                        <?php $no++; ?>
                        @endforeach
                        @foreach($data[2] as $d)
                        <tr>
                            <td class="tg-vbo4" style="text-align:center;" colspan="3">Total</td>
                            <td class="tg-8m6k">{{number_format($d->daya)}}</td>
                            <td class="tg-8m6k" colspan="2"></td>
                            <td class="tg-8m6k">{{number_format($d->pakai)}}</td>
                            <td class="tg-8m6k" colspan="3"></td>
                            <td class="tg-8m6k">{{number_format($d->beban)}}</td>
                            <td class="tg-8m6k">{{number_format($d->bpju)}}</td>
                            <td class="tg-8m6k">{{number_format($d->tagihan)}}</td>
                            <td class="tg-8m6k"></td>
                            <!-- <td class="tg-8m6k">{{number_format($d->realisasi)}}</td>
                            <td class="tg-8m6k">{{number_format($d->selisih)}}</td> -->
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