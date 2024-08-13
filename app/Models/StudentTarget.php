<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTarget extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s', 
        'updated_at' => 'date:Y-m-d H:i:s',
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
    ];

    protected $fillable = [
        'student_id',
        'session_id',
        'subject',
        'start_date',
        'end_date',
        'target',
        'created_at',
    ];
}
