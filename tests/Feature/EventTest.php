<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Project;
use App\ProjectStatus;
use App\ProjectEvent;
use App\ProjectWorkflow;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_project_event()
    {

        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $projectStatus1 = factory(ProjectStatus::class)->create();
        $projectStatus2 = factory(ProjectStatus::class)->create();
        $project = factory(Project::class)->create();
        $projectWorkflow = factory(ProjectWorkflow::class)->create([
            'order' => 1,
            'project_id' => $project->id,
            'old_status_id' => $projectStatus1->id,
            'new_status_id' => $projectStatus2->id,
        ]);


        $response = $this->post('/api/project-events', [
            'project_workflow_id' => $projectWorkflow->id,
            'name' => 'My first event',
            'description' => 'Description of my first event'
        ])->assertStatus(201);

        $this->assertCount(1, ProjectEvent::all());
        $event = ProjectEvent::find(1);
        $this->assertEquals('My first event', $event->name);
        $this->assertEquals('Description of my first event', $event->description);
        $this->assertEquals(1, $event->project_workflow_id);
        $this->assertNotFalse($event->active);

        $response->assertJson([
            'data' => [
                'type' => 'project-events',
                'id' => $event->id,
                'attributes' => [
                    'name' => $event->name,
                    'description' => $event->description,
                    'project_workflow_id' => $event->project_workflow_id,
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
            'project_workflow_id' => 1,
        ]);
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->patch('/api/project-events/' . $projectEvent->id, [
            'name' => 'My edited title 2',
            'description' => 'Description of my first project 2',
            'project_workflow_id' => 2,
            'active' => false
        ])->assertStatus(200);




        $this->assertCount(1, ProjectEvent::all());
        $event = ProjectEvent::find(1);
        $this->assertEquals('My edited title 2', $event->name);
        $this->assertEquals('Description of my first project 2', $event->description);
        $this->assertEquals(2, $event->project_workflow_id);
        $this->assertNotTrue($event->active);
        $response->assertJson([
            'data' => [
                'type' => 'project-events',
                'id' => $event->id,
                'attributes' => [
                    'name' => $event->name,
                    'description' => $event->description,
                    'project_workflow_id' => $event->project_workflow_id,
                    'active' => $event->active,
                ],

            ],
            'links' => [
                'self' => url('/project-events/' . $event->id),
            ]
        ]);
    }

     /** @test */
     public function a_event_can_be_requested()
     {
         $this->withoutExceptionHandling();
         $projectEvent = factory(ProjectEvent::class)->create();
         $this->actingAs($user = factory(User::class)->create(), 'api');
         $response = $this->get('/api/project-events/' . $projectEvent->id)
             ->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'type' => 'project-events',
                     'id' => $projectEvent->id,
                     'attributes' => [
                         'name' => $projectEvent->name,
                         'description' => $projectEvent->description,
                         'project_workflow_id' => $projectEvent->project_workflow_id,
                         'active' => $projectEvent->active,
                     ],

                 ],
                 'links' => [
                     'self' => url('/project-events/' . $projectEvent->id)
                 ]
             ]);
     }
}
