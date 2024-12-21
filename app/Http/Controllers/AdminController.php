<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Loan;
use App\Models\User;
use App\Models\Book;

class AdminController extends Controller
{
    public function login()
{
    // Redirect authenticated admins to the dashboard if already logged in
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }

    return view('admin.login');
}


public function loginSubmit(Request $request)
{
    // If already logged in, redirect to the dashboard
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }

    $request->validate([
        'email' => 'required|email|max:255',
        'password' => 'required|string|min:8|max:64',
    ], [
        'email.required' => 'The email field is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.max' => 'The email address must not exceed 255 characters.',
        'password.required' => 'The password field is required.',
        'password.min' => 'The password must be at least 8 characters.',
        'password.max' => 'The password must not exceed 64 characters.',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::guard('admin')->attempt($credentials)) {
        return redirect()->route('admin.dashboard');  
    }

    return back()->withErrors(['email' => 'Invalid credentials']);
}



    public function dashboard()
    {
        $total_users = User::count();
        $total_books = Book::count();
        $active_loans = Loan::whereNull('returned_date')->count();
    
        // Fetch the 10 newest loans ordered by creation date
        $recent_loans = Loan::with(['user', 'book'])
                            ->orderBy('created_at', 'desc')
                            ->take(10)
                            ->get();
    
        return view('admin.dashboard', [
            'total_users' => $total_users,
            'books' => Book::all(),
            'loans' => $recent_loans, // Pass the limited loans
        ]);

    }
    

}