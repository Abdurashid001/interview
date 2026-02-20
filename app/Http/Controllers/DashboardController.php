<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } elseif ($user->role === 'teacher') {
            return $this->teacherDashboard();
        } else {
            return $this->studentDashboard();
        }
    }

    private function adminDashboard()
    {
        // Admin statistikasi
        $data = [
            'totalUsers' => User::count(),
            'totalTeachers' => User::where('role', 'teacher')->count(),
            'totalStudents' => User::where('role', 'student')->count(),
            'totalInterviews' => Interview::count(),
            'recentUsers' => User::latest()->take(5)->get(),
            'recentInterviews' => Interview::with('teacher', 'student')->latest()->take(5)->get(),
            'notifications' => $this->getNotifications(),
            'systemUpdates' => $this->getSystemUpdates(),
        ];

        return view('dashboard.admin', $data);
    }

    private function teacherDashboard()
    {
        $teacherId = Auth::id();

        // O'qituvchi statistikasi
        $data = [
            'myInterviews' => Interview::where('teacher_id', $teacherId)->count(),
            'upcomingInterviews' => Interview::where('teacher_id', $teacherId)
                ->where('scheduled_at', '>', now())
                ->where('status', 'scheduled')
                ->count(),
            'completedInterviews' => Interview::where('teacher_id', $teacherId)
                ->where('status', 'completed')
                ->count(),
            'totalStudents' => Interview::where('teacher_id', $teacherId)
                ->distinct('student_id')
                ->count('student_id'),
            'recentInterviews' => Interview::where('teacher_id', $teacherId)
                ->with('student')
                ->latest()
                ->take(5)
                ->get(),
            'pendingInvitations' => $this->getPendingInvitations(),
            'notifications' => $this->getNotifications(),
            'studentRequests' => $this->getStudentRequests(),
            'schedule' => $this->getTodaySchedule(),
        ];

        return view('dashboard.teacher', $data);
    }

    private function studentDashboard()
    {
        $studentId = Auth::id();

        // Student statistikasi
        $data = [
            'myInterviews' => Interview::where('student_id', $studentId)->count(),
            'upcomingInterviews' => Interview::where('student_id', $studentId)
                ->where('scheduled_at', '>', now())
                ->where('status', 'scheduled')
                ->get(),
            'completedInterviews' => Interview::where('student_id', $studentId)
                ->where('status', 'completed')
                ->count(),
            'averageScore' => Interview::where('student_id', $studentId)
                ->where('status', 'completed')
                ->avg('score'),
            'pendingInvitations' => $this->getStudentInvitations(),
            'notifications' => $this->getNotifications(),
            'recentResults' => Interview::where('student_id', $studentId)
                ->where('status', 'completed')
                ->with('teacher')
                ->latest()
                ->take(5)
                ->get(),
            'nextInterview' => Interview::where('student_id', $studentId)
                ->where('scheduled_at', '>', now())
                ->where('status', 'scheduled')
                ->with('teacher')
                ->first(),
        ];

        return view('dashboard.student', $data);
    }

    private function getNotifications()
    {
        // Bildirishnomalarni olish
        return Notification::where('user_id', Auth::id())
            ->orWhere('user_id', null) // Umumiy bildirishnomalar
            ->latest()
            ->take(10)
            ->get();
    }

    private function getSystemUpdates()
    {
        // Tizim yangiliklari
        return [
            ['title' => 'Yangi imkoniyat', 'description' => 'Intervyu natijalarini PDF yuklash', 'date' => now()],
            ['title' => 'Tizim yangilandi', 'description' => 'Xavfsizlik yangilanishlari', 'date' => now()->subDays(1)],
        ];
    }

    private function getPendingInvitations()
    {
        // Kutilayotgan taklifnomalar
        try {
            // TO'G'RI: interview orqali teacher_id ni tekshirish
            return \App\Models\InterviewInvitation::whereHas('interview', function ($query) {
                $query->where('teacher_id', Auth::id());
            })
                ->where('status', 'pending')
                ->with('interview', 'student')
                ->get();
        } catch (\Exception $e) {
            \Log::error('Pending invitations error: ' . $e->getMessage());
            return collect([]);
        }
    }

    private function getStudentRequests()
    {
        // Student so'rovlari
        return [];
    }

    private function getTodaySchedule()
    {
        // Bugungi intervyular
        return Interview::where('teacher_id', Auth::id())
            ->whereDate('scheduled_at', today())
            ->with('student')
            ->get();
    }

    private function getStudentInvitations()
    {
        // Student uchun takliflar
        try {
        // Student uchun to'g'ridan-to'g'ri student_id bilan so'rash mumkin
        return \App\Models\InterviewInvitation::where('student_id', Auth::id())
            ->where('status', 'pending')
            ->with('interview.teacher')
            ->get();
    } catch (\Exception $e) {
        \Log::error('Student invitations error: ' . $e->getMessage());
        return collect([]);
    }
    }
}
