<?php
/**
 * Author: <Mofei> im@mofei.org
 * Date: 2019/8/7 下午5:49
 */

namespace Tiger\SDK\PrivateApi;


use Tiger\SDK\Http\Request;
use Tiger\SDK\TigerApi;

/**
 * 交易API
 *
 * Class    Trade
 * @package Tiger\SDK\PrivateApi
 * @see     https://openapi.itiger.com/docs/api/trade
 */
class Trade extends TigerApi
{
    /**
     * 获取订单号.
     *
     * @param  string $account 账户.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function orderNo($account)
    {
        $response = $this->call(Request::METHOD_POST, 'order_no', compact('account'));
        return $response->getApiData();
    }

    /**
     * 创建订单.
     *
     * @param  array $order 订单参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function placeOrder($order)
    {
        $response = $this->call(Request::METHOD_POST, 'place_order', $order);
        return $response->getApiData();
    }

    /**
     * 取消订单.
     *
     * @param  string $id      订单号.
     * @param  string $account 账户ID.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function cancelOrder($id, $account)
    {
        $response = $this->call(Request::METHOD_POST, 'cancel_order', compact('id', 'account'));
        return $response->getApiData();
    }

    /**
     * 修改订单.
     *
     * @param  array $order 订单参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function modifyOrder($order)
    {
        $response = $this->call(Request::METHOD_POST, 'modify_order', $order);
        return $response->getApiData();
    }
}