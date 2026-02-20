<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('interview_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained()->onDelete('cascade');
            $table->text('question'); // Savol
            $table->text('answer')->nullable(); // Student javobi
            $table->text('teacher_notes')->nullable(); // O'qituvchi izohi
            $table->integer('points')->nullable(); // Ushbu savol uchun ball
            $table->integer('order')->default(0); // Savol tartibi
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('interview_questions');
    }
};