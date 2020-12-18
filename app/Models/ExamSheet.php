<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSheet extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'exams_students';
    protected $fillable = ['started', 'finished', 'result'];

    public function student() {
        return $this->belongsTo(Student::class);
    }
    public function exam() {
        return $this->belongsTo(Exam::class);
    }
    public function questions() {
        return $this->belongsToMany(Question::class, 'questions_exams_students', 'exams_students_id');
    }
    public function answers() {
        return $this->belongsToMany(Answer::class,
                                    'questions_exams_students', 'exams_students_id');
    }
    
    public function unansweredQuestions() {
        $questions_id = [];
        foreach ($this->questions as $question) {
            $questions_id[] = $question->id;
        }
        foreach ($this->answers as $answer) {
            $index = array_search($answer->question->id, $questions_id);
            unset($questions_id[$index]);
        }
        $unanswered = [];
        foreach ($this->questions as $question) {
            if (in_array($question->id, $questions_id)) {
                $unanswered[] = $question;
            }
        }
        return $unanswered;
    }

    public function calculateResult() {
        if (! $this->is_valid()) {
            return null;
        }
        //Value of each right question:
        $n = 100 / $this->exam->number_of_questions;

        $sum = 0;
        foreach ($this->answers as $answer) {
            $sum += $answer->percentage_of_question * $n / 100;
        }
        $this->result = $sum;
        return $sum;
    }

    public function is_done() {
        return (!is_null($this->finished));
    }

    public function is_valid() 
    {
        // The exam must be already finished
        if ( !is_null($this->started) && $this->is_done() ) {
            // The exam must be started before it's finished
            
            $f = is_object($this->finished) ? $this->finished
                                            : new \DateTime($this->finished);
            $s = new \DateTime($this->started);
            if ( $s < $f) {
                
                // The exam duration (minutes) shuldn't exceed available time:
                $interval = $s->diff($f);
                // If the difference is a few seconds, we can consider it valid,
                // that's why the +1
                if ( $interval->format('%i') < $this->exam->time_available + 1) {
                    return true;
                }
            }
        }
        //If any of the conditions above weren't fullfilled:
        return false;
    }

    public function startExam()
    {
        // Get the questions randomly:
        $questions = $this->exam->questionBank->questions()
                               ->inRandomOrder()
                               ->take($this->exam->number_of_questions)
                               ->get();
        $questions_id = [];
        foreach ($questions as $oneQuestion) {
            $questions_id[] = $oneQuestion->id;
        }
        //Attach the questions to this particular ExamSheet, so every ExamSheet
        //will be different.
        $this->questions()->attach($questions_id);
        //Start time is now.
        $this->started = new \DateTime();
        $this->save();
        return $this->calculateDeadLine();
    }

    protected function calculateDeadLine()
    {
        $deadline = new \DateTime($this->started->format("Y-m-d H:i:s"));
        $adding = new \DateInterval('PT'.$this->exam->time_available.'M');
        $deadline->add($adding);
        //If the deadline, is AFTER the limit of the exam, we return that limit,
        //otherwise, we return the deadline:
        return ($deadline > new \DateTime($this->exam->until)) ? $this->exam->until : $deadline->format("Y-m-d H:i:s");
    }

    public function finishExam($answers)
    {
        $this->finished = new \DateTime();
        if (! $this->is_valid() ) { return -1; }
        $a = [];
        $q = [];
        if(count($answers) > 0) {
            foreach ($answers as $q => $answer) {
                //$ans = Answer::findOrFail($answer);
                $this->questions()->where('question_id',$q)->update(['answer_id'=> $answer]);
            }
            $this->save();
            return $this->calculateResult();
        }
        else {
            $this->result = 0;
            return 0;
        }
    }
}
