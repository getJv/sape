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
            'project_workflow_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'owner_id' => 'required',
        ]);
        $projectEvent = ProjectEvent::create(array_merge($data, ['active' => true]));
        return new ProjectEventResource($projectEvent);
    }
    public function update(ProjectEvent $projectEvent)
    {
        /**
         * TODO: Colocar os outros tipos de validação, integer Boolean e fazer seus teste
         */
        $data = request()->validate([
            'name' => 'required',
            'description' => 'required',
            'owner_id' => 'required',
            'project_workflow_id' => 'required',
            'active' => 'required'
        ]);

        $projectEvent->update($data);

        return new ProjectEventResource($projectEvent);
    }

    public function show(ProjectEvent $projectEvent)
    {
        return new ProjectEventResource($projectEvent);
    }
}
