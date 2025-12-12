<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supportTickets = SupportTicket::with('user')->get();

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' viewed tickets'
        ]); 

        return response()->json($supportTickets);        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Validation rules
        $validator = Validator::make($request->all(), [
            'category' => 'required|string',
            'description' => 'nullable|string',
            'priority' => 'required|in:high,medium,low',
            // 'user_id' => 'required|exists:users,id',
            'images.*' => 'nullable|image|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Get authenticated user
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        
        // Merge user_id into request data
        $data = array_merge($request->all(), ['user_id' => $user->id]);

        // Create ticket
        $ticket = SupportTicket::create($data);

        // Handle multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $filename = uniqid() . '_' . $imageFile->getClientOriginalName();
                Storage::disk('public')->putFileAs('tickets', $imageFile, $filename);

                $ticket->images()->create([
                    'name' => $filename
                ]);
            }
        }

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' created ticket ID '.$ticket->id
        ]);         

        return response()->json([
            'status' => true,
            'ticket' => $ticket,
            'images' => $ticket->load('images'), // include images in response
            'message' => 'Ticket created successfully.'
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supportTicket = SupportTicket::find($id);

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' viewed ticket ID '.$id
        ]); 

        return response()->json($supportTicket);        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $supportTicket = SupportTicket::find($id);

        if (!$supportTicket) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket not found.'
            ], 404);
        }

        // Validation rules
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'priority' => 'required|in:high,medium,low',
            // 'user_id' => 'required|exists:users,id',
            'images.*' => 'nullable|image|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update normal fields
        $supportTicket->update($request->except('images'));

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $filename = uniqid() . '_' . $imageFile->getClientOriginalName();
                Storage::disk('public')->putFileAs('tickets', $imageFile, $filename);

                $supportTicket->images()->create([
                    'name' => $filename
                ]);
            }
        }

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' updated ticket ID '.$id
        ]);         

        return response()->json([
            'status' => true,
            'ticket' => $supportTicket->load('images'),
            'message' => 'Ticket updated successfully.'
        ]);        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        SupportTicket::destroy($id);

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' deleted ticket ID '.$id
        ]); 

        return response()->json(['message' => 'Deleted']);        
    }

    public function deleteTicketImage($ticketId, $imageId)
    {
        $supportTicket = SupportTicket::findOrFail($ticketId);
        $image = $supportTicket->images()->findOrFail($imageId);

        // Delete the file from storage
        if (\Storage::exists('public/images/'.$image->name)) {
            \Storage::delete('public/images/'.$image->name);
        }

        // Delete the DB record
        $image->delete();

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' deleted an image for ticket ID '.$ticketId
        ]);         

        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully.'
        ]);
    }    
}
