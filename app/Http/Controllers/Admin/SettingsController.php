<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SysEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function edit(Request $request)
    {
        $data = [
            'business_name' => Setting::value('app.business_name', ''),
            'business_address' => Setting::value('app.business_address', ''),
            'business_phone' => Setting::value('app.business_phone', ''),
            'business_owner' => Setting::value('app.business_owner', ''),
        ];
        return view('admin.settings.edit', compact('data'));
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_name' => 'required'
        ], [
            'business_name.required' => 'Nama Usaha harus diisi.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $oldValues = Setting::values();

        DB::beginTransaction();
        Setting::setValue('app.business_name', $request->post('business_name', ''));
        Setting::setValue('app.business_address', $request->post('business_address', ''));
        Setting::setValue('app.business_phone', $request->post('business_phone', ''));
        Setting::setValue('app.business_owner', $request->post('business_owner', ''));
        DB::commit();

        $data = [
            'Old Value' => $oldValues,
            'New Value' => Setting::values(),
        ];

        SysEvent::log(SysEvent::SETTINGS, 'Change Settings', 'Pengaturan telah diperbarui.', $data);

        return redirect('admin/settings')->with('info', 'Pengaturan telah disimpan.');
    }
}
