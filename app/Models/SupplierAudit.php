<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierAudit extends Model
{
    use HasFactory;

    protected $casts = [
        // 'due_date' => 'datetime:Y-m-d', // Ensuring date stored in Y-m-d format
        // 'due_date' => 'datetime:Y-m-d',
        'audit_end_date' => 'date',
        'audit_start_date' => 'date',
        'end_date' => 'date',
        'start_date' => 'date',
        'created_at'=>'date',
        'intiation_date' =>'date'
        
     ];

    public function division(){
        return $this->belongsTo(QMSDivision::class,'division_id');
    }
}
