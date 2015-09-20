<h2>Attribute: {{ $attribute->name }}</h2>

<dl>
    <dt>Name</dt>
    <dd>{{ $attribute->name }}</dd>
    <dt>Type</dt>
    <dd>{{ $attribute->type }}</dd>
    <dt>Encrypted</dt>
    <dd>{{ $attribute->encrypted ? "True" : "False" }}</dd>
</dl>

{!! Former::open(route('attributes.destroy', $attribute))->method('DELETE') !!}
{!! Former::actions()->large_danger_submit('Delete Attribute') !!}
{!! Former::close() !!}
