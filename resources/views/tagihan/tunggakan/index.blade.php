<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Data Tunggakan</title>
        <link rel="icon" href="{{asset('img/logo.png')}}">
        <link rel="stylesheet" href="{{asset('css/tagihan/print.css')}}" media="all" />
    </head>

    <body onload="window.print()">
        <main>
            <table class="tg">
                <thead>
                    <tr>
                        <th colspan="8" style="border-style:none;">
                            <h2>DATA TUNGGAKAN</h2>
                            <h3>{{$blok}}</h3>
                            <h4>{{$dari}} s.d {{$ke}}</h4>
                        </th>
                    </tr>
                    <tr>
                        <th class="tg-r8fv">No.</th>
                        <th class="tg-r8fv">Periode</th>
                        <th class="tg-r8fv">Kontrol</th>
                        <th class="tg-r8fv">Tagihan</th>
                    </tr>
                    <?php $x=1; $total=0; use App\Models\IndoDate; ?>
                    @foreach($data as $d)
                    <tbody>
                        <tr>
                            <td class="tg-cegc">{{$x}}</td>
                            <td class="tg-g25h" style="text-align:left;">{{IndoDate::bulan($d->bln_tagihan, ' ')}}</td>
                            <td class="tg-g25h" style="text-align:left;">{{$d->kd_kontrol}}</td>
                            <td class="tg-g25h" style="text-align:right;">{{number_format($d->sel_tagihan)}}</td>
                        </tr>
                    </tbody>
                    <?php $x++; $total += $d->sel_tagihan ?>
                    @endforeach
                    <tr>
                        <td class="tg-r8fv" colspan="3">JUMLAH</td>
                        <td class="tg-r8fv">{{number_format($total)}}</td>
                    </tr>
                </thead>
            </table>
        </main>
    </body>
</html>
