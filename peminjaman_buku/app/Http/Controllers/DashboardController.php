<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Admin dashboard
     */
    public function adminDashboard()
    {
        $stats = [
            'totalBooks' => Book::count(),
            'totalMembers' => User::count(),
            'totalTransactions' => Transaction::count(),
            'borrowedBooks' => Transaction::where('status', 'borrowed')->count(),
            'returnedBooks' => Transaction::where('status', 'returned')->count(),
            'overdueBooks' => Transaction::where('status', 'borrowed')
                ->whereRaw('DATE(due_date) < CURDATE()')
                ->count(),
        ];

        $recentTransactions = Transaction::with(['user', 'book'])
            ->latest()
            ->take(5)
            ->get();

        $overdueTransactions = Transaction::overdue()
            ->with(['user', 'book'])
            ->orderBy('due_date')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTransactions', 'overdueTransactions'));
    }

    /**
     * User dashboard
     */
    public function userDashboard()
    {
        $user = Auth::user();
        
        $myTransactions = Transaction::with('book')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $borrowedCount = Transaction::where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->count();

        $returnedCount = Transaction::where('user_id', $user->id)
            ->where('status', 'returned')
            ->count();

        $availableBooks = Book::where('stock', '>', 0)->sum('stock');

        $totalFinesOwed = Transaction::where('user_id', $user->id)
            ->sum('fine_amount') ?? 0;

        return view('user.dashboard', compact(
            'myTransactions',
            'borrowedCount',
            'returnedCount',
            'availableBooks',
            'totalFinesOwed'
        ));
    }
}
