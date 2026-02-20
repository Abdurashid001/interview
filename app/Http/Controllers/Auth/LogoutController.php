<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Sessiyani tozalash
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Login sahifasiga yo'naltirish
        return redirect('/login');
    }
}