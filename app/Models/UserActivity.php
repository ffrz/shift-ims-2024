<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserActivity extends Model
{
    public $timestamps = false;

    public const AUTHENTICATION = 'authentication';
    public const USER_MANAGEMENT = 'user-mgmt';
    public const USER_GROUP_MANAGEMENT = 'user-group-mgmt';
    public const SERVICE_ORDER_MANAGEMENT = 'service-order-mgmt';
    public const SETTINGS = 'settings';
    public const PRODUCT_CATEGORY_MANAGEMENT = 'product-category-mgmt';
    public const PRODUCT_MANAGEMENT = 'product-mgmt';
    public const SUPPLIER_MANAGEMENT = 'supplier-mgmt';
    public const CUSTOMER_MANAGEMENT = 'customer-mgmt';
    public const COST_CATEGORY_MANAGEMENT = 'cost-category-mgmt';

    private static $_types = [
        self::AUTHENTICATION => 'Otentikasi',
        self::SETTINGS => 'Pengaturan',
        self::USER_MANAGEMENT => 'Pengelolaan Pengguna',
        self::USER_GROUP_MANAGEMENT => 'Pengelolaan Grup Pengguna',
        self::SERVICE_ORDER_MANAGEMENT => 'Pengelolaan Order Servis',
        self::PRODUCT_CATEGORY_MANAGEMENT => 'Pengelolaan Kategori Produk',
        self::PRODUCT_MANAGEMENT => 'Pengelolaan Produk',
        self::CUSTOMER_MANAGEMENT => 'Pengelolaan Pelanggan',
        self::SUPPLIER_MANAGEMENT => 'Pengelolaan Pemasok',
        self::COST_CATEGORY_MANAGEMENT => 'Pengelolaan Kategori Biaya',
    ];

    protected $casts = [
        'data' => 'json'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'username',
        'datetime',
        'type',
        'name',
        'description',
        'data',
    ];

    public static function log($type, $name, $description = '', $data = null, $username = null, $user_id = null)
    {
        $user = Auth::user();
        $id = $user_id;
        if ($username === null && $user) {
            $username = $user->username;
            $id = $user->id;
        }

        if ($username === null) {
            $username = '';
        }

        return self::create([
            'user_id' => $id,
            'username' => $username,
            'datetime' => now(),
            'type' => $type,
            'name' => $name,
            'description' => $description,
            'data' => $data,
        ]);
    }

    public function typeFormatted()
    {
        return self::formatType($this->type);
    }

    public static function types() {
        return self::$_types;
    }

    public static function formatType($type)
    {
        if (isset(self::$_types[$type])) {
            return self::$_types[$type];
        }

        throw new Exception('tipe event tidak terdaftar');
    }
}
