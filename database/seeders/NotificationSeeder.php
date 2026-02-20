<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        // Adminlar uchun
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'info',
                'title' => 'Tizim yangilanishi',
                'message' => 'CRM tizimi yangilandi. Yangi imkoniyatlar bilan tanishing!',
                'link' => '/admin/updates',
            ]);
        }

        // O'qituvchilar uchun
        $teachers = User::where('role', 'teacher')->get();
        foreach ($teachers as $teacher) {
            Notification::create([
                'user_id' => $teacher->id,
                'type' => 'interview',
                'title' => 'Yangi intervyu taklifi',
                'message' => 'Sizga yangi intervyu yaratish taklifi keldi',
                'link' => '/teacher/interviews/create',
            ]);
        }

        // Studentlar uchun
        $students = User::where('role', 'student')->get();
        foreach ($students as $student) {
            Notification::create([
                'user_id' => $student->id,
                'type' => 'invitation',
                'title' => 'Intervyu taklifi',
                'message' => 'Sizni yangi intervyuga taklif qilishdi',
                'link' => '/student/invitations',
            ]);
        }

        // Umumiy bildirishnomalar (barcha userlar uchun)
        Notification::create([
            'user_id' => null, // null = barcha userlar ko'radi
            'type' => 'info',
            'title' => 'Yangilik!',
            'message' => 'Platformada yangi imkoniyatlar mavjud',
        ]);
    }
}