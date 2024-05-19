<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\UserActivity;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    public function __construct()
    {
        ensure_user_can_access(AclResource::USER_ACTIVITY);
    }

    public function index()
    {
        $q = UserActivity::query();
        $q->orderBy('id', 'desc');
        $items = $q->get();
        
        return view('admin.user-activity.index', compact('items'));
    }

    public function show(Request $request, $id = 0)
    {
        $item = UserActivity::findOrFail($id);
        return view('admin.user-activity.show', compact('item'));
    }

    public function delete(Request $request)
    {
        $item = UserActivity::findOrFail($request->post('id', 0));
        $item->delete();
        return redirect('admin/user-activity')->with('info', 'Rekaman log aktivitas <b>#' . $item->id . '</b> telah dihapus.');
    }
}
