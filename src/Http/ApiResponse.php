<?php

namespace Tiger\SDK\Http;

use Tiger\SDK\ApiCode;
use Tiger\SDK\Exceptions\BusinessException;
use Tiger\SDK\Exceptions\HttpException;

class ApiResponse
{
    /**
     * @var Response $httpResponse
     */
    protected $httpResponse;

    protected $body;

    public function __construct(Response $response)
    {
        $this->httpResponse = $response;
    }

    public function getBody()
    {
        if (is_null($this->body)) {
            $this->body = $this->httpResponse->getBody(true);
        }
        return $this->body;
    }

    public function getApiCode()
    {
        $body = $this->getBody();
        return isset($body['code']) ? $body['code'] : '';
    }

    public function getApiMessage()
    {
        $body = $this->getBody();
        return isset($body['msg']) ? $body['msg'] : '';
    }

    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

    public function isSuccessful()
    {
        if ($this->httpResponse->isSuccessful()) {
            if ($this->getApiCode() == ApiCode::SUCCESS) {
                return true;
            }
        }
        return false;
    }

    public function mustSuccessful()
    {
        if (!$this->httpResponse->isSuccessful()) {
            $msg = sprintf(
                '[HTTP]Failure: status code is NOT 200, %s %s with body=%s, respond code=%d body=%s',
                $this->httpResponse->getRequest()->getMethod(),
                $this->httpResponse->getRequest()->getRequestUri(),
                $this->httpResponse->getRequest()->getBodyParams(),
                $this->httpResponse->getStatusCode(),
                $this->httpResponse->getBody()
            );
            $exception = new HttpException($msg, $this->httpResponse->getStatusCode());
            $exception->setRequest($this->httpResponse->getRequest());
            $exception->setResponse($this->httpResponse);
            throw $exception;
        }

        if (!$this->isSuccessful()) {
            $msg = sprintf(
                '[API]Failure: api code is NOT %s, %s %s with body=%s, respond code=%s message="%s" data=%s',
                ApiCode::SUCCESS,
                $this->httpResponse->getRequest()->getMethod(),
                $this->httpResponse->getRequest()->getRequestUri(),
                $this->httpResponse->getRequest()->getBodyParams(),
                $this->getApiCode(),
                $this->getApiMessage(),
                $this->httpResponse->getBody()
            );
            $exception = new BusinessException($msg, is_numeric($this->getApiCode()) ? $this->getApiCode() : 110);
            $exception->setResponse($this);
            throw $exception;
        }
    }

    /**
     * @return mixed
     * @throws BusinessException
     * @throws HttpException
     */
    public function getApiData()
    {
        $this->mustSuccessful();
        $body = $this->getBody();
        if (!isset($body['data'])) {
            return null;
        }
        return $body['data'];
    }

}