<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $fillable = ['session', 'class', 'fee_head_id', 'amount'];
    protected $casts = [
        'amount' => 'decimal:2',
    ];
    public function head()
    {
        return $this->belongsTo(FeeHead::class, 'fee_head_id');
    }
}
