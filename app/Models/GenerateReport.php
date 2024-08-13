<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenerateReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_full_name',
        'student_id',
        'session_date',
        'session_minutes',
        'session_start_time',
        'session_end_time',
        'target_start_date',
        'target_end_date',
        'session_rating',
        'target',
    ];

}
