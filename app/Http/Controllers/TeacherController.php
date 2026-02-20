<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher');
    }

    /**
     * Dashboard sahifasi
     */
    public function dashboard()
    {
        $teacherId = Auth::id();

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
            'schedule' => $this->getTodaySchedule(),
        ];

        return view('teacher.dashboard', $data);
    }

    /**
     * Studentlar ro'yxati
     */
    public function students()
    {
        $students = User::where('role', 'student')->get();
        return view('teacher.students', compact('students'));
    }

    /**
     * Profilni ko'rish
     */
    public function profile()
    {
        $teacherId = Auth::id();

        $data = [
            'totalInterviews' => Interview::where('teacher_id', $teacherId)->count(),
            'completedInterviews' => Interview::where('teacher_id', $teacherId)
                ->where('status', 'completed')
                ->count(),
            'pendingInterviews' => Interview::where('teacher_id', $teacherId)
                ->where('status', 'pending')
                ->count(),
            'totalStudents' => Interview::where('teacher_id', $teacherId)
                ->distinct('student_id')
                ->count('student_id'),
            'recentInterviews' => Interview::where('teacher_id', $teacherId)
                ->with('student')
                ->latest()
                ->take(5)
                ->get(),
            'showUploadForm' => true,
        ];

        return view('teacher.profile', $data);
    }


    /**
     * Profilni tahrirlash formasi
     */
    public function editProfile()
    {
        return view('teacher.profile-edit');
    }

    /**
     * Profilni yangilash
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'experience_years' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'experience_years' => $request->experience_years,
        ]);

        return redirect()->route('teacher.profile')
            ->with('success', 'Profil muvaffaqiyatli yangilandi!');
    }

    /**
     * Rasm yuklash
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Eski rasmni o'chirish (agar mavjud bo'lsa)
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Yangi rasmni yuklash
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $avatarPath;
        $user->save();

        return back()->with('success', 'Rasm muvaffaqiyatli yuklandi!');
    }

    /**
     * Parolni o'zgartirish
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('password_success', 'Parol muvaffaqiyatli o\'zgartirildi!');
    }

    /**
     * Bugungi intervyular
     */
    private function getTodaySchedule()
    {
        return Interview::where('teacher_id', Auth::id())
            ->whereDate('scheduled_at', today())
            ->with('student')
            ->get();
    }
}
