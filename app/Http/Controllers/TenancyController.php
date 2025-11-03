<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class TenancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = User::with('user','unit')->where('role', 'tenant')->get();
        return response()->json($tenants);         
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tenant = new User();
        $tenant->tenant_id = $request->tenant_id;
        $tenant->unit_id = $request->unit_id;
        $tenant->start_date = $request->start_date;
        $tenant->end_date = $request->end_date;
        $tenant->decimal = $request->deposit_amount;
        $tenant->status = $request->status;
        $tenant->save();
        return response()->json($tenant);         
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tenant = User::find($id);
        return response()->json($tenant);         
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tenant = User::find($id);
        $tenant->tenant_id = $request->tenant_id;
        $tenant->unit_id = $request->unit_id;
        $tenant->start_date = $request->start_date;
        $tenant->end_date = $request->end_date;
        $tenant->decimal = $request->deposit_amount;
        $tenant->status = $request->status;
        $tenant->save();
        return response()->json($tenant);         
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::destroy($id);
        return response()->json(['message' => 'Deleted']);        
    }
}
