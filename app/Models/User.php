<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // role ni qo'shildi
        'phone',
        'experience_years',
        'document_path',
        'is_approved',
        'approved_at',
        'avatar',  // AVATARNI QO'SHILDI
    ];

    public function isApprovedTeacher()
    {
        return $this->role === 'teacher' && $this->is_approved;
    }

    public function getApprovalStatusAttribute()
    {
        if ($this->role !== 'teacher') {
            return null;
        }

        return $this->is_approved ? 'Tasdiqlangan' : 'Kutilmoqda';
    }


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    // Rollarni tekshirish uchun metodlar
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    // Role nomini o'zbek tilida ko'rsatish
    public function getRoleNameAttribute()
    {
        return [
            'admin' => 'Administrator',
            'teacher' => 'O\'qituvchi',
            'student' => 'Student',
        ][$this->role] ?? $this->role;
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('d.m.Y') : 'Noma\'lum';
    }

    /**
     * Farqli vaqt uchun aksessuar
     */
    public function getCreatedAtForHumansAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans() : 'Noma\'lum';
    }
}
