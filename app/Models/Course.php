<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    // Relasi dengan classrooms
    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}