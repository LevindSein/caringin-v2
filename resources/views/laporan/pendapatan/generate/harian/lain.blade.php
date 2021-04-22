<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Pendapatan Lainnya | BP3C</title>
        <link rel="stylesheet" href="{{asset('css/keuangan/arsip.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>

    <body onload="window.print()">
        <div>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th colspan="5" style="border-style:none;">
                                <h2 style="text-align:center;">Pendapatan Lainnya<br>{{$tanggal}}</h2>
                            </th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv">No</th>
                            <th class="tg-r8fv">Kasir</th>
                            <th class="tg-r8fv">Rek</th>
                            <th class="tg-r8fv">Kontrol</th>
                            <th class="tg-r8fv">Jumlah</th>
                        </tr>
                        <tr>
                            <th class="tg-g255" colspan="5" style="height:1px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $jumlah = 0;
                        $i = 1;
                        ?>
                        @foreach($dataset as $r)
                            <tr>
                                <td class="tg-r8fz">{{$i}}</td>
                                <td class="tg-r8fz">{{substr($r->nama,0,15)}}</td>
                                <td class="tg-r8fz">{{date('m/Y', strtotime($r->tgl_tagihan))}}</td>
                                <td class="tg-r8fz">{{$r->kd_kontrol}}</td>
                                <td class="tg-r8fx">{{number_format($r->byr_lain)}}</td>
                            </tr>
                            <?php 
                            $jumlah = $jumlah + $r->byr_lain;
                            $i++;
                            ?>
                        @endforeach
                    </tbody>
                    <tr>
                        <td class="tg-g255" colspan="5" style="height:1px"></td>
                    </tr>
                    <tr>
                        <td class="tg-r8fz" colspan="4"><b>Total<b></td>
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