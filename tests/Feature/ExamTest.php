<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use \App\Models\QuestionBank;
use \App\Models\Question;
use \App\Models\Answer;
use \App\Models\Teacher;
use \App\Models\Exam;

class ExamTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    public function setUp():void
    {
        parent::setUp();
        // seed the database
        $this->artisan('db:seed');
    }
    public function test_a_teacher_can_create_an_exam()
    {
        $this->withoutExceptionHandling();
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher->user);

        $qb = $this->make_a_question_bank($teacher);

        $start = new \DateTime();
        $end = new \DateTime();
        $start->add(new \DateInterval('P10D'));
        $end->add(new \DateInterval('P11D'));

        $attributes = [
            'name' => $this->faker->sentence,
            'explanatory_text' => $this->faker->paragraph,
            'question_bank_id' => $qb->id,
            'date_from' => $start->format("Y-m-d"),
            'time_from' => $start->format("H:i:s"),
            'date_until' => $end->format("Y-m-d"),
            'time_until' => $end->format("H:i:s"),
            'time_available' => rand(5,60),
            'number_of_questions' => rand(1, $qb->questions()->count()),
        ];

        $this->post('exams', $attributes);

        $this->assertDataBaseHas('exams',
            [
                'name' => $attributes['name'],
                'explanatory_text' => $attributes['explanatory_text'],
                'from' => $attributes['date_from'].' '.$attributes['time_from'],
                'until' => $attributes['date_until'].' '.$attributes['time_until'],
                'time_available' => $attributes['time_available'],
                'number_of_questions' => $attributes['number_of_questions'],
                'question_bank_id' => $attributes['question_bank_id']
            ]);

        $this->get('/exams')->assertSee($attributes['name']);
        $this->get('/exams')->assertSee($attributes['explanatory_text']);
        $this->get('/exams')->assertSee($attributes['date_from']);
        $this->get('/exams')->assertSee($attributes['time_until']);
        $this->get('/exams')->assertSee($attributes['time_available']);
        $this->get('/exams')->assertSee($attributes['number_of_questions']);
    }
    
    public function test_a_teacher_can_see_and_edit_existing_exams()
    {
        $this->withoutExceptionHandling();
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher->user);
        
        $qb = $this->make_a_question_bank($teacher);

        $e = Exam::factory()->create(['question_bank_id'  => $qb->id]);
        $this->get('/exams')->assertSee($e->name);
        $this->get('/exams')->assertSee($e->explanatory_text);

        $start = new \DateTime();
        $end = new \DateTime();
        $start->add(new \DateInterval('P10D'));
        $end->add(new \DateInterval('P11D'));

        $attributes = [
            'name' =>'Modified name', 
            'explanatory_text' => 'Modified description',
            'question_bank_id' => $qb->id,
            'date_from' => $start->format("Y-m-d"),
            'time_from' => $start->format("H:i:s"),
            'date_until' => $end->format("Y-m-d"),
            'time_until' => $end->format("H:i:s"),
            'time_available' => rand(5,60),
            'number_of_questions' => rand(1, $qb->questions()->count()),
        ];
        $this->put('exams/'.$e->id, $attributes);
        
        $this->assertDataBaseHas('exams',
            [
                'name' => $attributes['name'],
                'explanatory_text' => $attributes['explanatory_text']
            ]);
        $this->get('/exams')->assertSee($attributes['name']);
        $this->get('/exams')->assertSee($attributes['explanatory_text']);
    }

    public function test_a_teacher_can_delete_an_exam()
    {
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher->user);
        $qb = $this->make_a_question_bank($teacher);

        $e = Exam::factory()->create(['question_bank_id'  => $qb->id]);
        $this->get('/exams')->assertSee($e->name);
        $this->get('/exams')->assertSee($e->explanatory_text);

        $this->delete('exams/'.$e->id);

        $this->get('/exams')->assertDontSee($e->name);
        $this->get('/exams')->assertDontSee($e->explanatory_text);
    }


    protected function make_a_question_bank($teacher) {
        $qb = QuestionBank::factory()->create(['teacher_id'=>$teacher->id]);
        for($i=0; $i<rand(1,20);$i++) {
            $q = Question::factory()->make(); 
            $q->question_bank()->associate($qb); 
            $q->save(); 
            $numberOfAnswers = 3;
            $correctAnswer = rand(0, $numberOfAnswers - 1);
            for($j=0; $j<$numberOfAnswers; $j++) {                
                if ($j == $correctAnswer) {
                    $a = Answer::factory()->make(['percentage_of_question' => 100]);
                }
                else {
                    $a = Answer::factory()->make();
                }
                $a->question()->associate($q);
                $a->save();
            }
            $q->save();
        }
        $qb->save();
        return $qb;
    }
}
