<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Tenancy;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = Property::with([
            'landlord',
            'agent',
            'units',
            'caretakers'
        ])->get();


        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' viewed properties'
        ]);

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

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' created property ID '.$property->id
        ]);        

        return response()->json($property->load('units'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $property = Property::find($id);

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' viewed property ID '.$id
        ]);

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
        $property->status = $request->status;
        $property->save();

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' updated property ID '.$id
        ]);

        return response()->json($property);        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Property::destroy($id);

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' deleted property ID '.$id
        ]);

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

    public function landlordProperties($id)
    {
        $landlordproperties = Property::where('landlord_id', $id)->with('landlord')->get();

        return response()->json([
            'success' => true,
            'landlordproperties' => $landlordproperties
        ]);
    } 
    
    // PropertyController.php
    public function tenants($propertyId)
    {
        // Optional: load property info if needed
        $property = Property::find($propertyId);
        $properties = Property::latest()->get();

        // Get tenants directly from the tenancies table
        $tenants = Tenancy::where('property_id', $propertyId)
    ->with(['tenant', 'unit'])
    ->get();

        return response()->json([
            'property' => $property, // optional, remove if you don't need property info
            'tenants' => $tenants,
            'properties' => $properties
        ]);
    }



}
