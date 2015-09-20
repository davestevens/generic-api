<h2>Attributes for <a href="{{ route('resources.show', $resource) }}">{{ $resource->name }}</a></h2>

@if (count($resource->attributes) === 0)
    @include('resource-attributes.index.empty')
@else
    @include('resource-attributes.index.list', ['attributes' => $resource->attributes])
@endif

<p>
    <a href="{!! route('resources.attributes.create', $resource) !!}">Create Attribute</a>
</p>
