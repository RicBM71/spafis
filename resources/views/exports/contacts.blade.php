<table>
    <tbody>
        <tr>
            <th>Given Name</th>
            <th>Family Name</th>
            <th>Phone 1 - Type</th>
            <th>Phone 1 - Value</th>
        </tr>
        @foreach($data as $item)
            <tr>
                <td>{{ $item->nombre}}</td>
                <td>{{ $item->apellidos}}</td>
                <th>Móvil</th>
                <td>{{ $item->telefonom}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
