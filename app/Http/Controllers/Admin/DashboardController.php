<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\Interview;
use App\Models\TeacherRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        // Statistika ma'lumotlari
        $totalUsers = User::count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        
        $totalInterviews = Interview::count();
        $pendingInterviews = Interview::where('status', 'pending')->count();
        $completedInterviews = Interview::where('status', 'completed')->count();
        
        $pendingTeacherRequests = TeacherRequest::where('status', 'pending')->count();
        
        $recentUsers = User::latest()->take(5)->get();
        $recentTeacherRequests = TeacherRequest::with('approver')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTeachers', 
            'totalStudents',
            'totalAdmins',
            'totalInterviews',
            'pendingInterviews',
            'completedInterviews',
            'pendingTeacherRequests',
            'recentUsers',
            'recentTeacherRequests'
        ));
    }
}