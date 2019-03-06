<?php

namespace Tests\Unit;

use App\Services\BoldApiService;
use Tests\TestCase;

/**
 * Class BoldApiServiceTest
 * @package Tests\Unit
 */
class BoldApiServiceTest extends TestCase
{
    private const HTTP_STATUS_NOT_FOUND = 404;

    /** @var BoldApiService $boldApiService */
    private $boldApiService;

    public function setUp(): void
    {
        parent::setUp();

        if (!$this->boldApiService instanceof BoldApiService) {
            $this->boldApiService = app(BoldApiService::class);
        }
    }

    public function testGetProductsEndpointExists()
    {
        $products = $this->boldApiService->getProducts(1, 1);

        $this->assertArrayHasKey('status', $products);
        $this->assertNotEquals($products['status'], static::HTTP_STATUS_NOT_FOUND);
    }

    public function testUpdateNextOrderDateEndpointExists()
    {
        $response = $this->boldApiService->updateNextOrderDate(1,1,'2012-02-22');

        $this->assertArrayHasKey('status', $response);
        $this->assertNotEquals($response['status'], static::HTTP_STATUS_NOT_FOUND);
    }
}
