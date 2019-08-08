<?php

namespace Tiger\SDK\Tests;

use Tiger\SDK\PublicApi\Time;

class TimeTest extends TestCase
{
    protected $apiClass    = Time::class;
    protected $apiWithAuth = false;

    /**
     * @dataProvider apiProvider
     * @param Time $api
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function testTimestamp(Time $api)
    {
        $timestamp = $api->timestamp();
        $this->assertInternalType('int', $timestamp);
    }
}