<table>
    <thead>
        <tr>
            <th colspan="8">
                <h3><b>DATA PEDAGANG {{$time}}</b></h3>
            </th>
        </tr>
        <tr>
            <th><b>No.</b></th>
            <th><b>Nama</b></th>
            <th><b>Pemilik</b></th>
            <th><b>Pengguna</b></th>
            <th><b>KTP</b></th>
            <th><b>HP</b></th>
            <th><b>Email</b></th>
            <th><b>Alamat</b></th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        @foreach($dataset as $d)
        <tr>
            <td>{{$i}}.</td>
            <td>{{$d->nama}}</td>
            <td>{{$d->pemilik}}</td>
            <td>{{$d->pengguna}}</td>
            <td>{{$d->ktp}}</td>
            <td>{{$d->hp}}</td>
            <td>{{$d->email}}</td>
            <td>{{$d->alamat}}</td>
        </tr>
        <?php $i++; ?>
        @endforeach
    </tbody>
</table>