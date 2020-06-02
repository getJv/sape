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
    public function retrieve_all_project_fields()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $project = factory(Project::class)->create();
        $field  = factory(Field::class)->create();
        $projectFields = factory(ProjectField::class)->create([
            'project_id' => $project->id,
            'field_id'   => $field->id,
        ]);

        $response = $this->get('/api/project-fields')->assertStatus(200);

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
                            'order' => $projectFields->order,
                            'active' => $projectFields->active,
                        ],
                    ],

                ]
            ],
            'total_project_fields' => 1,
            'links' => [
                'self' => url('/project-fields/')
            ]
        ]);
    }

    /** @test */
    public function projects_fields_are_ordered_by_order_field()
    {
        $this->withoutExceptionHandling();
        factory(Project::class)->create(['order' => 2]);
        factory(Field::class)->create();
        factory(Field::class)->create();
        factory(Field::class)->create();
        factory(ProjectField::class)->create(['project_id' => 1, 'field_id' => 1, 'order' => 3]);
        factory(ProjectField::class)->create(['project_id' => 1, 'field_id' => 1, 'order' => 1]);
        factory(ProjectField::class)->create(['project_id' => 1, 'field_id' => 1, 'order' => 2]);
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $projectFields = ProjectField::all();
        $this->assertCount(3, $projectFields);
        $response = $this->get('/api/project-fields/project/1')->assertStatus(200);
        $responseString = json_decode($response->getContent(), true);
        //Valida o ordamento da resposta
        $this->assertEquals(1, $responseString['data'][0]['data']['attributes']['order']);
        $this->assertEquals(2, $responseString['data'][1]['data']['attributes']['order']);
        $this->assertEquals(3, $responseString['data'][2]['data']['attributes']['order']);
    }

    /** @test */
    public function projects_fields_are_ordered_to_up()
    {
        $this->withoutExceptionHandling();
        $project = factory(Project::class)->create(['order' => 2]);
        factory(Field::class)->create();
        factory(Field::class)->create();
        factory(Field::class)->create();
        factory(ProjectField::class)->create(['project_id' => 1, 'field_id' => 1, 'order' => 2]);
        factory(ProjectField::class)->create(['project_id' => 1, 'field_id' => 1, 'order' => 1]);
        factory(ProjectField::class)->create(['project_id' => 1, 'field_id' => 1, 'order' => 3]);
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $projectFields =  $project->fields()->orderBy('order')->get();
        //Ordenamento anterior, projeto: 1 - Ordem: 2
        $this->assertEquals(1, $projectFields[1]->id);
        $this->assertEquals(2, $projectFields[1]->order);
        //Ordenamento anterior, projeto: 3 - Ordem: 3
        $this->assertEquals(3, $projectFields[2]->id);
        $this->assertEquals(3, $projectFields[2]->order);
        //Movimentação para posição do projeto 3 de ordem 3 para a ordem 2
        $response = $this->patch('/api/project-fields/3/up')->assertStatus(200);
        $responseString = json_decode($response->getContent(), true);
        //Ordenamento atual, projeto: 1 - Ordem: 3
        $this->assertEquals(1, $responseString['data'][2]['data']['id']);
        $this->assertEquals(3, $responseString['data'][2]['data']['attributes']['order']);
        //Ordenamento atual, projeto: 3 - Ordem: 2
        $this->assertEquals(3, $responseString['data'][1]['data']['id']);
        $this->assertEquals(2, $responseString['data'][1]['data']['attributes']['order']);
    }

    /** @test */
    public function projects_fields_are_ordered_to_down()
    {
        $this->withoutExceptionHandling();
        $project = factory(Project::class)->create(['order' => 2]);
        factory(Field::class)->create();
        factory(Field::class)->create();
        factory(Field::class)->create();
        factory(ProjectField::class)->create(['project_id' => 1, 'field_id' => 1, 'order' => 2]);
        factory(ProjectField::class)->create(['project_id' => 1, 'field_id' => 1, 'order' => 1]);
        factory(ProjectField::class)->create(['project_id' => 1, 'field_id' => 1, 'order' => 3]);
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $projectFields = $project->fields()->orderBy('order')->get();
        //Ordenamento anterior, projeto: 2 - Ordem: 1
        $this->assertEquals(2, $projectFields[0]->id);
        $this->assertEquals(1, $projectFields[0]->order);
        //Ordenamento anterior, projeto: 1 - Ordem: 2
        $this->assertEquals(1, $projectFields[1]->id);
        $this->assertEquals(2, $projectFields[1]->order);
        // movimenta o projeto do para a posição abaixo
        $response = $this->patch('/api/project-fields/2/down')->assertStatus(200);
        $responseString = json_decode($response->getContent(), true);
        //Ordenamento atual, projeto: 2 - Ordem: 2
        $this->assertEquals(2, $responseString['data'][1]['data']['id']);
        $this->assertEquals(2, $responseString['data'][1]['data']['attributes']['order']);
        //Ordenamento atual, projeto: 1 - Ordem: 1
        $this->assertEquals(1, $responseString['data'][0]['data']['id']);
        $this->assertEquals(1, $responseString['data'][0]['data']['attributes']['order']);
    }

    /** @test */
    public function projects_fields_order_reset()
    {
        $this->withoutExceptionHandling();
        $project = factory(Project::class)->create(['order' => 2]);
        factory(Field::class)->create();
        factory(Field::class)->create();
        factory(Field::class)->create();
        factory(ProjectField::class)->create(['project_id' => 1, 'field_id' => 1, 'order' => 10]);
        factory(ProjectField::class)->create(['project_id' => 1, 'field_id' => 1, 'order' => 20]);
        factory(ProjectField::class)->create(['project_id' => 1, 'field_id' => 1, 'order' => 30]);
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $projectFields = $project->fields()->orderBy('order')->get();
        $this->assertEquals(10, $projectFields[0]->order);
        $this->assertEquals(20, $projectFields[1]->order);
        $this->assertEquals(30, $projectFields[2]->order);
        $this->patch('/api/project-fields/project/1/reset')->assertStatus(200);
        $projectFields = $project->fields()->orderBy('order')->get();
        $this->assertEquals(1, $projectFields[0]->order);
        $this->assertEquals(2, $projectFields[1]->order);
        $this->assertEquals(3, $projectFields[2]->order);
    }

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
                            'order' => $projectFields->order,
                            'active' => $projectFields->active,
                        ],
                    ],

                ]
            ],
            'total_project_fields' => 1,
            'links' => [
                'self' => url('/project-fields/')
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
