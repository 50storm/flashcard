<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        // 有効なお知らせを取得して、開始日でソート
        $notices = Notice::where('is_active', true)
                         ->where(function ($query) {
                             $query->whereNull('end_date')
                                   ->orWhere('end_date', '>', now());
                         })
                         ->orderBy('start_date', 'desc')
                         ->get();

        return view('notices.index', compact('notices'));
    }
}
