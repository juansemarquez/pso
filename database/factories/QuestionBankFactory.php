<?php

namespace Database\Factories;

use App\Models\QuestionBank;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionBankFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuestionBank::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //There's a chance that a question bank has no description
        $description = rand(1,10) < 3 ? null : $this->faker->sentence;
        return [
            'name' => $this->faker->word(4),
            'description' => $description,
            'teacher_id' => \App\Models\Teacher::factory()->create()
        ];
    }
    /*
    $qb = QuestionBank::factory()->create(); 
    //Associates to $qb a random number of questions, with a random number
    //of answers each.
    for($i=0; $i<rand(1,20);$i++) {
        $q = Question::factory()->make(); 
        $q->question_bank()->associate($qb); 
        $q->save(); 
        for($j=0; $j<rand(3,10); $j++) {
            $a = Answer::factory()->make();
            $a->question()->associate($q);
            $a->save();
        }
    }
    */
}
