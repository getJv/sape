<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectField extends Model
{
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo('App\Project');
    }
    public function field()
    {
        return $this->belongsTo('App\Field');
    }
}
