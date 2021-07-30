<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
    </head>
    <style>
        body {
            position: relative;
            height: 297mm;
            width: 210mm;
        }

        .row {
            display: flex;
            /* equal height of the children */
        }

        .col {
            flex: 1;
            /* additionally, equal width */
            padding: 1em;
            border: solid;
        }

        .tg {
            border-collapse: collapse;
            border-spacing: 0;
        }
        .tg td {
            border-color: transparent;
            border-style: solid;
            border-width: 1px;
            font-family: Arial, sans-serif;
            font-size: 16px;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
        }
        .tg th {
            border-color: transparent;
            border-style: solid;
            border-width: 1px;
            font-family: Arial, sans-serif;
            font-size: 16px;
            font-weight: normal;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
        }
        .tg .tg-5b4e {
            border-width: 3px;
            border-bottom: none;
            border-left: none;
            border-right: none;
            border-color: black;
            font-family: "Times New Roman", Times, serif !important;
            font-size: 16px;
            text-align: center;
            vertical-align: middle;
        }
        .tg .tg-m43w {
            border-width: 3px;
            border-top: none;
            border-left: none;
            border-right: none;
            border-color: black;
            font-family: "Times New Roman", Times, serif !important;
            font-size: 18px;
            text-align: center;
            vertical-align: middle;
        }
        .tg .tg-spw2 {
            border-bottom: none;
            border-top: none;
            border-color: transparent;
            font-family: "Times New Roman", Times, serif !important;
            text-align: left;
            vertical-align: middle;
        }
        .tg .tg-spw3 {
            border-bottom: none;
            border-top: none;
            border-color: transparent;
            font-family: "Times New Roman", Times, serif !important;
            text-align: center;
            vertical-align: middle;
        }
        .tg .tg-spw4 {
            border-bottom: none;
            border-top: none;
            border-color: black;
            font-family: "Times New Roman", Times, serif !important;
            text-align: left;
            vertical-align: middle;
        }
        .tg .tg-spw5 {
            border-bottom: none;
            border-top: none;
            border-color: transparent;
            font-family: "Times New Roman", Times, serif !important;
            text-align: justify;
            vertical-align: middle;
        }
        .tg .tg-spw9 {
            border-color: transparent;
            font-family: "Times New Roman", Times, serif !important;
            text-align: right;
            vertical-align: middle;
        }

        .tg .tg-exl8 {
            border-bottom: none;
            border-top: none;
            border-color: black;
            font-family: "Times New Roman", Times, serif !important;
            text-align: center;
            vertical-align: middle;
        }
        .tg .tg-exl9 {
            border-bottom: none;
            border-top: none;
            border-color: black;
            font-family: "Times New Roman", Times, serif !important;
            text-align: center;
            vertical-align: middle;
        }
        .tg .tg-exl0 {
            border-color: black;
            font-family: "Times New Roman", Times, serif !important;
            text-align: center;
            vertical-align: middle;
        }
        .tg .tg-exl7 {
            border-color: transparent;
            font-family: "Times New Roman", Times, serif !important;
            text-align: center;
            vertical-align: middle;
        }
        .tg .tg-exll {
            border-right: none;
            border-bottom: none;
            border-top: none;
            border-color: black;
            font-family: "Times New Roman", Times, serif !important;
            text-align: center;
            vertical-align: middle;
        }
        .tg .tg-exlp {
            border-right: none;
            border-color: black;
            font-family: "Times New Roman", Times, serif !important;
            text-align: center;
            vertical-align: middle;
        }
        .tg .tg-tq83 {
            border-left: none;
            border-bottom: none;
            border-top: none;
            border-color: black;
            font-family: "Times New Roman", Times, serif !important;
            text-align: right;
            vertical-align: middle;
        }
        .tg .tg-tq88 {
            border-color: black;
            font-family: "Times New Roman", Times, serif !important;
            text-align: right;
            vertical-align: middle;
        }
        .tg .tg-tq89 {
            border-left: none;
            border-color: black;
            font-family: "Times New Roman", Times, serif !important;
            text-align: right;
            vertical-align: middle;
        }
    </style>
    <body>
        <div>
            <div class="row">
                <table class="tg" style="undefined;table-layout: fixed; width: 700px">
                    <colgroup>
                        <col style="width: 42px">
                        <col style="width: 182px">
                        <col style="width: 122px">
                        <col style="width: 62px">
                        <col style="width: 202px">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="tg-spw9" colspan="4"></th>
                            <th class="tg-spw9"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="tg-m43w" colspan="5">KEMITRAAN KOPPAS PASAR INDUK BANDUNG-PT.LPP<br>BADAN PENGELOLA PUSAT PERDAGANGAN CARINGIN<br>
                                <span style="font-size:22px">
                                    <b>UNIT MEKANIKAL DAN ELEKTRIKAL</b></span></td>
                        </tr>
                        <tr>
                            <td class="tg-5b4e" colspan="5"><br>
                                <span style="font-size:16px">
                                    <b>
                                        <u>BERITA ACARA</u>
                                    </b>
                                </span><br>Nomor: {{$nomor}}<br><br>
                                <b>Hal: Pembongkaran</b><br><br></td>
                        </tr>
                        <tr>
                            <td class="tg-spw5" colspan="5">Pada hari ini ________ tanggal ________ bulan
                                ________ tahun ________ telah dilakukan pekerjaan <b>Pembongkaran Alat Listrik / Air</b>
                                di lokasi {{$data->kd_kontrol}} atas nama saudara/i {{$data->pengguna}}.<br></td>
                        </tr>
                        <tr>
                            <td class="tg-spw5" colspan="5">Demikian Berita Acara ini kami buat untuk
                                dipergunakan sebagaimana mestinya. Apabila melakukan penyambungan kembali, maka
                                akan dikenakan Biaya Pemasangan Baru sesuai aturan yang berlaku.<br><br></td>
                        </tr>
                        <tr>
                            <td class="tg-spw3" colspan="3"></td>
                            <td class="tg-spw3" colspan="2"><br><br>Bandung, {{$data->cetak}}</td>
                        </tr>
                        <tr>
                            <td class="tg-spw3" colspan="2">Pelanggan<br><br><br><br><br>{{$data->pengguna}}</td>
                            <td class="tg-spw3"></td>
                            <td class="tg-spw3" colspan="2">Unit Mekanikal & Elektrikal<br><br><br><br><br>__________________</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>