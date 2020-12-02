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
}
