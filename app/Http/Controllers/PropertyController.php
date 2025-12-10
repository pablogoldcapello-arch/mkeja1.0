<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Unit;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = Property::with('product', 'client')->get();
        return response()->json($properties);        
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // Validate property data
    $validated = $request->validate([
        'landlord_id'   => 'required|integer|exists:users,id',
        'title'         => 'required|string',
        'description'   => 'nullable|string',
        'type'          => 'nullable|string|max:100',
        'location'      => 'nullable|string|max:255',
        'coordinates'   => 'nullable|string|max:100',
        'status'        => 'nullable|string|max:50',
        'units_no'      => 'required|integer|min:1', // ✅ fixed
    ]);

    // Assign agent_id
    $validated['agent_id'] = auth()->id();

    // Create property
    $property = Property::create($validated);

    // Auto-create units
    for ($i = 1; $i <= $validated['units_no']; $i++) {
        $property->units()->create([
            'unit_number'        => "Unit-" . $i,
            'deposit'            => null,
            'monthly_rent'       => null,
            'garbage_fee'        => null,
            'security_fee'       => null,
            'water_meter'        => null,
            'water_deposit'      => null,
            'electricity_meter'  => null,
            'electricity_deposit'=> null,
            'paybill_number'     => null,
            'account_number'     => null,
            'status'             => 'vacant',
        ]);
    }

    return response()->json($property->load('units'), 201);
}




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $property = Property::find($id);
        return response()->json($property);        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $property = Property::find($id);
        $property->landlord_id = $request->landlord_id;
        $property->agent_id = $request->agent_id;
        $property->title = $request->title;
        $property->description = $request->description;
        $property->type = $request->type;
        $property->location = $request->location;
        $property->coordinates = $request->coordinates;
        $property->rent_amount = $request->rent_amount;
        $property->status = $request->status;
        $property->save();
        return response()->json($property);        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Property::destroy($id);
        return response()->json(['message' => 'Deleted']);        
    }

    public function units($id)
    {
        $units = Unit::where('property_id', $id)->get();

        return response()->json([
            'success' => true,
            'units' => $units
        ]);
    }


}
