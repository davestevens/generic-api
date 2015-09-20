<?php

namespace App\Http\Controllers;
use App\Http\Requests\AttributeFormRequest;
use App\Models\Resource;
use App\Models\Attribute;
use Input;

class ResourceAttributesController extends Controller
{
    public function create($resourceId) {
        $resource = Resource::findOrFail($resourceId);
        $attribute = new Attribute;

        return view('resource-attributes.create', [
            'attribute' => $attribute,
            'resource' => $resource
        ]);
    }

    public function store($resourceId, AttributeFormRequest $request) {
        $resource = Resource::findOrFail($resourceId);
        $input = Input::all();
        $input['resource_id'] = $resource->id;
        Attribute::create($input);

        return redirect(route('resources.show', $resource));
    }
}
