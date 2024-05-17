<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    const STOCKED = 0;
    const NON_STOCKED = 1;
    const SERVICE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'type',
        'active',
        'code',
        'description',
        'barcode',
        'stock',
        'uom',
        'cost',
        'price',
        'notes',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
