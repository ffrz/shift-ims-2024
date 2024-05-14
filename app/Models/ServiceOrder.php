<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOrder extends Model
{
    use SoftDeletes;

    const ORDER_STATUS_ACTIVE = 0;
    const ORDER_STATUS_COMPLETED = 1;

    const SERVICE_STATUS_RECEIVED = 0;
    const SERVICE_STATUS_CHECKED = 1;
    const SERVICE_STATUS_WORKED = 2;
    const SERVICE_STATUS_SUCCESS = 3;
    const SERVICE_STATUS_FAILED = 4;

    const PAYMENT_STATUS_UNPAID = 0;
    //const PAYMENT_STATUS_PARTIALLY_PAID = 1;
    const PAYMENT_STATUS_FULLY_PAID = 2;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_name', 'customer_contact', 'customer_address',
        'device_type', 'device', 'equipments', 'device_sn', 'problems', 'actions',
        'date_checked', 'date_work_begin', 'date_completed', 'service_status',
        'order_status', ' date_received', 'date_completed', 'date_taken',
        'down_payment', 'estimated_cost', 'total_cost', 'payment_status',
        'technician', 'notes'
    ];

    static function formatOrderId($id)
    {
        return 'SVC-' . str_pad($id, 6, '0', STR_PAD_LEFT);
    }

    static function formatOrderStatus($status)
    {
        switch ($status) {
            case self::ORDER_STATUS_ACTIVE: return 'Aktif';
            case self::ORDER_STATUS_COMPLETED: return 'Selesai';
        }
        throw new Exception("Unknown order status.");
    }

    static function formatServiceStatus($status)
    {
        switch ($status) {
            case self::SERVICE_STATUS_RECEIVED: return 'Diterima';
            case self::SERVICE_STATUS_CHECKED: return 'Diperiksa';
            case self::SERVICE_STATUS_WORKED: return 'Dikerjakan';
            case self::SERVICE_STATUS_SUCCESS: return 'Sukses';
            case self::SERVICE_STATUS_FAILED: return 'Gagal';
        }

        throw new Exception("Unknown service status.");
    }

    static function formatPaymentStatus($status)
    {
        switch ($status) {
            case self::PAYMENT_STATUS_UNPAID: return 'Belum Lunas';
            // case self::PAYMENT_STATUS_PARTIALLY_PAID: return 'Belum Lunas';
            case self::PAYMENT_STATUS_FULLY_PAID: return 'Lunas';
        }

        throw new Exception("Unknown service status.");
    }
}
