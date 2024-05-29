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
        'creation_datetime',
        'closing_datetime',
        'creation_uid',
        'closing_uid',
    ];

    public function open()
    {
        $this->status = StockUpdate::STATUS_OPEN;
        $this->creation_datetime = date('Y-m-d H:m:s');
        $this->creation_uid = Auth::user()->id;
    }

    public function close($status)
    {
        $this->status = $status;
        $this->closing_datetime = date('Y-m-d H:m:s');
        $this->closing_uid = Auth::user()->id;
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
        return 'SU-' . $this->date() . '-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public function date()
    {
        $dt = explode(' ', $this->creation_datetime);
        return $dt[0];
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
        return $prefix . '-' . $this->date() . '-' . str_pad($this->id2, 5, '0', STR_PAD_LEFT);
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

    public function creation_user()
    {
        return $this->belongsTo(User::class, 'creation_uid');
    }

    public function closing_user()
    {
        return $this->belongsTo(User::class, 'closing_uid');
    }
}
