<?php
include '../vendor/autoload.php';

use Tiger\SDK\Auth;
use Tiger\SDK\Http\SwooleHttp;
use Tiger\SDK\TigerApi;
use Tiger\SDK\PrivateApi\Order;

// Set the base uri, default "https://api.Tiger.com" for production environment.
// TigerApi::setBaseUri('https://api.Tiger.com');

// Require PHP 7.1+ and Swoole 2.1.2+
// Require running in cli mode
go(function () {
    $auth = new Auth('key', 'secret', 'passphrase');
    $api = new Order($auth, new SwooleHttp);

    // Create 50 orders CONCURRENTLY in 1 second
    for ($i = 0; $i < 50; $i++) {
        go(function () use ($api, $i) {
            $order = [
                'clientOid' => uniqid(),
                'price'     => '1',
                'size'      => '1',
                'symbol'    => 'BTC-USDT',
                'type'      => 'limit',
                'side'      => 'buy',
                'remark'    => 'ORDER#' . $i,
            ];
            try {
                $result = $api->create($order);
                var_dump($result);
            } catch (\Throwable $e) {
                var_dump($e->getMessage());
            }
        });
    }
});