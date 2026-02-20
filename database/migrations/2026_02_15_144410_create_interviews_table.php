<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Suhbat mavzusi
            $table->text('description')->nullable(); // Tavsifi
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade'); // O'qituvchi ID
            $table->foreignId('student_id')->nullable()->constrained('users')->onDelete('set null'); // Student ID (tayinlangan bo'lsa)
            $table->dateTime('scheduled_at'); // Belgilangan vaqt
            $table->integer('duration')->default(30); // Davomiyligi (daqiqa)
            $table->enum('status', ['pending', 'scheduled', 'completed', 'cancelled'])->default('pending');
            $table->text('feedback')->nullable(); // O'qituvchi fikri
            $table->integer('score')->nullable(); // Baho (agar kerak bo'lsa)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('interviews');
    }
};