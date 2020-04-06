<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Project;
use App\Field;
use App\ProjectField;

class projectFieldTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_receive_new_fields()
    {
        $this->withoutExceptionHandling();
        $project = factory(Project::class)->create();
        $field   = factory(Field::class)->create();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->post('/api/project-fields', [
            'project_id' => $project->id,
            'field_id'   => $field->id,
        ])->assertStatus(201);

        $this->assertCount(1, ProjectField::all());
        $projectField = ProjectField::find(1);
        $this->assertEquals(1, $projectField->project_id);
        $this->assertEquals(1, $projectField->field_id);
        $this->assertNotFalse($projectField->active);
        $response->assertJson([
            'data' => [
                'type' => 'project-fields',
                'id' => $projectField->id,
                'attributes' => [
                    'project_id' => $projectField->project_id,
                    'field_id'   => $projectField->field_id,
                    'active'     => $projectField->active,
                ],

            ],
            'links' => [
                'self' => url('/project-fields/' . $projectField->id),
                /*  'project' => new ProjectResource($projectField->project),
                'field' => new FieldResource($projectField->field) */
            ]

        ]);
    }

    /** @test */
    public function duplicate_project_field_are_refused()
    {
        //$this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $project = factory(Project::class)->create();
        $field   = factory(Field::class)->create();
        factory(ProjectField::class)->create([
            'project_id' => $project->id,
            'field_id'    => $field->id,
        ]);

        $response = $this->post('/api/project-fields', [
            'project_id' => $project->id,
            'field_id'   => $field->id,
        ])->assertStatus(403);

        $this->assertCount(1, ProjectField::all());
        $response->assertJson([
            'errors' => [
                'code'  => 403,
                'title' => 'Este campo já foi vinculado ao projeto',
                'detail' => 'Não é possível vincular o compo ao projeto mais de uma vez'
            ]
        ]);
    }

    /** @test */
    public function retrieve_the_project_fields()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $project = factory(Project::class)->create();
        $field  = factory(Field::class)->create();
        $projectFields = factory(ProjectField::class)->create([
            'project_id' => $project->id,
            'field_id'   => $field->id,
        ]);

        $response = $this->get('/api/project-fields/project/1')->assertStatus(200);

        $response->assertJson([
            'data' => [
                [

                    'data' => [
                        'type' => 'project-fields',
                        'id' => $projectFields->id,
                        'attributes' => [
                            'project_id'    => $projectFields->project_id,
                            'field_id' => $projectFields->field_id,
                            'value' => $projectFields->value,
                            'active' => $projectFields->active,
                        ],
                    ],

                ]
            ],
            'total_project_fields' => 1,
            'links' => [
                'self' => url('/project-fields/project/' . $project->id)
            ]
        ]);
    }

    /** @test */
    public function retrieve_empty_the_project_fields()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        factory(Project::class)->create();
        factory(Field::class)->create();
        factory(ProjectField::class)->create();
        $response = $this->get('/api/project-fields/project/1')->assertStatus(200);

        $response->assertJson([
            'data' => [],
            'total_project_fields' => 0,
            'links' => [
                'self' => url('/project-fields/project/1')
            ]
        ]);
    }

    /** @test */
    public function a_user_can_edit_a_project_field()
    {
        $this->withoutExceptionHandling();

        $projectField = factory(ProjectField::class)->create();

        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->patch('/api/project-fields/' . $projectField->id, [
            'value' => 'kkkkk',
            'active' => false
        ])->assertStatus(200);

        $projectField = ProjectField::find(1);
        $this->assertEquals('kkkkk', $projectField->value);
        $this->assertNotTrue($projectField->active);

        $response->assertJson([
            'data' => [
                'type' => 'project-fields',
                'id' => $projectField->id,
                'attributes' => [
                    'project_id' => $projectField->project_id,
                    'field_id'   => $projectField->field_id,
                    'value'      => $projectField->value,
                    'active'     => $projectField->active,
                ],

            ],
            'links' => [
                'self' => url('/project-fields/' . $projectField->id),
                /*  'project' => new ProjectResource($projectField->project),
                'field' => new FieldResource($projectField->field) */
            ]
        ]);
    }
}
