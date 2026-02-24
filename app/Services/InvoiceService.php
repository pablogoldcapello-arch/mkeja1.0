<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Tenancy;
use Carbon\Carbon;

class InvoiceService
{
    /**
     * Create monthly rent invoice for a tenancy
     */
    public static function createRentInvoice(Tenancy $tenancy, ?string $rentMonth = null): ?Invoice
    {
        $month = $rentMonth ?? now()->format('Y-m');

        // ❌ Prevent duplicate invoice
        $exists = Invoice::where('tenant_id', $tenancy->tenant_id)
            ->where('rent_month', $month)
            ->exists();

        if ($exists) {
            return null;
        }

        // ✅ Get rent from unit
        $unit = $tenancy->unit;

        if (!$unit || !$unit->monthly_rent) {
            throw new \Exception("Missing unit or rent amount for tenancy {$tenancy->id}");
        }

        return Invoice::create([
            'tenant_id'      => $tenancy->tenant_id,
            'property_id'    => $unit->property_id,
            'service_id'     => null, // rent
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'amount_due'     => $unit->monthly_rent,
            'total_amount'     => $unit->monthly_rent,
            'rent_month'     => $month,
            'due_date'       => Carbon::now()->addDays(5),
            'status'         => 'draft',
        ]);
    }

    public static function createProviderInvoice(array $data): Invoice
    {
        // $data = [
        //   'provider_id' => 6,
        //   'services'    => ['plumbing','cleaning'],
        //   'amount_due'  => 5000,
        //   'due_date'    => '2025-12-30',
        // ];

        // Optional: prevent duplicate invoices for same provider + due date
        $exists = Invoice::where('provider_id', $data['provider_id'])
            ->where('due_date', $data['due_date'])
            ->exists();

        if ($exists) {
            throw new \Exception('Invoice already exists for this provider and date');
        }

        return Invoice::create([
            'provider_id'    => $data['provider_id'],
            'services'       => json_encode($data['services']), // store multiple services
            'amount_due'     => $data['amount_due'],
            'total_amount'     => $data['amount_due'],
            'due_date'       => $data['due_date'],
            'type'           => 'service_provider',
            'status'         => 'draft',
            'invoice_number' => Invoice::generateInvoiceNumber(),
        ]);
    }

    public static function autoGenerateInvoices()
    {
        $invoices = [];

        // Generate invoices for all tenants with active tenancies and no invoice for current month
        $tenants = Tenancy::with('tenant')
                    ->where('status', 'active')
                    ->get();

        foreach ($tenants as $tenancy) {
            try {
                $invoice = self::createRentInvoice($tenancy, now()->format('Y-m'));
                if ($invoice) {
                    $invoices[] = $invoice;
                }
            } catch (\Exception $e) {
                // skip duplicates or errors
                continue;
            }
        }

        // Optionally: do same for service providers if needed

        return $invoices;
    }


}
