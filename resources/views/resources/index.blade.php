<h2>Resources</h2>

@if (count($resources) === 0)
    @include('resources.index.empty')
@else
    @include('resources.index.list', ['resources' => $resources])
@endif

<p>
    <a href="{!! route('resources.create') !!}">Create Resource</a>
</p>
