<?php
/**
 * Author: <mofei> im@mofei.org
 * Date: 2019/8/8 下午5:37
 */

namespace Tiger\SDK\PrivateApi;


use Tiger\SDK\Http\Request;
use Tiger\SDK\TigerApi;

/**
 * 账户API
 *
 * Class    Account
 * @package Tiger\SDK\PrivateApi
 * @see     https://openapi.itiger.com/docs/api/account
 */
class Account extends TigerApi
{
    /**
     * 获取合约信息.
     *
     * @param  string $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function contract($params)
    {
        $response = $this->call(Request::METHOD_POST, 'contract', $params);
        return $response->getApiData();
    }

    /**
     * 获取持仓信息.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function positions($params)
    {
        $response = $this->call(Request::METHOD_POST, 'positions', $params);
        return $response->getApiData();
    }

    /**
     * 获取资产.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function assets($params)
    {
        $response = $this->call(Request::METHOD_POST, 'assets', $params);
        return $response->getApiData();
    }

    /**
     * 获取指定订单.
     *
     * @param  string $account 账户ID.
     * @param  string $id      订单号.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function orders($account, $id)
    {
        $response = $this->call(Request::METHOD_POST, 'orders', compact('account', 'id'));
        return $response->getApiData();
    }

    /**
     * 订单列表.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function orderList($params)
    {
        $response = $this->call(Request::METHOD_POST, 'orders', $params);
        return $response->getApiData();
    }

    /**
     * 已成交订单.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function filledOrders($params)
    {
        $response = $this->call(Request::METHOD_POST, 'filled_orders', $params);
        return $response->getApiData();
    }

    /**
     * 待成交订单.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function activeOrders($params)
    {
        $response = $this->call(Request::METHOD_POST, 'active_orders', $params);
        return $response->getApiData();
    }

    /**
     * 已撤销订单.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function inactiveOrders($params)
    {
        $response = $this->call(Request::METHOD_POST, 'inactive_orders', $params);
        return $response->getApiData();
    }
}