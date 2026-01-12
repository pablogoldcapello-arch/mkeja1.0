<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Unit;
use App\Models\Property;
use App\Models\SupportTicket;
use App\Models\Lease;

class DashboardController extends Controller
{
    public function stats()
    {
        $user = Auth::user();

        switch ($user->role) {

            /* =========================
               ADMIN DASHBOARD
            ========================= */
            case 'admin':
                return response()->json([
                    'stats' => [
                        'users'      => User::count(),
                        'landlords'  => User::where('role', 'landlord')->count(),
                        'tenants'    => User::where('role', 'tenant')->count(),
                        'properties' => Property::count(),
                        'rented'     => Lease::where('status', 'active')->count(),
                        'vacant'     => Unit::whereDoesntHave('leases', function ($q) {
                                            $q->where('status', 'active');
                                        })->count(),
                        'tickets'    => SupportTicket::count(),
                        'tickets_open' => SupportTicket::where('status', 'open')->count(),
                        'tickets_in_progress' => SupportTicket::where('status', 'in progress')->count(),
                        'tickets_resolved' => SupportTicket::where('status', 'resolved')->count(),
                    ]
                ]);

            /* =========================
               LANDLORD DASHBOARD
            ========================= */
            case 'landlord':

                $propertyIds = Property::where('landlord_id', $user->id)->pluck('id');

                // Tenant IDs from active leases in landlord's properties
                $tenantIds = Lease::whereIn('property_id', $propertyIds)
                                  ->where('status', 'active')
                                  ->pluck('tenant_id');

                $tickets = SupportTicket::whereIn('user_id', $tenantIds);

                return response()->json([
                    'stats' => [
                        'properties' => $propertyIds->count(),
                        'rented'     => Lease::whereIn('property_id', $propertyIds)
                                              ->where('status', 'active')
                                              ->count(),
                        'vacant'     => Unit::whereIn('property_id', $propertyIds)
                                            ->whereDoesntHave('leases', function ($q) {
                                                $q->where('status', 'active');
                                            })
                                            ->count(),
                        'tenants'    => $tenantIds->unique()->count(),
                        'tickets'    => $tickets->count(),
                        'tickets_open' => (clone $tickets)->where('status', 'open')->count(),
                        'tickets_in_progress' => (clone $tickets)->where('status', 'in progress')->count(),
                        'tickets_resolved' => (clone $tickets)->where('status', 'resolved')->count(),
                    ]
                ]);

            /* =========================
               CARETAKER DASHBOARD
            ========================= */
            case 'caretaker':

                $units = Unit::where('caretaker_id', $user->id)->pluck('id');

                $tickets = SupportTicket::whereIn('user_id', function($q) use ($units) {
                    $q->select('tenant_id')
                      ->from('leases')
                      ->whereIn('unit_id', $units)
                      ->where('status', 'active');
                });

                return response()->json([
                    'stats' => [
                        'units'   => $units->count(),
                        'rented'  => Unit::where('caretaker_id', $user->id)
                                         ->whereHas('leases', fn($q) => $q->where('status', 'active'))
                                         ->count(),
                        'vacant'  => Unit::where('caretaker_id', $user->id)
                                         ->whereDoesntHave('leases', fn($q) => $q->where('status', 'active'))
                                         ->count(),
                        'issues'  => $tickets->count(),
                        'tickets_open' => (clone $tickets)->where('status','open')->count(),
                        'tickets_in_progress' => (clone $tickets)->where('status','in progress')->count(),
                        'tickets_resolved' => (clone $tickets)->where('status','resolved')->count(),
                    ]
                ]);

            /* =========================
               SERVICE PROVIDER DASHBOARD
            ========================= */
            case 'service_provider':

                $tickets = SupportTicket::where('assigned_to', $user->id);

                return response()->json([
                    'stats' => [
                        'jobs'      => $tickets->count(),
                        'completed' => (clone $tickets)->where('status','resolved')->count(),
                        'pending'   => (clone $tickets)->where('status','open')->count(),
                        'in_progress' => (clone $tickets)->where('status','in progress')->count(),
                    ]
                ]);

            /* =========================
               TENANT DASHBOARD
            ========================= */
            case 'tenant':

                $activeLease = Lease::where('tenant_id', $user->id)
                                    ->where('status', 'active')
                                    ->first();

                $tickets = SupportTicket::where('user_id', $user->id);

                return response()->json([
                    'stats' => [
                        'unit'        => optional($activeLease?->unit)->unit_number ?? 'N/A',
                        'rent_status' => $activeLease ? 'rented' : 'vacant',
                        'requests'    => $tickets->where('status','open')->count(),
                        'tickets_in_progress' => $tickets->where('status','in progress')->count(),
                        'tickets_resolved' => $tickets->where('status','resolved')->count(),
                        'lease_start' => optional($activeLease?->start_date),
                        'lease_end'   => optional($activeLease?->end_date),
                    ]
                ]);

            /* =========================
               DEFAULT
            ========================= */
            default:
                return response()->json(['stats' => []]);
        }
    }
}
