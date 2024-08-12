<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAvailability extends Model
{
    use HasFactory;

    protected $table = 'student_availability';

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s', 
        'updated_at' => 'date:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'student_id',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
