<?php

// use \App\Entities\StockUpdate;

function current_user_can($access)
{
    $user = current_user();

    if (!$user) {
        return false;
    }

    if ($user->is_admin) {
        return true;
    }

    if (isset($user->acl[$access]) && $user->acl[$access] == 1) {
        return true;
    }

    return false;
}

function datetime_from_input($str)
{
    $input = explode(' ', $str);
    $date = explode('-', $input[0]);

    $out =  "$date[2]-$date[1]-$date[0]";
    if (count($input) == 2) {
        $out .=  " $input[1]";
    }

    return $out;
}

// /**
//  * Helper function untuk merender kelas 'active' pada nav-item sidebar
//  * @param $self View
//  * @param $name nama nav
//  * @return string
//  */
// function nav_active($self, $name) {
//     return !empty($self->navActive) && $self->navActive == $name ? 'active' : '';
// }

function extract_daterange($daterange) {
    if (preg_match("/^([0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])) - ([0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]))$/", $daterange, $matches)) {
        return [$matches[1], $matches[4]];
    }
    return false;
}

function format_product_costing_method($cm)
{
    switch ($cm) {
        case 0: return 'Harga Beli Manual';
        case 1: return 'Harga Beli Terakhir';
        case 2: return 'Rata-rata Harga Beli';
    }
}

function format_number($number, int $prec = 0)
{
    return number_format($number, $prec, ',', '.');
}

function str_to_double($str)
{
    return doubleVal(str_replace('.', '', $str));
}

function str_to_int($str)
{
    return intVal(str_replace('.', '', $str));
}

function format_service_order_status($status) {
    switch ($status) {
        case 1: return 'Aktif';
        case 2: return 'Selesai';
        case 3: return 'Dibatalkan';
    }
    return 'unknown status';
}

function format_service_status($status) {
    switch ($status) {
        case 0: return 'Diterima';
        case 1: return 'Sedang Diperiksa';
        case 2: return 'Sedang Diperbaiki';
        case 3: return 'Selesai: Sukses';
        case 4: return 'Selesai: Gagal';  
    }
}

function format_service_order_payment_status($status)
{
    switch ($status) {
        case 0: return 'Belum Dibayar';
        case 1: return 'Dibayar Sebagian';
        case 2: return 'Lunas';
        case 3: return 'Tidak Dibayar';
    }
}

function is_admin()
{
    return session()->get('current_user')->is_admin;
}

function current_user()
{
    return session()->get('current_user');
}

function format_service_order_code($id) {
    return 'SVC-' . str_pad($id, 5, '0', STR_PAD_LEFT);;
}

function format_datetime($date, $format = 'dd-MM-yyyy HH:mm:ss', $locale = null) {
    if (!$date) {
        return '?';
    }
    if (!$date instanceof DateTime) {
        $date = new DateTime($date);
    }
    return IntlDateFormatter::formatObject($date, $format, $locale);
}

function format_date($date, $format = 'dd-MM-yyyy', $locale = null) {
    if (!$date instanceof DateTime) {
        $date = new DateTime($date);
    }
    return IntlDateFormatter::formatObject($date, $format, $locale);
}

// function format_stock_update_type($type) {
//     switch ($type) {
//         case StockUpdate::UPDATE_TYPE_INITIAL_STOCK: return 'Stok Awal';
//         case StockUpdate::UPDATE_TYPE_MANUAL_AJDUSTMENT: return 'Penyesuaian Manual';
//         case StockUpdate::UPDATE_TYPE_ADJUSTMENT: return 'Stok Opname';
//         case StockUpdate::UPDATE_TYPE_PURCHASE_ORDER: return 'Pembelian';
//         case StockUpdate::UPDATE_TYPE_PURCHASE_ORDER_RETURN: return 'Retur Pembelian';
//         case StockUpdate::UPDATE_TYPE_SALES_ORDER: return 'Penjualan';
//         case StockUpdate::UPDATE_TYPE_SALES_ORDER_RETURN: return 'Retur Penjualan';
//     }    
// }

// function format_stock_update_status($status)
// {
//     switch ($status) {
//         case StockUpdate::STATUS_SAVED: return 'Disimpan';
//         case StockUpdate::STATUS_COMPLETED: return 'Selesai';
//         case StockUpdate::STATUS_CANCELED: return 'Dibatalkan';
//     }
// }

// function format_stock_update_payment_status($status)
// {
//     switch ($status) {
//         case StockUpdate::PAYMENTSTATUS_UNPAID: return 'Belum Lunas';
//         case StockUpdate::PAYMENTSTATUS_PARTIALLYPAID: return 'Dibayar Sebagian';
//         case StockUpdate::PAYMENTSTATUS_FULLYPAID: return 'Lunas';
//     }
// }

// function format_stock_update_code($type, $code)
// {
//     $code = str_pad($code, 5, '0', STR_PAD_LEFT);
//     switch ($type) {
//         case StockUpdate::UPDATE_TYPE_INITIAL_STOCK: return 'IS-' . $code;
//         case StockUpdate::UPDATE_TYPE_MANUAL_AJDUSTMENT: return 'MA-' . $code;
//         case StockUpdate::UPDATE_TYPE_ADJUSTMENT: return 'SA-' . $code;
//         case StockUpdate::UPDATE_TYPE_PURCHASE_ORDER: return 'PO-' . $code;
//         case StockUpdate::UPDATE_TYPE_PURCHASE_ORDER_RETURN: return 'POR-' . $code;
//         case StockUpdate::UPDATE_TYPE_SALES_ORDER: return 'SO-' . $code;
//         case StockUpdate::UPDATE_TYPE_SALES_ORDER_RETURN: return 'SOR-' . $code;
//     }
// }

// use App\Models\Product;
// function format_product_type($type) {
//     switch ($type) {
//         case Product::TYPE_NON_STOCKED: return 'Non Stok';
//         case Product::TYPE_STOCKED: return 'Stok';
//         case Product::TYPE_SERVICE: return 'Jasa';
//         case Product::TYPE_COMPOSITE: return 'Komposisi';
//     }
// }

function wa_send_url($contact) {
    $contact = str_replace('-', '', $contact);
    if (substr($contact, 0, 1) == '0') {
        $contact = '62' . substr($contact, 1, strlen($contact));
    }
    if (strlen($contact) > 10) {
        return "https://web.whatsapp.com/send?phone=$contact";
    }
    return '#';
}

function month_names($month)
{
    switch ((int)$month) {
        case 1: return "Januari";
        case 2: return "Februari";
        case 3: return "Maret";
        case 4: return "April";
        case 5: return "Mei";
        case 6: return "Juni";
        case 7: return "Juli";
        case 8: return "Agustus";
        case 9: return "September";
        case 10: return "Oktober";
        case 11: return "November";
        case 12: return "Desember";
    }
}