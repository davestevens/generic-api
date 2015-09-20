<?php

namespace App\Http\Requests;
use App\Http\Requests\Request;
use Input;

class AttributeFormRequest extends Request
{
    public function authorize(){
        return true;
    }

    public function rules() {
        return [
            'name' => 'required',
            'type' => 'required',
            'encrypted' => 'required'
        ];
    }
}
