<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Project;
use App\ProjectStatus;
use App\ProjectEvent;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_project_event()
    {

        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        factory(ProjectStatus::class)->create();
        factory(Project::class)->create();
        $response = $this->post('/api/project-events', [
            'project_status_id' => 1,
            'project_id' => 1,
            'name' => 'My first event',
            'description' => 'Description of my first event'
        ])->assertStatus(201);

        $this->assertCount(1, ProjectEvent::all());
        $event = ProjectEvent::find(1);
        $this->assertEquals('My first event', $event->name);
        $this->assertEquals('Description of my first event', $event->description);
        $this->assertEquals(1, $event->project_status_id);
        $this->assertEquals(1, $event->project_id);
        $this->assertNotFalse($event->active);
        $response->assertJson([
            'data' => [
                'type' => 'project-events',
                'id' => $event->id,
                'attributes' => [
                    'name' => $event->name,
                    'description' => $event->description,
                    'project_status_id' => $event->project_status_id,
                    'project_id' => $event->project_id,
                    'active' => $event->active,
                ],

            ],
            'links' => [
                'self' => url('/project-events/' . $event->id),
            ]
        ]);
    }

    /** @test */
    public function a_user_can_edit_a_project_event()
    {
        $this->withoutExceptionHandling();
        factory(ProjectStatus::class)->create();
        factory(ProjectStatus::class)->create();
        factory(Project::class)->create();
        factory(Project::class)->create();
        $projectEvent = factory(ProjectEvent::class)->create([
            'name' =>'teste',
            'description' => 'teste',
            'project_status_id' => 1,
            'project_id' => 1,
        ]);
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->patch('/api/project-events/' . $projectEvent->id, [
            'name' => 'My edited title 2',
            'description' => 'Description of my first project 2',
            'project_status_id' => 2,
            'project_id' => 2,
            'active' => false
        ])->assertStatus(200);




        $this->assertCount(1, ProjectEvent::all());
        $event = ProjectEvent::find(1);
        $this->assertEquals('My edited title 2', $event->name);
        $this->assertEquals('Description of my first project 2', $event->description);
        $this->assertEquals(2, $event->project_status_id);
        $this->assertEquals(2, $event->project_id);
        $this->assertNotTrue($event->active);
        $response->assertJson([
            'data' => [
                'type' => 'project-events',
                'id' => $event->id,
                'attributes' => [
                    'name' => $event->name,
                    'description' => $event->description,
                    'project_status_id' => $event->project_status_id,
                    'project_id' => $event->project_id,
                    'active' => $event->active,
                ],

            ],
            'links' => [
                'self' => url('/project-events/' . $event->id),
            ]
        ]);
    }
}
