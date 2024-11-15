<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends Controller
{
    public function loginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && \Hash::check($request->password, $admin->password)) {
            session(['admin' => $admin->id]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout()
    {
        session()->forget('admin');
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $books = Book::all();
        $users = User::all();
        $loans = Loan::all();

        if (!session()->has('admin')) {
            return redirect()->route('admin.login');
        }

        return view('admin.dashboard', compact('books', 'users', 'loans'));
    }
}

