<?php

namespace App\Console\Commands;

use App\Services\BoldApiService;
use Illuminate\Console\Command;

class UpdateNextOrderDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bold:update_next_order_date {shopify_customer_id} {subscription_id} {next_order_date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the next order date for a customer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var BoldApiService $boldApiService */
        $boldApiService = app(BoldApiService::class);

        $shopifyCustomerId = $this->argument('shopify_customer_id');
        $subscriptionId = $this->argument('subscription_id');
        $nextOrderDate = $this->argument('next_order_date');

        $boldApiService->updateNextOrderDate($shopifyCustomerId, $subscriptionId, $nextOrderDate);

        $this->info('Api request completed successfully');
    }
}
