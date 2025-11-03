<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activitylogs = ActivityLog::with('user')->get();
        return response()->json($activitylogs);        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $activitylog = new ActivityLog();
        $activitylog->user_id = $request->user_id;
        $activitylog->ip_address = $request->ip_address;
        $activitylog->description = $request->description;
        $activitylog->save();
        return response()->json($activitylog);         
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $activitylog = ActivityLog::find($id);
        return response()->json($activitylog);         
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $activitylog = ActivityLog::find($id);
        $activitylog->user_id = $request->user_id;
        $activitylog->ip_address = $request->ip_address;
        $activitylog->description = $request->description;
        $activitylog->save();
        return response()->json($activitylog);        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ActivityLog::destroy($id);
        return response()->json(['message' => 'Deleted']);        
    }
}
