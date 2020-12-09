<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\QuestionBank;
use App\Models\Teacher;

class QuestionBankTest extends TestCase
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
    public function test_a_teacher_can_create_a_question_bank()
    {
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher->user);
        $attributes = [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
        ];

        $this->post('/question_banks', $attributes);

        $this->assertDataBaseHas('question_banks', [
                                    'name' => $attributes['name'],
                                    'description' => $attributes['description']        
                                ]);

        $this->get('/question_banks')->assertSee($attributes['name']);
        $this->get('/question_banks')->assertSee($attributes['description']);
    }

    public function test_a_teacher_can_see_and_edit_existing_question_banks()
    {
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher->user);
        $qb = QuestionBank::factory()->create(['teacher_id'=>$teacher->id]);
        $this->get('/question_banks')->assertSee($qb->name);
        $this->get('/question_banks')->assertSee($qb->description);
        $attributes = [
            'name' =>'Modified name', 
            'description' => 'Modified description'
        ];
        $this->put('question_banks/'.$qb->id, $attributes);
        $this->get('/question_banks')->assertSee($attributes['name']);
        $this->get('/question_banks')->assertSee($attributes['description']);
    }

    public function test_a_teacher_can_delete_a_question_bank()
    {
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher->user);
        $qb = new QuestionBank();
        $qb->name = "To delete";
        $qb->description = "This qb will be deleted";
        $qb->teacher()->associate($teacher);
        $qb->save();

        $this->get('/question_banks')->assertSee($qb->name);
        $this->get('/question_banks')->assertSee($qb->description);

        $this->delete('question_banks/'.$qb->id);

        $this->get('/question_banks')->assertDontSee($qb->name);
        $this->get('/question_banks')->assertDontSee($qb->description);
    }

    public function test_a_teacher_cannot_see_other_teachers_question_banks()
    {
        $teacher = Teacher::factory()->create();
        $qb = new QuestionBank();
        $qb->name = "Anything";
        $qb->description = "Whatever description";
        $qb->teacher()->associate($teacher);
        $qb->save();

        $teacher2 = Teacher::factory()->create();
        $this->actingAs($teacher2->user);
        $this->get('/question_banks')->assertDontSee($qb->name);
        $this->get('/question_banks')->assertDontSee($qb->description);
    }

    public function test_a_teacher_can_add_a_question() {
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher->user);
        $qb = QuestionBank::factory()->create(['teacher_id'=>$teacher->id]);
        $attributes = [ 'question_bank_id' => $qb->id,
                        'text' => $this->faker->sentence()
                      ];
        $nAnswers = rand(2,10);
        for ($i=0; $i< $nAnswers; $i++) {
            $answerText[$i] = $this->faker->sentence();
            $answerValue[$i] = rand(0,100);
        }
        $attributes['answerText'] = $answerText;
        $attributes['answerValue'] = $answerValue;

        $this->post('questions', $attributes);

        $this->assertDataBaseHas('questions', [
                                    'text' => $attributes['text'],
                                    'question_bank_id' => $attributes['question_bank_id']        
        ]);
        for ($i=0; $i< $nAnswers; $i++) {
            $this->assertDataBaseHas('answers', [
                'text' => $attributes['answerText'][$i],
                'percentage_of_question' => $attributes['answerValue'][$i]
            ]);
        }
        $this->get('/question_banks/'.$qb->id)->assertSee($qb->name);
        $this->get('/question_banks/'.$qb->id)->assertSee($qb->description);
        $this->get('/question_banks/'.$qb->id)->assertSee($attributes['text']);
        for ($i=0; $i<$nAnswers; $i++) {
            $this->get('/question_banks/'.$qb->id)->assertSee($attributes['answerText'][$i]);
            $this->get('/question_banks/'.$qb->id)->assertSee($attributes['answerValue'][$i]);
        }            

    }


}
