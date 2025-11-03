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
        $unit = new Unit();
        $unit->property_id = $request->property_id;
        $unit->unit_number = $request->unit_number;
        $unit->rent = $request->rent;
        $unit->status = $request->status;
        $unit->save();
        return response()->json($unit);          
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
    public function update(Request $request, string $id)
    {
        $unit = Unit::find($id);
        $unit->property_id = $request->property_id;
        $unit->unit_number = $request->unit_number;
        $unit->rent = $request->rent;
        $unit->status = $request->status;
        $unit->save();
        return response()->json($unit);          
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
