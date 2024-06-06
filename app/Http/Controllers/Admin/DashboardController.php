<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ServiceOrder;
use App\Models\StockUpdate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $start_datetime_this_month = date('Y-m-01 00:00:00');
        $end_datetime_this_month = date('Y-m-t 23:59:59');

        $start_datetime_today = date('Y-m-d 00:00:00');
        $end_datetime_today = date('Y-m-d 23:59:59');

        $total_sales_this_month = DB::select('select ifnull(abs(sum(total)), 0) as sum from stock_updates where type=:type and status=:status and (datetime between :start and :end)', [
            'type' => StockUpdate::TYPE_SALES_ORDER,
            'status' => StockUpdate::STATUS_COMPLETED,
            'start' => $start_datetime_this_month,
            'end' => $end_datetime_this_month,
        ])[0]->sum;

        $sales_count_this_month = DB::select('select ifnull(count(0), 0) as count from stock_updates where type=:type and status=:status and (datetime between :start and :end)', [
            'type' => StockUpdate::TYPE_SALES_ORDER,
            'status' => StockUpdate::STATUS_COMPLETED,
            'start' => $start_datetime_this_month,
            'end' => $end_datetime_this_month,
        ])[0]->count;

        $total_sales_today = DB::select('select ifnull(abs(sum(total)), 0) as sum from stock_updates where type=:type and status=:status and (datetime between :start and :end)', [
            'type' => StockUpdate::TYPE_SALES_ORDER,
            'status' => StockUpdate::STATUS_COMPLETED,
            'start' => $start_datetime_today,
            'end' => $end_datetime_today,
        ])[0]->sum;

        $active_sales_count = DB::select('select ifnull(count(0), 0) as count from stock_updates where type=:type and status=:status', [
            'type' => StockUpdate::TYPE_SALES_ORDER,
            'status' => StockUpdate::STATUS_OPEN,
        ])[0]->count;

        $sales_count_today = DB::select('select ifnull(count(0), 0) as count from stock_updates where type=:type and status=:status and (datetime between :start and :end)', [
            'type' => StockUpdate::TYPE_SALES_ORDER,
            'status' => StockUpdate::STATUS_COMPLETED,
            'start' => $start_datetime_this_month,
            'end' => $end_datetime_this_month,
        ])[0]->count;

        $total_inventory_asset = DB::select('select ifnull(sum(stock*cost), 0) as sum from products where type=:type and active=1 and stock > 0', [
            'type' => Product::STOCKED
        ])[0]->sum;

        $total_inventory_asset_price = DB::select('select ifnull(sum(stock*price), 0) as sum from products where type=:type and active=1 and stock > 0', [
            'type' => Product::STOCKED
        ])[0]->sum;

        $data = [
            'active_service_order_count' => ServiceOrder::where('order_status', '=', 'active')->count(),
            'total_sales_this_month' => $total_sales_this_month,
            'sales_count_this_month' => $sales_count_today,
            'total_sales_today' => $total_sales_today,
            'sales_count_today' => $sales_count_this_month,
            'active_sales_count' => $active_sales_count,
            'total_inventory_asset' => $total_inventory_asset,
            'total_inventory_asset_price' => $total_inventory_asset_price,
        ];
        
        return view('admin.dashboard.index', compact('data'));
    }
}
