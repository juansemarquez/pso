<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['text'];

    public function answers()
    {
        return $this->hasMany(\App\Models\Answer::class);
    }

    public function question_bank()
    {
        return $this->belongsTo(\App\Models\QuestionBank::class);
    }
}
