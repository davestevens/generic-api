<h2>Attributes for {{ $resource->name }}</h2>

@if (count($resource->attributes) === 0)
    @include('resource-attributes.show.empty')
@else
    @include('resource-attributes.show.list', ['attributes' => $resource->attributes])
@endif

<p>
    <a href="{!! route('resources.attributes.create', $resource) !!}">Create Attribute</a>
</p>
