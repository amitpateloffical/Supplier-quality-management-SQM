<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capa extends Model
{
    use HasFactory;

    protected $casts = [
        'data' => 'array',  // Assuming 'data' is a JSON field or similar
        'intiation_date' => 'date', // If 'mfg_date' is a field
    ];
}
