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
            'rent_month'     => $month,
            'due_date'       => Carbon::now()->addDays(5),
            'status'         => 'draft',
        ]);
    }
}
