<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SysEvent;
use Illuminate\Http\Request;

class SysEventController extends Controller
{
    public function index()
    {
        $q = SysEvent::query();
        $q->orderBy('id', 'desc');
        $items = $q->get();
        
        return view('admin.sys-events.index', compact('items'));
    }

    public function show(Request $request, $id = 0)
    {
        $item = SysEvent::findOrFail($id);
        return view('admin.sys-events.show', compact('item'));
    }

    public function delete(Request $request)
    {
        $item = SysEvent::findOrFail($request->post('id', 0));
        $item->delete();
        return redirect('admin/sys-events')->with('info', 'Rekaman log aktivitas <b>#' . $item->id . '</b> telah dihapus.');
    }
}
