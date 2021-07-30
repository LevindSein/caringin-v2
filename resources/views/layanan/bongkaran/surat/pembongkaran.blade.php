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
        @if($data->listrik == 1)
        <div>
            <div class="row">
                <table class="tg" style="undefined;table-layout: fixed; width: 700px">
                    <colgroup>
                        <col style="width: 101px">
                        <col style="width: 220px">
                        <col style="width: 45px">
                        <col style="width: 101px">
                        <col style="width: 183px">
                    </colgroup>
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
                                        <u>SURAT PERINTAH PEMBONGKARAN METER LISTRIK</u>
                                    </b>
                                </span><br>Nomor: {{$data->nomor_listrik}}<br><br></td>
                        </tr>
                        <tr>
                            <td class="tg-spw5" colspan="5">Yang bertandatangan dibawah ini, Kepala Unit
                                Mekanikal & Elektrikal Badan Pengelola Pusat Perdagangan Caringin memerintahkan
                                kepada petugas :<br></td>
                        </tr>
                        <tr>
                            <td class="tg-spw2">Nama</td>
                            <td class="tg-spw2" colspan="4">: ____________________</td>
                        </tr>
                        <tr>
                            <td class="tg-spw2"></td>
                            <td class="tg-spw2" colspan="4">: ____________________</td>
                        </tr>
                        <tr>
                            <td class="tg-spw2"></td>
                            <td class="tg-spw2" colspan="4">: ____________________</td>
                        </tr>

                        <tr>
                            <td class="tg-spw2" colspan="5">Untuk membongkar alat meter listrik atas nama :<br></td>
                        </tr>
                        <tr>
                            <td class="tg-spw2">Nama</td>
                            <td class="tg-spw2" colspan="2">: {{$pengguna->nama}}</td>
                            <td class="tg-spw2">No. Kontrol</td>
                            <td class="tg-spw2">: {{$data->kd_kontrol}}</td>
                        </tr>
                        <tr>
                            <td class="tg-spw2">KTP</td>
                            <td class="tg-spw2" colspan="2">: {{$pengguna->ktp}}</td>
                            <td class="tg-spw2">No. HP</td>
                            <td class="tg-spw2">: {{$pengguna->hp}}</td>
                        </tr>
                        <tr>
                            <td class="tg-spw2">Kode Alat</td>
                            <td class="tg-spw2" colspan="2">: {{$data->data_listrik->kode}}</td>
                            <td class="tg-spw2">No. Seri</td>
                            <td class="tg-spw2">: {{$data->data_listrik->nomor}}</td>
                        </tr>
                        <tr>
                            <td class="tg-spw2">Lokasi</td>
                            <td class="tg-spw2" colspan="2">: {{$data->lok_tempat}}</td>
                            <td class="tg-spw2">Daya</td>
                            <td class="tg-spw2">: {{$data->data_listrik->daya}}</td>
                        </tr>
                        <tr>
                            <td class="tg-spw2" colspan="5"><br>Demikan Surat Perintah terebut agar dilaksanakan sebagaimana mestinya.<br></td>
                        </tr>
                        <tr>
                            <td class="tg-spw3" colspan="3"></td>
                            <td class="tg-spw3" colspan="2"><br><br>Bandung, {{$data->cetak}}</td>
                        </tr>
                        <tr>
                            <td class="tg-spw3" colspan="2">Admin,<br><br><br><br><br>{{$data->admin}}</td>
                            <td class="tg-spw3"></td>
                            <td class="tg-spw3" colspan="2">Ka. Unit Mekanikal & Elektrikal,<br><br><br><br><br>__________________</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        @if($data->air == 1 && $data->listrik == 1)
        <div style="page-break-before:always"></div>
        @endif
        @if($data->air == 1)
        <div>
            <div class="row">
                <table class="tg" style="undefined;table-layout: fixed; width: 700px">
                    <colgroup>
                        <col style="width: 101px">
                        <col style="width: 220px">
                        <col style="width: 45px">
                        <col style="width: 101px">
                        <col style="width: 183px">
                    </colgroup>
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
                                        <u>SURAT PERINTAH PEMBONGKARAN METER AIR</u>
                                    </b>
                                </span><br>Nomor: {{$data->nomor_air}}<br><br></td>
                        </tr>
                        <tr>
                            <td class="tg-spw5" colspan="5">Yang bertandatangan dibawah ini, Kepala Unit
                                Mekanikal & Elektrikal Badan Pengelola Pusat Perdagangan Caringin memerintahkan
                                kepada petugas :<br></td>
                        </tr>
                        <tr>
                            <td class="tg-spw2">Nama</td>
                            <td class="tg-spw2" colspan="4">: ____________________</td>
                        </tr>
                        <tr>
                            <td class="tg-spw2"></td>
                            <td class="tg-spw2" colspan="4">: ____________________</td>
                        </tr>
                        <tr>
                            <td class="tg-spw2"></td>
                            <td class="tg-spw2" colspan="4">: ____________________</td>
                        </tr>

                        <tr>
                            <td class="tg-spw2" colspan="5">Untuk membongkar alat meter air atas nama :<br></td>
                        </tr>
                        <tr>
                            <td class="tg-spw2">Nama</td>
                            <td class="tg-spw2" colspan="2">: {{$pengguna->nama}}</td>
                            <td class="tg-spw2">No. Kontrol</td>
                            <td class="tg-spw2">: {{$data->kd_kontrol}}</td>
                        </tr>
                        <tr>
                            <td class="tg-spw2">KTP</td>
                            <td class="tg-spw2" colspan="2">: {{$pengguna->ktp}}</td>
                            <td class="tg-spw2">No. HP</td>
                            <td class="tg-spw2">: {{$pengguna->hp}}</td>
                        </tr>
                        <tr>
                            <td class="tg-spw2">Kode Alat</td>
                            <td class="tg-spw2" colspan="2">: {{$data->data_air->kode}}</td>
                            <td class="tg-spw2">No. Seri</td>
                            <td class="tg-spw2">: {{$data->data_air->nomor}}</td>
                        </tr>
                        <tr>
                            <td class="tg-spw2">Lokasi</td>
                            <td class="tg-spw2" colspan="4">: {{$data->lok_tempat}}</td>
                        </tr>
                        <tr>
                            <td class="tg-spw2" colspan="5"><br>Demikan Surat Perintah terebut agar dilaksanakan sebagaimana mestinya.<br></td>
                        </tr>
                        <tr>
                            <td class="tg-spw3" colspan="3"></td>
                            <td class="tg-spw3" colspan="2"><br><br>Bandung, {{$data->cetak}}</td>
                        </tr>
                        <tr>
                            <td class="tg-spw3" colspan="2">Admin,<br><br><br><br><br>{{$data->admin}}</td>
                            <td class="tg-spw3"></td>
                            <td class="tg-spw3" colspan="2">Ka. Unit Mekanikal & Elektrikal,<br><br><br><br><br>__________________</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($data->air == 0 && $data->listrik == 0)
        Alat - Alat sudah dibongkar
        @endif
    </body>
</html>