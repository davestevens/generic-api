<?php

namespace App\Http\Controllers\Api;
use App\Models\DynamicModel;

class Controller extends \App\Http\Controllers\Controller
{
    public function index($table) {
        $this->ensureTableExists($table);
        $instances = $this->buildModel($table)->all();

        return $instances->toJSON();
    }

    public function store($table) {
        $this->ensureTableExists($table);
        $model = $this-buildModel($table);

        // TODO: get the attributes from the request body and set them
        $model->save();

        return $model->toJSON();
    }

    public function show($table, $id) {
        $this->ensureTableExists($table);
        $instance = $this->buildModel($table)->findOrFail($id);

        return $instance->toJSON();
    }

    public function update($table, $id) {
        $this->ensureTableExists($table);
        $instance = $this->buildModel($table)->findOrFail($id);

        // TODO: get the attributes from the request body and set them
        $instance->save();

        return $instance->toJSON();
    }

    public function destroy($table, $id) {
        $this->ensureTableExists($table);
        $instance = $this->buildModel($table)->findOrFail($id);

        $instance->delete();

        return $instance->toJSON();
    }

    private function buildModel($table) {
        return DynamicModel::fromTable($table);
    }

    private function ensureTableExists($table) {
        if (!\Schema::hasTable($table)) {
            \App::abort(404, "'$table' does not exist.");
        }
    }
}
