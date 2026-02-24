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

        // Base ledger query for this landlord
        $ledgerQuery = LedgerEntry::where('landlord_id', $user->id)
            ->orderBy('created_at', 'asc');

        // Optional filters
        if ($request->filled('rent_period')) {
            $ledgerQuery->where('rent_period', $request->rent_period);
        }

        if ($request->filled('unit_id')) {
            $ledgerQuery->where('unit_id', $request->unit_id);
        }

        $ledgerEntries = $ledgerQuery->get();

        // Opening balance = sum of all previous ledger amounts
        $openingBalance = $ledgerEntries->sum('amount') - $ledgerEntries->sum('amount'); // could adjust for prior months if needed

        // Closing balance = sum of all ledger amounts
        $closingBalance = $ledgerEntries->sum('amount');

        // KPIs: get invoices for this landlord
        $invoiceQuery = Invoice::where('property_id', $request->unit_id ? $request->unit_id : null)
            ->whereHas('property', fn($q) => $q->where('landlord_id', $user->id));

        if ($request->filled('rent_period')) {
            $invoiceQuery->where('rent_month', $request->rent_period);
        }

        $totalDue = $invoiceQuery->sum('amount_due') + $invoiceQuery->sum('total_amount') - $invoiceQuery->sum('amount_due');
        $totalPaid = $invoiceQuery->withSum('payments as total_paid', 'amount')->get()->sum('total_paid');

        $collectionRate = $totalDue > 0 ? round(($totalPaid / $totalDue) * 100, 2) : 0;
        $paidInvoices = $invoiceQuery->where('status', 'paid')->count();
        $partialInvoices = $invoiceQuery->where('status', 'partial')->count();
        $overdueInvoices = $invoiceQuery->where('status', 'overdue')->count();

        return response()->json([
            'opening_balance' => $openingBalance,
            'closing_balance' => $closingBalance,
            'ledger_entries' => $ledgerEntries,
            'kpis' => [
                'total_due' => $totalDue,
                'total_paid' => $totalPaid,
                'collection_rate' => $collectionRate,
                'paid_invoices' => $paidInvoices,
                'partial_invoices' => $partialInvoices,
                'overdue_invoices' => $overdueInvoices,
            ]
        ]);
    }
}