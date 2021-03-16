<table>
    <tbody>
        <tr>
            <th class="text-left">
                Fecha Cobro
            </th>
            <th class="text-left">
                F. Pago
            </th>
            <th class="text-left">
                Autorización
            </th>
            <th class="text-center">
                Cobrado
            </th>
            <th class="text-left">
                F. Cita
            </th>
            <th class="text-left">
                Tratamiento
            </th>
            <th class="text-center">
                Importe
            </th>
        </tr>
        @foreach($data['items'] as $item)
            <tr>
                <td class="text-left">{{getFecha($item->fecha)}}</td>
                <td class="text-left">{{$item->fpago}}</td>
                <td class="text-left">{{$item->autorizacion}}</td>
                <td class="text-right">{{ $item->cobrado }}</td>
                <td class="text-left">{{ getFecha($item->fecha_cita) }} {{$item->hora}}</td>
                <td class="text-left">{{$item->nombre}}</td>
                <td class="text-left">{{$item->importe}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
