<?php

namespace Database\Factories;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Exam::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start = new \DateTime();
        $end = new \DateTime();
        $start->add(new \DateInterval('P10D'));
        $end->add(new \DateInterval('P11D'));
        
        return [
            'name' => $this->faker->sentence,
            'explanatory_text' => $this->faker->paragraph,
            'from' => $start,
            'until' => $end,
            'time_available' => rand(5,60),
            'number_of_questions' => rand(2, 10)
        ];
    }
}
