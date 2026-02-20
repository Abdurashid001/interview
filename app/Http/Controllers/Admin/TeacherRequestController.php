<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Models\TeacherRequest;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $requests = TeacherRequest::with('approver')
            ->latest()
            ->paginate(15);
        
        return view('admin.teacher-requests.index', compact('requests'));
    }

    public function show(TeacherRequest $teacherRequest)
    {
        return view('admin.teacher-requests.show', compact('teacherRequest'));
    }

    public function approve(TeacherRequest $teacherRequest)
    {
        // Yangi o'qituvchi yaratish
        $user = User::create([
            'name' => $teacherRequest->name,
            'email' => $teacherRequest->email,
            'password' => $teacherRequest->password,
            'role' => 'teacher',
            'phone' => $teacherRequest->phone,
            'experience_years' => $teacherRequest->experience_years,
            'document_path' => $teacherRequest->document_path,
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        // So'rovni yangilash
        $teacherRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // O'qituvchiga bildirishnoma
        Notification::create([
            'user_id' => $user->id,
            'type' => 'success',
            'title' => 'So\'rovingiz tasdiqlandi!',
            'message' => 'O\'qituvchi sifatida ro\'yxatdan o\'tdingiz. Endi tizimga kirishingiz mumkin.',
            'link' => route('login'),
        ]);

        return redirect()->route('admin.teacher-requests.index')
            ->with('success', 'O\'qituvchi so\'rovi tasdiqlandi!');
    }

    public function reject(Request $request, TeacherRequest $teacherRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $teacherRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->rejection_reason,
            'approved_by' => auth()->id(),
        ]);

        // Faylni o'chirish (agar kerak bo'lsa)
        // Storage::disk('public')->delete($teacherRequest->document_path);

        // O'qituvchiga bildirishnoma
        Notification::create([
            'user_id' => null, // Email orqali xabar berish kerak
            'type' => 'danger',
            'title' => 'So\'rovingiz rad etildi',
            'message' => 'Sabab: ' . $request->rejection_reason,
        ]);

        return redirect()->route('admin.teacher-requests.index')
            ->with('info', 'O\'qituvchi so\'rovi rad etildi.');
    }

    public function downloadDocument(TeacherRequest $teacherRequest)
    {
        return Storage::disk('public')->download($teacherRequest->document_path);
    }
}