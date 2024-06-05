<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\Customer;
use App\Models\Party;
use App\Models\ServiceOrder;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\ServerBag;

class ServiceOrderController extends Controller
{
    public function index(Request $request)
    {
        ensure_user_can_access(AclResource::SERVICE_ORDER_LIST);

        $filter = [
            'order_status'   => (int)$request->get('order_status',   $request->session()->get('service_order.filter.order_status',    0)),
            'service_status' => (int)$request->get('service_status', $request->session()->get('service_order.filter.service_status', -1)),
            'payment_status' => (int)$request->get('payment_status', $request->session()->get('service_order.filter.payment_status', -1)),
            'search' => $request->get('search', ''),
        ];

        $q = ServiceOrder::query();

        if ($filter['order_status'] != -1) {
            $q->where('order_status', '=', $filter['order_status']);
        }

        if ($filter['service_status'] != -1) {
            $q->where('service_status', '=', $filter['service_status']);
        }

        if ($filter['payment_status'] != -1) {
            $q->where('payment_status', '=', $filter['payment_status']);
        }

        if (!empty($filter['search'])) {
            $q->where('customer_name', 'like', '%' . $filter['search'] . '%')
                ->orWhere('customer_phone', 'like', '%' . $filter['search'] . '%')
                ->orWhere('customer_address', 'like', '%' . $filter['search'] . '%')
                ->orWhere('device', 'like', '%' . $filter['search'] . '%')
                ->orWhere('device_sn', 'like', '%' . $filter['search'] . '%');
        }

        $q->orderBy('id', 'desc');

        $items = $q->paginate(10);

        $request->session()->put('service_order.filter.order_status', $filter['order_status']);
        $request->session()->put('service_order.filter.service_status', $filter['service_status']);
        $request->session()->put('service_order.filter.payment_status', $filter['payment_status']);

        return view('admin.service-order.index', compact('items', 'filter'));
    }

    public function action(Request $request, $id)
    {
        ensure_user_can_access(AclResource::EDIT_SERVICE_ORDER);

        $item = ServiceOrder::findOrFail($id);
        $action = $request->get('action');
        if ($action == 'taken') {
            $item->date_picked = current_date();
        } 
        else if ($action == 'service_receive') {
            $item->service_status = ServiceOrder::SERVICE_STATUS_NOT_YET_CHECKED;
            $item->date_received = current_date();
            $item->date_checked = null;
            $item->date_worked = null;
            $item->date_completed = null;
        } else if ($action == 'service_check') {
            $item->service_status = ServiceOrder::SERVICE_STATUS_CHECKED;
            $item->date_checked = current_date();
            $item->date_worked = null;
            $item->date_completed = null;
        } else if ($action == 'service_do') {
            $item->service_status = ServiceOrder::SERVICE_STATUS_WORKED;
            $item->date_worked = current_date();
            $item->date_completed = null;
        } else if ($action == 'service_success') {
            $item->service_status = ServiceOrder::SERVICE_STATUS_SUCCESS;
            $item->date_completed = current_date();
        } else if ($action == 'service_failed') {
            $item->service_status = ServiceOrder::SERVICE_STATUS_FAILED;
            $item->date_completed = current_date();
        } else if ($action == 'unpaid') {
            $item->payment_status = ServiceOrder::PAYMENT_STATUS_UNPAID;
        } else if ($action == 'partially_paid') {
            $item->payment_status = ServiceOrder::PAYMENT_STATUS_PARTIALLY_PAID;
        } else if ($action == 'fully_paid') {
            $item->payment_status = ServiceOrder::PAYMENT_STATUS_FULLY_PAID;
        } else if ($action == 'activate_order') {
            $item->order_status = ServiceOrder::ORDER_STATUS_ACTIVE;
            $item->closed_datetime = null;
            $item->closed_by_uid = null;            
        } else if ($action == 'complete_order') {
            $item->order_status = ServiceOrder::ORDER_STATUS_COMPLETED;
            $item->closed_datetime = current_datetime();
            $item->closed_by_uid = Auth::user()->id;
        } else if ($action == 'cancel_order') {
            $item->order_status = ServiceOrder::ORDER_STATUS_CANCELED;
            $item->closed_datetime = current_datetime();
            $item->closed_by_uid = Auth::user()->id;
        } else if ($action == 'complete_all') {
            $item->close(ServiceOrder::ORDER_STATUS_COMPLETED, ServiceOrder::SERVICE_STATUS_SUCCESS, ServiceOrder::PAYMENT_STATUS_FULLY_PAID);
        }

        $item->save();
        UserActivity::log(
            UserActivity::SERVICE_ORDER_MANAGEMENT,
            'Memperbarui status servis',
            'Status Order servis ' . $item->idFormatted() . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
            $item->toArray()
        );

        return redirect('admin/service-order/detail/' . $item->id)->with('info', 'Rekama order servis telah diperbarui.');
    }

