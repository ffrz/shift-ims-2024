<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ServiceOrder extends Model
{
    public $timestamps = false;

    const ORDER_STATUS_ACTIVE = 0;
    const ORDER_STATUS_COMPLETED = 1;
    const ORDER_STATUS_CANCELED = 2;

    const SERVICE_STATUS_RECEIVED = 0;
    const SERVICE_STATUS_CHECKED = 1;
    const SERVICE_STATUS_WORKED = 2;
    const SERVICE_STATUS_SUCCESS = 3;
    const SERVICE_STATUS_FAILED = 4;

    const PAYMENT_STATUS_UNPAID = 0;
    const PAYMENT_STATUS_PARTIALLY_PAID = 1;
    const PAYMENT_STATUS_FULLY_PAID = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id', 'customer_name', 'customer_contact', 'customer_address',
        'device_type', 'device', 'equipments', 'device_sn', 'problems', 'actions',
        'date_checked', 'date_work_begin', 'date_completed', 'service_status',
        'order_status', 'created_datetime', 'closed_datetime', ' date_received', 'date_taken',
        'down_payment', 'estimated_cost', 'total_cost', 'payment_status',
        'technician', 'notes'
    ];

    public function open()
    {
        $this->date_received = current_date();
        $this->created_datetime = current_datetime();
        $this->created_uid = Auth::user()->id;
        $this->order_status = ServiceOrder::ORDER_STATUS_ACTIVE;
        $this->down_payment = 0;
        $this->total_cost = 0;
        $this->estimated_cost = 0;
    }

    public function close($order_status, $service_status, $payment_status)
    {
        $this->order_status = $order_status;
        $this->service_status = $service_status;
        $this->payment_status = $payment_status;
        $this->date_completed = current_date();
        $this->closed_datetime = current_datetime();
        $this->closed_uid = Auth::user()->id;
    }

    public function orderId()
    {
        return self::formatOrderId($this->id);
    }

    static function formatOrderId($id)
    {
        return 'SVC-' . str_pad($id, 6, '0', STR_PAD_LEFT);
    }

    static function formatOrderStatus($status)
    {
        switch ($status) {
            case self::ORDER_STATUS_ACTIVE:
                return 'Aktif';
            case self::ORDER_STATUS_COMPLETED:
                return 'Selesai';
            case self::ORDER_STATUS_CANCELED:
                return 'Dibatalkan';
        }
        throw new Exception("Unknown order status.");
    }

    static function formatServiceStatus($status)
    {
        switch ($status) {
            case self::SERVICE_STATUS_RECEIVED:
                return 'Diterima';
            case self::SERVICE_STATUS_CHECKED:
                return 'Sedang Diperiksa';
            case self::SERVICE_STATUS_WORKED:
                return 'Sedang Dikerjakan';
            case self::SERVICE_STATUS_SUCCESS:
                return 'Selesai: Sukses';
            case self::SERVICE_STATUS_FAILED:
                return 'Selesai: Gagal';
        }

        throw new Exception("Unknown service status.");
    }

    static function formatPaymentStatus($status)
    {
        switch ($status) {
            case self::PAYMENT_STATUS_UNPAID:
                return 'Belum Dibayar';
            case self::PAYMENT_STATUS_PARTIALLY_PAID:
                return 'Dibayar Sebagian';
            case self::PAYMENT_STATUS_FULLY_PAID:
                return 'Lunas';
        }

        throw new Exception("Unknown service status.");
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_uid');
    }

    public function closed_by()
    {
        return $this->belongsTo(User::class, 'closed_uid');
    }
}
