<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        $active_sales_count = DB::select('select ifnull(count(0), 0) as count from stock_updates where type=:type and status=:status', [
            'type' => StockUpdate::TYPE_SALES_ORDER,
            'status' => StockUpdate::STATUS_OPEN,
        ])[0]->count;

        $data = [
            'active_service_order_count' => ServiceOrder::where('order_status', '=', 'active')->count(),
            'total_sales_this_month' => $total_sales_this_month,
            'sales_count_this_month' => $sales_count_this_month,
            'active_sales_count' => $active_sales_count,
        ];
        
        return view('admin.dashboard.index', compact('data'));
    }
}
