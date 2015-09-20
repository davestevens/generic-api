<?php

namespace App\Http\Requests;
use App\Http\Requests\Request;
use Input;

class ResourceFormRequest extends Request
{
    public function authorize(){
        return true;
    }

    public function rules() {
        return [
            'name' => 'required'
        ];
    }
}
