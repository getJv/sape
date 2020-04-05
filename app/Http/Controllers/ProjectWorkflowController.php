<?php

namespace App\Http\Controllers;

use App\ProjectWorkflow;
use App\ProjectStatus;
use App\Project;
use App\Http\Resources\ProjectWorkflow as ProjectWorkflowResource;
use App\Exceptions\ProjectNotFoundException;
use App\Exceptions\ProjectStatusNotFoundException;
use App\Exceptions\DuplicatedWorkflowStepExcetion;
use App\Http\Resources\ProjectWorkflowCollection;
use Illuminate\Http\Request;


class ProjectWorkflowController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'project_id' => 'required',
            'old_status_id' => 'required',
            'new_status_id' => 'required'
        ]);



        if (ProjectStatus::whereIn('id', [$data['old_status_id'], $data['new_status_id']])->count() != 2) {
            throw new ProjectStatusNotFoundException();
        }

        if (!Project::where('id', '=', $data['project_id'])->exists()) {
            throw new ProjectNotFoundException();
        }


        if (ProjectWorkFlow::where([
            'id' => $data['project_id'],
            'old_status_id' =>  $data['old_status_id'],
            'new_status_id' => $data['new_status_id'],
        ])->exists()) {
            throw new DuplicatedWorkflowStepExcetion();
        }

        $nextOrder = ProjectWorkflow::where('project_id', '=', $data['project_id'])->get()->count() + 1;
        $projectWorkflow = ProjectWorkflow::create(array_merge($data, ['order' => $nextOrder]));
        return new ProjectWorkflowResource($projectWorkflow);
    }

    public function destroy(ProjectWorkflow $projectWorkflow)
    {
        $projectWorkflow->delete();
        return response()->json([
            'meta' => [
                'message' => 'A exclusão realizada com sucesso'
            ]
        ], 200);
    }

    public function projectWorkflow(Project $project)
    {
        $list = $project->workflow;
        //TODO fazer um este disso
        if ($list->count() < 1) {
            return response()->json([

                'data' => [],
                'links' => [
                    'self' => url('/project-workflows/project/' . $project->id),
                ],
            ], 200);
        }

        return new ProjectWorkflowCollection($list);
    }
}
