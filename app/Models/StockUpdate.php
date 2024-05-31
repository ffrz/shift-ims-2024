<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockUpdate extends Model
{
    public $timestamps = false;

    const STATUS_OPEN = 0;
    const STATUS_COMPLETED = 1;
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
        'id2',
        'type',
        'status',
        'total_cost',
        'total_price',
        'notes',
        'created_datetime',
        'closed_datetime',
        'created_uid',
        'closed_uid',
        'last_saved_datetime',
        'last_saved_uid',
    ];

    public function open()
    {
        $this->status = StockUpdate::STATUS_OPEN;
        $this->created_datetime = current_datetime();
        $this->created_uid = Auth::user()->id;
        $this->last_saved_datetime = $this->created_datetime;
        $this->last_saved_uid = $this->created_uid;
    }

    public function close($status)
    {
        $this->status = $status;
        $this->closed_datetime = current_datetime();
        $this->closed_uid = Auth::user()->id;
        $this->last_saved_datetime = $this->closed_datetime;
        $this->last_saved_uid = $this->closed_uid;
    }

    public static function getNextId2($type)
    {
        return DB::table('stock_updates')
            ->selectRaw('ifnull(max(id2), 0) + 1 as next_id')
            ->where('type', '=', $type)
            ->value('next_id');
    }

    public function idFormatted()
    {
        return 'SU-' . date_from_datetime($this->created_datetime) . '-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public function id2Formatted()
    {
        $prefix = '';
        switch ($this->type) {
            case self::TYPE_SINGLE_ADJUSTMENT:
                $prefix = 'STA';
                break;
            case self::TYPE_MASS_ADJUSTMENT:
                $prefix = 'STO';
                break;
            case self::TYPE_SALES_ORDER:
                $prefix = 'SO';
                break;
            case self::TYPE_SALES_ORDER_RETURN:
                $prefix = 'SOR';
                break;
            case self::TYPE_PURCHASE_ORDER:
                $prefix = 'PO';
                break;
            case self::TYPE_PURCHASE_ORDER:
                $prefix = 'POR';
                break;
        }
        return $prefix . '-' . date_from_datetime($this->created_datetime) . '-' . str_pad($this->id2, 5, '0', STR_PAD_LEFT);
    }

    public function statusFormatted()
    {
        switch ($this->status) {
            case self::STATUS_OPEN:
                return 'Disimpan';
            case self::STATUS_COMPLETED:
                return 'Selesai';
            case self::STATUS_CANCELED:
                return 'Dibatalkan';
        }

        return 'Unknown Status';
    }

    public function typeFormatted()
    {
        switch ($this->type) {
            case self::TYPE_SINGLE_ADJUSTMENT:
                return 'Edit Stok';
            case self::TYPE_MASS_ADJUSTMENT:
                return 'Stok Opname';
            case self::TYPE_PURCHASE_ORDER:
                return 'Pembelian';
            case self::TYPE_PURCHASE_ORDER_RETURN:
                return 'Retur Pembelian';
            case self::TYPE_SALES_ORDER:
                return 'Penjualan';
            case self::TYPE_SALES_ORDER_RETURN:
                return 'Retur Penjualan';
        }

        return 'Unknown Status';
    }

    public function details()
    {
        return $this->hasMany(StockUpdateDetail::class, 'update_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_uid');
    }

    public function closed_by()
    {
        return $this->belongsTo(User::class, 'closed_uid');
    }

    public function last_saved_by()
    {
        return $this->belongsTo(User::class, 'last_saved_uid');
    }
}
