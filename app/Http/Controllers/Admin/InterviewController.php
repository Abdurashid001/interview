<?php

namespace App\Http\Controllers\Admin;

use App\Models\Interview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class InterviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Barcha intervyular ro'yxati
     */
    public function index()
    {
        $interviews = Interview::with('teacher', 'student')
            ->latest()
            ->paginate(15);
        
        return view('admin.interviews.index', compact('interviews'));
    }

    /**
     * Intervyu detallari
     */
    public function show(Interview $interview)
    {
        $interview->load('teacher', 'student', 'questions');
        return view('admin.interviews.show', compact('interview'));
    }

    /**
     * Intervyu statistikasi
     */
    public function statistics()
    {
        $totalInterviews = Interview::count();
        $completedInterviews = Interview::where('status', 'completed')->count();
        $pendingInterviews = Interview::where('status', 'pending')->count();
        $scheduledInterviews = Interview::where('status', 'scheduled')->count();
        
        $teachers = User::where('role', 'teacher')->withCount('interviews')->get();
        $averageScore = Interview::where('status', 'completed')->avg('score');
        
        return view('admin.interviews.statistics', compact(
            'totalInterviews',
            'completedInterviews',
            'pendingInterviews',
            'scheduledInterviews',
            'teachers',
            'averageScore'
        ));
    }
}