<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectEvent extends Model
{
    protected $guarded = [];

    public function project(){
        return $this->belongsTo('App\Project');
    }
    public function status(){
        return $this->belongsTo('App\ProjectStatus');
    }
}
