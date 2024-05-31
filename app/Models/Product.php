<?php

namespace App\Models;

use Exception;
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
        'supplier_id',
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

    public static function formatType($type)
    {
        switch ($type) {
            case self::STOCKED: return 'Barang Stok';
            case self::NON_STOCKED: return 'Barang Non Stok';
            case self::SERVICE: return 'Servis';
        }

        return throw new Exception('Unknown product type: ' . $type);
    }

    public function idFormatted()
    {
        return 'P-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
