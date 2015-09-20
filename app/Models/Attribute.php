<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function resource() {
        return $this->belongsTo('App\Models\Resource');
    }
}
