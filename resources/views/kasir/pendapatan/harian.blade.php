<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Rekap Pendapatan Harian | BP3C</title>
        <link rel="stylesheet" href="{{asset('css/kasir/bulanan/penerimaan.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>

    <body onload="window.print()">
        <div>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th colspan="5" style="border-style:none;">
                                <h3 style="text-align:center;">Rekap Pendapatan<br>{{$tanggal}}</h3>
                                <div id="project" style="margin-bottom:1rem;">
                                    <div>
                                        <span>Nama Perekap</span>:
                                        {{Session::get('username')}}</div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv" rowspan="2">No</th>
                            <th class="tg-r8fv" rowspan="2">Kasir</th>
                            <th class="tg-r8fv" colspan="2">Tagihan Rutin</th>
                            <th class="tg-r8fv" rowspan="2">Jumlah</th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv">Bulanan</th>
                            <th class="tg-r8fv">Harian</th>
                        </tr>
                        <tr>
                            <th class="tg-g255" colspan="5" style="height:1px"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $i = 1; 
                    $harian = 0;
                    $bulanan = 0;
                    $total = 0;?>
                    @foreach($dataset as $d)
                    @if($d['jumlah'] != 0)
                        <tr>
                            <td class="tg-r8fz"><b>{{$i}}</b></td>
                            <td class="tg-r8fz">{{$d['nama']}}</td>
                            <td class="tg-r8fx">{{number_format($d['bulanan'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['harian'])}}</td>
                            <td class="tg-r8fx">{{number_format($d['jumlah'])}}</td>
                        </tr>
                    <?php 
                    $i++;
                    $harian = $harian + $d['harian'];
                    $bulanan = $bulanan + $d['bulanan'];
                    $total = $total + $d['jumlah'];
                    ?>
                    @endif
                    @endforeach
                    </tbody>
                    <tr>
                        <td class="tg-g255" colspan="8" style="height:1px"></td>
                    </tr>
                    <tr>
                        <td class="tg-r8fz" colspan="2"><b>Total<b></td>
                        <td class="tg-r8fx"><b>Rp. {{number_format($bulanan)}}</b></td>
                        <td class="tg-r8fx"><b>Rp. {{number_format($harian)}}</b></td>
                        <td class="tg-r8fx"><b>Rp. {{number_format($total)}}</b></td>
                    </tr>
                </table>
            </main>
        </div>
        <div id="notices">
            <div style="text-align:right;">
                <b>Bandung, {{$cetak}}</b>
            </div>
            <br><br><br><br><br>
            <div class="notice" style="text-align:right;">{{Session::get('username')}}</div>
        </div>
        <div style="page-break-before:always">
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th colspan="13" style="border-style:none;">
                                <h3 style="text-align:center;">Rincian Pendapatan Tagihan Rutin Bulanan<br>{{$tanggal}}</h3>
                                <div id="project" style="margin-bottom:1rem;">
                                    <div>
                                        <span>Nama Perekap</span>:
                                        {{Session::get('username')}}</div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv" rowspan="3">No</th>
                            <th class="tg-r8fv" rowspan="3">Kasir</th>
                            <th class="tg-r8fv" rowspan="3">Rek</th>
                            <th class="tg-r8fv" rowspan="3">Kontrol</th>
                            <th class="tg-r8fv" colspan="8">Penerimaan Tunai</th>
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
                            <th class="tg-g255" colspan="13" style="height:1px"></th>
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
                        $i = 1;
                        ?>
                        @foreach($rincianbulan as $r)
                            <tr>
                                <td class="tg-r8fz">{{$i}}</td>
                                <td class="tg-r8fy">{{substr($r->nama,0,6)}}</td>
                                <td class="tg-r8fz">{{date('m/Y', strtotime($r->tgl_tagihan))}}</td>
                                <td class="tg-r8fz">{{$r->kd_kontrol}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_listrik - $r->byr_denlistrik)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_airbersih - $r->byr_denairbersih)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_keamananipk)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_kebersihan)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_denlistrik)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_denairbersih)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_airkotor)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_lain)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_listrik + $r->byr_airbersih + $r->byr_keamananipk + $r->byr_kebersihan + $r->byr_airkotor + $r->byr_lain)}}</td>
                            </tr>
                            <?php 
                            $listrik = $listrik + $r->byr_listrik - $r->byr_denlistrik;
                            $denlistrik = $denlistrik + $r->byr_denlistrik;
                            $airbersih = $airbersih + $r->byr_airbersih - $r->byr_denairbersih;
                            $denairbersih = $denairbersih + $r->byr_denairbersih;
                            $keamananipk = $keamananipk + $r->byr_keamananipk;
                            $kebersihan = $kebersihan + $r->byr_kebersihan;
                            $airkotor = $airkotor + $r->byr_airkotor;
                            $lain = $lain + $r->byr_lain;
                            $jumlah = $jumlah + $r->byr_listrik + $r->byr_airbersih + $r->byr_keamananipk + $r->byr_kebersihan + $r->byr_airkotor + $r->byr_lain;
                            $i++;
                            ?>
                        @endforeach
                    </tbody>
                    <tr>
                        <td class="tg-g255" colspan="13" style="height:1px"></td>
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
                    </tr>
                </table>
            </main>
        </div>
        <div id="notices">
            <div style="text-align:right;">
                <b>Bandung, {{$cetak}}</b>
            </div>
            <br><br><br><br><br>
            <div class="notice" style="text-align:right;">{{Session::get('username')}}</div>
        </div>
    </body>
</html>