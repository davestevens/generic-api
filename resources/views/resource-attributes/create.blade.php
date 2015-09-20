<h2>Create Attribute for {{ $resource->name }}</h2>

{!! Former::open(route('resources.attributes.store', $resource))->method('POST') !!}
@include('attributes/_form', ['submit_text' => 'Create Attribute'])
{!! Former::close() !!}
