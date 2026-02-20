<?php

namespace App\Http\Controllers\Student;

use Illuminate\Routing\Controller;
use App\Models\Interview;
use App\Models\InterviewInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('student');
    }

    // Studentning intervyulari
    public function index()
    {
        $interviews = Interview::where('student_id', Auth::id())
            ->with('teacher')
            ->orderBy('scheduled_at')
            ->get();

        $invitations = InterviewInvitation::where('student_id', Auth::id())
            ->with('interview.teacher')
            ->where('status', 'pending')
            ->get();

        return view('student.interviews.index', compact('interviews', 'invitations'));
    }

    // Bitta intervyuni ko'rish
    public function show(Interview $interview)
    {
        // Faqat o'z intervyusini ko'ra oladi
        if ($interview->student_id != Auth::id()) {
            abort(403);
        }

        $interview->load('questions', 'teacher');
        return view('student.interviews.show', compact('interview'));
    }

    // Javoblarni yuborish
    public function submitAnswers(Request $request, Interview $interview)
    {
        if ($interview->student_id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable|string',
        ]);

        foreach ($request->answers as $questionId => $answer) {
            $question = $interview->questions()->find($questionId);
            if ($question) {
                $question->update(['answer' => $answer]);
            }
        }

        return back()->with('success', 'Javoblar saqlandi!');
    }

    // Taklifni qabul qilish
    public function acceptInvitation(InterviewInvitation $invitation)
    {
        if ($invitation->student_id != Auth::id()) {
            abort(403);
        }

        $invitation->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        $invitation->interview->update([
            'student_id' => Auth::id(),
            'status' => 'scheduled',
        ]);

        return back()->with('success', 'Taklif qabul qilindi!');
    }

    // Taklifni rad etish
    public function declineInvitation(InterviewInvitation $invitation)
    {
        if ($invitation->student_id != Auth::id()) {
            abort(403);
        }

        $invitation->update([
            'status' => 'declined',
            'responded_at' => now(),
        ]);

        return back()->with('info', 'Taklif rad etildi.');
    }
}