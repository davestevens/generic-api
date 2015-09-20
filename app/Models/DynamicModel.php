<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Schema;
use App;
use Crypt;

/**
 * Model which enables dynamically setting which database table to use
 * http://stackoverflow.com/questions/18044577/laravel-4-dynamic-table-names-using-settable
 */

class DynamicModel extends Model
{
    static $_table;
    static $_encryptedAttributes = [];
    static $_encryptedAttributeMutators = [];

    public static function fromTable($resourceName, $attributes = array()) {
        $instance = new static($attributes);
        $instance->setTable($resourceName);

        // Define attributes which should be encrypted
        $resource = Resource::where('name', '=', $resourceName)->first();
        foreach ($resource->encryptedAttributes()->get() as $attribute) {
            $instance->defineEncryptedAttributeMutators($attribute->name);
        }

        return $instance;
    }

    public function __call($function, $parameters) {
        if (array_key_exists($function, static::$_encryptedAttributeMutators)) {
            return call_user_func_array(static::$_encryptedAttributeMutators[$function], $parameters);
        }
        return parent::__call($function, $parameters);
    }

    public static function cacheMutatedAttributes($class) {
        parent::cacheMutatedAttributes($class);
        static::$mutatorCache[$class] = static::$mutatorCache[$class] + static::$_encryptedAttributes;
        var_dump(static::$mutatorCache[$class]);
    }

    public function hasGetMutator($key) {
        return $this->isEncryptedAttribute($key) || parent::hasGetMutator($key);
    }

    public function hasSetMutator($key) {
        return $this->isEncryptedAttribute($key) || parent::hasGetMutator($key);
    }

    public function setTable($resourceName) {
        $table = getenv('DB_PREFIX').$resourceName;
        if (!Schema::hasTable($table)) {
            App::abort(404, "'$resourceName' does not exist.");
        }
        static::$_table = $table;
    }

    public function getTable() {
        return static::$_table;
    }

    private function defineEncryptedAttributeMutators($key) {
        static::$_encryptedAttributes[] = $key;
        // getter
        static::$_encryptedAttributeMutators['get'.Str::studly($key).'Attribute'] = function($value) {
            return $value ? Crypt::decrypt($value) : $value;
        };
        // setter
        static::$_encryptedAttributeMutators['set'.Str::studly($key).'Attribute'] = function($value) {
            $this->attributes[$key] = Crypt::encrypt($value);
        };
    }

    public function isEncryptedAttribute($key) {
        return in_array($key, static::$_encryptedAttributes);
    }
}
