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

    public static function fromTable($resourceName, $attributes = array()) {
        $instance = new static($attributes);
        $instance->setTable($resourceName);

        $resource = Resource::where('name', '=', $resourceName)->first();
        foreach ($resource->encryptedAttributes()->get() as $attribute) {
            $instance->defineEncryptedAttribute($attribute);
        }

        return $instance;
    }

    public function __call($function, $parameters) {
        if (strpos($function, 'Attribute') !== false
            &&
            preg_match('/^([g|s]et)(.+)Attribute$/', $function, $matches)
        ) {
            if (static::$snakeAttributes) {
                $matches[2] = Str::snake($matches[2]);
            }

            if (array_key_exists($matches[2], static::$_encryptedAttributes)) {
                return call_user_func_array(static::$_encryptedAttributes[$matches[2]][$matches[1]], $parameters);
            }
        }
        return parent::__call($function, $parameters);
    }

    public static function cacheMutatedAttributes($class) {
        parent::cacheMutatedAttributes($class);
        static::$mutatorCache[$class] = static::$mutatorCache[$class] + array_keys(static::$_encryptedAttributes);
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

    protected function getCastType($key) {
        if ($this->isEncryptedAttribute($key)) {
            return static::$_encryptedAttributes[$key]['type'];
        }
        else {
            return parent::getCastType($key);
        }
    }

    private function defineEncryptedAttribute($attribute) {
        $key = $attribute->name;
        static::$_encryptedAttributes[$key] = [
            'get' => function($value) use ($key) {
                $value = $value ? Crypt::decrypt($value) : $value;
                return $this->castAttribute($key, $value);
            },
            'set' => function($value) use ($key) {
                $this->attributes[$key] = $value? Crypt::encrypt($value) : $value;
            },
            'type' => $attribute->type
        ];
    }

    public function isEncryptedAttribute($key) {
        return array_key_exists($key, static::$_encryptedAttributes);
    }
}
