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
    $users = User::all(); // Get all users
    $books = Book::all(); // Get all books
    $loans = Loan::whereNull('returned_date')->get(); // Get active loans

    return view('admin.dashboard', [
        'users' => $users,
        'books' => $books,
        'loans' => $loans,
        'total_users' => $users->count(),
        'total_books' => $books->count(),
        'active_loans' => $loans->count(),
    ]);
}

}