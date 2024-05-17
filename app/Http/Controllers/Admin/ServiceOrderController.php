<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\ServiceOrder;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ServiceOrderController extends Controller
{
    public function index(Request $request)
    {
        /** @disregard P1009 */
        if (!Auth::user()->canAccess(AclResource::SERVICE_ORDER_LIST))
            abort(403, 'AKSES DITOLAK');

        $filter = [
            'order_status'   => (int)$request->get('order_status',   $request->session()->get('service_order.filter.order_status',    0)),
            'service_status' => (int)$request->get('service_status', $request->session()->get('service_order.filter.service_status', -1)),
            'payment_status' => (int)$request->get('payment_status', $request->session()->get('service_order.filter.payment_status', -1)),
            'record_status' => (int)$request->get('record_status', $request->session()->get('service_order.filter.record_status', 1)),
        ];

        $q = ServiceOrder::query();

        if ($filter['order_status'] != -1)
            $q->where('order_status', '=', $filter['order_status']);
        if ($filter['service_status'] != -1)
            $q->where('service_status', '=', $filter['service_status']);
        if ($filter['payment_status'] != -1)
            $q->where('payment_status', '=', $filter['payment_status']);

        if ($filter['record_status'] == -1)
            $q->withTrashed();
        else if ($filter['record_status'] == 0)
            $q->onlyTrashed();

        $q->orderBy('id', 'asc');

        $items = $q->get();

        $request->session()->put('service_order.filter.order_status', $filter['order_status']);
        $request->session()->put('service_order.filter.service_status', $filter['service_status']);
        $request->session()->put('service_order.filter.payment_status', $filter['payment_status']);
        $request->session()->put('service_order.filter.record_status', $filter['record_status']);

        return view('admin.service-order.index', compact('items', 'filter'));
    }

    public function action(Request $request, $id)
    {
        $item = ServiceOrder::findOrFail($id);
        $action = $request->get('action');
        if ($action == 'service_success') {
            $item->service_status = ServiceOrder::SERVICE_STATUS_SUCCESS;
            $item->date_completed = date('Y-m-d');
        } else if ($action == 'service_failed') {
            $item->service_status = ServiceOrder::SERVICE_STATUS_FAILED;
            $item->date_completed = date('Y-m-d');
        } else if ($action == 'fully_paid') {
            $item->payment_status = ServiceOrder::PAYMENT_STATUS_FULLY_PAID;
        } else if ($action == 'complete_order') {
            $item->order_status = ServiceOrder::ORDER_STATUS_COMPLETED;
        } else if ($action == 'cancel_order') {
            $item->order_status = ServiceOrder::ORDER_STATUS_CANCELED;
        }

        $item->save();
        UserActivity::log(
            UserActivity::SERVICE_ORDER_MANAGEMENT,
            'Memperbarui status servis',
            'Status Order servis ' . e(ServiceOrder::formatOrderId($item->id)) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
            $item->toArray()
        );

        return redirect('admin/service-order/detail/' . $item->id)->with('info', 'Rekama order servis telah diperbarui.');
    }

    public function duplicate(Request $request, $sourceId)
    {
        /** @disregard P1009 */
        if (!Auth::user()->canAccess(AclResource::ADD_SERVICE_ORDER))
            abort(403, 'AKSES DITOLAK');

        $item = ServiceOrder::findOrFail($sourceId);
        $item = $item->replicate();
        $item->id = 0;

        $device_types = ServiceOrder::withTrashed(true)
            ->groupBy('device_type')->orderBy('device_type', 'asc')
            ->pluck('device_type')->toArray();

        $devices = ServiceOrder::withTrashed(true)
            ->groupBy('device')->orderBy('device', 'asc')
            ->pluck('device')->toArray();

        return view('admin.service-order.edit', compact('item', 'device_types', 'devices'));
    }

    public function detail($id)
    {
        $item = ServiceOrder::find($id);
        return view('admin.service-order.detail', compact('item'));
    }

    public function print($id)
    {
        $item = ServiceOrder::find($id);
        return view('admin.service-order.print', compact('item'));
    }

    public function edit(Request $request, $id = 0)
    {
        /** @disregard P1009 */
        if ((!$id && !Auth::user()->canAccess(AclResource::ADD_SERVICE_ORDER)) || ($id && !Auth::user()->canAccess(AclResource::EDIT_SERVICE_ORDER)))
            abort(403, 'AKSES DITOLAK');

        if ($id) {
            $item = ServiceOrder::find($id);
        } else {
            $item = new ServiceOrder();
            $item->date_received = date('Y-m-d');
            $item->order_status = ServiceOrder::ORDER_STATUS_ACTIVE;
            $item->down_payment = 0;
            $item->total_cost = 0;
            $item->estimated_cost = 0;
        }

        if (!$item)
            return redirect('admin/service-order')->with('warning', 'Order servis tidak ditemukan.');

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'customer_name' => 'required',
                'customer_contact' => 'required',
                'customer_address' => 'required',
                'device_type' => 'required',
                'device' => 'required',
                'equipments' => 'required',
                'problems' => 'required',
            ], [
                'customer_name.required' => 'Nama pelanggan harus diisi.',
                'customer_contact.required' => 'Kontak pelanggan harus diisi.',
                'customer_address.required' => 'Alamat pelanggan harus diisi.',
                'device_type.required' => 'Jenis perangkat harus diisi.',
                'device.required' => 'Perangkat harus diisi.',
                'equipments.required' => 'Kelengkapan harus diisi.',
                'problems.required' => 'Keluhan harus diisi.',
            ]);

            if ($validator->fails())
                return redirect()->back()->withInput()->withErrors($validator);

            $data = ['Old Data' => $item->toArray()];
            $requestData = $request->all();

            if (empty($requestData['date_taken']))
                $requestData['date_taken'] = null;
            if (empty($requestData['date_completed']))
                $requestData['date_completed'] = null;

            $item->fill($requestData);
            $item->save();
            $data['New Data'] = $item->toArray();

            UserActivity::log(
                UserActivity::SERVICE_ORDER_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Order Servis',
                'Order servis ' . e(ServiceOrder::formatOrderId($item->id)) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            return redirect('admin/service-order/detail/' . $item->id)->with('info', 'Order servis ' . ServiceOrder::formatOrderId($item->id) . ' telah disimpan.');
        }

        $device_types = ServiceOrder::withTrashed(true)
            ->groupBy('device_type')->orderBy('device_type', 'asc')
            ->pluck('device_type')->toArray();

        $devices = ServiceOrder::withTrashed(true)
            ->groupBy('device')->orderBy('device', 'asc')
            ->pluck('device')->toArray();

        return view('admin.service-order.edit', compact('item', 'device_types', 'devices'));
    }

    public function delete($id)
    {
        /** @disregard P1009 */
        if (!Auth::user()->canAccess(AclResource::DELETE_SERVICE_ORDER))
            abort(403, 'AKSES DITOLAK');

        if (!$item = ServiceOrder::find($id))
            $message = 'Order tidak ditemukan.';
        else if ($item->delete($id)) {
            $message = 'Order #' . e($item->orderId()) . ' telah dihapus.';
            UserActivity::log(
                UserActivity::SERVICE_ORDER_MANAGEMENT,
                'Hapus Order',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/service-order')->with('info', $message);
    }

    public function restore($id)
    {
        /** @disregard P1009 */
        if (!Auth::user()->canAccess(AclResource::DELETE_SERVICE_ORDER))
            abort(403, 'AKSES DITOLAK');

        if (!$item = ServiceOrder::withTrashed()->find($id))
            $message = 'Order tidak ditemukan.';
        else {
            $item->restore();
            $message = 'Order #' . e($item->orderId()) . ' telah dipulihkan.';
            UserActivity::log(
                UserActivity::SERVICE_ORDER_MANAGEMENT,
                'Pulihkan Order Servis',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/service-order')->with('info', $message);
    }
}
