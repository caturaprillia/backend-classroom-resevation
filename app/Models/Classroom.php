<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['name', 'location', 'start_time', 'end', 'course_id', 'user_id'];

    // Relasi dengan course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relasi dengan user (pengajar)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan reservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    
}
