<table>
    <thead>
        <tr>
            <th colspan="8">
                <h3><b>DATA TEMPAT USAHA AKTIF {{$time}}</b></h3>
            </th>
        </tr>
        <tr>
            <th><b>No.</b></th>
            <th><b>Kontrol</b></th>
            <th><b>Pengguna</b></th>
            <th><b>Los</b></th>
            <th><b>Jml.Los</b></th>
            <th><b>Ket.Lokasi</b></th>
            <th><b>Usaha</b></th>
            <th><b>Fasilitas</b></th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        @foreach($dataset as $d)
        <tr>
            <td>{{$i}}.</td>
            <td>{{$d->kd_kontrol}}</td>
            <td>{{$d->pengguna}}</td>
            <td>{{$d->no_alamat}}</td>
            <td>{{$d->jml_alamat}}</td>
            <td>{{$d->lok_tempat}}</td>
            <td>{{$d->bentuk_usaha}}</td>
            <td>{{$d->fasilitas}}</td>
        </tr>
        <?php $i++; ?>
        @endforeach
    </tbody>
</table>
