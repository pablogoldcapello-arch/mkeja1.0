<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\Tenancy;
use App\Services\InvoiceService;

class GenerateMonthlyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-monthly-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenancies = Tenancy::with('unit')->where('status', 'active')->get();

        foreach ($tenancies as $tenancy) {
            InvoiceService::createRentInvoice($tenancy);
        }

        $this->info('Monthly invoices generated.');
    }



}
