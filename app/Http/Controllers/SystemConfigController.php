<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemConfig;

class SystemConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $systemconfig = SystemConfig::with('tenancy')->get();
        return response()->json($systemconfig);        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $systemconfig = new SystemConfig();
        $systemconfig->key = $request->key;
        $systemconfig->value = $request->value;
        $systemconfig->save();
        return response()->json($systemconfig);         
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $systemconfig = SystemConfig::find($id);
        return response()->json($systemconfig);        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $systemconfig = SystemConfig::find($id);
        $systemconfig->key = $request->key;
        $systemconfig->value = $request->value;
        $systemconfig->save();
        return response()->json($systemconfig);        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        SystemConfig::destroy($id);
        return response()->json(['message' => 'Deleted']);         
    }
}
