<?php

namespace App\Http\Controllers;
use App\Models\Resource;
use App\Models\DynamicModel;

class ResourceDataController extends Controller
{
    public function index($resourceId) {
        $resource = Resource::findOrFail($resourceId);
        $data = DynamicModel::fromTable($resource->name)->all();

        return view('resource-data.index', [
            'resource' => $resource,
            'data' => $data
        ]);
    }
}
