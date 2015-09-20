<table>
    <thead>
        <tr>
            @foreach ($data[0]->toArray() as $key => $_value)
                <th>{{ $key }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $datum)
            <tr>
                @foreach ($datum->toArray() as $value)
                    <td>{{ $value }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

