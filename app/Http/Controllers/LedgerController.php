<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LedgerEntry;
use App\Models\Invoice;

class LedgerController extends Controller
{
    /**
     * Display landlord ledger entries along with KPIs
     */
    public function index(Request $request)
    {
        $user = $request->user();

        /** --------------------
         * Ledger
         * -------------------*/
        $ledgerEntries = LedgerEntry::where('landlord_id', $user->id)
            ->when($request->filled('rent_period'), fn ($q) =>
                $q->where('rent_period', $request->rent_period)
            )
            ->when($request->filled('unit_id'), fn ($q) =>
                $q->where('unit_id', $request->unit_id)
            )
            ->orderBy('created_at')
            ->get();

        $openingBalance = 0; // unless you implement carry-forward logic
        $closingBalance = $ledgerEntries->sum('amount');

        /** --------------------
         * Invoices (CORRECT)
         * -------------------*/
        $baseInvoiceQuery = Invoice::whereHas('property', fn ($q) =>
            $q->where('landlord_id', $user->id)
        );

        if ($request->filled('rent_period')) {
            $baseInvoiceQuery->where('rent_month', $request->rent_period);
        }

        if ($request->filled('unit_id')) {
            $baseInvoiceQuery->where('property_id', $request->unit_id);
        }

        /** --------------------
         * KPIs
         * -------------------*/
        $totalDue = (clone $baseInvoiceQuery)->sum('amount_due');

        $totalPaid = \DB::table('payments')
            ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->join('properties', 'invoices.property_id', '=', 'properties.id')
            ->where('properties.landlord_id', $user->id)
            ->where('payments.status', 'successful')
            ->sum('payments.amount');

        $pendingPayments = \DB::table('payments')
        ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
        ->join('properties', 'invoices.property_id', '=', 'properties.id')
        ->where('properties.landlord_id', $user->id)
        ->where('payments.status', 'pending')
        ->sum('payments.amount');    

        $collectionRate = $totalDue > 0
            ? round(($totalPaid / ($totalDue + $totalPaid)) * 100, 2)
            : 0;

        $draftInvoices = (clone $baseInvoiceQuery)->where('status', 'draft')->count();
        $paidInvoices = (clone $baseInvoiceQuery)->where('status', 'paid')->count();
        $partialInvoices = (clone $baseInvoiceQuery)->where('status', 'partial')->count();
        $overdueInvoices = (clone $baseInvoiceQuery)->where('status', 'overdue')->count();

        return response()->json([
            'opening_balance' => $openingBalance,
            'closing_balance' => $closingBalance,
            'ledger_entries' => $ledgerEntries,
            'kpis' => [
                'total_due' => $totalDue,
                'total_paid' => $totalPaid,
                'pending_payments' => $pendingPayments,
                'collection_rate' => $collectionRate,
                'draft_invoices' => $draftInvoices,
                'paid_invoices' => $paidInvoices,
                'partial_invoices' => $partialInvoices,
                'overdue_invoices' => $overdueInvoices,
            ]
        ]);
    }

    public function landlordLedger($id) {
        $entries = LedgerEntry::with(['property', 'unit', 'tenant'])
            ->whereHas('property', fn($q) => $q->where('landlord_id', $id))
            ->get();

        return response()->json(['ledger_entries' => $entries]);
    }
}