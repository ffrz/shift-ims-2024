<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use App\Models\SysEvent;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceOrderController extends Controller
{
    public function index(Request $request)
    {
        $filter = [
            'order_status'   => (int)$request->get('order_status', 0),
            'service_status' => (int)$request->get('service_status', -1),
            'payment_status' => (int)$request->get('payment_status', -1),
        ];

        $q = ServiceOrder::query();
        if ($filter['order_status'] != -1)
            $q->where('order_status', '=', $filter['order_status']);
        if ($filter['service_status'] != -1)
            $q->where('service_status', '=', $filter['service_status']);
        if ($filter['payment_status'] != -1)
            $q->where('payment_status', '=', $filter['payment_status']);

        $q->orderBy('id', 'asc');
        $items = $q->get();

        return view('admin.service-order.index', compact('items', 'filter'));
    }

    public function duplicate(Request $request, $sourceId)
    {
        $sourceItem = ServiceOrder::findOrFail($sourceId);
        $item = $sourceItem->replicate();
        return view('admin.service-order.edit', compact('item'));
    }

    public function edit(Request $request, $id = 0)
    {
        $item = $id ? ServiceOrder::find($id) : new ServiceOrder();

        if (!$item)
            return redirect('admin/service-order')->with('warning', 'Order servis tidak ditemukan.');

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:user_groups,name,' . $request->id . '|max:100',
            ], [
                'name.required' => 'Nama grup harus diisi.',
                'name.unique' => 'Nama grup sudah digunakan.',
                'name.max' => 'Nama grup terlalu panjang, maksimal 100 karakter.',
            ]);

            if ($validator->fails())
                return redirect()->back()->withInput()->withErrors($validator);

            $data = ['Old Data' => $group->toArray()];
            $group->fill($request->all());
            $group->save();
            $data['New Data'] = $group->toArray();

            SysEvent::log(
                SysEvent::USERGROUP_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Grup Pengguna',
                'Grup pengguna ' . e($group->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            return redirect('admin/user-groups')->with('info', 'Grup pengguna telah disimpan.');
        }

        $device_types = ServiceOrder::withTrashed(true)->orderBy('device_type', 'asc')->groupBy('device_type')->pluck('device_type')->toArray();
        $devices = ServiceOrder::withTrashed(true)->groupBy('device')->orderBy('device', 'asc')->pluck('device')->toArray();
        return view('admin.service-order.edit', compact('item', 'device_types', 'devices'));
    }

    public function delete($id)
    {
        if (!$item = ServiceOrder::find($id))
            $message = 'Order tidak ditemukan.';
        else if ($item->delete($id)) {
            $message = 'Order #' . e($item->id) . ' telah dihapus.';
            SysEvent::log(
                SysEvent::SERVICEORDER_MANAGEMENT,
                'Hapus Order',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/service-order')->with('info', $message);
    }
}
