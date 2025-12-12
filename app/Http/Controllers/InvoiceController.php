<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\Invoice;

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

    // Get the current date and format it for reference number
        $orgDate = now();
        $newDate = $orgDate->format('YmdHis'); // Using Carbon formatting
        $refno = "INV" . $newDate . " " . $request->tenant_id; // Proper string concatenation
            
        $invoice = new Invoice();
        $invoice->tenant_id = $request->tenant_id;
        $invoice->invoice_number = $refno;
        $invoice->amount_due = $request->amount_due;
        $invoice->rent_month = $request->rent_month;
        $invoice->status = 'unpaid';
        $invoice->save();

        //record actvity log
        $user = auth()->user(); // or JWTAuth::parseToken()->authenticate();
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => $user->name.' created invoice '.$refno. ' for tenant ID '.$request->tenant_id
        ]);

        return response()->json($invoice);         
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
