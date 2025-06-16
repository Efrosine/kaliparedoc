<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the logs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::all()->pluck('name', 'id');

        $query = Log::query()->with('user');

        // Filter by user if specified
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action if specified
        if ($request->has('action') && $request->action) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        // Filter by model type if specified
        if ($request->has('model_type') && $request->model_type) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by date range if specified
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Get unique model types for filter dropdown
        $modelTypes = Log::distinct('model_type')->pluck('model_type')->filter();

        // Order by newest first
        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('superadmin.logs.index', compact('logs', 'users', 'modelTypes'));
    }

    /**
     * Get detailed information about a specific log entry.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $log = Log::with('user')->findOrFail($id);

        // Load the related model if available
        $relatedModel = null;
        if ($log->model_type && $log->model_id) {
            $modelClass = 'App\\Models\\' . $log->model_type;
            if (class_exists($modelClass)) {
                $relatedModel = $modelClass::find($log->model_id);
            }
        }

        return view('superadmin.logs.show', compact('log', 'relatedModel'));
    }
}
