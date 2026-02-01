<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    protected $fillable = [
        'student_id',
        'session',        // ✅ ADD THIS
        'receipt_no',
        'paid_amount',
        'payment_mode',
        'paid_date',
        'note'
    ];

    public function items()
    {
        return $this->hasMany(FeePaymentItem::class, 'fee_payment_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}

