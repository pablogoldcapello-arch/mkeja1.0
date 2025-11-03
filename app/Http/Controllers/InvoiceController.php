<?php

namespace App\Http\Controllers;

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
        $invoice = new Invoice();
        $invoice->tenancy_id = $request->tenancy_id;
        $invoice->invoice_number = $request->invoice_number;
        $invoice->amount_due = $request->amount_due;
        $invoice->due_date = $request->due_date;
        $invoice->status = $request->status;
        $invoice->save();
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
        $invoice->tenancy_id = $request->tenancy_id;
        $invoice->invoice_number = $request->invoice_number;
        $invoice->amount_due = $request->amount_due;
        $invoice->due_date = $request->due_date;
        $invoice->status = $request->status;
        $invoice->save();
        return response()->json($invoice);        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Invoice::destroy($id);
        return response()->json(['message' => 'Deleted']);          
    }
}
