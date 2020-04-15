<?php

namespace App\Http\Controllers;

use App\Http\Resources\Project as ProjectResource;
use App\Http\Resources\ProjectCollection;
use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller {
    public function store() {
        $data = request()->validate([
            'name'        => 'required',
            'description' => 'required',
        ]);
        $project = Project::create(array_merge($data, ['active' => true]));
        return new ProjectResource($project);
    }

    public function update(Project $project) {
        $data = request()->validate([
            'name'        => 'required',
            'description' => 'required',
            'active'      => 'required',
        ]);

        $project->update($data);
        return new ProjectResource($project);
    }

    public function index() {
        return new ProjectCollection(Project::all());
    }

    public function show(Project $project) {
        return new ProjectResource($project);
    }

}
