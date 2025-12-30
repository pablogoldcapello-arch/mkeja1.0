<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
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

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' viewed listings '
        ]);

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

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' created listing ID '.$listing->id
        ]);        

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

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' viewed listing ID '.$id
        ]);        

        return response()->json([
            'status' => true,
            'listing' => $listing
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $listing = Listing::find($id);

        if (!$listing) {
            return response()->json([
                'status' => false,
                'message' => 'Listing not found.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'type' => 'required|in:apartment,house,bedsitter,studio,office,land',
            'status' => 'required|in:for_sale,for_rent,sold,occupied',
            'images.*' => 'nullable|image|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update normal fields
        $listing->update($request->except('images'));

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $filename = uniqid() . '_' . $imageFile->getClientOriginalName();
                Storage::disk('public')->putFileAs('listings', $imageFile, $filename);

                $listing->images()->create([
                    'name' => $filename
                ]);
            }
        }

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' updated listing ID '.$id
        ]);        

        return response()->json([
            'status' => true,
            'listing' => $listing->load('images'),
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

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' deleted listing ID '.$id
        ]);        

        return response()->json([
            'status' => true,
            'message' => 'Listing deleted successfully.'
        ]);
    }

    public function deleteImage($listingId, $imageId)
    {
        $listing = Listing::findOrFail($listingId);
        $image = $listing->images()->findOrFail($imageId);

        // Delete the file from storage
        if (\Storage::exists('public/listings/'.$image->name)) {
            \Storage::delete('public/listings/'.$image->name);
        }

        // Delete the DB record
        $image->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully.'
        ]);
    }

    public function single()
    {
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        $listings = Listing::where('user_id', $user->id)->get();

        return response()->json([
            'status' => true,
            'listings' => $listings
        ]);
    }

}
