<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreLogin extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'store_name',
        'store_id',
        'status',
    ];

    protected $hidden = [
        'cookie_encrypt'
    ];
}
