<h2>Resource: {!! $resource->name !!}</h2>

<p>
    <a href="{!! route('resources.attributes.index', $resource) !!}">Attributes</a>
</p>

<p>
    <a href="{!! route('resources.attributes.create', $resource) !!}">Create Attribute</a>
</p>

{!! Former::open(route('resources.destroy', $resource))->method('DELETE') !!}
{!! Former::actions()->large_danger_submit('Delete Resource') !!}
{!! Former::close() !!}
