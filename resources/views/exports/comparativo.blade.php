<table>
    <tbody>
        <tr>
            <th>Tratamiento</th>
            <th>Sesiones</th>
            <th>Ses. A/A</th>
            <th>Valores</th>
            <th>Val. A/A</th>
        </tr>
        @foreach($data['items'] as $item)
            <tr>
                <td>{{ $item['tratamiento']}}</td>
                <td>{{ $item['p1']}}</td>
                <td>{{ $item['p2']}}</td>
                <td>{{ $item['i1']}}</td>
                <td>{{ $item['i2']}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
