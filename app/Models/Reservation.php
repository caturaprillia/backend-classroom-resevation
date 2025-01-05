<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'classroom_id', 
        'user_id', 
        'reservation_startTime', 
        'reservation_endTime', 
    ];

    // Relasi dengan classroom
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Relasi dengan user (penyewa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
