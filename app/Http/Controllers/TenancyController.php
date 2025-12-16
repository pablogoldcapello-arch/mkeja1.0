<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenancy;

class TenancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenancy::with('user','unit')->where('role', 'tenant')->get();
        return response()->json($tenants);         
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tenant = new Tenancy();
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
        $tenant = Tenancy::find($id);
        return response()->json($tenant);         
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tenant = Tenancy::find($id);
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
        Tenancy::destroy($id);
        return response()->json(['message' => 'Deleted']);        
    }

    public function assignTenant(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:users,id',
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'nullable|exists:units,id',
            'start_date' => 'required|date',
        ]);

        // Optional: Check if unit is already assigned
        if ($request->unit_id) {
            $existing = Tenancy::where('unit_id', $request->unit_id)
                            ->where('status', 'active')->first();
            if ($existing) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unit is already assigned to another tenant.'
                ], 400);
            }
        }

        $tenancy = Tenancy::create([
            'tenant_id' => $request->tenant_id,
            'property_id' => $request->property_id,
            'unit_id' => $request->unit_id,
            'start_date' => $request->start_date,
            'status' => 'active',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant assigned successfully',
            'data' => $tenancy
        ]);
    }

}
