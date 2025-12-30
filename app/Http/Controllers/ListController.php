<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Listing;
use App\Models\Invoice;
use App\Models\Service;
use Carbon\Carbon;

class ListController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        $services = Service::latest()->get();
        $landlords = User::latest()->where('role','landlord')->get();
        $caretakers = User::latest()->where('role','caretaker')->get();
        $serviceproviders = User::latest()->where('role','service_provider')->get();
        $tenants = User::latest()->where('role','tenant')->get();
        $properties = Property::with('landlord')->latest()->get();
        $activitylogs = ActivityLog::with('user')->latest()->get();
        $listings = Listing::with('images')->latest()->get();
        $awaitinginvoicing = Invoice::latest()
        ->with(['tenant','provider','property'])
        ->where('status', 'draft')
        ->get();
        $pendingtickets = SupportTicket::with(['images','user'])
        ->where('status', 'open')
        ->orWhere('status', 'in progress')
        ->latest()->get();
        $closedtickets = SupportTicket::with(['images','user'])
        ->where('status', 'resolved')
        ->orWhere('status', 'closed')
        ->latest()->get();        


        return response()->json([
            "lists" => [
                'users' => $users,
                'services' => $services,
                'landlords' => $landlords,
                'caretakers' => $caretakers,
                'serviceproviders' => $serviceproviders,
                'tenants' => $tenants,
                'properties' => $properties,
                'awaitinginvoicing' => $awaitinginvoicing,
                'listings' => $listings,
                'pendingtickets' => $pendingtickets,
                'closedtickets' => $closedtickets,
                'activitylogs' => $activitylogs
                
            ]
        ]);

    }  
    
    public function listings()
    {
        $listings = Listing::with('images')->latest()->get();

        // Return as JSON
        return response()->json([
            'listings' => $listings,
        ]);
    }

    public function users()
    {
        $users = User::latest()->get();

        // Return as JSON
        return response()->json([
            'users' => $users,
        ]);
    }
    
    public function landlords()
    {
        $landlords = User::latest()->where('role','landlord')->get();

        // Return as JSON
        return response()->json([
            'landlords' => $landlords,
        ]);
    }
    
    public function caretakers()
    {
        $caretakers = User::latest()->where('role','caretaker')->get();

        // Return as JSON
        return response()->json([
            'caretakers' => $caretakers,
        ]);
    } 
    
    public function serviceProviders()
    {
        $serviceproviders = User::latest()->where('role','service_provider')->get();

        // Return as JSON
        return response()->json([
            'serviceproviders' => $serviceproviders,
        ]);
    } 
    
    public function tenants()
    {
        $tenants = User::latest()->where('role','tenant')->get();
        $properties = Property::with('landlord')->latest()->get();

        // Return as JSON
        return response()->json([
            'tenants' => $tenants,
            'properties' => $properties,
        ]);
    }
    
    public function properties()
    {
        $landlords = User::latest()->where('role','landlord')->get();
        $properties = Property::with('landlord')->latest()->get();

        // Return as JSON
        return response()->json([
            'landlords' => $landlords,
            'properties' => $properties,
        ]);
    }
    
    public function activityLogs()
    {
        $activitylogs = ActivityLog::with('user')->orderByDesc('id')->get();

        // Return as JSON
        return response()->json([
            'activitylogs' => $activitylogs,
        ]);
    } 
    
    public function dashboard()
    {
        $activitylogs = ActivityLog::with('user')->orderByDesc('id')->get();

        // Return as JSON
        return response()->json([
            'activitylogs' => $activitylogs,
        ]);
    }     
}
