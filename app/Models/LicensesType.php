<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicensesType extends Model
{

    use HasFactory;

    protected $table = 'licenses_types';

    protected $fillable = [
        '_name',
        'features',
        'akaeay_',
    ];
    protected $casts = [
        'features' => 'encrypted',      // Auto-decrypt on retrieval
        'akaeay_' => 'encrypted',
    ];
}
