<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\CostCategory;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function __construct()
    {
        ensure_user_can_access(AclResource::EXPENSE_MANAGEMENT);
    }

    public function index(Request $request)
    {
        $filter = [
            'm' => $request->get('m', date('m')),
            'y' => $request->get('y', date('Y')),
            'category_id' => $request->get('category_id', -1),
            'search' => $request->get('search'),
        ];

        $q = Expense::query();
        if (!empty($filter['search'])) {
            $q->where('description', 'like', '%' . $filter['search'] . '%');
        }

        if (!empty($filter['category_id'])) {
            $q->where('category_id', '=', $filter['category_id']);
        }

        if (!empty($filter['y'])) {
            $q->whereRaw('year(date)=' . (int)$filter['y']);
        }

        if (!empty($filter['m'])) {
            $q->whereRaw('month(date)=' . (int)$filter['m']);
        }

        $items = $q->orderBy('id', 'desc')->paginate(10);
        $categories = ExpenseCategory::orderBy('name', 'asc')->get();
        $years = years(2023, date('Y'));
        $months = months();

        return view('admin.expense.index', compact('items', 'categories', 'years', 'months', 'filter'));
    }

    public function edit(Request $request, $id = 0)
    {
        $item = null;
        if ($id) {
            $item = Expense::find($id);
            if (!$item) {
                return redirect('admin/expense')->with('warning', 'Biaya tidak ditemukan.');
            }
        } else {
            $item = new Expense();
            $item->date = current_date();
        }

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'description' => 'required|max:100',
                'category_id' => 'required',
                'date' => 'required|date',
            ], [
                'description.required' => 'Deskripsi harus diisi.',
                'description.max' => 'Deskripsi terlalu panjang, maksimal 100 karakter.',
                'category_id.required' => 'Kategori harus dipilih.',
                'date.required' => 'Tanggal harus diisi.',
                'date.date' => 'Format tanggal salah.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $data = ['Old Data' => $item->toArray()];
            $item->fill($request->all());
            $item->amount = numberFromInput($request->amount);
            $item->save();
            $data['New Data'] = $item->toArray();

            UserActivity::log(
                UserActivity::EXPENSE_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Biaya',
                'Biaya ' . e($item->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            return redirect('admin/expense')->with('info', 'Biaya telah disimpan.');
        }

        $categories = ExpenseCategory::orderBy('name', 'asc')->get();

        return view('admin.expense.edit', compact('item', 'categories'));
    }

    public function delete($id)
    {
        if (!$item = Expense::find($id)) {
            $message = 'Rekaman biaya tidak ditemukan.';
        } else if ($item->delete($id)) {
            $message = 'Rekaman biaya ' . e($item->description) . ' telah dihapus.';
            UserActivity::log(
                UserActivity::EXPENSE_MANAGEMENT,
                'Hapus Biaya',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/expense')->with('info', $message);
    }
}
