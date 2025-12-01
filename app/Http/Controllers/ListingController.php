<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listings = Listing::all();

        return response()->json([
            "lists" => [
                'status' => true,
                'listings' => $listings
            ]
        ]);        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'type' => 'required|in:apartment,house,bedsitter,studio,office,land',
            'status' => 'required|in:for_sale,for_rent,sold,occupied',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'neighborhood' => 'nullable|string',
            'coordinates' => 'nullable|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'living_rooms' => 'nullable|integer',
            'kitchens' => 'nullable|integer',
            'balcony' => 'nullable|boolean',
            'floor_level' => 'nullable|integer',
            'total_area' => 'nullable|numeric',
            'furnished' => 'nullable|boolean',
            'price' => 'nullable|numeric',
            'currency' => 'nullable|string|max:10',
            'deposit' => 'nullable|numeric',
            'payment_terms' => 'nullable|string',
            'parking' => 'nullable|boolean',
            'parking_spaces' => 'nullable|integer',
            'security' => 'nullable|boolean',
            'water_supply' => 'nullable|boolean',
            'electricity' => 'nullable|boolean',
            'internet' => 'nullable|boolean',
            'swimming_pool' => 'nullable|boolean',
            'gym' => 'nullable|boolean',
            'garden' => 'nullable|boolean',
            'elevator' => 'nullable|boolean',
            'main_image' => 'nullable|string',
            'video_tour' => 'nullable|string',
            'floor_plan' => 'nullable|string',
            // 'user_id' => 'required|exists:users,id',
            'images.*' => 'nullable|image|max:5120',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'year_built' => 'nullable|digits:4',
            'renovated' => 'nullable|boolean',
            'special_features' => 'nullable|string'
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

        // Create listing
        $listing = Listing::create($data);

        // Handle multiple images
if ($request->hasFile('images')) {
    foreach ($request->file('images') as $imageFile) {
        $filename = uniqid() . '_' . $imageFile->getClientOriginalName();
        Storage::disk('public')->putFileAs('listings', $imageFile, $filename);

        $listing->images()->create([
            'name' => $filename
        ]);
    }
}

        return response()->json([
            'status' => true,
            'listing' => $listing,
            'images' => $listing->load('images'), // include images in response
            'message' => 'Listing created successfully.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $listing = Listing::find($id);

        if (!$listing) {
            return response()->json([
                'status' => false,
                'message' => 'Listing not found.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'listing' => $listing
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $listing = Listing::find($id);

        if (!$listing) {
            return response()->json([
                'status' => false,
                'message' => 'Listing not found.'
            ], 404);
        }

        $listing->update($request->all());

        return response()->json([
            'status' => true,
            'listing' => $listing,
            'message' => 'Listing updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $listing = Listing::find($id);

        if (!$listing) {
            return response()->json([
                'status' => false,
                'message' => 'Listing not found.'
            ], 404);
        }

        $listing->delete();

        return response()->json([
            'status' => true,
            'message' => 'Listing deleted successfully.'
        ]);
    }
}
