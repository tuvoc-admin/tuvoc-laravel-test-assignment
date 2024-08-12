<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSession extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s', 
        'updated_at' => 'date:Y-m-d H:i:s',
        'start_time' => 'date:Y-m-d H:i:s',
        'end_time' => 'date:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'student_id',
        'start_time',
        'end_time',
        'is_recurring',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function notifications()
    {
        return $this->hasMany(SessionNotification::class);
    }

    public function rating()
    {
        return $this->hasOne(SessionRating::class);
    }
}
