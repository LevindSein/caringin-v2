<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Data Pedagang | BP3C</title>
        <link rel="stylesheet" href="{{asset('css/laporan/pemakaian/style-pemakaian.css')}}" media="all"/>
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
                        <th colspan="10" style="border-style:none;">
                            <h3 style="text-align:center;">DATA PEDAGANG<br>{{$time}}</h3>
                        </th>
                    </tr>
                    <tr>
                        <th class="tg-r8fv" style="width:5%">No.</th>
                        <th class="tg-r8fv" style="width:10%">Username</th>
                        <th class="tg-r8fv" style="width:10%">Nama</th>
                        <th class="tg-r8fv" style="width:10%">No.Ang</th>
                        <th class="tg-r8fv" style="width:10%">Pemilik</th>
                        <th class="tg-r8fv" style="width:10%">Pengguna</th>
                        <th class="tg-r8fv" style="width:10%">KTP</th>
                        <th class="tg-r8fv" style="width:10%">HP</th>
                        <th class="tg-r8fv" style="width:10%">Email</th>
                        <th class="tg-r8fv" style="width:15%">Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach($dataset as $d)
                    <tr>
                        <td class="tg-cegc">{{$i}}.</td>
                        <td class="tg-cegc">{{$d->username}}</td>
                        <td class="tg-cegc" style="white-space:normal; word-break:break-word;">{{$d->nama}}</td>
                        <td class="tg-cegc">{{$d->anggota}}</td>
                        <td class="tg-cegc" style="white-space:normal; word-break:break-word;">{{$d->pemilik}}</td>
                        <td class="tg-cegc" style="white-space:normal; word-break:break-word;">{{$d->pengguna}}</td>
                        <td class="tg-cegc">{{$d->ktp}}</td>
                        <td class="tg-cegc">{{$d->hp}}</td>
                        <td class="tg-cegc">{{$d->email}}</td>
                        <td class="tg-cegc" style="white-space:normal; word-break:break-word;">{{$d->alamat}}</td>
                    </tr>
                    <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
        </main>
    </body>
</html>