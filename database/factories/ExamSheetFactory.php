<?php

namespace Database\Factories;

use App\Models\ExamSheet;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamSheetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExamSheet::class;

    /**
     * Define the model's default state.
     *
     * @param string $situation 'null' | 'started' | 'finished'
     * @return array
     */
    public function definition($situation = null)
    {
        if ($situation === 'started') {
            return [ 'started' => new \DateTime(),
                     'finished' => null,
                     'result' => null
            ];
        }
        elseif ($situation === 'finished') {
            $start = new \DateTime();
            $end = new \DateTime();
            $start->sub(new DateInterval('PT1H'));
            $end->sub(new DateInterval('PT50M'));

            return [ 'started' => $start,
                     'end' => $end,
                     'result' => rand(1,10)
            ];
        }
        else {
            return [ 'started' => null,
                     'finished' => null,
                     'result' => null
            ];
        }
    }
}
