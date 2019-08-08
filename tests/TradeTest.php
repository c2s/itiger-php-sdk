<?php

namespace Tiger\SDK\Tests;

use Tiger\SDK\PrivateApi\Trade;

class TradeTest extends TestCase
{

    protected $apiClass    = Trade::class;
    protected $apiWithAuth = true;


    /**
     * @dataProvider apiProvider
     * @param Trade $api
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function testOrderNo(Trade $api)
    {
        $data = $api->orderNo('U10090139');
        var_dump($data);
    }
}