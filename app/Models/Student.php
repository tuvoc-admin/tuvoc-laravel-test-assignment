<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Student extends Model
{
    use HasFactory,Notifiable;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s', 
        'updated_at' => 'date:Y-m-d H:i:s',
    ];
    
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'email',
        'phone',
    ];
}
