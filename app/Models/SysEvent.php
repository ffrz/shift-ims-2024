<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SysEvent extends Model
{
    public $timestamps = false;

    public const AUTHENTICATION = 'authentication';
    public const USER_MANAGEMENT = 'user-mgmt';
    public const USER_GROUP_MANAGEMENT = 'user-group-mgmt';
    public const SERVICE_ORDER_MANAGEMENT = 'service-order-mgmt';
    public const SETTINGS = 'settings';
    public const PRODUCT_CATEGORY_MANAGEMENT = 'product-category-mgmt';
    public const PRODUCT_MANAGEMENT = 'product-mgmt';

    protected $casts = [
        'data' => 'json'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'datetime',
        'type',
        'name',
        'description',
        'data',
    ];

    public static function log($type, $name, $description = '', $data = null, $username = null)
    {
        if ($username === null && Auth::user()) {
            $username = Auth::user()->username;
        }

        if ($username === null) {
            $username = '';
        }

        return self::create([
            'username' => $username,
            'datetime' => now(),
            'type' => $type,
            'name' => $name,
            'description' => $description,
            'data' => $data,
        ]);
    }

    function formattedType()
    {
        return self::formatType($this->type);
    }

    static function formatType($type)
    {
        switch ($type) {
            case self::AUTHENTICATION: return 'Otentikasi';
            case self::SETTINGS: return 'Pengaturan';
            case self::USER_MANAGEMENT: return 'Pengelolaan Pengguna';
            case self::USER_GROUP_MANAGEMENT: return 'Pengelolaan Grup Pengguna';
            case self::SERVICE_ORDER_MANAGEMENT: return 'Pengelolaan Order Servis';
            case self::PRODUCT_CATEGORY_MANAGEMENT: return 'Pengelolaan Kategori Produk';
            case self::PRODUCT_MANAGEMENT: return 'Pengelolaan Produk';
        }

        throw new Exception('tipe event tidak terdaftar');
    }
}
