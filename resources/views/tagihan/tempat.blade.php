<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Form Pendataan {{$status}} | BP3C</title>
        <link rel="stylesheet" href="{{asset('css/tagihan/form-tagihan.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>
    <style type="text/css">
    table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    </style>
    <body onload="window.print()">
        <main>
            <table class="tg">
                <thead>
                    <tr>
                        <th colspan="14" style="border-style:none;">
                            <h3 style="text-align:center;">REKAP TAGIHAN TEMPAT USAHA {{strtoupper($status)}}<br>{{$now}}</h3>
                        </th>
                    </tr>
                    <tr>
                        <th class="tg-r8fv" style="width:4%">Kontrol</th>
                        <th class="tg-r8fv">Jan</th>
                        <th class="tg-r8fv">Feb</th>
                        <th class="tg-r8fv">Mar</th>
                        <th class="tg-r8fv">Apr</th>
                        <th class="tg-r8fv">Mei</th>
                        <th class="tg-r8fv">Jun</th>
                        <th class="tg-r8fv">Jul</th>
                        <th class="tg-r8fv">Agu</th>
                        <th class="tg-r8fv">Sep</th>
                        <th class="tg-r8fv">Okt</th>
                        <th class="tg-r8fv">Nov</th>
                        <th class="tg-r8fv">Des</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataset as $d)
                    <tr>
                        <td class="tg-cegc">{{$d->kd_kontrol}}</td>
                        <td class="tg-cegc"></td>
                        <td class="tg-cegc"></td>
                        <td class="tg-cegc"></td>
                        <td class="tg-cegc"></td>
                        <td class="tg-cegc"></td>
                        <td class="tg-cegc"></td>
                        <td class="tg-cegc"></td>
                        <td class="tg-cegc"></td>
                        <td class="tg-cegc"></td>
                        <td class="tg-cegc"></td>
                        <td class="tg-cegc"></td>
                        <td class="tg-cegc"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </main>
    </body>
</html>