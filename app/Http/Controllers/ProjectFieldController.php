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

    public function index()
    {
        return new ProjectFieldCollection(ProjectField::where('active', true)->orderBy('id')->get());
    }

    /**
     * Reset a ordem de todos os projetos tendo seu ordenamento como padrão.
     */
    public function orderReset(Project $project)
    {


        $projectFields = $project->fields()->get();
        $index = 1;
        foreach ($projectFields as $projectField) {

            $projectField->update(['order' => $index++]);
        }

        return new ProjectFieldCollection(ProjectField::orderBy('order')->get());
    }
    public function orderUp(ProjectField $projectField)
    {

        $beforeProjectField = ProjectField::where('order', $projectField->order - 1)->first();
        $beforeProjectField->update(['order' => $beforeProjectField->order + 1]);
        $projectField->update(['order' => $projectField->order - 1]);

        return new ProjectFieldCollection(ProjectField::orderBy('order')->get());
    }

    public function orderDown(ProjectField $projectField)
    {


        $afterProjectField = ProjectField::where('order', $projectField->order + 1)->first();
        $afterProjectField->update(['order' => $afterProjectField->order - 1]);
        $projectField->update(['order' => $projectField->order + 1]);

        return new ProjectFieldCollection(ProjectField::orderBy('order')->get());
    }
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

        $nextOrder = ProjectField::all()->count() + 1;
        $projectField = ProjectField::create(array_merge($data, ['active' => true, 'order' => $nextOrder]));
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
        $list = $project->fields()->orderBy('order')->get();

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
