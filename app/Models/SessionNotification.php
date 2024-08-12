<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'session_id',
        'student_id',
        'notification_time',
        'is_sent',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
