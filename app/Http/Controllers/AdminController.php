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
        return view('admin.login');
    }

    public function loginSubmit(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['Invalid credentials']);
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