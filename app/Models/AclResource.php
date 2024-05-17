<?php

namespace App\Models;

class AclResource
{
    const OPEN_SYSTEM_MENU = 'open-system-menu';
    const OPEN_PURCHASING_MENU = 'open-purchasing-menu';
    const OPEN_SALES_MENU = 'open-sales-menu';
    const OPEN_INVENTORY_MENU = 'open-inventory-menu';

    const VIEW_ACTIVITY_LOG = 'view-activity-log';

    const MANAGE_PRODUCT_CATEGORIES = 'manage-product-categories';

    const VIEW_USERS = 'view-users';
    const VIEW_USER_GROUPS = 'view-user-groups';
    const CHANGE_SETTINGS = 'change-settings';

    const VIEW_SUPPLIERS = 'manage-suppliers';
    const ADD_SUPPLIER = 'add-supplier';
    const EDIT_SUPPLIER = 'edit-supplier';
    const DELETE_SUPPLIER = 'delete-supplier';

    const VIEW_CUSTOMERS = 'manage-customers';
    const ADD_CUSTOMER = 'add-customer';
    const EDIT_CUSTOMER = 'edit-customer';
    const DELETE_CUSTOMER = 'delete-customer';

    const VIEW_SALES_ORDERS = 'manage-sales-orders';
    const ADD_SALES_ORDER = 'add-sales-order';
    const EDIT_SALES_ORDER = 'edit-sales-order';
    const DELETE_SALES_ORDER = 'delete-sales-order';

    public static function all()
    {
        return [
            'Penjualan' => [
                self::OPEN_SALES_MENU => 'Mengakses menu penjualan',
                'Order Penjualan' => [
                    self::VIEW_SALES_ORDERS => 'Melihat daftar penjualan',
                    self::ADD_SALES_ORDER => 'Menambah order penjualan',
                    self::EDIT_SALES_ORDER => 'Mengubah order penjualan',
                    self::DELETE_SALES_ORDER => 'Menghapus order penjualan',
                ],
                'Pelanggan' => [
                    self::VIEW_CUSTOMERS => 'Melihat daftar pelanggan',
                    self::ADD_CUSTOMER => 'Menambah pelanggan',
                    self::EDIT_CUSTOMER => 'Mengubah Pelanggan',
                    self::DELETE_CUSTOMER => 'Menghapus pelanggan',
                ],
            ],
            'Inventori' => [
                self::MANAGE_PRODUCT_CATEGORIES => 'Pengelolaan kategori produk',
            ],
            'Pembelian' => [
                self::OPEN_PURCHASING_MENU => 'Mengakses menu pembelian',
                'Pemasok' => [
                    self::VIEW_SUPPLIERS => 'Melihat daftar pemasok',
                    self::ADD_SUPPLIER => 'Menambah pPemasok',
                    self::EDIT_SUPPLIER => 'Mengubah pemasok',
                    self::DELETE_SUPPLIER => 'Menghapus pemasok',
                ]
            ],
            'Sistem' => [
                self::OPEN_SYSTEM_MENU => 'Mengakses menu sistem',
                'Pengguna' => [
                    self::VIEW_USERS => 'Melihat daftar pengguna',
                ],
                'Grup Pengguna' => [
                    self::VIEW_USER_GROUPS => 'Melihat daftar grup pengguna',
                ],
                'Pengaturan' => [
                    self::CHANGE_SETTINGS => 'Mengubah pengaturan',
                ]
            ]
        ];
    }
}
