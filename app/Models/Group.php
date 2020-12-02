<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [ 'name', 'description' ];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function students() {
        return $this->belongsToMany(Student::class, 'groups_students');
    }

    /**
     * Returns an array with the students that don't belong to this group
     */
    public function otherStudents() {
        $groups = self::where('teacher_id', $this->teacher->id)->where('id','<>',$this->id)->get();
        $students = [];
        $student_ids = [];
        foreach ($groups as $group) {
            foreach ($group->students as $student) {
                if (!in_array($student->id, $student_ids) && !$student->isInGroup($this)) {
                    $students[] = $student;
                    $student_ids[] = $student->id;
                }
            }
        }
        return $students;
    }
}
