<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $guarded = [];

    public function status()
    {
        return $this->belongsTo('App\ProjectStatus');
    }
}
