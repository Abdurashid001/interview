<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'interview_id', 'question', 'answer', 'teacher_notes', 'points', 'order'
    ];

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }
}