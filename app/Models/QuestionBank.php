<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    use HasFactory;
    protected $fillable = ['name','description'];
    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher::class);
    }
    public function questions()
    {
        return $this->hasMany(\App\Models\Question::class);
    }

    public function check()
    {
        //Checks if it has questions:
        $n = $this->questions()->count();
        if ($n === 0) {
            return [false, "¡This question bank has no questions!"];
        }
        else {
            foreach ($this->questions as $question) {
                if ($question->answers()->count() < 2) {
                    return [false, "There are questions with less than two possible answers"];
                }
                $atLeastOneIs100 = false;
                foreach ($question->answers as $answer) {
                    if ( $answer->percentage_of_question === 100) {
                        $atLeastOneIs100 = true;
                        break;
                    }
                }
                if (! $atLeastOneIs100) {
                    return [false, "There are questions with no correct answer"];
                }
            }
            if ($n === 1) {
                return [true, "Question bank has a question with at least one correct answer"];
            }
            else {
                return [true,
                 "Question bank has $n questions, each of them with at least one correct answer"];
            }
        }
    }
                    
                    
                    
}
