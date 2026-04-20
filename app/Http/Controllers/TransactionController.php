<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        
        $query = Transaction::with(['user', 'book']);
        
        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('book', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $transactions = $query->with('paymentFines')->latest()->paginate(10);
        
        // Calculate fine statistics
        $totalFines = Transaction::sum('fine_amount');
        
        // Refresh fines for overdue transactions
        Transaction::overdue()->get()->each(function ($transaction) {
            $transaction->calculateFine();
            $transaction->save();
        });
        
        return view('admin.transactions.index', compact('transactions', 'search', 'status', 'totalFines'));
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();
        $books = Book::where('stock', '>', 0)->get();
        
        return view('admin.transactions.create', compact('users', 'books'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'now_minus_7' => now()->subDays(7)->format('Y-m-d'),
        ]);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date|after_or_equal:now_minus_7',
            'due_date' => 'required|date|after:borrow_date',
        ]);

        $book = Book::findOrFail($validated['book_id']);
        
        if ($book->stock < 1) {
            return back()->with('error', 'Buku tidak tersedia!');
        }

        Transaction::create([
            'user_id' => $validated['user_id'],
            'book_id' => $validated['book_id'],
            'borrow_date' => $validated['borrow_date'],
            'due_date' => $validated['due_date'],
            'status' => 'borrowed',
        ]);

        $book->decrement('stock');

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaksi peminjaman berhasil ditambahkan!');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'book']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $users = User::where('role', 'user')->get();
        $books = Book::all();
        $transaction->load('user', 'book');
        return view('admin.transactions.edit', compact('transaction', 'users', 'books'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date',
            'status' => 'required|in:pending,borrowed,returned,overdue',
            'days_late' => 'nullable|integer|min:0',
            'fine_amount' => 'nullable|numeric|min:0',
        ]);

        $oldBookId = $transaction->book_id;
        $transaction->update($validated);

        // Adjust stock for book change
        if ($oldBookId != $validated['book_id']) {
            Book::find($oldBookId)->increment('stock');
            Book::find($validated['book_id'])->decrement('stock');
        }

        // Auto recalculate fine if days_late or dates changed, unless manually set
        if (isset($validated['days_late']) && $validated['days_late'] == 0) {
            $transaction->calculateFine();
        }

        if ($validated['status'] === 'returned') {
            $transaction->book->increment('stock');
        }

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil diupdate!');
    }

    public function myTransactions(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status');
        
        // Auto accrue fines for user
        Transaction::accrueUserFines($user->id);
        
        $query = Transaction::with('book')->where('user_id', $user->id);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $transactions = $query->with('paymentFines')->latest()->paginate(10);
        
        $totalFinesOwed = Transaction::where('user_id', $user->id)
            ->sum('fine_amount');
        
        return view('user.transactions.index', compact('transactions', 'status', 'totalFinesOwed'));
    }

    public function createBorrow()
    {
        $books = Book::where('stock', '>', 0)->get();
        
        return view('user.transactions.create', compact('books'));
    }

    public function borrow(Request $request)
    {
        $request->merge([
            'now_minus_7' => now()->subDays(7)->format('Y-m-d'),
        ]);

        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date|after_or_equal:now_minus_7',
            'due_date' => 'required|date|after:borrow_date',
        ]);

        $book = Book::findOrFail($validated['book_id']);
        
        if ($book->stock < 1) {
            return back()->with('error', 'Buku tidak tersedia!');
        }

        Transaction::create([
            'user_id' => Auth::id(),
            'book_id' => $validated['book_id'],
            'borrow_date' => $validated['borrow_date'],
            'due_date' => $validated['due_date'],
            'status' => 'borrowed',
        ]);

        $book->decrement('stock');

        return redirect()->route('user.transactions.index')
            ->with('success', 'Peminjaman berhasil! Silakan ambil buku di perpustakaan.');
    }

    public function returnBook(Transaction $transaction)
    {
        if ($transaction->status !== 'borrowed') {
            return back()->with('error', 'Buku ini tidak dalam status peminjaman!');
        }

        $transaction->update([
            'return_date' => now(),
            'status' => 'returned',
        ]);

        $transaction->calculateFine();
        $transaction->save();

        $transaction->book->increment('stock');

        $fineMsg = $transaction->fine_amount > 0 ? ' (Denda: Rp ' . number_format($transaction->fine_amount) . ')' : '';
        return back()->with('success', 'Buku berhasil dikembalikan!' . $fineMsg . ' Terima kasih.');
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,borrowed,returned,overdue',
        ]);

        $oldStatus = $transaction->status;
        $transaction->update(['status' => $validated['status']]);

        if ($validated['status'] === 'returned') {
            $transaction->calculateFine();
            $transaction->save();
        }

        if ($oldStatus === 'borrowed' && $validated['status'] === 'returned') {
            $transaction->book->increment('stock');
        } elseif ($oldStatus === 'returned' && $validated['status'] === 'borrowed') {
            $transaction->book->decrement('stock');
        }

        return back()->with('success', 'Status transaksi berhasil diperbarui!');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->status === 'borrowed') {
            $transaction->book->increment('stock');
        }

        $transaction->delete();

        return back()->with('success', 'Transaksi berhasil dihapus!');
    }

    public function payFine(Request $request, Transaction $transaction)
    {
        if (Auth::id() !== $transaction->user_id && Auth::user()->role !== 'admin') {
            return back()->with('error', 'Anda tidak memiliki akses untuk ini.');
        }

        if ($transaction->fine_amount <= 0) {
            return back()->with('error', 'Tidak ada denda untuk dibayar.');
        }

        $amount = $transaction->fine_amount;

        // Create payment record
        \App\Models\PaymentFine::create([
            'transaction_id' => $transaction->id,
            'user_id' => $transaction->user_id,
            'amount' => $amount,
            'payment_date' => now(),
            'status' => 'paid',
            'notes' => 'Pembayaran denda keterlambatan buku #'.$transaction->id,
        ]);

        // Zero the fine
        $transaction->update(['fine_amount' => 0]);

        return back()->with('success', 'Denda Rp '.number_format($amount).' berhasil dibayar dan tercatat!');
    }
}
