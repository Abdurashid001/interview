<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use App\Models\Interview;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * TO'G'RI: __construct (ikkita underscore)
     */
    public function __construct()  // ✅ IKKITA UNDERSCORE
    {
        $this->middleware('auth');
        $this->middleware('student');
    }

    public function dashboard()
    {
        $studentId = Auth::id();

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

        return view('student.dashboard', $data);
    }

    public function courses()
    {
        $studentId = Auth::id();

        // Agar kurslar modeli bo'lmasa, mock ma'lumotlar
        $data = [
            'totalCourses' => 5,
            'completedCourses' => 2,
            'inProgressCourses' => 3,
            'certificates' => 1,
            'courses' => collect([
                (object)[
                    'id' => 1,
                    'title' => 'PHP asoslari',
                    'description' => 'PHP dasturlash tilining asoslari',
                    'progress' => 75,
                    'duration' => 20,
                    'teacher' => (object)['name' => 'Aliyev Alisher'],
                ],
                (object)[
                    'id' => 2,
                    'title' => 'Laravel Framework',
                    'description' => 'Laravel bilan katta loyihalar yaratish',
                    'progress' => 40,
                    'duration' => 30,
                    'teacher' => (object)['name' => 'Boboyev Bobur'],
                ],
                (object)[
                    'id' => 3,
                    'title' => 'JavaScript',
                    'description' => 'Frontend dasturlash asoslari',
                    'progress' => 100,
                    'duration' => 25,
                    'teacher' => (object)['name' => 'Karimov Karim'],
                ],
            ])
        ];

        return view('student.courses', $data);
    }

    public function profile()
    {
        $studentId = Auth::id();

        $data = [
            'totalInterviews' => Interview::where('student_id', $studentId)->count(),
            'completedInterviews' => Interview::where('student_id', $studentId)
                ->where('status', 'completed')
                ->count(),
            'averageScore' => Interview::where('student_id', $studentId)
                ->where('status', 'completed')
                ->avg('score'),
            'totalCourses' => 5,
            'recentResults' => Interview::where('student_id', $studentId)
                ->where('status', 'completed')
                ->with('teacher')
                ->latest()
                ->take(5)
                ->get(),
            'showUploadForm' => true,
        ];

        return view('student.profile', $data);
    }

    public function editProfile()
    {
        return view('student.profile-edit');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('student.profile')
            ->with('success', 'Profil muvaffaqiyatli yangilandi!');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Eski rasmni o'chirish
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Yangi rasmni yuklash
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $avatarPath;
        $user->save();

        return back()->with('success', 'Rasm muvaffaqiyatli yuklandi!');
    }

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
}
