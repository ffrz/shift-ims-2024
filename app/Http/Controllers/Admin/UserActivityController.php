<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserActivityController extends Controller
{
    public function __construct()
    {
        /** @disregard P1009 */
        if (!Auth::user()->canAccess(AclResource::ACTIVITY_LOG))
            abort(403, 'AKSES DITOLAK');
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
