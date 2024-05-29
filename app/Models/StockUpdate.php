<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockUpdate extends Model
{
    public $timestamps = false;

    const STATUS_OPEN = 0;
    const STATUS_CLOSED = 1;
    const STATUS_CANCELED = 2;

    const TYPE_SINGLE_ADJUSTMENT = 0;
    const TYPE_MASS_ADJUSTMENT = 1;
    const TYPE_SALES_ORDER = 11;
    const TYPE_SALES_ORDER_RETURN = 12;
    const TYPE_PURCHASE_ORDER = 21;
    const TYPE_PURCHASE_ORDER_RETURN = 22;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'type',
        'status',
        'total_cost',
        'total_price',
        'notes',
    ];

    public function idFormatted()
    {
        return 'SU-' . $this->date . '-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public function statusFormatted()
    {
        switch ($this->status) {
            case self::STATUS_OPEN: return 'Disimpan';
            case self::STATUS_CLOSED: return 'Selesai';
            case self::STATUS_CANCELED: return 'Dibatalkan';
        }

        return 'Unknown Status';
    }

    public function details(): HasMany
    {
        return $this->hasMany(StockUpdateDetail::class, 'update_id');
    }
}
