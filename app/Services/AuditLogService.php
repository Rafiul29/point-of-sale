<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    /**
     * Write an audit log entry.
     *
     * @param  string  $action      e.g. 'sale_created', 'stock_in', 'price_changed'
     * @param  Model   
     * @param  array   
     * @param  array  
     */
    public static function log(string $action, Model $model, array $oldValues = [], array $newValues = []): void
    {
        AuditLog::create([
            'user_id'        => Auth::id(),
            'action'         => $action,
            'auditable_type' => class_basename($model),
            'auditable_id'   => $model->id,
            'old_values'     => $oldValues ?: null,
            'new_values'     => $newValues ?: null,
        ]);
    }
}
