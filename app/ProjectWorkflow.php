<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectWorkflow extends Model
{
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo('App\Project');
    }
    public function old_status()
    {
        return $this->belongsTo('App\ProjectStatus');
    }
    public function new_status()
    {
        return $this->belongsTo('App\ProjectStatus');
    }
}
