<table>
    <thead>
        <tr>
            <th>Name</th>
            <th colspan="2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($resources as $resource)
            <tr>
                <td>{{ $resource->name }}</td>
                <td><a href="{!! route('resources.show', $resource) !!}">Show</a></td>
                <td><a href="{!! route('resources.edit', $resource) !!}">Edit</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
