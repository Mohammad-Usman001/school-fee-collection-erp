<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeHead extends Model
{
    protected $fillable = ['name', 'frequency', 'is_active'];

    public function structures()
    {
        return $this->hasMany(FeeStructure::class);
    }
}
