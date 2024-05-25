<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderDetail extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'quantity',
        'uom',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(SalesOrder::class);
    }
}
