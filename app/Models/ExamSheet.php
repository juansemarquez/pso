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
        return $this->belongsToMany(Question::class, 'questions_exams_students');
    }
    public function answers() {
        return $this->belongsToMany(Answer::class,
                                    'questions_exams_students',
                                    'answer_chosen_id', 'id');
    }
    public function calculateResult() {        
        if (! $this->is_valid()) {
            return false;
        }
        //Value of each right question:
        $n = 100 / $this->exam->number_of_questions;
        $sum = 0;
        foreach ($this->answers as $answer) {
            $sum += $answer->percentage_of_question * $n / 100;
        }
        return $sum;
    }

    public function is_done() {
        return is_null($this->finished);
    }

    public function is_valid() {
        // The exam must be already finished
        if ( !is_null($this->started) && $this->is_done() ) {
            // The exam must be started before it's finished
            if ( $this->started < $this->finished ) {
                // The exam duration (minutes) shuldn't exceed available time:
                $interval = $this->started($this->finished);
                if ( $interval->format('%i') <= $this->exam->time_available ) {
                    return true;
                }
            }
        }
        //If any of the conditions above weren't fullfilled:
        return false;
    }

}
