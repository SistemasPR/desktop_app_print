<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrinterCategory extends Model
{
    //
    protected $fillable = [
        'printer_ip',
        'printer_name',
        'printer_id',
        'category_id',
        'all_categories',
        'status',
    ];
}
