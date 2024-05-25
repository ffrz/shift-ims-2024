<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesOrder extends Model
{
    const ORDER_STATUS_OPEN = 0;
    const ORDER_STATUS_CLOSED = 1;
    const ORDER_STATUS_CANCELED = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'status',
        'paid',
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'total',
        'total_receivable',
        'notes',
    ];

    public function idFormatted()
    {
        return 'SO-' . $this->date . '-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    static function formatOrderStatus($status)
    {
        switch ($status) {
            case self::ORDER_STATUS_OPEN: return 'Aktif';
            case self::ORDER_STATUS_CLOSED: return 'Selesai';
            case self::ORDER_STATUS_CANCELED: return 'Dibatalkan';
        }
        throw new Exception("Unknown order status.");
    }

    public function details(): HasMany
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'order_id');
    }
}
