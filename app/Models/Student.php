<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [ 'first_name', 'last_name' ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class,'groups_students');
    }

    public function isInGroup(Group $group) {
        foreach ($this->groups as $g) {
            if ($g->id === $group->id) {
                return true;
            }
        }
        return false;
    }

    public function exams() {
        return $this->belongsToMany(Exam::class,'exams_students');
    }

    public function examSheets() {
        return $this->hasMany(ExamSheet::class);
    }

    public function activeExams()
    {
        $now = new \DateTime();
        $active = [];
        foreach ($this->examSheets as $oneExam) {
            if ( new \DateTime($oneExam->exam->from) < $now 
                && new\DateTime($oneExam->exam->until) > $now
                && is_null($oneExam->started) && is_null($oneExam->finished)
                ) {
                $active[] = $oneExam;
            }
        }
        return $active;
    }

    public function finishedExams()
    {
        $finished = [];
        foreach ($this->examSheets as $oneExam) {
            if ($oneExam->is_done() && $oneExam->is_valid()) {
                if (is_null($oneExam->result)) { $oneExam->calculateResult(); }
                $finished[] = $oneExam;
            }
        }
        return $finished;
    }

    public function futureExams()
    {
        $future = [];
        $now = new \DateTime();
        foreach ($this->examSheets as $oneExam) {
            if (new \DateTime($oneExam->exam->from) > $now && is_null($oneExam->started) ) {
                $future[] = $oneExam;
            }
        }
        return $future;
    }
}
