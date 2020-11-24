<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
     * @test
     * An admin can create teachers
     *
     * @return void
     */
    public function an_admin_can_create_a_teacher()
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
}
