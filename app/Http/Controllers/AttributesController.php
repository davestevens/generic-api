<?php

namespace App\Http\Controllers;
use App\Models\Attribute;
use Input;

class AttributesController extends Controller
{
    public function show($id) {
        $attribute = Attribute::findOrFail($id);

        return view('attributes.show', ['attribute' => $attribute]);
    }

    public function destroy($id) {
        $attribute = Attribute::findOrFail($id);

        $attribute->delete();

        return redirect(route('resources.show', $attribute->resource));
    }
}
