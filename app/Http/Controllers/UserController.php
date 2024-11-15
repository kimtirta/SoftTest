<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function loginForm()
    {
        return view('user.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($request->only('email', 'password'))) {
            return redirect()->route('user.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('user.login');
    }

    public function dashboard()
{
    if (!auth()->check()) {
        return redirect()->route('user.login');
    }

    // Mendapatkan pinjaman dari user yang sedang login
    $loans = auth()->user()->loans;

    return view('user.dashboard', compact('loans'));
}
public function index()
{
    // Your logic here
    return view('book.index');
}

}

