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

    public function otherStudents() {
        $groups = self::where('teacher_id', $this->teacher->id)->where('id','<>',$this->id)->get();
        $students = [];
        foreach ($groups as $group) {
            foreach ($group->students as $student) {
                if (! $student->isInGroup($this) ) {
                    $students[] = $student;
                }
            }
        }
        return array_values(array_unique($students));
    }
}
