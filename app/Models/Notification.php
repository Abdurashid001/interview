<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Bildirishnoma egasi (foydalanuvchi)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * O'qilmagan bildirishnomalar
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Bildirishnomani o'qilgan deb belgilash
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Bildirishnoma turiga qarab icon qaytarish
     */
    public function getIconAttribute()
    {
        return [
            'info' => 'ℹ️',
            'success' => '✅',
            'warning' => '⚠️',
            'danger' => '❌',
            'interview' => '📅',
            'invitation' => '📨',
            'result' => '📊',
        ][$this->type] ?? '📢';
    }

    /**
     * Bildirishnoma rangi
     */
    public function getColorAttribute()
    {
        return [
            'info' => 'info',
            'success' => 'success',
            'warning' => 'warning',
            'danger' => 'danger',
        ][$this->type] ?? 'secondary';
    }
}