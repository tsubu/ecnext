<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the audit logs.
     */
    public function index()
    {
        $logs = AuditLog::with('user')->latest()->paginate(50);
        return view('admin.audit-logs.index', compact('logs'));
    }
}
