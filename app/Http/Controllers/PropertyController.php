<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;

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
        // Validate the request if needed
        $validated = $request->validate([
            'landlord_id'   => 'required|integer|exists:users,id',
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'type'          => 'nullable|string|max:100',
            'location'      => 'nullable|string|max:255',
            'coordinates'   => 'nullable|string|max:100',
            // 'rent_amount'   => 'nullable|numeric',
            'status'        => 'nullable|string|max:50',
        ]);

        // Assign agent_id as the logged-in user
        $validated['agent_id'] = auth()->id();

        // Create the property
        $property = Property::create($validated);

        return response()->json($property, 201);
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
}
