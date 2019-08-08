<?php

namespace Tiger\SDK;

use Tiger\SDK\Http\ApiResponse;

abstract class TigerApi extends Api
{
    /**
     * Call an API
     * @param string $method
     * @param string $uri
     * @param array $params
     * @param array $headers
     * @param int $timeout
     * @return ApiResponse
     * @throws Exceptions\HttpException
     * @throws Exceptions\InvalidApiUriException
     */
    public function call($requestMethod, $method, array $bizParams = [], array $headers = [], $timeout = 30)
    {
        $response = parent::call($requestMethod, $method, $bizParams, $headers, $timeout);
        return new ApiResponse($response);
    }
}