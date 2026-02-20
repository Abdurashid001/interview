<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'teacher_id', 'student_id',
        'scheduled_at', 'duration', 'status', 'feedback', 'score'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    // O'qituvchi
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Student
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Savollar
    public function questions()
    {
        return $this->hasMany(InterviewQuestion::class);
    }

    // Taklifnomalar
    public function invitations()
    {
        return $this->hasMany(InterviewInvitation::class);
    }

    // Status ranglari
    public function getStatusColorAttribute()
    {
        return [
            'pending' => 'warning',
            'scheduled' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
        ][$this->status] ?? 'secondary';
    }

    // Status nomlari
    public function getStatusNameAttribute()
    {
        return [
            'pending' => 'Kutilmoqda',
            'scheduled' => 'Rejalashtirilgan',
            'completed' => 'Yakunlangan',
            'cancelled' => 'Bekor qilingan',
        ][$this->status] ?? $this->status;
    }
}