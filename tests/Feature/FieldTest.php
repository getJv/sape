<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Field;

class FieldTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_field()
    {

        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->post('/api/fields', [
            'name' => 'my field',
            'description' => 'my field description',
            'type' => 'integer',
        ])->assertStatus(201);

        $this->assertCount(1, Field::all());
        $field = Field::find(1);
        $this->assertEquals('my field', $field->name);
        $this->assertEquals('my field description', $field->description);
        $this->assertNotFalse($field->active);
        $response->assertJson([
            'data' => [
                'type' => 'fields',
                'id' => $field->id,
                'attributes' => [
                    'name' => $field->name,
                    'type' => $field->type,
                    'description' => $field->description,
                    'min' => $field->min,
                    'max' => $field->max,
                    'max' => $field->mask,
                    'active' => $field->active,
                ],

            ],
            'links' => [
                'self' => url('/fields/' . $field->id)
            ]
        ]);
    }

    /** @test */
    public function a_user_can_edit_a_field()
    {
        $this->withoutExceptionHandling();
        $field = factory(Field::class)->create();

        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->patch('/api/fields/' . $field->id, [
            'name' => 'My edited title',
            'description' => 'Description of my field 2',
            'max' => 3,
            'mask' => 'data',
            'active' => false
        ])->assertStatus(200);


        $field = Field::find(1);
        $this->assertEquals('My edited title', $field->name);
        $this->assertEquals('Description of my field 2', $field->description);
        $this->assertEquals(3, $field->max);
        $this->assertEquals('data', $field->mask);
        $this->assertNotTrue($field->description);

        $response->assertJson([
            'data' => [
                'type' => 'fields',
                'id' => $field->id,
                'attributes' => [
                    'name' => $field->name,
                    'type' => $field->type,
                    'description' => $field->description,
                    'min' => $field->min,
                    'max' => $field->max,
                    'mask' => $field->mask,
                    'active' => $field->active,
                ],

            ],
            'links' => [
                'self' => url('/fields/' . $field->id)
            ]
        ]);
    }

    /** @test */
    public function a_field_can_be_requested()
    {
        $this->withoutExceptionHandling();
        $field = factory(field::class)->create();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->get('/api/fields/' . $field->id)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'type' => 'fields',
                    'id' => $field->id,
                    'attributes' => [
                        'name' => $field->name,
                        'type' => $field->type,
                        'description' => $field->description,
                        'min' => $field->min,
                        'max' => $field->max,
                        'mask' => $field->mask,
                        'active' => $field->active,
                    ],

                ],
                'links' => [
                    'self' => url('/fields/' . $field->id)
                ]
            ]);
    }

    /** @test */
    public function fields_can_be_requested()
    {
        $this->withoutExceptionHandling();
        factory(Field::class)->create();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->get('/api/fields')->assertStatus(200);
        $fields = Field::all();
        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'fields',
                        'id' => $fields->first()->id,
                        'attributes' => [
                            'name' => $fields->first()->name,
                            'type' => $fields->first()->type,
                            'description' => $fields->first()->description,
                            'min' => $fields->first()->min,
                            'max' => $fields->first()->max,
                            'mask' => $fields->first()->mask,
                            'active' => $fields->first()->active,
                        ],

                    ],
                    'links' => [
                        'self' => url('/fields/' . $fields->first()->id)
                    ]
                ]
            ],
            'total_fields' => $fields->count(),
            'links' => [
                'self' => url('/fields/')
            ]
        ]);
    }
}
