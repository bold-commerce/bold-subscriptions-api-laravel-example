<?php
namespace App\Handlers;
use Psr\Http\Message\ResponseInterface;

class BoldApiResponseHandler {
    /**
     * @var BoldApiRequestHandler $requestHandler
     */
    protected $requestHandler;

    /**
     * BoldApiResponseHandler constructor.
     * @param BoldApiRequestHandler $requestHandler
     */
    public function __construct($requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    /**
     * TODO: If a request fails due to an expired token, retry the request after authenticating again
     *
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ResponseInterface $response)
    {
        if ($response->getStatusCode() === 401) {
            $this->requestHandler->authenticate();
        }

        return $response;
    }
}
