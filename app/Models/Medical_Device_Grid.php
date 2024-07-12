<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical_Device_Grid extends Model
{
    use HasFactory;
    protected $table = 'medical__device__grids';
    protected $fillable =  ['mdg_id','identifier','data'] ;

    protected $casts = ['data'=>'array'];
    
}
