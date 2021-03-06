<?php

namespace App\Http\Controllers;
use App\Http\Requests\ResourceFormRequest;
use App\Models\Resource;
use Input;

class ResourcesController extends Controller
{
    public function index() {
        $resources = Resource::all();

        return view('resources.index', ['resources' => $resources]);
    }

    public function create() {
        $resource = new Resource;

        return view('resources.create', ['resource' => $resource]);
    }

    public function store(ResourceFormRequest $request) {
        $input = Input::all();
        Resource::create($input);

        return redirect(route('resources.index'));
    }

    public function show($id) {
        $resource = Resource::findOrFail($id);

        return view('resources.show', ['resource' => $resource]);
    }

    public function destroy($id) {
        $resource = Resource::findOrFail($id);

        $resource->delete();

        return redirect(route('resources.index'));
    }
}
