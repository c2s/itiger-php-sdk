<?php

namespace Tiger\SDK\PublicApi;

use Tiger\SDK\Http\Request;
use Tiger\SDK\TigerApi;

/**
 * Class Time
 * @package Tiger\SDK\PublicApi
 * @see https://docs.Tiger.com/#time
 */
class Time extends TigerApi
{
    /**
     * Get the timestamp of Server in milliseconds
     * @return int
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function timestamp()
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/timestamp');
        return $response->getApiData();
    }
}