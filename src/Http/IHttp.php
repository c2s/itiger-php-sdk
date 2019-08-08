<?php

namespace Tiger\SDK\Http;

use Tiger\SDK\Exceptions\HttpException;
use Tiger\SDK\Exceptions\InvalidApiUriException;

interface IHttp
{
    /**
     * @param Request $request
     * @param float|int $timeout in seconds
     * @return Response
     * @throws HttpException
     * @throws InvalidApiUriException
     */
    public function request(Request $request, $timeout = 30);
}