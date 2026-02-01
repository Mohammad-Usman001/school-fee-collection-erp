<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'unique_id',
        'session',
        'name',
        'father_name',
        'class',
        'section',
        'phone',
        'address',
    ];

    /**
     * Relationships
     */
   
}
