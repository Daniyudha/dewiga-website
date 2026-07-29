<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchedulePayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'schedule_id',
        'payment_number',
        'payment_type',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'proof_file',
        'notes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    const PAYMENT_TYPES = [
        'down_payment' => 'Uang Muka',
        'installment' => 'Cicilan',
        'settlement' => 'Pelunasan',
        'refund' => 'Pengembalian',
        'adjustment' => 'Penyesuaian',
    ];

    const PAYMENT_METHODS = [
        'cash' => 'Tunai',
        'bank_transfer' => 'Transfer Bank',
        'qris' => 'QRIS',
        'other' => 'Lainnya',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return self::PAYMENT_TYPES[$this->payment_type] ?? $this->payment_type;
    }

    public function getMethodLabelAttribute(): string
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }

    public static function generatePaymentNumber(): string
    {
        $year = now()->year;
        $month = now()->format('m');
        $last = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->lockForUpdate()
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $last ? ((int) substr($last->payment_number, -4)) + 1 : 1;

        return 'PAY-' . $year . $month . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}