<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\ExpenseCategory;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseCategoryController extends Controller
{
    public function __construct()
    {
        ensure_user_can_access(AclResource::EXPENSE_CATEGORY_MANAGEMENT);
    }
    
    public function index()
    {
        $items = ExpenseCategory::orderBy('name', 'asc')->get();
        return view('admin.expense-category.index', compact('items'));
    }

    public function edit(Request $request, $id = 0)
    {
        $item = $id ? ExpenseCategory::find($id) : new ExpenseCategory();
        if (!$item) {
            return redirect('admin/expense-category')->with('warning', 'Kategori biaya tidak ditemukan.');
        }

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:expense_categories,name,' . $request->id . '|max:100',
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
                UserActivity::EXPENSE_CATEGORY_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Kategori Biaya',
                'Kategori Biaya ' . e($item->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            return redirect('admin/expense-category')->with('info', 'Kategori biaya telah disimpan.');
        }

        return view('admin.expense-category.edit', compact('item'));
    }

    public function delete($id)
    {
        // fix me, notif kalo kategori ga bisa dihapus
        if (!$item = ExpenseCategory::find($id))
            $message = 'Kategori tidak ditemukan.';
        else if ($item->delete($id)) {
            $message = 'Kategori ' . e($item->name) . ' telah dihapus.';
            UserActivity::log(
                UserActivity::EXPENSE_CATEGORY_MANAGEMENT,
                'Hapus Kategori',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/expense-category')->with('info', $message);
    }
}
