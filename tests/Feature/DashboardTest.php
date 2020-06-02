<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Project;
use App\ProjectField;
use App\Field;
use App\Dashboard;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_group_dashboard_item()
    {

        $this->withoutExceptionHandling();
        $project = factory(Project::class)->create();
        $project2 = factory(Project::class)->create();
        $project3 = factory(Project::class)->create();
        $field = factory(Field::class)->create(['type' => 'enumField']);
        $project = factory(ProjectField::class)->create(['project_id' => $project->id, 'field_id' => $field->id, 'value' => 'RJ']);
        $project = factory(ProjectField::class)->create(['project_id' => $project2->id, 'field_id' => $field->id, 'value' => 'RJ']);
        $project = factory(ProjectField::class)->create(['project_id' => $project3->id, 'field_id' => $field->id, 'value' => 'SP']);

        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->post('/api/dashboards', [
            'field_id'  => 1,
            'operation' => "group"
        ])->assertStatus(201);

        $this->assertCount(1, Dashboard::all());
        $dashboard = Dashboard::find(1);
        $this->assertEquals(1, $dashboard->field_id);
        $this->assertEquals('group', $dashboard->operation);
        $response->assertJson([
            'data' => [
                'type' => 'dashboards',
                'id'   =>  $dashboard->id,
                'attributes' => [
                    'field_id'  => $dashboard->field_id,
                    'operation' => $dashboard->operation,
                    'data' => [
                        [
                            'item'  => 'RJ',
                            'value' => 2
                        ],
                        [
                            'item'  => 'SP',
                            'value' => 1
                        ],
                    ]
                ],

            ],
            'links' => [
                'self' => url('/dashboards/' . $dashboard->id)
            ]
        ]);
    }

    /** @test */
    public function a_user_can_create_table_dashboard_item()
    {

        $this->withoutExceptionHandling();
        $project = factory(Project::class)->create(['name'  => 'proj1']);
        $project2 = factory(Project::class)->create(['name' => 'proj2']);
        $project3 = factory(Project::class)->create(['name' => 'proj3']);
        $field = factory(Field::class)->create(['type' => 'textField']);
        factory(ProjectField::class)->create(['project_id' => $project->id, 'field_id' => $field->id, 'value' => 'Azul']);
        factory(ProjectField::class)->create(['project_id' => $project2->id, 'field_id' => $field->id, 'value' => 'Vermelho']);
        factory(ProjectField::class)->create(['project_id' => $project3->id, 'field_id' => $field->id, 'value' => 'Amarelo']);

        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->post('/api/dashboards', [
            'field_id'  => 1,
            'operation' => "table"
        ])->assertStatus(201);

        $this->assertCount(1, Dashboard::all());
        $dashboard = Dashboard::find(1);
        $this->assertEquals(1, $dashboard->field_id);
        $this->assertEquals('table', $dashboard->operation);

        $response->assertJson([
            'data' => [
                'type' => 'dashboards',
                'id'   =>  $dashboard->id,
                'attributes' => [
                    'field_id'  => $dashboard->field_id,
                    'operation' => $dashboard->operation,
                    'data' => [
                        [
                            'item'  => $project->name,
                            'value' => 'Azul'
                        ],
                        [
                            'item'  => $project2->name,
                            'value' => 'Vermelho'
                        ],
                        [
                            'item'  => $project3->name,
                            'value' => 'Amarelo'
                        ],
                    ]
                ],

            ],
            'links' => [
                'self' => url('/dashboards/' . $dashboard->id)
            ]
        ]);
    }

    /** @test */
    public function a_user_can_create_sum_dashboard_item()
    {

        $this->withoutExceptionHandling();
        $project = factory(Project::class)->create(['name'  => 'proj1']);
        $project2 = factory(Project::class)->create(['name' => 'proj2']);
        $project3 = factory(Project::class)->create(['name' => 'proj3']);
        $field = factory(Field::class)->create(['type' => 'numField']);
        factory(ProjectField::class)->create(['project_id' => $project->id, 'field_id' => $field->id, 'value'  => '10,00']);
        factory(ProjectField::class)->create(['project_id' => $project2->id, 'field_id' => $field->id, 'value' => '20,00']);
        factory(ProjectField::class)->create(['project_id' => $project3->id, 'field_id' => $field->id, 'value' => '30,00']);

        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->post('/api/dashboards', [
            'field_id'  => 1,
            'operation' => "sum"
        ])->assertStatus(201);

        $this->assertCount(1, Dashboard::all());
        $dashboard = Dashboard::find(1);
        $this->assertEquals(1, $dashboard->field_id);
        $this->assertEquals('sum', $dashboard->operation);

        $response->assertJson([
            'data' => [
                'type' => 'dashboards',
                'id'   =>  $dashboard->id,
                'attributes' => [
                    'field_id'  => $dashboard->field_id,
                    'operation' => $dashboard->operation,
                    'data' => [
                        [
                            'item'  => 'Somatório',
                            'value' => '60'
                        ]
                    ]
                ],

            ],
            'links' => [
                'self' => url('/dashboards/' . $dashboard->id)
            ]
        ]);
    }

    /** @test */
    public function a_user_can_create_avg_dashboard_item()
    {

        $this->withoutExceptionHandling();
        $project = factory(Project::class)->create(['name'  => 'proj1']);
        $project2 = factory(Project::class)->create(['name' => 'proj2']);
        $project3 = factory(Project::class)->create(['name' => 'proj3']);
        $field = factory(Field::class)->create(['type' => 'numField']);
        factory(ProjectField::class)->create(['project_id' => $project->id, 'field_id' => $field->id, 'value'  => '10,00']);
        factory(ProjectField::class)->create(['project_id' => $project2->id, 'field_id' => $field->id, 'value' => '20,00']);
        factory(ProjectField::class)->create(['project_id' => $project3->id, 'field_id' => $field->id, 'value' => '30,00']);

        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->post('/api/dashboards', [
            'field_id'  => 1,
            'operation' => "avg"
        ])->assertStatus(201);

        $this->assertCount(1, Dashboard::all());
        $dashboard = Dashboard::find(1);
        $this->assertEquals(1, $dashboard->field_id);
        $this->assertEquals('avg', $dashboard->operation);

        $response->assertJson([
            'data' => [
                'type' => 'dashboards',
                'id'   =>  $dashboard->id,
                'attributes' => [
                    'field_id'  => $dashboard->field_id,
                    'operation' => $dashboard->operation,
                    'data' => [
                        [
                            'item'  => 'Média aritimética',
                            'value' => '20'
                        ]
                    ]
                ],

            ],
            'links' => [
                'self' => url('/dashboards/' . $dashboard->id)
            ]
        ]);
    }

    /** @test */
    public function retrieve_dashboard_items()
    {

        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');

        $project = factory(Project::class)->create(['name'  => 'proj1']);
        $project2 = factory(Project::class)->create(['name'  => 'proj2']);

        $field = factory(Field::class)->create(['type' => 'enumField']);
        $field2 = factory(Field::class)->create(['type' => 'numbeField']);
        $field3 = factory(Field::class)->create(['type' => 'textField']);
        $field4 = factory(Field::class)->create(['type' => 'numberField']);

        factory(ProjectField::class)->create(['project_id' => $project->id, 'field_id' => $field->id, 'value'  => 'RJ']);
        factory(ProjectField::class)->create(['project_id' => $project2->id, 'field_id' => $field->id, 'value' => 'RJ']);
        $this->post('/api/dashboards', [
            'field_id'  => $field->id,
            'operation' => "group"
        ])->assertStatus(201);


        factory(ProjectField::class)->create(['project_id' => $project->id, 'field_id' => $field2->id, 'value'  => '10,00']);
        factory(ProjectField::class)->create(['project_id' => $project2->id, 'field_id' => $field2->id, 'value' => '20,00']);
        $this->post('/api/dashboards', [
            'field_id'  => $field2->id,
            'operation' => "sum"
        ])->assertStatus(201);

        factory(ProjectField::class)->create(['project_id' => $project->id, 'field_id' => $field3->id, 'value'  => 'Vermelho']);
        factory(ProjectField::class)->create(['project_id' => $project2->id, 'field_id' => $field3->id, 'value' => 'Azul']);
        $this->post('/api/dashboards', [
            'field_id'  => $field3->id,
            'operation' => "table"
        ])->assertStatus(201);


        factory(ProjectField::class)->create(['project_id' => $project->id, 'field_id' => $field4->id, 'value'  => '10,00']);
        factory(ProjectField::class)->create(['project_id' => $project2->id, 'field_id' => $field4->id, 'value' => '20,00']);
        $this->post('/api/dashboards', [
            'field_id'  => $field4->id,
            'operation' => "avg"
        ])->assertStatus(201);


        $dasboards = Dashboard::all();

        $this->assertCount(4, Dashboard::all());
        $response = $this->get('/api/dashboards')->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'dashboards',
                        'id'   =>  $dasboards[0]->id,
                        'attributes' => [
                            'field_id'  => $dasboards[0]->field_id,
                            'operation' => $dasboards[0]->operation,
                            'data' => [
                                [
                                    'item'  => 'RJ',
                                    'value' => 2
                                ]
                            ]
                        ],

                    ],
                    'links' => [
                        'self' => url('/dashboards/' . $dasboards[0]->id)
                    ]
                ],
                [
                    'data' => [
                        'type' => 'dashboards',
                        'id'   =>  $dasboards[1]->id,
                        'attributes' => [
                            'field_id'  => $dasboards[1]->field_id,
                            'operation' => $dasboards[1]->operation,
                            'data' => [
                                [
                                    'item'  => 'Somatório',
                                    'value' => '30'
                                ]
                            ]
                        ],

                    ],
                    'links' => [
                        'self' => url('/dashboards/' . $dasboards[1]->id)
                    ]
                ],
                [
                    'data' => [
                        'type' => 'dashboards',
                        'id'   =>  $dasboards[2]->id,
                        'attributes' => [
                            'field_id'  => $dasboards[2]->field_id,
                            'operation' => $dasboards[2]->operation,
                            'data' => [
                                [
                                    'item'  => $project->name,
                                    'value' => 'Vermelho'
                                ],
                                [
                                    'item'  => $project2->name,
                                    'value' => 'Azul'
                                ]
                            ]
                        ],

                    ],
                    'links' => [
                        'self' => url('/dashboards/' .  $dasboards[2]->id)
                    ]
                ],
                [
                    'data' => [
                        'type' => 'dashboards',
                        'id'   =>  $dasboards[3]->id,
                        'attributes' => [
                            'field_id'  => $dasboards[3]->field_id,
                            'operation' => $dasboards[3]->operation,
                            'data' => [
                                [
                                    'item'  => 'Média aritimética',
                                    'value' => '15'
                                ]
                            ]
                        ],

                    ],
                    'links' => [
                        'self' => url('/dashboards/' . $dasboards[3]->id)
                    ]
                ]
            ],
            'total_dashboards' => $dasboards->count(),
            'links' => [
                'self' => url('/dashboards/')
            ]
        ]);
    }
}
