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
}