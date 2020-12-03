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
}
