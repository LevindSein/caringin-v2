<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Pendapatan Keamanan IPK | BP3C</title>
        <link rel="stylesheet" href="{{asset('css/keuangan/arsip.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>

    <body onload="window.print()">
        <div>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th colspan="7" style="border-style:none;">
                                <h2 style="text-align:center;">Pendapatan Keamanan & IPK<br>{{$tahun}}</h2>
                            </th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv" rowspan="2">No</th>
                            <th class="tg-r8fv" rowspan="2">Kasir</th>
                            <th class="tg-r8fv" rowspan="2">Rek</th>
                            <th class="tg-r8fv" rowspan="2">Kontrol</th>
                            <th class="tg-r8fv" colspan="2">Keamanan & IPK</th>
                            <th class="tg-r8fv" rowspan="2">Jumlah</th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv">Keamanan</th>
                            <th class="tg-r8fv">IPK</th>
                        </tr>
                        <tr>
                            <th class="tg-g255" colspan="7" style="height:1px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $keamanan = 0;
                        $ipk = 0;
                        $jumlah = 0;
                        $i = 1;
                        ?>
                        @foreach($dataset as $r)
                            <tr>
                                <td class="tg-r8fz">{{$i}}</td>
                                <td class="tg-r8fz">{{substr($r->nama,0,15)}}</td>
                                <td class="tg-r8fz">{{date('m/Y', strtotime($r->tgl_tagihan))}}</td>
                                <td class="tg-r8fz">{{$r->kd_kontrol}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_keamanan)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_ipk)}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_keamananipk)}}</td>
                            </tr>
                            <?php 
                            $keamanan = $keamanan + ($r->byr_keamanan);
                            $ipk = $ipk + ($r->byr_ipk);
                            $jumlah = $jumlah + $r->byr_keamananipk;
                            $i++;
                            ?>
                        @endforeach
                    </tbody>
                    <tr>
                        <td class="tg-g255" colspan="7" style="height:1px"></td>
                    </tr>
                    <tr>
                        <td class="tg-r8fz" colspan="4"><b>Total<b></td>
                        <td class="tg-r8fx"><b>{{number_format($keamanan)}}</b></td>
                        <td class="tg-r8fx"><b>{{number_format($ipk)}}</b></td>
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