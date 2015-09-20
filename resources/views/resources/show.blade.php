<h2>Resource: {!! $resource->name !!}</h2>

<h3>Attributes</h3>
@if (count($resource->attributes) === 0)
    @include('resources.show.no-attributes')
@else
    @include('resources.show.attributes', ['attributes' => $resource->attributes])
@endif

<p>
    <a href="{!! route('resources.attributes.create', $resource) !!}">Create Attribute</a>
</p>

{!! Former::open(route('resources.destroy', $resource))->method('DELETE') !!}
{!! Former::actions()->large_danger_submit('Delete Resource') !!}
{!! Former::close() !!}
