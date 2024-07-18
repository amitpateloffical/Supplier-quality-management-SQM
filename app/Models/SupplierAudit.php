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
        'created_at'=>'date' ];

    public function division(){
        return $this->belongsTo(QMSDivision::class,'division_id');
    }
}
