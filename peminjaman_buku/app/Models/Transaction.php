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

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
        'due_date' => 'date',
        'fine_amount' => 'decimal:0',
        'days_late' => 'integer',
    ];

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
        if ($this->status === 'borrowed' && $this->isOverdue()) {
            $this->days_late = now()->diffInDays($this->due_date);
$this->fine_amount = $this->days_late * 10000; // Rp 10.000 per day
        } else {
            $this->days_late = 0;
            $this->fine_amount = 0;
        }
    }

    public function isOverdue(): bool
    {
        return $this->status === 'borrowed' && now()->gt($this->due_date);
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

