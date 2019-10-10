<?php

namespace Tiger\SDK\Tests;

use Tiger\SDK\Auth;
use Tiger\SDK\Http\GuzzleHttp;
use Tiger\SDK\TigerApi;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $apiClass = 'Must be declared in the subclass';
    protected $apiWithAuth = false;

    public function apiProvider()
    {
        $publicKey        = '';
        $privateKey       = '';

//        $publicKey       = (string)getenv('API_PUBLIC_KEY');
//        $privateKey       = (string)getenv('API_PRIVATE_KEY');

        $apiBaseUri       = (string)getenv('API_BASE_URI');
        $apiSkipVerifyTls = (bool)getenv('API_SKIP_VERIFY_TLS');
        $apiDebugMode     = (bool)getenv('API_DEBUG_MODE');
        TigerApi::setSkipVerifyTls($apiSkipVerifyTls);
        TigerApi::setDebugMode($apiDebugMode);
        if ($apiBaseUri) {
            TigerApi::setBaseUri($apiBaseUri);
        }

        $auth = new Auth($publicKey, $privateKey);
        return [
            [new $this->apiClass($this->apiWithAuth ? $auth : null)],
        ];
    }

    protected function assertPagination($data)
    {
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('totalNum', $data);
        $this->assertArrayHasKey('totalPage', $data);
        $this->assertArrayHasKey('pageSize', $data);
        $this->assertArrayHasKey('currentPage', $data);
        $this->assertArrayHasKey('items', $data);
        $this->assertInternalType('array', $data['items']);
    }
}