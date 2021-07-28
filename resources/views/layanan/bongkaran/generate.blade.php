<table>
    <thead>
        <tr>
            <th colspan="8">
                <h3><b>DATA KONTROL BONGKARAN {{$time}}</b></h3>
            </th>
        </tr>
        <tr>
            <th><b>No.</b></th>
            <th><b>Kontrol</b></th>
            <th><b>Bulan</b></th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        @foreach($dataset as $d)
        <tr>
            <td>{{$i}}.</td>
            <td>{{$d->kd_kontrol}}</td>
            <td>
                @if($d->stt_bongkar >= 4)
                    >=&nbsp;
                @endif 
                {{$d->stt_bongkar}} Bulan
           </td>
        </tr>
        <?php $i++; ?>
        @endforeach
    </tbody>
</table>