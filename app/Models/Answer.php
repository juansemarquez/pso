<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $fillable = [ 'text', 'percentage_of_question' ];
    public function question()
    {
        return $this->belongsTo(\App\Models\Answer::class);
    }
}
