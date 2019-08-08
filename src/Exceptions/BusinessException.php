<?php

namespace Tiger\SDK\Exceptions;

use Tiger\SDK\Http\ApiResponse;

class BusinessException extends \Exception
{
    /**
     * @var ApiResponse $response
     */
    protected $response;

    /**
     * @return ApiResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param ApiResponse $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

}