<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeInvoiceItem extends Model
{
    protected $fillable = ['fee_invoice_id', 'fee_head_id', 'amount'];

    public function invoice()
    {
        return $this->belongsTo(FeeInvoice::class, 'fee_invoice_id');
    }

    public function head()
    {
        return $this->belongsTo(FeeHead::class, 'fee_head_id');
    }
}
