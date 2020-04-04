<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Project;
use App\ProjectStatus;
use App\Http\Resources\Project as ProjectResource;
use App\Http\Resources\ProjectStatus as ProjectStatusResource;
use App\ProjectWorkflow;

class ProjectWorkflowTest extends TestCase
{
    use RefreshDatabase;
    // testar se o status que o projeto esta entranto realmente faz parte do seu workflow

    /** @test */
    public function a_user_can_create_a_workflow_step()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $project = factory(Project::class)->create();
        $projectStatus1  = factory(ProjectStatus::class)->create();
        $projectStatus2  = factory(ProjectStatus::class)->create();
        $nextOrderStatus = ProjectWorkflow::where('project_id', '=', $project->id)->get()->count() + 1;
        $response = $this->post('/api/project-workflows', [
            'project_id' => $project->id,
            'old_status_id' => $projectStatus1->id,
            'new_status_id' => $projectStatus2->id,
        ])->assertStatus(201);
        $this->assertCount(1, ProjectWorkflow::all());
        $ProjectWorkflow = ProjectWorkflow::find(1);
        $this->assertEquals($project->id, $ProjectWorkflow->project_id);
        $this->assertEquals($projectStatus1->id, $ProjectWorkflow->old_status_id);
        $this->assertEquals($projectStatus2->id, $ProjectWorkflow->new_status_id);
        $this->assertEquals($nextOrderStatus, $ProjectWorkflow->order);
        $response->assertJson([
            'data' => [
                'type' => 'project-workflows',
                'id' => $ProjectWorkflow->id,
                'attributes' => [
                    'order'         => 1,
                    'project_id'    => $ProjectWorkflow->project_id,
                    'old_status_id' => $ProjectWorkflow->old_status_id,
                    'new_status_id' => $ProjectWorkflow->new_status_id,
                ],
            ],
            'links' => [
                'self' => url('/project-workflows/' . $ProjectWorkflow->id),
                'project' => [
                    'data'  => [
                        'type' => 'projects',
                        'id'   => $ProjectWorkflow->project->id,
                        'attributes' => [
                            'name' => $ProjectWorkflow->project->name,
                            'description' => $ProjectWorkflow->project->description,
                            'active' => $ProjectWorkflow->project->active,
                        ]
                    ],
                    'links' => [
                        'self' => url('/projects/' . $ProjectWorkflow->project->id)
                    ]
                ],
                'old_status' => [
                    'data'  => [
                        'type' => 'project-statuses',
                        'id'   => $ProjectWorkflow->old_status->id,
                        'attributes' => [
                            'name' => $ProjectWorkflow->old_status->name,
                            'description' => $ProjectWorkflow->old_status->description,
                            'active' => $ProjectWorkflow->old_status->active,
                        ]
                    ],
                    'links' => [
                        'self' => url('/project-statuses/' . $ProjectWorkflow->old_status->id)
                    ]
                ],
                'new_status' => [
                    'data'  => [
                        'type' => 'project-statuses',
                        'id'   => $ProjectWorkflow->new_status->id,
                        'attributes' => [
                            'name' => $ProjectWorkflow->new_status->name,
                            'description' => $ProjectWorkflow->new_status->description,
                            'active' => $ProjectWorkflow->new_status->active,
                        ]
                    ],
                    'links' => [
                        'self' => url('/project-statuses/' . $ProjectWorkflow->new_status->id)
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function workflow_step_aceita_apenas_old_status_existentes()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $project = factory(Project::class)->create();
        $projectStatus1  = factory(ProjectStatus::class)->create();
        $projectStatus2  = factory(ProjectStatus::class)->create();
        $response = $this->post('/api/project-workflows', [
            'project_id' => $project->id,
            'old_status_id' => 123,
            'new_status_id' => $projectStatus2->id,
        ])->assertStatus(404);
        $this->assertCount(0, ProjectWorkflow::all());
        $response->assertJson([
            'errors' => [
                'code'  => 404,
                'title' => 'O status de projeto não existe ou foi excluído',
                'detail' => 'O sistema tentou utilizar status de projeto inexistente ou que já foi excluído'
            ]
        ]);
    }
    /** @test */
    public function workflow_step_aceita_apenas_new_status_existentes()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $project = factory(Project::class)->create();
        $projectStatus1  = factory(ProjectStatus::class)->create();
        $projectStatus2  = factory(ProjectStatus::class)->create();
        $response = $this->post('/api/project-workflows', [
            'project_id' => $project->id,
            'old_status_id' => $projectStatus2->id,
            'new_status_id' => 123,
        ])->assertStatus(404);
        $this->assertCount(0, ProjectWorkflow::all());
        $response->assertJson([
            'errors' => [
                'code'  => 404,
                'title' => 'O status de projeto não existe ou foi excluído',
                'detail' => 'O sistema tentou utilizar status de projeto inexistente ou que já foi excluído'
            ]
        ]);
    }

    /** @test */
    public function workflow_step_aceita_apenas_projects_existentes()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $project = factory(Project::class)->create();
        $projectStatus1  = factory(ProjectStatus::class)->create();
        $projectStatus2  = factory(ProjectStatus::class)->create();
        $response = $this->post('/api/project-workflows', [
            'project_id' => 123,
            'old_status_id' => $projectStatus1->id,
            'new_status_id' => $projectStatus2->id,
        ])->assertStatus(404);
        $this->assertCount(0, ProjectWorkflow::all());
        $response->assertJson([
            'errors' => [
                'code'  => 404,
                'title' => 'O projeto não existe ou foi excluído',
                'detail' => 'O sistema tentou utilizar um projeto inexistente ou que já foi excluído'
            ]
        ]);
    }

    /** @test */
    public function duplicate_workflow_step_are_refused()
    {
        //$this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $project = factory(Project::class)->create();
        $projectStatus1  = factory(ProjectStatus::class)->create();
        $projectStatus2  = factory(ProjectStatus::class)->create();
        factory(ProjectWorkflow::class)->create([
            'project_id' => $project->id,
            'old_status_id' => $projectStatus1->id,
            'new_status_id' => $projectStatus2->id,
        ]);
        $response = $this->post('/api/project-workflows', [
            'project_id' => $project->id,
            'old_status_id' => $projectStatus1->id,
            'new_status_id' => $projectStatus2->id,
        ])->assertStatus(403);

        $this->assertCount(1, ProjectWorkflow::all());
        $response->assertJson([
            'errors' => [
                'code'  => 403,
                'title' => 'Já existe um passo de workflow com esta origem e destino.',
                'detail' => 'Não é possivel adicionar a mesma origem e destido duas vezes.'
            ]
        ]);
    }

    /** @test */
    public function remove_a_workflow_step()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        factory(ProjectWorkflow::class)->create();
        $response = $this->delete('/api/project-workflows/1')->assertStatus(200);

        $this->assertCount(0, ProjectWorkflow::all());
        $response->assertJson([
            'meta' => [
                'message' => 'A exclusão realizada com sucesso'
            ]
        ]);
    }

    /** @test */
    public function retrieve_the_project_workflow()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $project = factory(Project::class)->create();
        $projectStatus1  = factory(ProjectStatus::class)->create();
        $projectStatus2  = factory(ProjectStatus::class)->create();
        $projectWorkflow = factory(ProjectWorkflow::class)->create([
            'order' => 1,
            'project_id' => $project->id,
            'old_status_id' => $projectStatus1->id,
            'new_status_id' => $projectStatus2->id,
        ]);

        $response = $this->get('/api/project-workflows/project/1')->assertStatus(200);


        $response->assertJson([
            'data' => [
                [

                    'data' => [
                        'type' => 'project-workflows',
                        'id' => $projectWorkflow->id,
                        'attributes' => [
                            'order'         => 1,
                            'project_id'    => $projectWorkflow->project_id,
                            'old_status_id' => $projectWorkflow->old_status_id,
                            'new_status_id' => $projectWorkflow->new_status_id,
                        ],
                    ],

                ]
            ],
            'workflow_steps' => 1,
            'links' => [
                'self' => url('/project-workflows/project/' . $project->id)
            ]
        ]);
    }
}
