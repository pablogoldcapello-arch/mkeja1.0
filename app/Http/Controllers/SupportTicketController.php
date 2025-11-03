<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportTicket;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supportTickets = SupportTicket::with('tenancy')->get();
        return response()->json($supportTickets);        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $supportTicket = new SupportTicket();
        $supportTicket->user_id = $request->user_id;
        $supportTicket->category = $request->category;
        $supportTicket->priority = $request->priority;
        $supportTicket->description = $request->description;
        $supportTicket->resolution = $request->resolution;
        $supportTicket->assigned_to = $request->assigned_to;
        $supportTicket->status = $request->status;
        $supportTicket->save();
        return response()->json($supportTicket);         
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supportTicket = SupportTicket::find($id);
        return response()->json($supportTicket);        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supportTicket = SupportTicket::find($id);
        $supportTicket->user_id = $request->user_id;
        $supportTicket->category = $request->category;
        $supportTicket->priority = $request->priority;
        $supportTicket->description = $request->description;
        $supportTicket->resolution = $request->resolution;
        $supportTicket->assigned_to = $request->assigned_to;
        $supportTicket->status = $request->status;
        $supportTicket->save();
        return response()->json($supportTicket);         
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        SupportTicket::destroy($id);
        return response()->json(['message' => 'Deleted']);        
    }
}
