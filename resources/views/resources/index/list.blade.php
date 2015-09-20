<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($resources as $resource)
            <tr>
                <td>{{ $resource->name }}</td>
                <td><a href="{!! route('resources.show', $resource) !!}">Show</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
