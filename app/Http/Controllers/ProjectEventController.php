<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProjectEvent;
use App\Http\Resources\ProjectEvent as ProjectEventResource;


class ProjectEventController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'project_id' => 'required',
            'project_status_id' => 'required',
            'name' => 'required',
            'description' => 'required',
        ]);
        $projectEvent = ProjectEvent::create(array_merge($data, ['active' => true]));
        return new ProjectEventResource($projectEvent);
    }
    public function update(ProjectEvent $projectEvent)
    {

        $data = request()->validate([
            'name' => 'required',
            'description' => 'required',
            'project_id' => 'required',
            'project_status_id' => 'required',
            'active' => 'required'
        ]);

        $projectEvent->update($data);

        return new ProjectEventResource($projectEvent);
    }
}
