@php
    use App\Models\IndoDate;
@endphp

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Data Tunggakan</title>
        <link rel="icon" href="{{asset('img/logo.png')}}">
        <link rel="stylesheet" href="{{asset('css/tagihan/form-tagihan.css')}}" media="all" />
    </head>

    <body onload="window.print()">
        <main>
            <table class="tg">
                <thead>
                    <tr>
                        <th colspan="10" style="border-style:none;">
                            <h2>RINCIAN DATA TUNGGAKAN</h2>
                            <h3>{{$blok}}</h3>
                            <h4>{{$dari}} s.d {{$ke}}</h4>
                        </th>
                    </tr>
                    <tr>
                        <th class="tg-r8fv">No.</th>
                        <th class="tg-r8fv">Periode</th>
                        <th class="tg-r8fv">Kontrol</th>
                        <th class="tg-r8fv">Listrik</th>
                        <th class="tg-r8fv">Air Bersih</th>
                        <th class="tg-r8fv">K.aman IPK</th>
                        <th class="tg-r8fv">Kebersihan</th>
                        <th class="tg-r8fv">Air Kotor</th>
                        <th class="tg-r8fv">Lain</th>
                        <th class="tg-r8fv">Tagihan</th>
                    </tr>
                    <?php
                    $x=1;
                    $listrik=0;
                    $airbersih=0;
                    $keamananipk=0;
                    $kebersihan=0;
                    $airkotor=0;
                    $lain=0;
                    $total=0;
                    ?>
                    @foreach($data as $d)
                    <tbody>
                        <tr>
                            <td class="tg-cegc">{{$x}}</td>
                            <td class="tg-g25h" style="text-align:left;">{{IndoDate::bulan($d->bln_tagihan, ' ')}}</td>
                            <td class="tg-g25h" style="text-align:left;">{{$d->kd_kontrol}}</td>
                            <td class="tg-g25h" style="text-align:right;">{{number_format($d->sel_listrik)}}</td>
                            <td class="tg-g25h" style="text-align:right;">{{number_format($d->sel_airbersih)}}</td>
                            <td class="tg-g25h" style="text-align:right;">{{number_format($d->sel_keamananipk)}}</td>
                            <td class="tg-g25h" style="text-align:right;">{{number_format($d->sel_kebersihan)}}</td>
                            <td class="tg-g25h" style="text-align:right;">{{number_format($d->sel_airkotor)}}</td>
                            <td class="tg-g25h" style="text-align:right;">{{number_format($d->sel_lain)}}</td>
                            <td class="tg-g25h" style="text-align:right;">{{number_format($d->sel_tagihan)}}</td>
                        </tr>
                    </tbody>
                    <?php
                    $x++;
                    $listrik += $d->sel_listrik;
                    $airbersih += $d->sel_airbersih;
                    $keamananipk += $d->sel_keamananipk;
                    $kebersihan += $d->sel_kebersihan;
                    $airkotor += $d->sel_airkotor;
                    $lain += $d->sel_lain;
                    $total += $d->sel_tagihan;
                    ?>
                    @endforeach
                    <tr>
                        <td class="tg-r8fv" colspan="3">JUMLAH</td>
                        <td class="tg-r8fv">{{number_format($listrik)}}</td>
                        <td class="tg-r8fv">{{number_format($airbersih)}}</td>
                        <td class="tg-r8fv">{{number_format($keamananipk)}}</td>
                        <td class="tg-r8fv">{{number_format($kebersihan)}}</td>
                        <td class="tg-r8fv">{{number_format($airkotor)}}</td>
                        <td class="tg-r8fv">{{number_format($lain)}}</td>
                        <td class="tg-r8fv">{{number_format($total)}}</td>
                    </tr>
                </thead>
            </table>
        </main>
    </body>
</html>
