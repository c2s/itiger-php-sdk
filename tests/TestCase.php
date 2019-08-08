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
        $publicKey        = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDNID3hM9hK3HemJ389X3OqzdsE
MP7IFB6pkM2YiUgO6N9FWI3EBCi7p/JbC9OcOnpdkbrvQMnCAMiv11Gp4Zdmv1gX
5JgMwAavtxCbPG/7A3vXP3R4uQp4HMV0VKjmdm8/A7TqgmdiTtC2/GfHHN+6Xgjf
2Vu8CU1jjw63NWmbGwIDAQAB
-----END PUBLIC KEY-----';
        $privateKey       = '-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQDNID3hM9hK3HemJ389X3OqzdsEMP7IFB6pkM2YiUgO6N9FWI3E
BCi7p/JbC9OcOnpdkbrvQMnCAMiv11Gp4Zdmv1gX5JgMwAavtxCbPG/7A3vXP3R4
uQp4HMV0VKjmdm8/A7TqgmdiTtC2/GfHHN+6Xgjf2Vu8CU1jjw63NWmbGwIDAQAB
AoGBALp9iKS5XPjarhEqsZzbri5oz6lk3g6LdEEXfEQk85VSlMLYOrMuUNYjX8z0
2KOd6ugs+PZSQdwWmF599SntBpZri82IYWludGUqhp/eEmnfyJt8DB7idT4YM+Yi
UJGcnkiTcuj6HqC93RdvsLLJBT8lS/U2zu80MbdqL0xOvUn5AkEA7UAG2/VCuIuY
wVFJF6LvQh74nbp145Y52z7lF59dC0ZUjNsU5Nx2VKbDONMTUyvHVSpwpOecX/AD
cD0W5iZzbQJBAN1WSdvbk8KmtJL4mKATYnVZ0EzNmoDHDm8Gn+qoK1Czx2Ar+CV+
veHHCmHArfRVO1ATJXOjQJKKvE+4B5kVK6cCQHV42Gyc+hZqeI5v8yMS+CjjMPLY
Wnr/7VHTyJdzvxxQDJlZ+PSA/M5ZbBx81vq9mItg4jmkvNb7/pfah7YZn9kCQQCH
cwjb/PHdyvryfiOYwoQcYIwylBga+rYAh8NRbPyk9H/sgLvo5jj5dD8MN0e9IHOd
uznOHHeltjUra+lqayQ9AkAi3HNUwsQGRx4WTiKmsaJvBOjukfirgRmp1ojME0wb
uKKou0jkiwfsXKw3jw6Q9TD+pF1v/vLm+YHhG+dVd0/Q
-----END RSA PRIVATE KEY-----';

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
//            [new $this->apiClass($this->apiWithAuth ? $auth : null, new GuzzleHttp(['skipVerifyTls' => $apiSkipVerifyTls]))],
            //[new $this->apiClass($this->apiWithAuth ? $auth : null, new SwooleHttp(['skipVerifyTls' => $apiSkipVerifyTls]))],
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