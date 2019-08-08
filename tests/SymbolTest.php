<?php

namespace Tiger\SDK\Tests;

use Tiger\SDK\PrivateApi\Symbol;

class SymbolTest extends TestCase
{

    protected $apiClass    = Symbol::class;
    protected $apiWithAuth = true;

    /**
     * @dataProvider apiProvider
     * @param Symbol $api
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function testAllSymbols(Symbol $api)
    {
        $data = $api->allSymbols('US');
        var_dump($data);
    }

}