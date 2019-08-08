<?php
/**
 * Author: <mofei> im@mofei.org
 * Date: 2019/8/8 下午6:10
 */

namespace Tiger\SDK\PrivateApi;


use Tiger\SDK\Http\Request;
use Tiger\SDK\TigerApi;

/**
 * 行情API
 *
 * Class    Quote
 * @package Tiger\SDK\PrivateApi
 * @see     https://openapi.itiger.com/docs/api/quote
 */

class Quote extends TigerApi
{

    /**
     * 获取市场状态.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function marketState($params)
    {
        $response = $this->call(Request::METHOD_POST, 'market_state', $params);
        return $response->getApiData();
    }

    /**
     * 获取股票代号列表和名称.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function allSymbols($params)
    {
        $response = $this->call(Request::METHOD_POST, 'all_symbols', $params);
        return $response->getApiData();
    }

    /**
     * 获取分时数据.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function timeLine($params)
    {
        $response = $this->call(Request::METHOD_POST, 'timeline', $params);
        return $response->getApiData();
    }

    /**
     * 获取实时行情.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function quoteRealTime($params)
    {
        $response = $this->call(Request::METHOD_POST, 'quote_real_time', $params);
        return $response->getApiData();
    }

    /**
     * 获取K线数据.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function kline($params)
    {
        $response = $this->call(Request::METHOD_POST, 'kline', $params);
        return $response->getApiData();
    }

    /**
     * 获取逐笔成交.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function tradeTick($params)
    {
        $response = $this->call(Request::METHOD_POST, 'trade_tick', $params);
        return $response->getApiData();
    }

    /**
     * 获取合约列表.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function quoteContract($params)
    {
        $response = $this->call(Request::METHOD_POST, 'quote_contract', $params);
        return $response->getApiData();
    }

    /**
     * 获取做空数据.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function quoteShortableStocks($params)
    {
        $response = $this->call(Request::METHOD_POST, 'quote_shortable_stocks', $params);
        return $response->getApiData();
    }

    /**
     * 获取股票交易信息.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function quoteStockTrade($params)
    {
        $response = $this->call(Request::METHOD_POST, 'quote_stock_trade', $params);
        return $response->getApiData();
    }

    /**
     * 获取期权过期日.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function optionExpiration($params)
    {
        $response = $this->call(Request::METHOD_POST, 'option_expiration', $params);
        return $response->getApiData();
    }

    /**
     * 获取期权链.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function optionChain($params)
    {
        $response = $this->call(Request::METHOD_POST, 'option_chain', $params);
        return $response->getApiData();
    }

    /**
     * 获取期权行情摘要.
     *
     * @param  array $params 输入参数.
     * @return mixed
     * @throws \Tiger\SDK\Exceptions\BusinessException
     * @throws \Tiger\SDK\Exceptions\HttpException
     * @throws \Tiger\SDK\Exceptions\InvalidApiUriException
     */
    public function optionBrief($params)
    {
        $response = $this->call(Request::METHOD_POST, 'option_brief', $params);
        return $response->getApiData();
    }

}