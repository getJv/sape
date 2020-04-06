<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\ProjectField;
use App\Http\Resources\ProjectField as ProjectFieldResource;
use App\Http\Resources\ProjectFieldCollection;
use App\Exceptions\DuplicatedProjectFieldException;


class ProjectFieldController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'project_id' => 'required',
            'field_id' => 'required',
        ]);

        if (ProjectField::where([
            'project_id' =>  $data['project_id'],
            'field_id' => $data['field_id'],
        ])->exists()) {
            throw new DuplicatedProjectFieldException();
        }


        $projectField = ProjectField::create(array_merge($data, ['active' => true]));
        return new ProjectFieldResource($projectField);
    }

    public function update(ProjectField $projectField)
    {
        $data = request()->validate([
            'value' => '',
            'active' => 'required'
        ]);
        $projectField->update($data);

        return new ProjectFieldResource($projectField);
    }

    public function projectFields(Project $project)
    {
        $list = $project->fields;

        if ($list->count() < 1) {
            return response()->json([

                'data' => [],
                'total_project_fields' => $list->count(),
                'links' => [
                    'self' => url('/project-fields/project/' . $project->id),
                ],
            ], 200);
        }

        return new ProjectFieldCollection($list);
    }
}
