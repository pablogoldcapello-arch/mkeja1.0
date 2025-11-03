<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

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
}
