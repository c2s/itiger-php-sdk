<?php

namespace Tiger\SDK\PrivateApi;


use Tiger\SDK\Http\Request;
use Tiger\SDK\TigerApi;

/**
 * 基础数据API
 *
 * Class    Base
 * @package Tiger\SDK\PrivateApi
 * @see     https://openapi.itiger.com/docs/api/data
 */
class Base extends TigerApi
{
    /**
     * 获取日级别数据.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function financialDaily($params)
    {
        $response = $this->call(Request::METHOD_POST, 'financial_daily', $params);
        return $response->getApiData();
    }

    /**
     * 获取最新的财务数据.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function financialReport($params)
    {
        $response = $this->call(Request::METHOD_POST, 'financial_report', $params);
        return $response->getApiData();
    }

    /**
     * 获取拆合股数据.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function corporateAction($params)
    {
        $response = $this->call(Request::METHOD_POST, 'corporate_action', $params);
        return $response->getApiData();
    }

}