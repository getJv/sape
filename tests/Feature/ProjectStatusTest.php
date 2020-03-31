<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->post('/api/project-status', [
            'name' => 'My first status',
            'description' => 'Description of my first status'
        ])->assertStatus(201);

        $this->assertCount(1, ProjectStatus::all());
        $projectStatus = ProjectStatus::find(1);
        $this->assertEquals('My first status', $projectStatus->name);
        $this->assertEquals('Description of my first status', $projectStatus->description);
        $this->assertEquals(true, $projectStatus->active);
        $response->assertJson([
            'data' => [
                'type' => 'project-statuses',
                'id' => $projectStatus->id,
                'attributes' => [
                    'name' => $projectStatus->name,
                    'description' => $projectStatus->description,
                ],

            ],
            'links' => [
                'self' => url('/project-status/' . $projectStatus->id)
            ]
        ]);
    }
}
