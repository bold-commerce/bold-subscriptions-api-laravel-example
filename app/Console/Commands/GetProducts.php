<?php

namespace App\Console\Commands;

use App\Services\BoldApiService;
use Illuminate\Console\Command;

class GetProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bold:get_products {shopify_customer_id} {subscription_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the products in a customer\'s subscription';

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

        $products = $boldApiService->getProducts($shopifyCustomerId, $subscriptionId);

        $this->info(json_encode($products['data']));
    }
}
