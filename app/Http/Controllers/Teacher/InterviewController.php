<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Routing\Controller;
use App\Models\Interview;
use App\Models\InterviewQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher');
    }

    // O'qituvchining barcha intervyulari
    // public function index()
    // {
    //     $interviews = Interview::where('teacher_id', Auth::id())
    //         ->with('student')
    //         ->orderBy('scheduled_at', 'desc')
    //         ->get();

    //     return view('teacher.interviews.index', compact('interviews'));
    // }

    public function index(Request $request)
    {
        $query = Interview::where('teacher_id', Auth::id())
            ->with('student')
            ->orderBy('scheduled_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by title or student name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $interviews = $query->paginate(15);

        return view('teacher.interviews.index', compact('interviews'));
    }

    // Yangi intervyu yaratish formasi
    public function create()
    {
        $students = User::where('role', 'student')->get();
        return view('teacher.interviews.create', compact('students'));
    }

    // Intervyuni saqlash
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'student_id' => 'nullable|exists:users,id',
            'scheduled_at' => 'required|date|after:now',
            'duration' => 'required|integer|min:15|max:180',
        ]);

        $interview = Interview::create([
            'title' => $request->title,
            'description' => $request->description,
            'teacher_id' => Auth::id(),
            'student_id' => $request->student_id,
            'scheduled_at' => $request->scheduled_at,
            'duration' => $request->duration,
            'status' => $request->student_id ? 'scheduled' : 'pending',
        ]);

        return redirect()->route('teacher.interviews.questions', $interview);
    }

    // Savollar qo'shish formasi
    public function questions(Interview $interview)
    {
        if ($interview->teacher_id != Auth::id()) {
            abort(403);
        }

        return view('teacher.interviews.questions', compact('interview'));
    }

    // Savollarni saqlash
    public function storeQuestions(Request $request, Interview $interview)
    {
        $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*' => 'required|string',
        ]);

        foreach ($request->questions as $index => $questionText) {
            InterviewQuestion::create([
                'interview_id' => $interview->id,
                'question' => $questionText,
                'order' => $index,
            ]);
        }

        return redirect()->route('teacher.interviews.show', $interview)
            ->with('success', 'Savollar qo\'shildi!');
    }

    // Intervyuni ko'rish
    public function show(Interview $interview)
    {
        if ($interview->teacher_id != Auth::id()) {
            abort(403);
        }

        $interview->load('questions', 'student');
        return view('teacher.interviews.show', compact('interview'));
    }


    // Studentlarni taklif qilish
    public function invite(Request $request, Interview $interview)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id',
            'message' => 'nullable|string',
        ]);

        foreach ($request->student_ids as $studentId) {
            $interview->invitations()->create([
                'student_id' => $studentId,
                'message' => $request->message,
            ]);
        }

        return back()->with('success', 'Taklifnomalar yuborildi!');
    }


    public function submitResults(Request $request, Interview $interview)
    {
        if ($interview->teacher_id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'feedback' => 'nullable|string',
            'score' => 'nullable|integer|min:0|max:100',
            'questions' => 'required|array',
            'questions.*.points' => 'nullable|integer|min:0|max:100',
            'questions.*.teacher_notes' => 'nullable|string',
        ]);

        // Savollarga ball va izohlarni saqlash
        foreach ($request->questions as $questionId => $data) {
            $question = InterviewQuestion::find($questionId);
            if ($question) {
                $question->update([
                    'points' => $data['points'] ?? null,
                    'teacher_notes' => $data['teacher_notes'] ?? null,
                ]);
            }
        }

        // Intervyuni yangilash
        $interview->update([
            'feedback' => $request->feedback,
            'score' => $request->score,
            'status' => 'completed',
        ]);

        return redirect()->route('teacher.interviews.show', $interview)
            ->with('success', 'Natijalar muvaffaqiyatli saqlandi!');
    }

    // Natijalarni kiritish
    public function results(Request $request, Interview $interview)
    {
        $request->validate([
            'feedback' => 'nullable|string',
            'score' => 'nullable|integer|min:0|max:100',
            'questions' => 'required|array',
            'questions.*.answer' => 'nullable|string',
            'questions.*.points' => 'nullable|integer',
            'questions.*.teacher_notes' => 'nullable|string',
        ]);

        foreach ($request->questions as $questionId => $data) {
            $question = InterviewQuestion::find($questionId);
            if ($question) {
                $question->update($data);
            }
        }

        $interview->update([
            'feedback' => $request->feedback,
            'score' => $request->score,
            'status' => 'completed',
        ]);

        return redirect()->route('teacher.interviews.show', $interview)
            ->with('success', 'Natijalar saqlandi!');
    }
}
