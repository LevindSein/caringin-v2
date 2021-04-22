<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Pendapatan Semua Fasilitas | BP3C</title>
        <link rel="stylesheet" href="{{asset('css/laporan/pendapatan/tagihan.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>

    <body onload="window.print()">
        <div>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th colspan="15" style="border-style:none;">
                                <h2 style="text-align:center;">Pendapatan Semua Fasilitas<br>{{$bulan}}</h2>
                            </th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv" rowspan="2">No</th>
                            <th class="tg-r8fv" rowspan="2">Kasir</th>
                            <th class="tg-r8fv" rowspan="2">Rek</th>
                            <th class="tg-r8fv" rowspan="2">Kontrol</th>
                            <th class="tg-r8fv" colspan="2">Listrik</th>
                            <th class="tg-r8fv" colspan="2">Air Bersih</th>
                            <th class="tg-r8fv" colspan="2">K.aman IPK</th>
                            <th class="tg-r8fv" colspan="2">Kebersihan</th>
                            <th class="tg-r8fv" rowspan="2">Air Kotor</th>
                            <th class="tg-r8fv" rowspan="2">Lainnya</th>
                            <th class="tg-r8fv" rowspan="2">Jumlah</th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv">Rea</th>
                            <th class="tg-r8fv">Den</th>
                            <th class="tg-r8fv">Rea</th>
                            <th class="tg-r8fv">Den</th>
                            <th class="tg-r8fv">K.aman</th>
                            <th class="tg-r8fv">IPK</th>
                            <th class="tg-r8fv">90%</th>
                            <th class="tg-r8fv">10%</th>
                        </tr>
                        <tr>
                            <th class="tg-g255" colspan="15" style="height:1px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $listrik = 0;
                        $denlistrik = 0;
                        $airbersih = 0;
                        $denairbersih = 0;
                        $keamanan = 0;
                        $ipk = 0;
                        $kebersihan90 = 0;
                        $kebersihan10 = 0;
                        $airkotor = 0;
                        $lain = 0;
                        $jumlah = 0;
                        $i = 1;
                        ?>
                        @foreach($dataset as $r)
                            <tr>
                                <td class="tg-r8fz">{{$i}}</td>
                                <td class="tg-r8fz">{{substr($r->nama,0,8)}}</td>
                                <td class="tg-r8fz">{{date('m/Y', strtotime($r->tgl_tagihan))}}</td>
                                <td class="tg-r8fz">{{$r->kd_kontrol}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_listrik - $r->byr_denlistrik)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_denlistrik)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_airbersih - $r->byr_denairbersih)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_denairbersih)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_keamanan)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_ipk)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_kebersihan * (90 / 100))}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_kebersihan * (10 / 100))}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_airkotor)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_lain)}}</td>
                                <td class="tg-r8fx">{{number_format($r->realisasi)}}</td>
                            </tr>
                            <?php 
                            $listrik = $listrik + $r->byr_listrik - $r->byr_denlistrik;
                            $denlistrik = $denlistrik + $r->byr_denlistrik;
                            $airbersih = $airbersih + $r->byr_airbersih - $r->byr_denairbersih;
                            $denairbersih = $denairbersih + $r->byr_denairbersih;
                            $keamanan = $keamanan + ($r->byr_keamanan);
                            $ipk = $ipk + ($r->byr_ipk);
                            $kebersihan90 = $kebersihan90 + ($r->byr_kebersihan * (90 / 100));
                            $kebersihan10 = $kebersihan10 + ($r->byr_kebersihan * (10 / 100));
                            $airkotor = $airkotor + $r->byr_airkotor;
                            $lain = $lain + $r->byr_lain;
                            $jumlah = $jumlah + $r->realisasi;
                            $i++;
                            ?>
                        @endforeach
                    </tbody>
                    <tr>
                        <td class="tg-g255" colspan="15" style="height:1px"></td>
                    </tr>
                    <tr>
                        <td class="tg-r8fz" colspan="4"><b>Total<b></td>
                        <td class="tg-r8fx"><b>{{number_format($listrik)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($denlistrik)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($airbersih)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($denairbersih)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($keamanan)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($ipk)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($kebersihan90)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($kebersihan10)}}</b></td>
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