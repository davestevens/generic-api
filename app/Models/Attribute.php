<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Schema;

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'type',
        'encrypted',
        'resource_id'
    ];

    public $type_options = [
        'string' => 'String'
    ];

    public $encrypted_options = [
        '0' => 'False',
        '1' => 'True'
    ];

    public static function boot() {
        parent::boot();

        Attribute::creating(function($attribute) {
            if (!($attribute->resource)) return false;
        });

        Attribute::created(function($attribute) {
            $prefix = getenv('DB_PREFIX');
            Schema::table($prefix.$attribute->resource->name, function($table) use($attribute) {
                $type = $attribute->isEncrypted() ? 'text' : $attribute->type;
                call_user_func([$table, $type], $attribute->name);
            });
        });

        Attribute::deleted(function($attribute) {
            $prefix = getenv('DB_PREFIX');
            Schema::table($prefix.$attribute->resource->name, function($table) use($attribute) {
                $table->dropColumn($attribute->name);
            });
        });
    }

    public function resource() {
        return $this->belongsTo('App\Models\Resource');
    }

    public function isEncrypted() {
        return $this->encrypted == 1;
    }
}
