<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePaymentItem extends Model
{
    protected $fillable = ['fee_payment_id', 'fee_invoice_id', 'amount'];

    public function payment()
    {
        // ✅ FIXED: foreign key correct
        return $this->belongsTo(FeePayment::class, 'fee_payment_id');
    }

    public function invoice()
    {
        return $this->belongsTo(FeeInvoice::class, 'fee_invoice_id');
    }
}
