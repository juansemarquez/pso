<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    
    protected $fillable = [ 'name', 'explanatory_text', 'from', 'until',
                            'time_available', 'number_of_questions'];
    public function questionBank()
    {
        return $this->belongsTo(QuestionBank::class);
    }
    
    public function teacher()
    {
        return $this->hasOneThrough(Teacher::class, QuestionBank::class);
    }

    //public function students() {
    //    return $this->belongsToMany(Student::class(), 'exams_students');
    //}

    public function examSheets() {
        return $this->hasMany(ExamSheet::class);
    }

    public function groups() {
        return $this->belongsToMany(Group::class, 'exams_groups');
    }

    /**
     * Returns an array with the groups that don't have this exam assigned
     */
    public function otherGroups() {
        if($this->groups()->count() == 0) { 
            $groupsNo = Group::where('teacher_id', $this->questionBank->teacher->id)->get();
        }
        else {
            $groupsYes = [];
            foreach ($this->groups as $group) { $groupsYes[] = $group->id; }
            $groupsNo = Group::whereNotIn('id',$groupsYes)
                      ->where('teacher_id', $this->questionBank->teacher->id)->get();
            
        }
        return $groupsNo;
    }

    
}
