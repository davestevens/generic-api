<h2>Resource: {!! $resource->name !!}</h2>

{!! Former::open(route('resources.destroy', $resource))->method('DELETE') !!}
{!! Former::actions()->large_danger_submit('Delete') !!}
{!! Former::close() !!}
