<table>
    <thead>
        <tr>
            <th>No Member</th>
            <th>Key2</th>
            <th>Key3</th>
            <th>Currency</th>
            <th>Name</th>
            <th>No Tagihan</th>
            <th>Periode</th>
            <th>Jth Tempo</th>
            <th>Bill Info 05</th>
            <th>Bill Info 06</th>
            <th>Bill Info 07</th>
            <th>Bill Info 08</th>
            <th>Bill Info 09</th>
            <th>Bill Info 10</th>
            <th>Bill Info 11</th>
            <th>Bill Info 12</th>
            <th>Bill Info 13</th>
            <th>Bill Info 14</th>
            <th>Bill Info 15</th>
            <th>Bill Info 16</th>
            <th>Bill Info 17</th>
            <th>Bill Info 18</th>
            <th>Bill Info 19</th>
            <th>Bill Info 20</th>
            <th>Bill Info 21</th>
            <th>Bill Info 22</th>
            <th>Bill Info 23</th>
            <th>Bill Info 24</th>
            <th>Bill Info 25</th>
            <th>Periode Open</th>
            <th>Periode Close</th>
            <th>SubBill 01</th>
            <th>SubBill 02</th>
            <th>SubBill 03</th>
            <th>SubBill 04</th>
            <th>SubBill 05</th>
            <th>SubBill 06</th>
            <th>SubBill 07</th>
            <th>SubBill 08</th>
            <th>SubBill 09</th>
            <th>SubBill 10</th>
            <th>SubBill 11</th>
            <th>SubBill 12</th>
            <th>SubBill 13</th>
            <th>SubBill 14</th>
            <th>SubBill 15</th>
            <th>SubBill 16</th>
            <th>SubBill 17</th>
            <th>SubBill 18</th>
            <th>SubBill 19</th>
            <th>SubBill 20</th>
            <th>SubBill 21</th>
            <th>SubBill 22</th>
            <th>SubBill 23</th>
            <th>SubBill 24</th>
            <th>SubBill 25</th>
            <th>end record</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dataset as $d)
        <tr>
            <td>{{$d->kode . $d->number}}</td>
            <td></td>
            <td></td>
            <td>{{$d->currency}}</td>
            <td>{{$d->name}}</td>
            <td>{{$d->number}}</td>
            <td>{{$d->periode}}</td>
            <td>{{$d->jth_tempo}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$d->open}}</td>
            <td>{{$d->close}}</td>
            <td>{{$d->bill}}</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>\\\</td>
            <td>~</td>
        </tr>
        @endforeach
    </tbody>
</table>
