<?php
namespace App\Http\Controllers\Admin;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Interview;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;
use App\Models\InterviewApplicant;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }


    // Barcha foydalanuvchilar
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
        
        

        // PAGINATION: 15 ta foydalanuvchi

        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    // Filter by role
    public function filterByRole($role)
    {
        $users = User::where('role', $role)->get();
        $roleName = [
            'student' => 'Studentlar',
            'teacher' => 'O\'qituvchilar',
            'admin' => 'Administratorlar'
        ][$role] ?? $role;

        return view('admin.users.index', compact('users', 'roleName'));
    }

    // Statistika
    public function statistics()
    {
        // Foydalanuvchi statistikasi
        $totalUsers = User::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalAdmins = User::where('role', 'admin')->count();

        // Intervyu statistikasi
        $totalInterviews = Interview::count();
        $completedInterviews = Interview::where('status', 'completed')->count();
        $scheduledInterviews = Interview::where('status', 'scheduled')->count();
        $pendingInterviews = Interview::where('status', 'pending')->count();
        $averageScore = Interview::where('status', 'completed')->avg('score');

        // Oxirgi ro'yxatdan o'tganlar
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.statistics', compact(
            'totalUsers',
            'totalStudents',
            'totalTeachers',
            'totalAdmins',
            'totalInterviews',
            'completedInterviews',
            'scheduledInterviews',
            'pendingInterviews',
            'averageScore',
            'recentUsers'
        ));
    }

    // Role ni o'zgartirish (admin uchun)
    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:student,teacher,admin'
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Rol muvaffaqiyatli o\'zgartirildi!');
    }


    /**
     * Parolni o'zgartirish formasi
     */
    public function editPassword(User $user)
    {
        return view('admin.users.change-password', compact('user'));
    }

    /**
     * Parolni yangilash
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Foydalanuvchi paroli muvaffaqiyatli o\'zgartirildi!');
    }

    public function destroy(User $user)
    {
        // Admin o'zini o'chira olmasligi kerak
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Siz o\'zingizni o\'chira olmaysiz!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Foydalanuvchi o\'chirildi!');
    }

    /**
     * O'qituvchi so'rovini tasdiqlash
     */
    public function approveTeacher(User $user)
    {
        if ($user->role !== 'teacher') {
            return back()->with('error', 'Bu foydalanuvchi o\'qituvchi emas!');
        }

        $user->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'O\'qituvchi tasdiqlandi!');
    }



    /**
     * Yangi foydalanuvchi yaratish formasi
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Yangi foydalanuvchini saqlash
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:admin,teacher,student',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'is_approved' => $request->role === 'teacher' ? false : true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Yangi foydalanuvchi muvaffaqiyatli yaratildi!');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,teacher,student',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Foydalanuvchi ma\'lumotlari yangilandi!');
    }

    // public function editPassword(User $user)
    // {
    //     return view('admin.users.change-password', compact('user'));
    // }

    // public function updatePassword(Request $request, User $user)
    // {
    //     $request->validate([
    //         'password' => ['required', 'confirmed', Password::min(8)],
    //     ]);

    //     $user->update([
    //         'password' => Hash::make($request->password)
    //     ]);

    //     return redirect()->route('admin.users.index')
    //         ->with('success', 'Foydalanuvchi paroli muvaffaqiyatli o\'zgartirildi!');
    // }

    // public function destroy(User $user)
    // {
    //     if ($user->id === auth()->id()) {
    //         return back()->with('error', 'Siz o\'zingizni o\'chira olmaysiz!');
    //     }

    //     $user->delete();

    //     return redirect()->route('admin.users.index')
    //         ->with('success', 'Foydalanuvchi o\'chirildi!');
    // }

    // public function approveTeacher(User $user)
    // {
    //     if ($user->role !== 'teacher') {
    //         return back()->with('error', 'Bu foydalanuvchi o\'qituvchi emas!');
    //     }

    //     $user->update([
    //         'is_approved' => true,
    //         'approved_at' => now(),
    //     ]);

    //     return redirect()->route('admin.users.index')
    //         ->with('success', 'O\'qituvchi tasdiqlandi!');
    // }
}
