{!! Former::populate($attribute) !!}
{!! Former::text('name') !!}
{!! Former::select('type')->options($attribute->type_options) !!}
{!! Former::select('encrypted')->options($attribute->encrypted_options) !!}
{!! Former::actions()->large_primary_submit($submit_text) !!}
