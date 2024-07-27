<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierChecklist extends Model
{
    use HasFactory;
    protected $fillable = [
        'supplier_id',
        'doc_type',
        'issue_date',
        'expiry_date',
        'expiry_date',
        'attachment',
        'remarks'
    ];
}
