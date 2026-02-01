<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeInvoice extends Model
{
    protected $fillable = [
        'student_id', 'session', 'month',
        'total_amount', 'paid_amount', 'due_amount', 'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function items()
    {
        return $this->hasMany(FeeInvoiceItem::class);
    }
}
