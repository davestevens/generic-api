<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Schema;

class Resource extends Model
{
    protected $fillable = [
        'name'
    ];

    public static function boot()
    {
        parent::boot();

        Resource::created(function($resource) {
            /**
             * Create a new table (with Table Prefix)
             */
            $prefix = getenv('DB_PREFIX');
            Schema::create($prefix.$resource->name, function($table) {
               $table->increments('id');
                $table->timestamps();
            });
        });

        Resource::deleted(function($resource) {
            $prefix = getenv('DB_PREFIX');
            Schema::drop($prefix.$resource->name);
        });
    }
}
