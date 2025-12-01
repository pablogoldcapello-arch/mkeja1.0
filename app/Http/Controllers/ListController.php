<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Listing;

class ListController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        $landlords = User::latest()->where('role','landlord')->get();
        $properties = Property::latest()->get();
        $listings = Listing::with('images')->latest()->get();

        return response()->json([
            "lists" => [
                'users' => $users,
                'landlords' => $landlords,
                'properties' => $properties,
                'listings' => $listings,
                
            ]
        ]);

    }      
}
