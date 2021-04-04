<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Sisa Tagihan | BP3C</title>
        <link rel="stylesheet" href="{{asset('css/kasir/bulanan/penerimaan.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>

    <style>
        @media print {
            tr.page-break  { display: block; page-break-before: always; }
        }   
    </style>

    <body onload="window.print()">
        <div>
            <header class="clearfix">
                <h2 style="text-align:center;">Rekap Sisa Tagihan<br>{{$bulan}}</h2>
            </header>
            <main>
            <table class="tg">
                    <thead>
                        <tr>
                            <th class="tg-r8fv" rowspan="3">Blok</th>
                            <th class="tg-r8fv" rowspan="3">Rek</th>
                            <th class="tg-r8fv" colspan="8">Sisa Tagihan</th>
                            <th class="tg-r8fv" rowspan="3">Jumlah</th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv" rowspan="2">Listrik</th>
                            <th class="tg-r8fv" rowspan="2">Air Brs</th>
                            <th class="tg-ccvv" rowspan="2" style="vertical-align:middle;">K.aman</th>
                            <th class="tg-ccvv" rowspan="2" style="vertical-align:middle;">K.bersih</th>
                            <th class="tg-r8fv" colspan="2">Denda</th>
                            <th class="tg-r8fv" rowspan="2">Air Ktr</th>
                            <th class="tg-r8fv" rowspan="2">Lain<sup>2</sup></th>
                        </tr>
                        <tr>
                            <th class="tg-ccvv">Listrik</th>
                            <th class="tg-ccvv">Air Brs</th>
                        </tr>
                        <tr>
                            <th class="tg-g255" colspan="11" style="height:1px"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rekap as $d)
                        <tr>
                            <td class="tg-r8fz"><b>{{$d['blok']}}</b></td>
                            <td class="tg-r8fx">{{$d['rek']}}</td>
                            <td class="tg-r8fx">{{number_format($d['listrik'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['airbersih'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['keamananipk'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['kebersihan'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['denlistrik'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['denairbersih'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['airkotor'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['lain'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['jumlah'])}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tr>
                        <td class="tg-g255" colspan="11" style="height:1px"></td>
                    </tr>
                    <tr>
                        <td class="tg-r8fz"><b>Total<b></td>
                        <td class="tg-r8fx"><b>{{$t_rekap['rek']}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($t_rekap['listrik'])}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($t_rekap['airbersih'])}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($t_rekap['keamananipk'])}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($t_rekap['kebersihan'])}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($t_rekap['denlistrik'])}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($t_rekap['denairbersih'])}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($t_rekap['airkotor'])}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($t_rekap['lain'])}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($t_rekap['jumlah'])}}</b></td>
                    </tr>
                </table>
            </main>
        </div>
        <div style="page-break-before:always">
            <header class="clearfix">
                <h2 style="text-align:center;">Rincian Sisa Tagihan<br>{{$bulan}}</h2>
            </header>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th class="tg-r8fv" rowspan="3">No</th>
                            <th class="tg-r8fv" rowspan="3">Kontrol</th>
                            <th class="tg-r8fv" rowspan="3">Nama</th>
                            <th class="tg-r8fv" colspan="8">Sisa Tagihan</th>
                            <th class="tg-r8fv" rowspan="3">Jumlah</th>
                            <th class="tg-r8fv" rowspan="3">Keterangan</th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv" rowspan="2">Listrik</th>
                            <th class="tg-r8fv" rowspan="2">Air Brs</th>
                            <th class="tg-ccvv" rowspan="2" style="vertical-align:middle;">K.aman</th>
                            <th class="tg-ccvv" rowspan="2" style="vertical-align:middle;">K.bersih</th>
                            <th class="tg-r8fv" colspan="2">Denda</th>
                            <th class="tg-r8fv" rowspan="2">Air Ktr</th>
                            <th class="tg-r8fv" rowspan="2">Lain<sup>2</sup></th>
                        </tr>
                        <tr>
                            <th class="tg-ccvv">Listrik</th>
                            <th class="tg-ccvv">Air Brs</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    ?>
                    @foreach($rincian as $r)
                        <tr>
                            <td class="tg-r8fz">{{$i}}</td>
                            <td class="tg-r8fz">{{$r['kode']}}</td>
                            <td class="tg-r8fy">{{substr($r['pengguna'],0,13)}}</td>
                            <td class="tg-r8fx">{{number_format($r['listrik'])}}</td>
                            <td class="tg-r8fx">{{number_format($r['airbersih'])}}</td>
                            <td class="tg-r8fx">{{number_format($r['keamananipk'])}}</td>
                            <td class="tg-r8fx">{{number_format($r['kebersihan'])}}</td>
                            <td class="tg-r8fx">{{number_format($r['denlistrik'])}}</td>
                            <td class="tg-r8fx">{{number_format($r['denairbersih'])}}</td>
                            <td class="tg-r8fx">{{number_format($r['airkotor'])}}</td>
                            <td class="tg-r8fx">{{number_format($r['lain'])}}</td>
                            <td class="tg-r8fx">{{number_format($r['jumlah'])}}</td>
                            <td class="tg-r8fy">{{$r['lokasi']}}</td>
                        </tr>
                    <?php
                    $i++;
                    ?>
                    @endforeach
                    </tbody>
                </table>
            </main>
        </div>
    </body>
</html>