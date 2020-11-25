<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \App\Models\Teacher;

class TeachersTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    public function setUp():void
    {
        parent::setUp();
        // seed the database
        $this->artisan('db:seed');
    }

    /**
     * An admin can create teachers
     *
     * @return void
     */
    public function test_an_admin_can_create_a_teacher()
    {
        $this->actingAs(\App\Models\User::where('name','admin')->first());
        $attributes = [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '12345678',
            'password-repeat' => '12345678'
        ];

        $this->post('/teacher', $attributes);

        $this->assertDataBaseHas('teachers', [
                                    'first_name' => $attributes['first_name'],
                                    'last_name' => $attributes['last_name']        
                                ]);

        $userName = strtolower($attributes['first_name'][0] . 
                               $attributes['last_name']);

        $this->assertDataBaseHas('users', [
                                    'name' => $userName,
                                    'email' => $attributes['email']
                                ]);

        $this->get('/teacher')->assertSee($attributes['first_name']);
        $this->get('/teacher')->assertSee($attributes['last_name']);
        $this->get('/teacher')->assertSee($attributes['email']);
    }

    public function test_an_admin_can_see_and_edit_existing_teacher() 
    {
        $this->actingAs(\App\Models\User::where('name','admin')->first());
        $t = Teacher::factory()->create();
        $attributes = [
            'first_name' =>'Modified first', 
            'last_name' => 'Modified last',
            'email' => 'different_email@mail.com',
        ];
        $this->put('teacher/'.$t->id, $attributes);
        $this->get('/teacher')->assertSee($attributes['first_name']);
        $this->get('/teacher')->assertSee($attributes['last_name']);
        $this->get('/teacher')->assertSee($attributes['email']);
    }


    public function test_an_admin_can_delete_existing_teacher() 
    {
        $this->actingAs(\App\Models\User::where('name','admin')->first());
        $t = Teacher::factory()->create();
        $this->get('/teacher')->assertSee($t->user->email);
        $this->delete('/teacher/'.$t->id);
        $this->get('/teacher')->assertDontSee($t->user->email);
    }

    public function test_a_non_admin_user_cannot_see_teachers() {
        $t = Teacher::factory()->create();
        $this->actingAs($t->user);
        $this->get('/teacher')->assertStatus(403);

    }


}
