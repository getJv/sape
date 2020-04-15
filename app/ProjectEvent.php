<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectEvent extends Model
{
    protected $guarded = [];

    public function project_workflow(){
        return $this->belongsTo('App\ProjectWorkflow');
    }
}
