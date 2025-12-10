<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::with('property')->get();
        return response()->json($units);        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id'        => 'required|integer|exists:properties,id',
            'unit_number'        => 'required|string|max:255',
            'type'               => 'nullable|string|max:255',
            'deposit'            => 'nullable|numeric',
            'monthly_rent'       => 'nullable|numeric',
            'status'             => 'nullable|string|max:50',
        ]);

        // Create using mass assignment
        $unit = Unit::create($validated);

        return response()->json([
            'message' => 'Unit created successfully',
            'unit'    => $unit
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unit = Unit::find($id);
        return response()->json($unit);         
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the unit
        $unit = Unit::findOrFail($id);

        // Validate input
        $validated = $request->validate([
            'property_id'        => 'sometimes|exists:properties,id',
            'unit_number'        => 'sometimes|string|max:255',
            'type'               => 'sometimes|string|max:255',
            'deposit'            => 'sometimes|numeric|min:0',
            'monthly_rent'       => 'sometimes|numeric|min:0',
            'status'             => 'sometimes|string|in:vacant,occupied,maintenance'
        ]);

        // Update the unit
        $unit->update($validated);

        // Return updated unit
        return response()->json([
            'message' => 'Unit updated successfully',
            'unit' => $unit
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Unit::destroy($id);
        return response()->json(['message' => 'Deleted']);         
    }
}
