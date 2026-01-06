<?php

namespace App\Http\Controllers;

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
            'phone' => 'required|string|min:9',
            'amount' => 'required|numeric|min:1',
            'account_reference' => 'required|string',
            'statement_id' => 'required|integer'
        ]);

        $phone  = $this->formatPhone($request->phone);
        $amount = $request->amount;

        try {
            $response = $this->mpesa->stkPush([
                'phone' => $phone,
                'amount' => $amount,
                'account_reference' => $request->account_reference,
                'transaction_desc' => $request->description ?? 'Rent payment',
            ]);

            return response()->json([
                'message' => 'STK Push sent successfully',
                'response' => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send STK Push',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
