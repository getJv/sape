<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Field;
use App\Http\Resources\Field as FieldResource;
use App\Http\Resources\FieldCollection;

class FieldController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
            'description' => 'required',
            'type' => 'string | required',
            'min'  => 'integer',
            'min'  => 'integer',
            'mask' => 'string',

        ]);
        $field = Field::create(array_merge($data, ['active' => true]));
        return new FieldResource($field);
    }

    public function update(Field $field)
    {
        $data = request()->validate([
            'name' => 'required',
            'description' => 'required',
            'min'  => 'integer',
            'max'  => 'integer',
            'mask' => 'string',
            'required' => 'boolean',
            'active' => 'required | boolean'
        ]);
        $field->update($data);
        return new FieldResource($field);
    }

    public function show(Field $field)
    {
        return new FieldResource($field);
    }

    public function index()
    {
        return new FieldCollection(Field::all());
    }
}
