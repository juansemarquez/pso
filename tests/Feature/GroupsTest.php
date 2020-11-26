<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Group;
use App\Models\Teacher;

class GroupTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    public function setUp():void
    {
        parent::setUp();
        // seed the database
        $this->artisan('db:seed');
    }
    /**
     * A teacher can create groups.
     *
     * @return void
     */
    public function test_a_teacher_can_create_a_group()
    {
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher->user);
        $attributes = [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
        ];

        $this->post('/groups', $attributes);

        $this->assertDataBaseHas('groups', [
                                    'name' => $attributes['name'],
                                    'description' => $attributes['description']        
                                ]);

        $this->get('/groups')->assertSee($attributes['name']);
        $this->get('/groups')->assertSee($attributes['description']);
    }

    public function test_a_teacher_can_see_and_edit_existing_groups()
    {
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher->user);
        $group = Group::factory()->create(['teacher_id'=>$teacher->id]);
        $this->get('/groups')->assertSee($group->name);
        $this->get('/groups')->assertSee($group->description);
        $attributes = [
            'name' =>'Modified name', 
            'description' => 'Modified description'
        ];
        $this->put('groups/'.$group->id, $attributes);
        $this->get('/groups')->assertSee($attributes['name']);
        $this->get('/groups')->assertSee($attributes['description']);
    }

    public function test_a_teacher_can_delete_a_group()
    {
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher->user);
        $group = new Group();
        $group->name = "To delete";
        $group->description = "This group will be deleted";
        $group->teacher()->associate($teacher);
        $group->save();

        $this->get('/groups')->assertSee($group->name);
        $this->get('/groups')->assertSee($group->description);

        $this->delete('groups/'.$group->id);

        $this->get('/groups')->assertDontSee($group->name);
        $this->get('/groups')->assertDontSee($group->description);
    }

    public function test_a_teacher_cannot_see_other_teachers_groups()
    {
        $teacher = Teacher::factory()->create();
        $group = new Group();
        $group->name = "To delete";
        $group->description = "This group will be deleted";
        $group->teacher()->associate($teacher);
        $group->save();

        $teacher2 = Teacher::factory()->create();
        $this->actingAs($teacher2->user);
        $this->get('/groups')->assertDontSee($group->name);
        $this->get('/groups')->assertDontSee($group->description);
    }
}
