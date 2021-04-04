<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Rekap Pendapatan Bulanan | Keuangan</title>
        <link rel="stylesheet" href="{{asset('css/penerimaan.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>

    <body onload="window.print()">
        <div>
            <header class="clearfix">
                <h2 style="text-align:center;">Rekap Pendapatan<br>{{$bulan}}</h2>
            </header>
            <main>
                <table class="tg">
                    <thead>
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
                    $bulanan = $bulanan + $d['bulanan'];
                    $harian = $harian + $d['harian'];
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
            <header class="clearfix">
                <h2 style="text-align:center;">Rincian Pendapatan Tagihan Rutin Bulanan<br>{{$bulan}}</h2>
            </header>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th class="tg-r8fv" rowspan="3">No</th>
                            <th class="tg-r8fv" rowspan="3">Kasir</th>
                            <th class="tg-r8fv" rowspan="3">Tgl.Setor</th>
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
                            <th class="tg-g255" colspan="12" style="height:1px"></th>
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
                            @foreach($r as $d)
                            <tr>
                                <td class="tg-r8fz">{{$i}}</td>
                                <td class="tg-r8fy">{{substr($d['nama'],0,6)}}</td>
                                <td class="tg-r8fz">{{date('d-m-Y',strtotime($d['setor']))}}</td>
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
                            <?php 
                            $listrik = $listrik + $d['listrik'];
                            $denlistrik = $denlistrik + $d['denlistrik'];
                            $airbersih = $airbersih + $d['airbersih'];
                            $denairbersih = $denairbersih + $d['denairbersih'];
                            $keamananipk = $keamananipk + $d['keamananipk'];
                            $kebersihan = $kebersihan + $d['kebersihan'];
                            $airkotor = $airkotor + $d['airkotor'];
                            $lain = $lain + $d['lain'];
                            $jumlah = $jumlah + $d['jumlah'];
                            $i++;
                            ?>
                            @endforeach
                        @endforeach
                    </tbody>
                    <tr>
                        <td class="tg-g255" colspan="12" style="height:1px"></td>
                    </tr>
                    <tr>
                        <td class="tg-r8fz" colspan="3"><b>Total<b></td>
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