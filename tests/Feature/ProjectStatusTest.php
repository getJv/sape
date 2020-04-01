<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\ProjectStatus;

class ProjectStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->post('/api/project-statuses', [
            'name' => 'My first status',
            'description' => 'Description of my first status',
        ])->assertStatus(201);
        $this->assertCount(1, ProjectStatus::all());
        $projectStatus = ProjectStatus::find(1);
        $this->assertEquals('My first status', $projectStatus->name);
        $this->assertEquals('Description of my first status', $projectStatus->description);
        $this->assertNotFalse($projectStatus->active);
        $response->assertJson([
            'data' => [
                'type' => 'project-statuses',
                'id' => $projectStatus->id,
                'attributes' => [
                    'name' => $projectStatus->name,
                    'description' => $projectStatus->description,
                    'active' => $projectStatus->active
                ],

            ],
            'links' => [
                'self' => url('/project-statuses/' . $projectStatus->id)
            ]
        ]);
    }

    /** @test */
    public function a_user_can_edit_a_project_status()
    {
        $this->withoutExceptionHandling();
        $projectStatus = factory(ProjectStatus::class)->create();

        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->patch('/api/project-statuses/' . $projectStatus->id, [
            'name' => 'My edited status title',
            'description' => 'Description ofXXX my first project',
            'active' => false
        ])->assertStatus(200);

        $projectStatus = ProjectStatus::find(1);
        $this->assertEquals('My edited status title', $projectStatus->name);
        $this->assertEquals('Description ofXXX my first project', $projectStatus->description);
        $this->assertNotTrue($projectStatus->active);

        $response->assertJson([
            'data' => [
                'type' => 'project-statuses',
                'id' => $projectStatus->id,
                'attributes' => [
                    'name' => $projectStatus->name,
                    'description' => $projectStatus->description,
                    'active' => $projectStatus->active
                ],

            ],
            'links' => [
                'self' => url('/project-statuses/' . $projectStatus->id)
            ]
        ]);
    }

    /** @test */
    public function a_project_status_can_be_requested()
    {
        $this->withoutExceptionHandling();
        $projectStatus = factory(ProjectStatus::class)->create();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->get('/api/project-statuses/' . $projectStatus->id)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'type' => 'project-statuses',
                    'id' => $projectStatus->id,
                    'attributes' => [
                        'name' => $projectStatus->name,
                        'description' => $projectStatus->description,
                        'active' => $projectStatus->active,
                    ],

                ],
                'links' => [
                    'self' => url('/project-statuses/' . $projectStatus->id)
                ]
            ]);
    }
    /** @test */
    public function project_statuses_can_be_requested()
    {
        $this->withoutExceptionHandling();
        factory(ProjectStatus::class)->create();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->get('/api/project-statuses')->assertStatus(200);
        $projectStatueses = ProjectStatus::all();
        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        "type" => 'project-statuses',
                        'id' => $projectStatueses->first()->id,
                        'attributes' => [
                            'name' => $projectStatueses->first()->name,
                            'description' => $projectStatueses->first()->description,
                            'active' => $projectStatueses->first()->active
                        ]
                    ],
                    'links' => [
                        'self' => url('/project-statuses/' . $projectStatueses->first()->id)
                    ]
                ]
            ],
            'project_statuses_count' => $projectStatueses->count(),
            'links' => [
                'self' => url('/project-statuses')
            ]
        ]);
    }
    /** @test */
    public function name_is_required_to_create_a_project_status()
    {

        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->post('/api/project-statuses', [
            'name' => '',
            'description' => 'Description of my first project'
        ])->assertStatus(422);

        $responseString = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('name', $responseString['errors']['meta']);
    }
    /** @test */
    public function description_is_required_to_create_a_project_status()
    {

        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->post('/api/project-statuses', [
            'name' => 'afdsfdsf sd ',
            'description' => ''
        ])->assertStatus(422);

        $responseString = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('description', $responseString['errors']['meta']);
    }
    /** @test */
    public function active_field_is_required_to_edit_a_project_status()
    {

        $this->actingAs($user = factory(User::class)->create(), 'api');
        factory(ProjectStatus::class)->create();
        $response = $this->patch('/api/project-statuses/1', [
            'name' => 'afdsfdsf sd ',
            'description' => 'fds fd d d ',
            'active' => ''
        ])->assertStatus(422);

        $responseString = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('active', $responseString['errors']['meta']);
    }
    /** @test */
    public function name_field_is_required_to_edit_a_project_status()
    {

        $this->actingAs($user = factory(User::class)->create(), 'api');
        factory(ProjectStatus::class)->create();
        $response = $this->patch('/api/project-statuses/1', [
            'name' => '',
            'description' => 'fds fd d d ',
            'active' => true
        ])->assertStatus(422);

        $responseString = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('name', $responseString['errors']['meta']);
    }
    /** @test */
    public function description_field_is_required_to_edit_a_project_status()
    {

        $this->actingAs($user = factory(User::class)->create(), 'api');
        factory(ProjectStatus::class)->create();
        $response = $this->patch('/api/project-statuses/1', [
            'name' => 'dfd fd ',
            'description' => '',
            'active' => true
        ])->assertStatus(422);

        $responseString = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('description', $responseString['errors']['meta']);
    }
}
