<?php
use App\Models\TarifListrik;

$tarif = TarifListrik::first();

$rekmin = $data['trf_rekmin'];
$beban = $data['trf_beban'];
$blok1 = $data['trf_blok1'];
$blok2 = $data['trf_blok2'];
$standar = $data['trf_standar'];
$bpju = $data['trf_bpju'];
$denda1 = $data['trf_denda'];
$denda2 = $data['trf_denda_lebih'];
$ppn = $data['trf_ppn'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
        <title>Simulasi Listrik | BP3C</title>
        <link rel="stylesheet" href="{{asset('css/simulasi/simulasi.css')}}" media="all"/>
        <link
            href="{{asset('vendor/fontawesome/css/all.min.css')}}"
            rel="stylesheet"
            type="text/css">
        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>
    <style type="text/css">
    table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    </style>
    <body>
        <h2 style="text-align:center;"><span id="tarif-normal"></span><span id="tarif-kenaikan"></span></h2><br>
        <h2 style="text-align:center;" id="kenaikan"></h2>
        <?php 
        $tarifNormal   = 0;
        $tarifKenaikan = 0;
        ?>
        @for($i=1;$i<=2;$i++)
        @if($i == 1)
        <?php
        $tarifNormal = $ttlRekap[5];
        ?>
        @else
        <div style="page-break-before:always"></div>
        @foreach($rincian as $data)
        <div>
            <main>
                <table class="tg">
                    <thead>
                        <tr>
                            <th colspan="14" style="border-style:none;">
                                <h3 style="text-align:center;">SIMULASI RINCIAN TAGIHAN LISTRIK<br>{{$bln}}<br>{{$data[0]}}</h3>
                            </th>
                        </tr>
                        <tr>
                            <th class="tg-r8fv">No.</th>
                            <th class="tg-r8fv">Kontrol</th>
                            <th class="tg-r8fv">Pengguna</th>
                            <th class="tg-r8fv">Daya</th>
                            <th class="tg-r8fv">M.Lalu</th>
                            <th class="tg-r8fv">M.Baru</th>
                            <th class="tg-r8fv">Pakai</th>
                            <th class="tg-r8fv">Rek.Min</th>
                            <th class="tg-r8fv">B.Blok1</th>
                            <th class="tg-r8fv">B.Blok2</th>
                            <th class="tg-r8fv">B.Beban</th>
                            <th class="tg-r8fv">BPJU</th>
                            <th class="tg-r8fv">Tagihan</th>
                            <th class="tg-r8fv" style="width:10%">Ket</th>
                            <!-- <th class="tg-r8fv">Realisasi</th>
                            <th class="tg-r8fv">Selisih</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $jml_beban = 0;
                        $jml_bpju = 0;
                        $jml_tagihan = 0;
                        ?>
                        @foreach($data[1] as $d)
                        <?php $tagihan = 0; ?>
                        <tr>
                            <td class="tg-cegc">{{$no}}</td>
                            <td class="tg-cegc">{{$d->kontrol}}</td>
                            <td class="tg-cegc" style="text-align:left;">{{$d->pengguna}}</td>
                            <td class="tg-cegc">{{number_format($d->daya)}}</td>
                            <td class="tg-cegc">{{number_format($d->lalu)}}</td>
                            <td class="tg-cegc">{{number_format($d->baru)}}</td>
                            <td class="tg-cegc">{{number_format($d->pakai)}}</td>
                            <?php
                            if($d->rekmin > 0)
                                $rekmin_listrik = $d->daya * $rekmin;
                            else
                                $rekmin_listrik = 0;
                            $tagihan = $tagihan + $rekmin_listrik;
                            ?>
                            <td class="tg-cegc">{{number_format($rekmin_listrik)}}</td>

                            <?php
                            $a = round(($d->daya * $standar) / 1000);
                            if($d->rekmin == 0)
                                $blok1_listrik = $blok1 * $a;
                            else
                                $blok1_listrik = 0;
                            $tagihan = $tagihan + $blok1_listrik;
                            ?>
                            <td class="tg-cegc">{{number_format($blok1_listrik)}}</td>

                            <?php
                            if($rekmin == 0)
                                $blok2_listrik = $blok2 * $d->pakai;
                            else
                                if($d->rekmin == 0){
                                    if($d->pakai >= $a){
                                        $b = $d->pakai - $a;
                                        $blok2_listrik = $blok2 * $b;
                                    }
                                    else{
                                        $blok2_listrik = $blok2 * $d->pakai;
                                    }
                                }
                                else{
                                    $blok2_listrik = 0;
                                }
                            $tagihan = $tagihan + $blok2_listrik;
                            ?>
                            <td class="tg-cegc">{{number_format($blok2_listrik)}}</td>

                            <?php
                            if($d->rekmin == 0)
                                $beban_listrik = $d->daya * $beban;
                            else 
                                $beban_listrik = 0;
                            $tagihan = $tagihan + $beban_listrik;
                            ?>
                            <td class="tg-cegc">{{number_format($beban_listrik)}}</td>

                            <?php
                            $c = $rekmin_listrik + $blok1_listrik + $blok2_listrik + $beban_listrik;
                            $bpju_listrik = ($bpju / 100) * $c;
                            $tagihan = $tagihan + $bpju_listrik;
                            ?>
                            <td class="tg-cegc">{{number_format($bpju_listrik)}}</td>

                            <?php
                            $ttl_tagihan = round($tagihan + ($tagihan * ($ppn / 100)));
                            ?>
                            <td class="tg-cegc">{{number_format($ttl_tagihan)}}</td>

                            <td class="tg-cegc" style="white-space:normal; word-break:break-word;">{{$d->lokasi}}</td>
                            <!-- <td class="tg-cegc">{{number_format($d->realisasi)}}</td>
                            <td class="tg-cegc">{{number_format($d->selisih)}}</td> -->
                        </tr>
                        <?php 
                        $no++; 
                        $jml_beban   = $jml_beban + $beban_listrik;
                        $jml_bpju    = $jml_bpju + $bpju_listrik;
                        $jml_tagihan = $jml_tagihan + $ttl_tagihan;
                        ?>
                        @endforeach
                        @foreach($data[2] as $d)
                        <tr>
                            <td class="tg-vbo4" style="text-align:center;" colspan="3">Total</td>
                            <td class="tg-8m6k">{{number_format($d->daya)}}</td>
                            <td class="tg-8m6k" colspan="2"></td>
                            <td class="tg-8m6k">{{number_format($d->pakai)}}</td>
                            <td class="tg-8m6k" colspan="3"></td>
                            <td class="tg-8m6k">{{number_format($jml_beban)}}</td>
                            <td class="tg-8m6k">{{number_format($jml_bpju)}}</td>
                            <td class="tg-8m6k">{{number_format($jml_tagihan)}}</td>
                            <td class="tg-8m6k"></td>
                            <!-- <td class="tg-8m6k">{{number_format($d->realisasi)}}</td>
                            <td class="tg-8m6k">{{number_format($d->selisih)}}</td> -->
                        </tr>
                        <?php $tarifKenaikan = $tarifKenaikan + $jml_tagihan; ?>
                        @endforeach
                    </tbody>
                </table>
            </main>
            <div style="page-break-after:always"></div>
        </div>
        @endforeach
        @endif
        @endfor

        <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

        <script>
        $(document).ready(function () {
            $("#tarif-normal").html('<?php echo "Rp. ".number_format($tarifNormal); ?>' + ' ');
            $("#tarif-kenaikan").html('<i class="fas fa-angle-double-right"></i> ' + '<?php echo "Rp. ".number_format($tarifKenaikan); ?>');
            var selisih = '<?php echo($tarifKenaikan - $tarifNormal); ?>';
            if(selisih < 0)
                var selisih = '<i class="fas fa-angle-double-down"></i> Rp. ' + '<?php echo number_format(abs($tarifKenaikan - $tarifNormal)); ?>';
            else
                var selisih = '<i class="fas fa-angle-double-up"></i> Rp. ' + '<?php echo number_format($tarifKenaikan - $tarifNormal); ?>';
            $("#kenaikan").html(selisih);
        });
        </script>
    </body>
</html>