<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\MpesaService;

class MpesaController extends Controller
{
    protected MpesaService $mpesa;

    public function __construct(MpesaService $mpesa)
    {
        $this->mpesa = $mpesa;
    }

    private function formatPhone($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (substr($phone, 0, 2) === '07') {
            return '254' . substr($phone, 1);
        }

        if (substr($phone, 0, 1) === '7') {
            return '254' . $phone;
        }

        return $phone;
    }

    public function stkPush(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'phone' => 'required|string|min:9',
            'account_reference' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $payment = Payment::findOrFail($request->payment_id);
        $phone = $this->formatPhone($request->phone);

        try {
            $response = $this->mpesa->stkPush([
                'phone' => $phone,
                'amount' => $payment->amount, // ✅ locked
                'account_reference' => $request->account_reference,
                'transaction_desc' => $request->description ?? 'Rent payment',
            ]);

            $payment->update([
                'checkout_request_id' => $response['CheckoutRequestID'] ?? null
            ]);

            return response()->json(['message' => 'STK Push sent']);

        } catch (\Exception $e) {
            $payment->update(['status' => 'failed']); // ✅ goes here

            return response()->json([
                'message' => 'Failed to send STK Push'
            ], 500);
        }
    }
}
