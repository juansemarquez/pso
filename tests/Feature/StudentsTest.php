<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \App\Models\Teacher;
use \App\Models\Student;
use \App\Models\User;

class StudentsTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    public function setUp():void
    {
        parent::setUp();
        // seed the database
        $this->artisan('db:seed');
    }

    public function test_a_teacher_can_create_a_student()
    {
        //$this->withoutExceptionHandling();
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher->user);

        $password = $this->faker->password();
        $group = \App\Models\Group::factory()->create(['teacher_id'=>$teacher->id]);
        //$group->save();
        //$group = \App\Models\Group::where('teacher_id', $teacher->id)->first();
        $attributes = [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $password,
            'password-repeat' => $password,
            "group" => [
                $group->id => $group->id
            ] 
        ];

        $this->post('/students', $attributes);

        $this->assertDataBaseHas('students', [
                                    'first_name' => $attributes['first_name'],
                                    'last_name' => $attributes['last_name'],
                                ]);

        $this->assertDataBaseHas('users', [
                                    'email' => $attributes['email'],
                                ]);
        $this->get('/students')->assertSee($attributes['first_name']);
        $this->get('/students')->assertSee($attributes['last_name']);
        $this->get('/students')->assertSee($attributes['email']);
    }

    public function test_a_teacher_can_see_and_edit_existing_students()
    {
        $this->withoutExceptionHandling();
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher->user);
        $student = Student::factory()->create();
        $group = \App\Models\Group::factory()->create(['teacher_id'=>$teacher->id]);
        $student->groups()->attach($group);
        $student->save();
        $this->get('/students')->assertSee($student->first_name);
        $this->get('/students')->assertSee($student->last_name);
        $attributes = [
            'first_name' =>'Modified name', 
            'last_name' => 'Modified surname',
            'email' => 'modified@email.com'
        ];
        $this->put('/students/'.$student->id, $attributes);
        $this->get('/students')->assertSee($attributes['first_name']);
        $this->get('/students')->assertSee($attributes['last_name']);
        $this->get('/students')->assertSee($attributes['email']);
    }

    public function test_a_teacher_can_delete_a_student()
    {
        $this->withoutExceptionHandling();
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher->user);
        $student = Student::factory()->create();
        //$student->first_name = "To delete";
        //$student->last_name = "Last name will be deleted";
        //$student->teacher()->associate($teacher);
        //$student->save();

        $this->get('/students')->assertSee($student->name);
        $this->get('/students')->assertSee($student->description);

        $this->delete('students/'.$student->id);

        $this->get('/students')->assertDontSee($student->first_name);
        $this->get('/students')->assertDontSee($student->last_name);
    }

}
