<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');
        return view('audit.show', compact('auditLog'));
    }

    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('model')) {
            $query->where('auditable_type', $request->model);
        }

        $logs    = $query->paginate(25)->withQueryString();
        $users   = User::orderBy('name')->get();
        $actions = AuditLog::distinct()->orderBy('action')->pluck('action');
        $models  = AuditLog::distinct()->orderBy('auditable_type')->pluck('auditable_type');

        return view('audit.index', compact('logs', 'users', 'actions', 'models'));
    }
}
