<h2>Edit Resource: {!! $resource->name !!}</h2>

{!! Former::open(route('resources.update', $resource))->method('PUT') !!}
@include('resources/_form', ['submit_text' => 'Update Resource'])
{!! Former::close() !!}
