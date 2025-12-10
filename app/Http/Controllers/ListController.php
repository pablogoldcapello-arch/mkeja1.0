<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Listing;
use App\Models\Invoice;
use Carbon\Carbon;

class ListController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        $landlords = User::latest()->where('role','landlord')->get();
        $caretakers = User::latest()->where('role','caretaker')->get();
        $serviceproviders = User::latest()->where('role','service_provider')->get();
        $tenants = User::latest()->where('role','tenant')->get();
        $properties = Property::with('landlord')->latest()->get();
        $listings = Listing::with('images')->latest()->get();
        $awaitinginvoicing = Invoice::latest()
        ->with(['tenant'])
        ->where('status', 'unpaid')
        ->get();

        return response()->json([
            "lists" => [
                'users' => $users,
                'landlords' => $landlords,
                'caretakers' => $caretakers,
                'serviceproviders' => $serviceproviders,
                'tenants' => $tenants,
                'properties' => $properties,
                'awaitinginvoicing' => $awaitinginvoicing,
                'listings' => $listings,
                
            ]
        ]);

    }      
}
