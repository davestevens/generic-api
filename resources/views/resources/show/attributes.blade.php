<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Encrypted</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($attributes as $attribute)
            <tr>
                <td>{{ $attribute->name }}</td>
                <td>{{ $attribute->type }}</td>
                <td>{{ $attribute->encrypted ? "True" : "False" }}</td>
                <td><a href="{{ route('attributes.show', $attribute) }}">Show</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
