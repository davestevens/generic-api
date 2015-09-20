<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

        // Define attributes which should be encrypted
        $resource = Resource::where('name', '=', $resourceName)->first();
        foreach ($resource->encryptedAttributes()->get() as $attribute) {
            $instance->setEncryptedAttribute($attribute->name);
        }

        return $instance;
    }

    public function getAttribute($key) {
        $value = parent::getAttribute($key);
        if ($this->isEncryptedAttribute($key)) {
            $value = Crypt::decrypt($value);
        }
        return $value;
    }

    public function setAttribute($key, $value) {
        if ($this->isEncryptedAttribute($key)) {
            $value = Crypt::encrypt($value);
        }
        parent::setAttribute($key, $value);
    }

    /**
     * Used by toJson and toArray for retrieving attributes
     */
    public function attributesToArray() {
        $attributes = parent::attributesToArray();
        foreach ($attributes as $key => $value) {
            if ($this->isEncryptedAttribute($key)) {
                $attributes[$key] = Crypt::decrypt($value);
            }
        }
        return $attributes;
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

    public function setEncryptedAttribute($name) {
        static::$_encryptedAttributes[] = $name;
    }

    public function isEncryptedAttribute($key) {
        return in_array($key, static::$_encryptedAttributes);
    }
}
