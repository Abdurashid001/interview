<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Teacher\InterviewController as TeacherInterviewController;
use App\Http\Controllers\Student\InterviewController as StudentInterviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\InterviewController as AdminInterviewController;
use App\Http\Controllers\TeacherRequestController;
use App\Http\Controllers\Admin\TeacherRequestController as AdminTeacherRequestController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

// Route::get('/', function () {
//     return view('welcome');
// });



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/my-profile', [ProfileController::class, 'edit'])->name('profile');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
});

require __DIR__ . '/auth.php';


// Admin routes (faqat adminlar uchun)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/filter/{role}', [UserController::class, 'filterByRole'])->name('users.filter');
    Route::get('/statistics', [UserController::class, 'statistics'])->name('statistics');
    Route::put('/users/{user}/change-role', [UserController::class, 'changeRole'])->name('users.change-role');
    Route::get('/interviews', [AdminInterviewController::class, 'index'])->name('interviews.index');
    Route::get('/interviews/{interview}', [AdminInterviewController::class, 'show'])->name('interviews.show');
    Route::get('/interviews/statistics', [AdminInterviewController::class, 'statistics'])->name('interviews.statistics');

    Route::get('/teacher-requests', [AdminTeacherRequestController::class, 'index'])->name('teacher-requests.index');
    Route::get('/teacher-requests/{teacherRequest}', [AdminTeacherRequestController::class, 'show'])->name('teacher-requests.show');
    Route::post('/teacher-requests/{teacherRequest}/approve', [AdminTeacherRequestController::class, 'approve'])->name('teacher-requests.approve');
    Route::post('/teacher-requests/{teacherRequest}/reject', [AdminTeacherRequestController::class, 'reject'])->name('teacher-requests.reject');
    Route::get('/teacher-requests/{teacherRequest}/download', [AdminTeacherRequestController::class, 'downloadDocument'])->name('teacher-requests.download');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');

    // Password change routes
    Route::get('/users/{user}/change-password', [App\Http\Controllers\Admin\UserController::class, 'editPassword'])->name('users.change-password');
    Route::put('/users/{user}/password', [App\Http\Controllers\Admin\UserController::class, 'updatePassword'])->name('users.update-password');

    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/approve', [App\Http\Controllers\Admin\UserController::class, 'approveTeacher'])->name('users.approve');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create'); // YANGI
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store'); // YANGI
});

// Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/teacher-requests', [AdminTeacherRequestController::class, 'index'])->name('teacher-requests.index');
//     Route::get('/teacher-requests/{teacherRequest}', [AdminTeacherRequestController::class, 'show'])->name('teacher-requests.show');
//     Route::post('/teacher-requests/{teacherRequest}/approve', [AdminTeacherRequestController::class, 'approve'])->name('teacher-requests.approve');
//     Route::post('/teacher-requests/{teacherRequest}/reject', [AdminTeacherRequestController::class, 'reject'])->name('teacher-requests.reject');
//     Route::get('/teacher-requests/{teacherRequest}/download', [AdminTeacherRequestController::class, 'downloadDocument'])->name('teacher-requests.download');
// });



// Teacher routes (faqat o'qituvchilar uchun)
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/students', [TeacherController::class, 'students'])->name('students');
    Route::get('/profile', [TeacherController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [TeacherController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [TeacherController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/upload-avatar', [TeacherController::class, 'uploadAvatar'])->name('profile.upload-avatar');
    Route::post('/profile/change-password', [TeacherController::class, 'changePassword'])->name('profile.change-password');
    // Interview routes
    Route::get('/interviews', [TeacherInterviewController::class, 'index'])->name('interviews.index');
    Route::get('/interviews/create', [TeacherInterviewController::class, 'create'])->name('interviews.create');
    Route::post('/interviews', [TeacherInterviewController::class, 'store'])->name('interviews.store');
    Route::get('/interviews/{interview}/questions', [TeacherInterviewController::class, 'questions'])->name('interviews.questions');
    Route::post('/interviews/{interview}/questions', [TeacherInterviewController::class, 'storeQuestions'])->name('interviews.store-questions');
    Route::get('/interviews/{interview}', [TeacherInterviewController::class, 'show'])->name('interviews.show');
    Route::post('/interviews/{interview}/invite', [TeacherInterviewController::class, 'invite'])->name('interviews.invite');
    Route::post('/interviews/{interview}/results', [TeacherInterviewController::class, 'submitResults'])->name('interviews.results');
    Route::get('/interviews/{interview}/results', [TeacherInterviewController::class, 'show'])->name('interviews.results.show');
});

Route::get('/teacher-register', [TeacherRequestController::class, 'create'])->name('teacher.register');
Route::post('/teacher-request', [TeacherRequestController::class, 'store'])->name('teacher-request.store');
Route::get('/teacher-request/pending', [TeacherRequestController::class, 'pending'])->name('teacher-request.pending');





// Student routes (faqat studentlar uchun)
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/courses', [StudentController::class, 'courses'])->name('courses');
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [StudentController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/upload-avatar', [StudentController::class, 'uploadAvatar'])->name('profile.upload-avatar');
    Route::post('/profile/change-password', [StudentController::class, 'changePassword'])->name('profile.change-password');
    Route::get('/interviews', [StudentInterviewController::class, 'index'])->name('interviews.index');
    Route::get('/interviews/{interview}', [StudentInterviewController::class, 'show'])->name('interviews.show');
    Route::post('/interviews/{interview}/answers', [StudentInterviewController::class, 'submitAnswers'])->name('interviews.submit-answers');
    Route::post('/invitations/{invitation}/accept', [StudentInterviewController::class, 'acceptInvitation'])->name('invitations.accept');
    Route::post('/invitations/{invitation}/decline', [StudentInterviewController::class, 'declineInvitation'])->name('invitations.decline');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


// Notification routes (faqat autentifikatsiyalangan foydalanuvchilar uchun)
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/{notification}', [NotificationController::class, 'show'])->name('show');
    Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
});

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
