<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>FORM ALAT METERAN</title>
        <link rel="icon" href="{{asset('img/logo.png')}}">
        <link rel="stylesheet" href="{{asset('css/tagihan/print.css')}}" media="all" />
    </head>
  
    <body onload="window.print()">
        @for($i=1;$i<=2;$i++)
        @if($i == 1)
        @foreach($dataset[0] as $data)
        <main>
            <table class="tg">
                <thead>
                    <tr>
                        <th colspan="8" style="border-style:none;">
                            <h4>FORM METERAN LISTRIK</h4>
                            <h4>{{$bulan}}</h4>
                            <h4>{{$data[0]}}</h4>
                        </th>
                    </tr>
                    <tr>
                        <th class="tg-r8fv">No.</th>
                        <th class="tg-r8fv">Kontrol</th>
                        <th class="tg-r8fv">Pengguna</th>
                        <th class="tg-r8fv">Nomor</th>
                        <th class="tg-r8fv">Daya</th>
                        <th class="tg-r8fv">Lalu</th>
                        <th class="tg-r8fv" style="width:18%">Baru</th>
                        <th class="tg-r8fv" style="width:10%; white-space:normal; word-break:break-word;">Ket.</th>
                    </tr>
                </thead>
                <?php $x=1; ?>
                @foreach($data[1] as $d)
                <tbody>
                    <tr>
                        <td class="tg-cegc">{{$x}}</td>
                        <td class="tg-cegc">{{$d->kontrol}}</td>
                        <td class="tg-g25h" style="text-align:left;">{{substr($d->nama,0,13)}}</td>
                        <td class="tg-g25h" style="text-align:center;">{{$d->nomor}}</td>
                        <td class="tg-g25h" style="text-align:right;">{{number_format($d->daya)}}</td>
                        <td class="tg-g25h" style="text-align:right;">{{number_format($d->lalu)}}</td>
                        <td></td>
                        <td>{{$d->lokasi}}</td>
                    </tr>
                </tbody>
                <?php $x++; ?>
                @endforeach
            </table>
        </main>
        <div style="page-break-after:always;"></div>
        @endforeach
        @else
        @foreach($dataset[1] as $data)
        <main>
            <table class="tg">
                <thead>
                    <tr>
                        <th colspan="7" style="border-style:none;">
                            <h4>FORM METERAN AIR</h4>
                            <h4>{{$bulan}}</h4>
                            <h4>{{$data[0]}}</h4>
                        </th>
                    </tr>
                    <tr>
                        <th class="tg-r8fv">No.</th>
                        <th class="tg-r8fv">Kontrol</th>
                        <th class="tg-r8fv">Pengguna</th>
                        <th class="tg-r8fv">Nomor</th>
                        <th class="tg-r8fv">Lalu</th>
                        <th class="tg-r8fv" style="width:18%">Baru</th>
                        <th class="tg-r8fv" style="width:10%">Ket.</th>
                    </tr>
                </thead>
                <?php $x=1; ?>
                @foreach($data[1] as $d)
                <tbody>
                    <tr>
                        <td class="tg-cegc">{{$x}}</td>
                        <td class="tg-cegc">{{$d->kontrol}}</td>
                        <td class="tg-g25h" style="text-align:left;">{{substr($d->nama,0,13)}}</td>
                        <td class="tg-g25h" style="text-align:center;">{{($d->nomor)}}</td>
                        <td class="tg-g25h" style="text-align:right;">{{number_format($d->lalu)}}</td>
                        <td></td>
                        <td>{{$d->lokasi}}</td>
                    </tr>
                </tbody>
                <?php $x++; ?>
                @endforeach
            </table>
        </main>
        <div style="page-break-after:always;"></div>
        @endforeach
        @endif
        @endfor
    </body>
</html>