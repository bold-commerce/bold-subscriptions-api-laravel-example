<?php
namespace App\Services;

use App\Handlers\BoldApiRequestHandler;
use App\Handlers\BoldApiResponseHandler;
use function GuzzleHttp\choose_handler;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class BoldApiService {

    const BOLD_API_BASE_URL = 'https://ro.boldapps.net/api/';

    protected $client;
    protected $requestHandler;
    protected $responseHandler;

    public function __construct()
    {
        $this->requestHandler = new BoldApiRequestHandler(env('BOLD_API_PRIVATE_KEY'), env('BOLD_API_HANDLE'), env('MYSHOPIFY_DOMAIN'));
        $this->responseHandler = new BoldApiResponseHandler($this->requestHandler);

        $stack = HandlerStack::create(choose_handler());
        $stack->push(Middleware::mapRequest($this->requestHandler));
        $stack->push(Middleware::mapResponse($this->responseHandler));

        /**
         * Setup basic headers for making API requests
         *
         * See BoldApiRequestHandler for further detail on what happens
         * before each request goes out (e.g. adding the authorization
         * header)
         */
        $this->client = new Client([
            'handler' => $stack,
            'base_uri' => static::BOLD_API_BASE_URL.'third_party/',
            'headers' => [
                'cache-control' => 'no-cache',
                'content-type' => 'application/json',
            ],
        ]);
    }

    /**
     * Update next order date for a customer's subscription
     *
     * @param $shopifyCustomerId
     * @param $subscriptionId
     * @param $nextOrderDate
     * @return bool|mixed
     */
    public function updateNextOrderDate($shopifyCustomerId, $subscriptionId, $nextOrderDate)
    {
        /**
         * Make the request to the API
         * Note: Adding the authorization header and shop query param is handled in BoldApiRequestHandler
         */
        try {
            $res = $this->client->put('manage/subscription/orders/'.$subscriptionId.'/next_ship_date?customer_id='.$shopifyCustomerId, [
                'json' => [
                    'next_shipping_date' => $nextOrderDate,
                ]
            ]);

            $result = json_decode($res->getBody(), true);
        }
        catch (ClientException $e) {
            return ['status' => $e->getResponse()->getStatusCode()];
        }

        return $result;
    }

    /**
     * Get products in a customer's subscription
     *
     * @param $shopifyCustomerId
     * @param $subscriptionId
     * @return bool|mixed
     */
    public function getProducts($shopifyCustomerId, $subscriptionId)
    {
        try {
            $res = $this->client->get('manage/subscription/orders/'.$subscriptionId.'/products?customer_id='.$shopifyCustomerId);
            $result = json_decode($res->getBody(), true);
        }
        catch (ClientException $e) {
            return ['status' => $e->getResponse()->getStatusCode()];
        }

        return $result;
    }

    /**
     * Get initial data (subscriptions) for the given Shopify customer id
     *
     * @param $shopifyCustomerId
     * @return bool|mixed
     */
    public function getInitialData($shopifyCustomerId)
    {
        try {
            $res = $this->client->get('manage/subscription/initial_data?customer_id='.$shopifyCustomerId);
            $result = json_decode($res->getBody(), true);
        }
        catch (ClientException $e) {
            return ['status' => $e->getResponse()->getStatusCode()];
        }

        return $result;
    }
}
