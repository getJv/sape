<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Project extends Model
{
    protected $guarded = [];

    public function workflow()
    {
        return $this->hasMany('App\ProjectWorkflow');
    }
}
