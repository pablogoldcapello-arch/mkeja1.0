<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
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
        $request->validate([
            'tenant_id' => 'required|exists:users,id',
            'rent_month' => 'nullable|string'
        ]);

        // ✅ Normalize rent_month
        $rentMonth = Carbon::parse('01 ' . $request->rent_month)
        ->format('Y-m');

        $tenancy = Tenancy::with('unit')
            ->where('tenant_id', $request->tenant_id)
            ->where('status', 'active')
            ->firstOrFail();

        $invoice = InvoiceService::createRentInvoice(
            $tenancy,
            $rentMonth
        );

        if (!$invoice) {
            return response()->json([
                'message' => 'Invoice already exists for this tenant and month'
            ], 409);
        }

        // Activity log
        ActivityLog::create([
            'user_id' => auth()->id(),
            'description' => auth()->user()->name .
                ' created invoice ' . $invoice->invoice_number .
                ' for tenant ID ' . $tenancy->tenant_id
        ]);

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
        $invoice->tenant_id = $request->tenant_id;
        $invoice->invoice_number = $request->invoice_number;
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
}
