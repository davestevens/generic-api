<?php

namespace App\Http\Controllers\Api;
use App\Models\DynamicModel;
use App\Models\Resource;
use Input;

class Controller extends \App\Http\Controllers\Controller
{
    public function index($resource) {
        $instances = $this->buildModel($resource)->all();

        return response($instances->toJSON())->header('Content-Type', 'application/json');
    }

    public function store($resource) {
        $instance = $this->buildModel($resource);

        $resourceModel = Resource::where('name', '=', $resource)->first();
        foreach ($resourceModel->attributes as $attribute) {
            $instance[$attribute->name] = Input::get($attribute->name);
        }
        $instance->save();

        return response($instance->toJSON())->header('Content-Type', 'application/json');
    }

    public function show($resource, $id) {
        $instance = $this->buildModel($resource)->findOrFail($id);

        return response($instance->toJSON())->header('Content-Type', 'application/json');
    }

    public function update($resource, $id) {
        $instance = $this->buildModel($resource)->findOrFail($id);

        $resourceModel = Resource::where('name', '=', $resource)->first();
        foreach ($resourceModel->attributes as $attribute) {
            $instance[$attribute->name] = Input::get($attribute->name);
        }
        $instance->save();

        return response($instance->toJSON())->header('Content-Type', 'application/json');
    }

    public function destroy($resource, $id) {
        $instance = $this->buildModel($resource)->findOrFail($id);

        $instance->delete();

        return response($instance->toJSON())->header('Content-Type', 'application/json');
    }

    private function buildModel($resource) {
        return DynamicModel::fromTable($resource);
    }
}
