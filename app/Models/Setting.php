<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'school_name',
        'school_phone',
        'school_email',
        'school_address',
        'school_logo',
        'receipt_prefix',
        'receipt_footer',
        'currency_symbol',
        'session_year',
        'backup_retention',
    ];
}
