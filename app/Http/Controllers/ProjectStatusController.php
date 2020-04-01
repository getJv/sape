<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProjectStatus;
use App\Http\Resources\ProjectStatus  as ProjectStatusResource;
use App\Http\Resources\ProjectStatusCollection;

class ProjectStatusController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
            'description' => 'required',
        ]);
        $projectStatus = ProjectStatus::create(array_merge($data, ['active' => true]));
        return new ProjectStatusResource($projectStatus);
    }
    public function update(ProjectStatus $projectStatus)
    {
        $data = request()->validate([
            'name' => 'required',
            'description' => 'required',
            'active' => 'required'
        ]);
        $projectStatus->update($data);

        return new ProjectStatusResource($projectStatus);
    }
    public function show(ProjectStatus $projectStatus)
    {
        return new ProjectStatusResource($projectStatus);
    }
    public function index()
    {
        return new ProjectStatusCollection(ProjectStatus::all());
    }
}
