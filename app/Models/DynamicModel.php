<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model which enables dynamically setting which database table to use
 * http://stackoverflow.com/questions/18044577/laravel-4-dynamic-table-names-using-settable
 */

class DynamicModel extends Model
{
    static $_table;

    public static function fromTable($table, $attributes = array()) {
        $ret = null;
        if(class_exists($table)) {
            $ret = new $table($attributes);
        }
        else {
            $ret = new static($attributes);
            $ret->setTable($table);
        }
        return $ret;
    }

    public function setTable($table) {
        static::$_table = $table;
    }

    public function getTable() {
        return static::$_table;
    }
}
