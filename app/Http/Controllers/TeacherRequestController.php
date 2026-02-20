<?php

namespace App\Http\Controllers;

use App\Models\TeacherRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherRequestController extends Controller
{
    public function create()
    {
        return view('auth.teacher-register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teacher_requests|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'experience_years' => 'required|string|max:255',
            'document' => 'required|mimes:pdf|max:5120', // 5MB max
            'notes' => 'nullable|string',
        ]);

        // PDF faylni saqlash
        $documentPath = $request->file('document')->store('teacher-documents', 'public');

        // So'rovni saqlash
        $teacherRequest = TeacherRequest::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'experience_years' => $request->experience_years,
            'document_path' => $documentPath,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        // Adminlarga bildirishnoma yuborish
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'teacher_request',
                'title' => 'Yangi o\'qituvchi so\'rovi',
                'message' => "{$request->name} o'qituvchi bo'lish uchun so'rov yubordi",
                'link' => route('admin.teacher-requests.show', $teacherRequest),
            ]);
        }

        return redirect()->route('teacher-request.pending')
            ->with('success', 'So\'rovingiz qabul qilindi. Admin tasdiqlashidan keyin tizimga kira olasiz.');
    }

    public function pending()
    {
        return view('auth.teacher-pending');
    }
}