<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrow_date',
        'return_date',
        'due_date',
        'status',
        'fine_amount',
        'days_late',
    ];

    public function paymentFines(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PaymentFine::class);
    }

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
        'due_date' => 'date',
        'fine_amount' => 'decimal:2',
        'days_late' => 'integer',
    ];

    public function getFormattedFineAttribute(): string
    {
        return 'Rp ' . number_format(abs($this->fine_amount));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function calculateFine()
    {
        $lateDate = $this->return_date ?? now();
        if ($lateDate->lte($this->due_date)) {
            $this->days_late = 0;
            $this->fine_amount = 0;
            return;
        }

        $this->days_late = $lateDate->diffInDays($this->due_date, false);
        $this->fine_amount = $this->days_late * 10000; // Rp 10.000 per hari terlambat
        $this->saveQuietly();
    }

    public static function accrueDailyFines()
    {
        $overdue = self::overdue()->get();
        $updated = 0;
        foreach ($overdue as $transaction) {
            $transaction->calculateFine();
            $updated++;
        }
        return $updated;
    }

    public static function accrueUserFines($userId)
    {
        self::where('user_id', $userId)
            ->overdue()
            ->get()
            ->each(function ($transaction) {
                $transaction->calculateFine();
            });
    }

    public function isOverdue(): bool
    {
        $checkDate = $this->return_date ?? now();
        return $this->status === 'borrowed' && $checkDate->gt($this->due_date);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'borrowed')
                     ->where('due_date', '<', now());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }
}

