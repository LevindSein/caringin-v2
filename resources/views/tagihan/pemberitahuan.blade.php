<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Pemberitahuan {{$blok}}</title>
        <link rel="stylesheet" href="{{asset('css/tagihan/pemberitahuan.css')}}" media="all"/>
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>
    <body onload="window.print()">
        @foreach($dataset as $d)
            @if($d['total'] != 0 || $d['total'] != NULL)
            <?php $i = 1; ?>
            <div class="row" style="page-break-after:always">
                <table class="tg" style="undefined;table-layout: fixed; width: 450px">
                    <colgroup>
                        <col style="width: 30px">
                        <col style="width: 80px">
                        <col style="width: 50px">
                        <col style="width: 80px">
                        <col style="width: 100px">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="tg-gmla" colspan="5"><span style="font-weight:bold">BADAN PENGELOLA</span><br><span style="font-weight:bold">PUSAT PERDAGANGAN CARINGIN</span><br><span style="font-weight:bold">SEGI PEMBERITAHUAN</span></th>
                        </tr>
                        <tr>
                            <td class="tg-0r18" colspan="5"><span style="font-weight:bold">{{$bulan}}</span></td>
                        </tr>
                        <tr>
                            <td class="tg-zd5p" colspan="3">Kode&nbsp;&nbsp;&nbsp;&nbsp;: {{$d[0]}}</td>
                            <td class="tg-tb39" colspan="2"><b>{{substr($d[1],0,25)}}</b></td>
                        </tr>
                        <tr>
                            <td class="tg-zd5i" colspan="5">No.Los&nbsp;: {{$d['alamat']}}</td>
                        </tr>
                        <tr>
                            <td class="tg-zd5i" colspan="5">Lok&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$d['lokasi']}}</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="tg-umgj" colspan="4">FASILITAS</td>
                            <td class="tg-95y4">HARGA</td>
                        </tr>
                        @if($d[2] != 0)
                        <tr>
                            <td class="tg-0r15">{{$i}}.</td>
                            <td class="tg-tb31" colspan="3">Listrik</td>
                            <td class="tg-7jiy">{{number_format($d[2])}}</td>
                        </tr>
                        <tr>
                            <td class="tg-0r15"></td>
                            <td class="tg-tb31"></td>
                            <td class="tg-tb31">Daya</td>
                            <td class="tg-7jip">{{number_format($d['daya_listrik'])}}</td>
                            <td class="tg-7jiy"></td>
                        </tr>
                        <tr>
                            <td class="tg-0r15"></td>
                            <td class="tg-tb31"></td>
                            <td class="tg-tb31">Awal</td>
                            <td class="tg-7jip">{{number_format($d['awal_listrik'])}}</td>
                            <td class="tg-7jiy"></td>
                        </tr>
                        <tr>
                            <td class="tg-0r15"></td>
                            <td class="tg-tb31"></td>
                            <td class="tg-tb31">Akhir</td>
                            <td class="tg-7jip">{{number_format($d['akhir_listrik'])}}</td>
                            <td class="tg-7jiy"></td>
                        </tr>
                        <tr>
                            <td class="tg-0r15"></td>
                            <td class="tg-tb31"></td>
                            <td class="tg-tb31">Pakai</td>
                            <td class="tg-7jip">{{number_format($d['pakai_listrik'])}}</td>
                            <td class="tg-7jiy"></td>
                        </tr>
                        <?php $i++; ?>
                        @endif
                        @if($d[3] != 0)
                        <tr>
                            <td class="tg-0r15">{{$i}}.</td>
                            <td class="tg-tb31" colspan="3">Air Bersih</td>
                            <td class="tg-7jiy">{{number_format($d[3])}}</td>
                        </tr>
                        <tr>
                            <td class="tg-0r15"></td>
                            <td class="tg-tb31"></td>
                            <td class="tg-tb31">Awal</td>
                            <td class="tg-7jip">{{number_format($d['awal_airbersih'])}}</td>
                            <td class="tg-7jiy"></td>
                        </tr>
                        <tr>
                            <td class="tg-0r15"></td>
                            <td class="tg-tb31"></td>
                            <td class="tg-tb31">Akhir</td>
                            <td class="tg-7jip">{{number_format($d['akhir_airbersih'])}}</td>
                            <td class="tg-7jiy"></td>
                        </tr>
                        <tr>
                            <td class="tg-0r15"></td>
                            <td class="tg-tb31"></td>
                            <td class="tg-tb31">Pakai</td>
                            <td class="tg-7jip">{{number_format($d['pakai_airbersih'])}}</td>
                            <td class="tg-7jiy"></td>
                        </tr>
                        <?php $i++; ?>
                        @endif
                        @if($d[4] != 0)
                        <tr>
                            <td class="tg-0r15">{{$i}}.</td>
                            <td class="tg-tb31" colspan="3">Keamanan IPK</td>
                            <td class="tg-7jiy">{{number_format($d[4])}}</td>
                        </tr>
                        <?php $i++; ?>
                        @endif
                        @if($d[5] != 0)
                        <tr>
                            <td class="tg-0r15">{{$i}}.</td>
                            <td class="tg-tb31" colspan="3">Kebersihan</td>
                            <td class="tg-7jiy">{{number_format($d[5])}}</td>
                        </tr>
                        <?php $i++; ?>
                        @endif
                        @if($d[6] != 0)
                        <tr>
                            <td class="tg-0r15">{{$i}}.</td>
                            <td class="tg-tb31" colspan="3">Air Kotor</td>
                            <td class="tg-7jiy">{{number_format($d[6])}}</td>
                        </tr>
                        <?php $i++; ?>
                        @endif
                        @if($d[7] != 0)
                        <tr>
                            <td class="tg-0r15">{{$i}}.</td>
                            <td class="tg-tb31" colspan="3">Tunggakan</td>
                            <td class="tg-7jiy">{{number_format($d[7])}}</td>
                        </tr>
                        <?php $i++; ?>
                        @endif
                        @if($d[8] != 0)
                        <tr>
                            <td class="tg-0r15">{{$i}}.</td>
                            <td class="tg-tb31" colspan="3">Denda</td>
                            <td class="tg-7jiy">{{number_format($d[8])}}</td>
                        </tr>
                        <?php $i++; ?>
                        @endif
                        @if($d[9] != 0)
                        <tr>
                            <td class="tg-0r15">{{$i}}.</td>
                            <td class="tg-tb31" colspan="3">Lain - Lain</td>
                            <td class="tg-7jiy">{{number_format($d[9])}}</td>
                        </tr>
                        <?php $i++; ?>
                        @endif
                        @if($d[2] == 0)
                        <tr>
                            <td class="tg-0r15">&nbsp;</td>
                            <td class="tg-tb31" colspan="3">&nbsp;</td>
                            <td class="tg-7jiy">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="tg-0r15">&nbsp;</td>
                            <td class="tg-tb31">&nbsp;</td>
                            <td class="tg-tb31">&nbsp;</td>
                            <td class="tg-7jip">&nbsp;</td>
                            <td class="tg-7jiy">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="tg-0r15">&nbsp;</td>
                            <td class="tg-tb31">&nbsp;</td>
                            <td class="tg-tb31">&nbsp;</td>
                            <td class="tg-7jip">&nbsp;</td>
                            <td class="tg-7jiy">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="tg-0r15">&nbsp;</td>
                            <td class="tg-tb31">&nbsp;</td>
                            <td class="tg-tb31">&nbsp;</td>
                            <td class="tg-7jip">&nbsp;</td>
                            <td class="tg-7jiy">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="tg-0r15">&nbsp;</td>
                            <td class="tg-tb31">&nbsp;</td>
                            <td class="tg-tb31">&nbsp;</td>
                            <td class="tg-7jip">&nbsp;</td>
                            <td class="tg-7jiy">&nbsp;</td>
                        </tr>
                        @endif
                        @if($d[3] == 0)
                        <tr>
                            <td class="tg-0r15">&nbsp;</td>
                            <td class="tg-tb31" colspan="3">&nbsp;</td>
                            <td class="tg-7jiy">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="tg-0r15">&nbsp;</td>
                            <td class="tg-tb31">&nbsp;</td>
                            <td class="tg-tb31">&nbsp;</td>
                            <td class="tg-7jip">&nbsp;</td>
                            <td class="tg-7jiy">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="tg-0r15">&nbsp;</td>
                            <td class="tg-tb31">&nbsp;</td>
                            <td class="tg-tb31">&nbsp;</td>
                            <td class="tg-7jip">&nbsp;</td>
                            <td class="tg-7jiy">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="tg-0r15">&nbsp;</td>
                            <td class="tg-tb31">&nbsp;</td>
                            <td class="tg-tb31">&nbsp;</td>
                            <td class="tg-7jip">&nbsp;</td>
                            <td class="tg-7jiy">&nbsp;</td>
                        </tr>
                        @endif
                        @if($d[4] == 0)
                        <tr>
                            <td class="tg-0r15">&nbsp;</td>
                            <td class="tg-tb31" colspan="3">&nbsp;</td>
                            <td class="tg-7jiy">&nbsp;</td>
                        </tr>
                        @endif
                        @if($d[5] == 0)
                        <tr>
                            <td class="tg-0r15">&nbsp;</td>
                            <td class="tg-tb31" colspan="3">&nbsp;</td>
                            <td class="tg-7jiy">&nbsp;</td>
                        </tr>
                        @endif
                        @if($d[6] == 0)
                        <tr>
                            <td class="tg-0r15">&nbsp;</td>
                            <td class="tg-tb31" colspan="3">&nbsp;</td>
                            <td class="tg-7jiy">&nbsp;</td>
                        </tr>
                        @endif
                        @if($d[7] == 0)
                        <tr>
                            <td class="tg-0r15">&nbsp;</td>
                            <td class="tg-tb31" colspan="3">&nbsp;</td>
                            <td class="tg-7jiy">&nbsp;</td>
                        </tr>
                        @endif
                        @if($d[8] == 0)
                        <tr>
                            <td class="tg-0r15">&nbsp;</td>
                            <td class="tg-tb31" colspan="3">&nbsp;</td>
                            <td class="tg-7jiy">&nbsp;</td>
                        </tr>
                        @endif
                        @if($d[9] == 0)
                        <tr>
                            <td class="tg-0r15">&nbsp;</td>
                            <td class="tg-tb31" colspan="3">&nbsp;</td>
                            <td class="tg-7jiy">&nbsp;</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="tg-ey1n" colspan="3">TOTAL</td>
                            <td class="tg-7jit"><span style="font-weight:bold">Rp.</span></td>
                            <td class="tg-q23v">{{number_format($d['total'])}}</td>
                        </tr>
                        <tr>
                            <td class="tg-7jio" colspan="5"><i>{{$d['terbilang']}}</i></td>
                        </tr>
                        <tr>
                            <td class="tg-0r15">-</td>
                            <td class="tg-tb37" colspan="4">Pembayaran via Bank BRI 0401.01.000928.307 An. Sri Indra Nurhayati. Hindari <b>denda</b>, bayar sebelum tanggal 15.</td>
                        </tr>
                        <tr>
                            <td class="tg-0r16">-</td>
                            <td class="tg-zd55" colspan="4">Pemberitahuan ini merupakan edaran yang sah. Sudah termasuk PPN.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
        @endforeach
    </body>
</html>