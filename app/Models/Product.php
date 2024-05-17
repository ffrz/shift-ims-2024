<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'active',
        'code',
        'description',
        'barcode',
        'cost',
        'price',
        'uom',
        'quantity',
    ];
}
