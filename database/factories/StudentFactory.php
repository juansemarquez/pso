<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

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
        $counter = 0;
        while ( true ) {
            if ( \App\Models\User::where('name',$username)->count() == 0 ) {
                $counter = 0;
                break;
            }
            else {
                $counter++;
                $username = $username . $counter;
            }
        }
        $u = User::factory()->create(['name' => $username]);
        $r = \App\Models\Role::where('description', 'student')->first();
        $u->roles()->attach($r);
        $u->save();
        return [
            'first_name' => $first, 
            'last_name' => $last, 
            'user_id' => $u->id
        ];
    }
}
