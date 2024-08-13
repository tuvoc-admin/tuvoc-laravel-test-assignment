<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('generate_reports', function (Blueprint $table) {
            $table->id();
            $table->string('student_full_name');
            $table->date('session_date');
            $table->integer('session_minutes');
            $table->time('session_start_time');
            $table->time('session_end_time');
            $table->date('target_start_date');
            $table->date('target_end_date');
            $table->integer('session_rating');
            $table->integer('target');
            $table->timestamps();
        });

        DB::table('generate_reports')->insert([
            'student_full_name' => 'John shea',
            'session_date' => '2024-08-13',
            'session_minutes' => 15,
            'session_start_time' => '01:00:00',
            'session_end_time' => '01:15:00',
            'target_start_date' => '2024-08-12',
            'target_end_date' => '2024-08-13',
            'session_rating'=> '3',
            'target' => '5',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generate_reports');
    }
};
