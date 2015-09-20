<h2>Data for <a href="{{ route('resources.show', $resource) }}">{{ $resource->name }}</a></h2>

@if (count($data) === 0)
    @include('resource-data.index.empty')
@else
    @include('resource-data.index.list', ['data' => $data])
@endif
