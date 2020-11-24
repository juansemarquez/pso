<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Teacher::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $first = $this->faker->firstName;
        $last = $this->faker->lastName;
        $username = strtolower($first[0]) . strtolower($last);
        $u = User::factory()->create(['name' => $username]);
        $r = \App\Models\Role::where('description', 'teacher')->first();
        $u->roles()->attach($r);
        $u->save();
                
        return [
            'first_name' => $first, 
            'last_name' => $last, 
            'user_id' => $u->id
        ];
    }
}
