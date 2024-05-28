<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockUpdateDetail extends Model
{
    public $timestaps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'update_id',
        'product_id',
        'quantity',
        'cost',
        'price',
    ];

    public function stockUpdate()
    {
        return $this->belongsTo(StockUpdate::class);
    }
}
