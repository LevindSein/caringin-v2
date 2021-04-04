<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Penerimaan Kasir | BP3C</title>
        <link rel="stylesheet" href="{{asset('css/kasir/bulanan/penerimaan.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>

    <body onload="window.print()">
        <div>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th colspan="14" style="border-style:none;">
                                <h3 style="text-align:center;">Rekap Penerimaan {{$tanggal}}</h3>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2" style="border-style:none;text-align:left;">Nama Kasir</th>
                            <th colspan="12" style="border-style:none;text-align:left;">: {{Session::get('username')}}</th>
                        </tr>
                        <tr>
                            <th colspan="2" style="border-style:none;text-align:left;">Shift Kasir</th>
                            <th colspan="12" style="border-style:none;text-align:left;">: {{$shift}}</th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv" rowspan="3">Blok</th>
                            <th class="tg-r8fv" rowspan="3">Rek</th>
                            <th class="tg-r8fv" colspan="8">Penerimaan Tunai</th>
                            <th class="tg-r8fv" rowspan="3">Jumlah</th>
                            <th class="tg-r8fv" rowspan="3">Disc</th>
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
                            <th class="tg-g255" colspan="12" style="height:1px"></th>
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
                            <td class="tg-r8fx">{{number_format($d['diskon'])}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tr>
                        <td class="tg-g255" colspan="12" style="height:1px"></td>
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
                        <td class="tg-r8fx"><b>{{number_format($t_rekap['diskon'])}}</b></td>
                    </tr>
                </table>
            </main>
        </div>
        <div style="page-break-before:always">
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th colspan="14" style="border-style:none;">
                                <h3 style="text-align:center;">Rincian Penerimaan {{$tanggal}}</h3>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2" style="border-style:none;text-align:left;">Nama Kasir</th>
                            <th colspan="12" style="border-style:none;text-align:left;">: {{Session::get('username')}}</th>
                        </tr>
                        <tr>
                            <th colspan="2" style="border-style:none;text-align:left;">Shift Kasir</th>
                            <th colspan="12" style="border-style:none;text-align:left;">: {{$shift}}</th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv" rowspan="3">No</th>
                            <th class="tg-r8fv" rowspan="3">Rek</th>
                            <th class="tg-r8fv" rowspan="3">Kontrol</th>
                            <th class="tg-r8fv" rowspan="3">Nama</th>
                            <th class="tg-r8fv" colspan="8">Penerimaan Tunai</th>
                            <th class="tg-r8fv" rowspan="3">Jumlah</th>
                            <th class="tg-r8fv" rowspan="3">Disc</th>
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
                            <th class="tg-g255" colspan="14" style="height:1px"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $listrik = 0;
                    $denlistrik = 0;
                    $airbersih = 0;
                    $denairbersih = 0;
                    $keamananipk = 0;
                    $kebersihan = 0;
                    $airkotor = 0;
                    $lain = 0;
                    $jumlah = 0;
                    $diskon = 0;
                    $i = 1;
                    ?>
                    @foreach($rincian as $r)
                        <tr>
                            <td class="tg-r8fz">{{$i}}</td>
                            <td class="tg-r8fz">{{$r['rek']}}</td>
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
                            <td class="tg-r8fx">{{number_format($r['diskon'])}}</td>
                        </tr>
                        <?php 
                        $listrik = $listrik + $r['listrik'];
                        $denlistrik = $denlistrik + $r['denlistrik'];
                        $airbersih = $airbersih + $r['airbersih'];
                        $denairbersih = $denairbersih + $r['denairbersih'];
                        $keamananipk = $keamananipk + $r['keamananipk'];
                        $kebersihan = $kebersihan + $r['kebersihan'];
                        $airkotor = $airkotor + $r['airkotor'];
                        $lain = $lain + $r['lain'];
                        $jumlah = $jumlah + $r['jumlah'];
                        $diskon = $diskon + $r['diskon'];
                        $i++;
                        ?>
                    @endforeach
                    </tbody>
                    <tr>
                        <td class="tg-g255" colspan="14" style="height:1px"></td>
                    </tr>
                    <tr>
                        <td class="tg-r8fz" colspan="4"><b>Total<b></td>
                        <td class="tg-r8fx"><b>{{number_format($listrik)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($airbersih)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($keamananipk)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($kebersihan)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($denlistrik)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($denairbersih)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($airkotor)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($lain)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($jumlah)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($diskon)}}</b></td>
                    </tr>
                </table>
                <div id="notices">
                    <div style="text-align:right;">
                        <b>Bandung, {{$cetak}}</b>
                    </div>
                    <br><br><br><br><br>
                    <div class="notice" style="text-align:right;">{{Session::get('username')}}</div>
                </div>
            </main>
        </div>
    </body>
</html>