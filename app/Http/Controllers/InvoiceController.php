<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Tenancy;
use App\Services\InvoiceService;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with('tenancy')->get();
        return response()->json($invoices);         
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->type === 'tenant') {
            $request->validate([
                'tenant_id' => 'required|exists:users,id',
                'rent_month' => 'nullable|string',
            ]);

            try {
                $rentMonth = Carbon::parse('01 ' . $request->rent_month)->format('Y-m');
            } catch (\Exception $e) {
                return response()->json(['message' => 'Invalid rent month'], 422);
            }

            $tenancy = Tenancy::with('unit')
                ->where('tenant_id', $request->tenant_id)
                ->where('status', 'active')
                ->firstOrFail();

            $invoice = InvoiceService::createRentInvoice($tenancy, $rentMonth);

            if (!$invoice) {
                return response()->json(['message' => 'Invoice already exists for this tenant and month'], 409);
            }
        } elseif ($request->type === 'service_provider') {
            $request->validate([
                'provider_id' => 'required|exists:users,id',
                'services'    => 'required|array|min:1',
                'amount_due'  => 'required|numeric|min:0',
                'due_date'    => 'required|date',
            ]);

            $invoice = InvoiceService::createProviderInvoice([
                'provider_id' => $request->provider_id,
                'services'    => $request->services,
                'amount_due'  => $request->amount_due,
                'due_date'    => $request->due_date,
            ]);
        } else {
            return response()->json(['message' => 'Invalid invoice type'], 422);
        }

        if ($invoice) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'description' => auth()->user()->name . ' created invoice ' . $invoice->invoice_number
            ]);
        }

        return response()->json($invoice, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = Invoice::find($id);
        return response()->json($invoice);         
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $invoice = Invoice::find($id);
        $invoice->amount_due = $request->amount_due;
        $invoice->due_date = $request->due_date;
        $invoice->status = $request->status;
        $invoice->save();

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' updated invoice '.$invoice->invoice_number. ' for tenant ID '.$request->tenant_id
        ]);

        return response()->json($invoice);        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Invoice::destroy($id);

            //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' deleted invoice '
        ]);

        return response()->json(['message' => 'Deleted']);          
    }

    public function autoGenerate(Request $request)
    {
        try {
            // Call a service or logic to generate invoices for all pending tenants/providers
            $invoices = InvoiceService::autoGenerateInvoices();

            return response()->json([
                'message' => 'Invoices generated successfully',
                'data' => $invoices
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }


}
