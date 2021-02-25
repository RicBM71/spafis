<table>
    <tbody>
        <tr>
            <th>Mes</th>
            <th>Ejercicio {{$data['ejercicios'][0]}}</th>
            <th>Ejercicio {{$data['ejercicios'][1]}}</th>
            <th>Dif. Abs</th>
            <th>Dif. Rel</th>
            <th>Ejercicio {{$data['ejercicios'][2]}}</th>
            <th>Dif. Abs</th>
            <th>Dif. Rel</th>
            <th>Ejercicio {{$data['ejercicios'][3]}}</th>
            <th>Dif. Abs</th>
            <th>Dif. Rel</th>
        </tr>
        @foreach($data['items'] as $item)
            <tr>
                <td>{{ $item['mes']}}</td>
                <td>{{ $item['eje1']}}</td>
                <td>{{ $item['eje2']}}</td>
                <td>{{ $item['dif1_2']}}</td>
                <td>{{ $item['por1_2']}}</td>
                <td>{{ $item['eje3']}}</td>
                <td>{{ $item['dif1_3']}}</td>
                <td>{{ $item['por1_3']}}</td>
                <td>{{ $item['eje4']}}</td>
                <td>{{ $item['dif1_4']}}</td>
                <td>{{ $item['por1_4']}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
