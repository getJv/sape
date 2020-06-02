<?php

namespace App\Http\Controllers;

use App\Http\Resources\Project as ProjectResource;
use App\Http\Resources\ProjectCollection;
use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Reset a ordem de todos os projetos tendo seu ordenamento como padrão.
     */
    public function orderReset()
    {


        $projects = Project::all();
        $index = 1;
        foreach ($projects as $project) {

            $project->update(['order' => $index++]);
        }

        return new ProjectCollection(Project::orderBy('order')->get());
    }
    public function orderUp(Project $project)
    {


        $beforeProject = Project::where('order', $project->order - 1)->first();
        $beforeProject->update(['order' => $beforeProject->order + 1]);
        $project->update(['order' => $project->order - 1]);

        return new ProjectCollection(Project::orderBy('order')->get());
    }

    public function orderDown(Project $project)
    {


        $afterProject = Project::where('order', $project->order + 1)->first();
        $afterProject->update(['order' => $afterProject->order - 1]);
        $project->update(['order' => $project->order + 1]);

        return new ProjectCollection(Project::orderBy('order')->get());
    }

    public function store()
    {
        $data = request()->validate([
            'name'        => 'required',
            'description' => 'required',
        ]);
        $nextOrder = Project::all()->count() + 1;
        $project = Project::create(array_merge($data, ['active' => true, 'order' => $nextOrder]));
        return new ProjectResource($project);
    }

    public function update(Project $project)
    {
        $data = request()->validate([
            'name'        => 'required',
            'description' => 'required',
            'active'      => 'required',
        ]);

        $project->update($data);
        return new ProjectResource($project);
    }

    public function index()
    {
        return new ProjectCollection(Project::orderBy('order')->get());
    }

    public function show(Project $project)
    {
        return new ProjectResource($project);
    }
}