    public function duplicate(Request $request, $sourceId)
    {
        ensure_user_can_access(AclResource::ADD_SERVICE_ORDER);

        $item = ServiceOrder::findOrFail($sourceId);
        $item = $item->replicate();
        $item->id = 0;

        $device_types = ServiceOrder::groupBy('device_type')->orderBy('device_type', 'asc')
            ->pluck('device_type')->toArray();

        $devices = ServiceOrder::groupBy('device')->orderBy('device', 'asc')
            ->pluck('device')->toArray();

        return view('admin.service-order.edit', compact('item', 'device_types', 'devices'));
    }

    public function detail($id)
    {
        $item = ServiceOrder::with(['created_by', 'closed_by'])->findOrFail($id);
        return view('admin.service-order.detail', compact('item'));
    }

    public function print($id)
    {
        $item = ServiceOrder::findOrFail($id);
        return view('admin.service-order.print', compact('item'));
    }

    public function edit(Request $request, $id = 0)
    {
        if (!$id) {
            ensure_user_can_access(AclResource::ADD_SERVICE_ORDER);
            $item = new ServiceOrder();
            $item->open();
        } else {
            ensure_user_can_access(AclResource::EDIT_SERVICE_ORDER);
            $item = ServiceOrder::find($id);
        }

        if (!$item)
            return redirect('admin/service-order')->with('warning', 'Order servis tidak ditemukan.');

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'customer_name' => 'required',
                'customer_phone' => 'required',
                'customer_address' => 'required',
                'device_type' => 'required',
                'device' => 'required',
                'equipments' => 'required',
                'problems' => 'required',
            ], [
                'customer_name.required' => 'Nama pelanggan harus diisi.',
                'customer_phone.required' => 'Kontak pelanggan harus diisi.',
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

            if (empty($requestData['date_picked']))
                $requestData['date_picked'] = null;
            if (empty($requestData['date_completed']))
                $requestData['date_completed'] = null;

            $requestData['down_payment'] = numberFromInput($requestData['down_payment']);
            $requestData['estimated_cost'] = numberFromInput($requestData['estimated_cost']);
            $requestData['total_cost'] = numberFromInput($requestData['total_cost']);

            empty_string_to_null($requestData, ['date_received', 'date_checked', 'date_worked', 'date_completed', 'date_picked']);

            $prevStatus = $item->order_status;
            $item->fill($requestData);

            if ($item->order_status == ServiceOrder::ORDER_STATUS_ACTIVE) {
                $item->closed_datetime = null;
            }

            if (
                $prevStatus == ServiceOrder::ORDER_STATUS_ACTIVE
                && $item->order_status != ServiceOrder::ORDER_STATUS_ACTIVE
            ) {
                $item->closed_datetime = current_datetime();
                $item->closed_by_uid = Auth::user()->id;
            }

            DB::beginTransaction();
            if (empty($request->customer_id)) {
                $customer = new Customer();
                $customer->id2 = Customer::getNextId2($customer->type);
                $customer->name = (string)$item->customer_name;
                $customer->phone = (string)$item->customer_phone;
                $customer->address = (string)$item->customer_address;
                $customer->active = true;
                $customer->notes = '';
                $customer->save();
                $item->customer_id = $customer->id;
            }

            $item->save();
            $data['New Data'] = $item->toArray();

            UserActivity::log(
                UserActivity::SERVICE_ORDER_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Order Servis',
                'Order servis ' . e($item->idFormatted()) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );
            DB::commit();

            return redirect('admin/service-order/detail/' . $item->id)->with('info', 'Order servis ' . $item->idFormatted() . ' telah disimpan.');
        }

        $device_types = ServiceOrder::groupBy('device_type')->orderBy('device_type', 'asc')
            ->pluck('device_type')->toArray();

        $devices = ServiceOrder::groupBy('device')->orderBy('device', 'asc')
            ->pluck('device')->toArray();

        $customers = Customer::where('type', '=', Party::TYPE_CUSTOMER)
            ->where('active', '=', 1)
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.service-order.edit', compact('item', 'device_types', 'devices', 'customers'));
    }

    public function delete($id)
    {
        ensure_user_can_access(AclResource::DELETE_SERVICE_ORDER);
        $item = ServiceOrder::findOrFail($id);

        if ($item->delete($id)) {
            $message = 'Order #' . e($item->idFormatted()) . ' telah dihapus.';
            UserActivity::log(
                UserActivity::SERVICE_ORDER_MANAGEMENT,
                'Hapus Order',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/service-order')->with('info', $message);
    }
}
