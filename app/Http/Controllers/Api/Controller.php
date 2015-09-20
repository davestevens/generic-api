<?php

namespace App\Http\Controllers\Api;
use App\Models\DynamicModel;

class Controller extends \App\Http\Controllers\Controller
{
    public function index($resource) {
        $this->ensureTableExists($resource);
        $instances = $this->buildModel($resource)->all();

        return $instances->toJSON();
    }

    public function store($resource) {
        $this->ensureTableExists($resource);
        $model = $this-buildModel($resource);

        // TODO: get the attributes from the request body and set them
        $model->save();

        return $model->toJSON();
    }

    public function show($resource, $id) {
        $this->ensureTableExists($resource);
        $instance = $this->buildModel($resource)->findOrFail($id);

        return $instance->toJSON();
    }

    public function update($resource, $id) {
        $this->ensureTableExists($resource);
        $instance = $this->buildModel($resource)->findOrFail($id);

        // TODO: get the attributes from the request body and set them
        $instance->save();

        return $instance->toJSON();
    }

    public function destroy($resource, $id) {
        $this->ensureTableExists($resource);
        $instance = $this->buildModel($resource)->findOrFail($id);

        $instance->delete();

        return $instance->toJSON();
    }

    private function buildModel($resource) {
        $table = $this->buildTableName($resource);
        return DynamicModel::fromTable($table);
    }

    private function ensureTableExists($resource) {
        $table = $this->buildTableName($resource);
        if (!\Schema::hasTable($table)) {
            \App::abort(404, "'$resource' does not exist.");
        }
    }

    private function buildTableName($resource) {
        return getenv('DB_PREFIX').$resource;
    }
}
