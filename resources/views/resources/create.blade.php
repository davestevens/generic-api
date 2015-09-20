<h2>Create Resource</h2>

{!! Former::open(route('resources.store'))->method('POST') !!}
@include('resources/_form', ['submit_text' => 'Create Resource'])
{!! Former::close() !!}
