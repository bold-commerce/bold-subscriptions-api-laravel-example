<?php

namespace App\Console\Commands;

use App\Services\BoldApiService;
use Illuminate\Console\Command;

class GetInitialData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bold:get_initial_data {shopify_customer_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get a payload of all the most immediately useful information for a subscription. Generally used for building a customer portal.';

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

        $products = $boldApiService->getInitialData($shopifyCustomerId);

        $this->info(json_encode($products['data']));
    }
}
