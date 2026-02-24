<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\LedgerEntry;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with('tenant')->get();
        return response()->json($payments);         
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $payment = new Payment();
        $payment->tenancy_id = $request->tenancy_id;
        $payment->amount = $request->amount;
        $payment->payment_method = $request->payment_method;
        $payment->transaction_code = $request->transaction_code;
        $payment->payment_date = $request->payment_date;
        $payment->status = $request->status;
        $payment->save();
        return response()->json($payment);         
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::find($id);
        return response()->json($payment);         
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::find($id);
        $payment->tenancy_id = $request->tenancy_id;
        $payment->amount = $request->amount;
        $payment->payment_method = $request->payment_method;
        $payment->transaction_code = $request->transaction_code;
        $payment->payment_date = $request->payment_date;
        $payment->status = $request->status;
        $payment->save();
        return response()->json($payment);         
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Payment::destroy($id);
        return response()->json(['message' => 'Deleted']);         
    }

public function initiate(Request $request)
{
    $request->validate([
        'invoice_id' => 'required|exists:invoices,id',
        'amount' => 'required|numeric|min:1'
    ]);

    DB::beginTransaction();

    try {
        // 1️⃣ Fetch invoice
        $invoice = Invoice::findOrFail($request->invoice_id);

        // 2️⃣ Determine tenant & property from invoice
        $tenantId   = $invoice->tenant_id;
        $propertyId = $invoice->property_id;
        $unitId     = $invoice->unit_id ?? null; // optional

        // 3️⃣ Create payment
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'tenant_id'  => $tenantId,
            'amount'     => $request->amount,
            'payment_method' => 'mpesa',
            'status'        => 'pending',
        ]);

        // 4️⃣ Derive landlord from property
        $landlordId = $propertyId ? Property::findOrFail($propertyId)->landlord_id : null;

        // 5️⃣ Create ledger entry
        LedgerEntry::create([
            'landlord_id'  => $landlordId,
            'property_id'  => $propertyId,
            'unit_id'      => $unitId,
            'tenant_id'    => $tenantId,
            'entry_type'   => 'payment',
            'amount'       => -$request->amount, // reduces balance
            'rent_period'  => $invoice->rent_month,
            'reference_type' => 'payment',
            'reference_id'   => $payment->id,
            'created_by'     => auth()->id() ?? null,
        ]);

        // 6️⃣ Update invoice: reduce amount_due and set status
        $invoice->amount_due = max($invoice->amount_due - $request->amount, 0);

        if ($invoice->amount_due <= 0) {
            $invoice->status = 'paid';
        } elseif ($invoice->amount_due < $invoice->total_amount) {
            $invoice->status = 'partial';
        } else {
            $invoice->status = 'draft';
        }

        $invoice->save();

        DB::commit();

        return response()->json([
            'payment_id' => $payment->id,
            'message' => 'Payment initiated successfully'
        ]);

    } catch (\Throwable $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Payment initiation failed',
            'error' => $e->getMessage()
        ], 500);
    }
}    
}
