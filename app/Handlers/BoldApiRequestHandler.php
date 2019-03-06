<?php
namespace App\Handlers;
use App\Services\BoldApiService;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Client;

class BoldApiRequestHandler {
    /**
     * Auth token gained from authenticating with third party api
     *
     * @var string $authToken
     */
    protected $authToken;

    /**
     * Used to authenticate with third party API and retrieve a temporary auth token
     *
     * @var string $privateKey
     * @var string $appHandle
     * @var string $myshopifyDomain
     */
    protected $privateKey;
    protected $appHandle;
    protected $myshopifyDomain;

    public function __construct($privateKey, $appHandle, $myshopifyDomain)
    {
        $this->privateKey = $privateKey;
        $this->appHandle = $appHandle;
        $this->myshopifyDomain = $myshopifyDomain;
    }

    /**
     * Called before any API request goes out
     *
     * @param RequestInterface $request
     * @param array $options
     * @return RequestInterface
     */
    public function __invoke(RequestInterface $request, array $options = [])
    {
        // if we have not authenticated yet, authenticate
        if ($this->authToken === null) {
            $this->authenticate();
        }

        return $request
            ->withHeader('BOLD-Authorization', 'Bearer '.$this->authToken)
            ->withUri(Uri::withQueryValue($request->getUri(), 'shop', $this->myshopifyDomain));
    }

    /**
     * Authenticate with the third party api
     */
    public function authenticate()
    {
        try {
            $client = new Client([
                'base_uri' => BoldApiService::BOLD_API_BASE_URL,
                'headers' => [
                    'cache-control' => 'no-cache',
                    'content-type' => 'application/json',
                    'BOLD-Authorization' => $this->privateKey,
                ]
            ]);

            $res = $client->get('auth/third_party_token?handle='.$this->appHandle.'&shop='.$this->myshopifyDomain);
            if ($res->getStatusCode() === 200) {
                $this->authToken = json_decode($res->getBody())->token;
                return;
            }

            throw new \Exception('Failed to authenticate with Bold third party api');
        }
        catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
