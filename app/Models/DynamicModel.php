<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Schema;
use App;
use Crypt;

class DynamicModel extends Model
{
    static $_table;
    static $_encryptedAttributes = [];

    /**
     * Build a DynamicModel
     *
     * Sets static::$_table with the table name based on the passed resource name
     * Builds a list of encrypted attributes for defining mutators and casters
     */
    public static function forResource($resourceName, $attributes = array()) {
        $instance = new static($attributes);
        $instance->setTable($resourceName);

        $resource = Resource::where('name', '=', $resourceName)->first();
        foreach ($resource->encryptedAttributes()->get() as $attribute) {
            $instance->defineEncryptedAttribute($attribute);
        }

        return $instance;
    }

    /**
     * Overrides function from Illuminate\Database\Eloquent\Model
     * Calls a mutator function in static::$_encryptedAttributes if required
     */
    public function __call($function, $parameters) {
        if (preg_match('/^([g|s]et)(.+)Attribute$/', $function, $matches)) {
            $mutator = $matches[1];
            $attribute = static::$snakeAttributes ? Str::snake($matches[2]) : $matches[2];

            if ($this->isEncryptedAttribute($attribute)) {
                $function = static::$_encryptedAttributes[$attribute][$mutator];
                return call_user_func_array($function, $parameters);
            }
        }
        return parent::__call($function, $parameters);
    }

    /**
     * Overrides function from Illuminate\Database\Eloquent\Model
     * Includes our mutated attribute keys (used for JSON output)
     */
    public static function cacheMutatedAttributes($class) {
        parent::cacheMutatedAttributes($class);
        static::$mutatorCache[$class] = static::$mutatorCache[$class] + array_keys(static::$_encryptedAttributes);
    }

    /**
     * Overrides function from Illuminate\Database\Eloquent\Model
     * Checks if the attribute has a mutator define by us
     */
    public function hasGetMutator($key) {
        return $this->isEncryptedAttribute($key) || parent::hasGetMutator($key);
    }

    /**
     * Overrides function from Illuminate\Database\Eloquent\Model
     * Checks if the attribute has a mutator define by us
     */
    public function hasSetMutator($key) {
        return $this->isEncryptedAttribute($key) || parent::hasGetMutator($key);
    }

    /**
     * Overrides function from Illuminate\Database\Eloquent\Model
     * Returns our defined cast type for encrypted attributes
     */
    protected function getCastType($key) {
        if ($this->isEncryptedAttribute($key)) {
            return static::$_encryptedAttributes[$key]['type'];
        }
        else {
            return parent::getCastType($key);
        }
    }

    /**
     * Overrides function from Illuminate\Database\Eloquent\Model
     * Builds a table name based on our resource, stores it on the Class
     */
    public function setTable($resourceName) {
        $table = getenv('DB_PREFIX').$resourceName;
        if (!Schema::hasTable($table)) {
            App::abort(404, "'$resourceName' does not exist.");
        }
        static::$_table = $table;
    }

    /**
     * Overrides function from Illuminate\Database\Eloquent\Model
     * Retrieves our built table name from the Class
     */
    public function getTable() {
        return static::$_table;
    }

    /**
     * Builds an array of mutators and the type for casting
     * Mutators decrypt/encrypt the value using Crypt
     */
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

    /**
     * Checks if the attribute is one of our encrypted attributes
     */
    private function isEncryptedAttribute($key) {
        return array_key_exists($key, static::$_encryptedAttributes);
    }
}
