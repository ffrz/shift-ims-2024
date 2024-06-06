<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\CostCategory;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function __construct()
    {
        // ensure_user_can_access(AclResource::COST_CATEGORY_MANAGEMENT);
    }
    
    public function index()
    {
        $items = Cost::orderBy('id', 'desc')->get();
        return view('admin.cost.index', compact('items'));
    }

    public function edit(Request $request, $id = 0)
    {
        $item = $id ? CostCategory::find($id) : new CostCategory();
        if (!$item) {
            return redirect('admin/cost-category')->with('warning', 'Kategori biaya tidak ditemukan.');
        }

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:cost_categories,name,' . $request->id . '|max:100',
            ], [
                'name.required' => 'Nama kategori harus diisi.',
                'name.unique' => 'Nama kategori sudah digunakan.',
                'name.max' => 'Nama kategori terlalu panjang, maksimal 100 karakter.',
            ]);

            if ($validator->fails())
                return redirect()->back()->withInput()->withErrors($validator);

            $data = ['Old Data' => $item->toArray()];
            $item->fill($request->all());
            $item->save();
            $data['New Data'] = $item->toArray();

            UserActivity::log(
                UserActivity::COST_CATEGORY_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Kategori Biaya',
                'Kategori Biaya ' . e($item->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            return redirect('admin/cost-category')->with('info', 'Kategori biaya telah disimpan.');
        }

        return view('admin.cost-category.edit', compact('item'));
    }

    public function delete($id)
    {
        // fix me, notif kalo kategori ga bisa dihapus
        if (!$item = CostCategory::find($id))
            $message = 'Kategori tidak ditemukan.';
        else if ($item->delete($id)) {
            $message = 'Kategori ' . e($item->name) . ' telah dihapus.';
            UserActivity::log(
                UserActivity::COST_CATEGORY_MANAGEMENT,
                'Hapus Kategori',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/cost-category')->with('info', $message);
    }
}
