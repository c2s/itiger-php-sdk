
# PHP SDK for Tiger API
> The detailed document [https://docs.Tiger.com](https://docs.Tiger.com), in order to receive the latest API change notifications, please `Watch` this repository.

[![Latest Version](https://img.shields.io/github/release/imofei/Tiger-php-sdk.svg)](https://github.com/imofei/Tiger-php-sdk/releases)
[![PHP Version](https://img.shields.io/packagist/php-v/imofei/Tiger-php-sdk.svg?color=green)](https://secure.php.net)
[![Build Status](https://travis-ci.org/imofei/Tiger-php-sdk.svg?branch=master)](https://travis-ci.org/imofei/Tiger-php-sdk)
[![Total Downloads](https://poser.pugx.org/imofei/Tiger-php-sdk/downloads)](https://packagist.org/packages/imofei/Tiger-php-sdk)
[![License](https://poser.pugx.org/imofei/Tiger-php-sdk/license)](LICENSE)
[![Total Lines](https://tokei.rs/b1/github/imofei/Tiger-php-sdk)](https://github.com/imofei/Tiger-php-sdk)

## Requirements

| Dependency | Requirement |
| -------- | -------- |
| [PHP](https://secure.php.net/manual/en/install.php) | `>=5.5.0` `Recommend PHP7+` |
| [guzzlehttp/guzzle](https://github.com/guzzle/guzzle) | `~6.0` |

## Install
> Install package via [Composer](https://getcomposer.org/).

```shell
composer require "imofei/Tiger-php-sdk:~1.0.0"
```

## Usage

### Choose environment

| Environment | BaseUri |
| -------- | -------- |
| *Production* `DEFAULT` | https://api.Tiger.com|
| *Sandbox* | https://sandbox-api.Tiger.com |

```php
// Switch to the sandbox environment
TigerApi::setBaseUri('https://sandbox-api.Tiger.com');
```

### Debug mode & logging

```php
// Debug mode will record the logs of API and WebSocket to files in the directory "TigerApi::getLogPath()" according to the minimum log level "TigerApi::getLogLevel()".
TigerApi::setDebugMode(true);

// Logging in your code
// TigerApi::setLogPath('/tmp');
// TigerApi::setLogLevel(Monolog\Logger::DEBUG);
TigerApi::getLogger()->debug("I'am a debug message");
```

### Examples
> See the [test case](tests) for more examples.

#### Example of API `without` authentication

```php
use Tiger\SDK\PublicApi\Time;

$api = new Time();
$timestamp = $api->timestamp();
var_dump($timestamp);
```

#### Example of API `with` authentication

```php
use Tiger\SDK\Auth;
use Tiger\SDK\PrivateApi\Account;
use Tiger\SDK\Exceptions\HttpException;
use Tiger\SDK\Exceptions\BusinessException;

$auth = new Auth('key', 'secret', 'passphrase');
$api = new Account($auth);

try {
    $result = $api->getOverview();
    var_dump($result);
} catch (HttpException $e) {
    var_dump($e->getMessage());
} catch (BusinessException $e) {
    var_dump($e->getMessage());
}
```

#### Example of WebSocket feed

```php
use Tiger\SDK\Auth;
use Tiger\SDK\PrivateApi\WebSocketFeed;
use Ratchet\Client\WebSocket;
use React\EventLoop\LoopInterface;

$auth = null;
// Need to pass the Auth parameter when subscribing to a private channel($api->subscribePrivateChannel()).
// $auth = new Auth('key', 'secret', 'passphrase');
$api = new WebSocketFeed($auth);

$query = ['connectId' => uniqid('', true)];
$channels = [
    ['topic' => '/market/ticker:KCS-BTC'], // Subscribe multiple channels
    ['topic' => '/market/ticker:ETH-BTC'],
];

$api->subscribePublicChannels($query, $channels, function (array $message, WebSocket $ws, LoopInterface $loop) use ($api) {
    var_dump($message);

    // Unsubscribe the channel
    // $ws->send(json_encode($api->createUnsubscribeMessage('/market/ticker:ETH-BTC')));

    // Stop loop
    // $loop->stop();
}, function ($code, $reason) {
    echo "OnClose: {$code} {$reason}\n";
});
```

#### ⚡️Coroutine HTTP client for asynchronous IO
> See the [benchmark](examples/BenchmarkCoroutine.php), almost `20x` faster than `curl`.

```bash
pecl install swoole
composer require swlib/saber
```

```php
use Tiger\SDK\Auth;
use Tiger\SDK\Http\SwooleHttp;
use Tiger\SDK\TigerApi;
use Tiger\SDK\PrivateApi\Order;
use Tiger\SDK\PublicApi\Time;

// Require PHP 7.1+ and Swoole 2.1.2+
// Require running in cli mode

go(function () {
    $api = new Time(null, new SwooleHttp));
    $timestamp = $api->timestamp();
    var_dump($timestamp);
});

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
```

### API list

<details>
<summary>Tiger\SDK\PrivateApi\Account</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| Tiger\SDK\PrivateApi\Account::getOverview() | YES | https://docs.Tiger.com/#account |
| Tiger\SDK\PrivateApi\Account::getTransactionHistory() | YES | https://docs.Tiger.com/#get-transaction-history |
| Tiger\SDK\PrivateApi\Account::transferIn() | YES | https://docs.Tiger.com/#transfer-funds-from-imofei-main-account-to-Tiger-account |
| Tiger\SDK\PrivateApi\Account::transferOut() | YES | https://docs.Tiger.com/##transfer-funds-from-Tiger-account-to-imofei-main-account |
| Tiger\SDK\PrivateApi\Account::cancelTransferOut() | YES | https://docs.Tiger.com/#cancel-transfer-out-request |
| Tiger\SDK\PrivateApi\Account::getTransferList() | YES | https://docs.Tiger.com/#get-transfer-out-request-records |
</details>

<details>
<summary>Tiger\SDK\PrivateApi\Deposit</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| Tiger\SDK\PrivateApi\Deposit::getAddress() | YES | https://docs.Tiger.com/#get-deposit-address |
| Tiger\SDK\PrivateApi\Deposit::getDeposits() | YES | https://docs.Tiger.com/#get-deposit-list |

</details>

<details>
<summary>Tiger\SDK\PrivateApi\Fill</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| Tiger\SDK\PrivateApi\Fill::getFills() | YES | https://docs.Tiger.com/#list-fills |
| Tiger\SDK\PrivateApi\Fill::getRecentList() | YES | https://docs.Tiger.com/#recent-fills |
</details>

<details>
<summary>Tiger\SDK\PrivateApi\Order</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| Tiger\SDK\PrivateApi\Order::create() | YES | https://docs.Tiger.com/#place-a-new-order |
| Tiger\SDK\PrivateApi\Order::cancel() | YES | https://docs.Tiger.com/#cancel-an-order |
| Tiger\SDK\PrivateApi\Order::batchCancel() | YES | https://docs.Tiger.com/#cancel-all-orders |
| Tiger\SDK\PrivateApi\Order::stopOrders() | YES | https://docs.Tiger.com/#list-orders |
| Tiger\SDK\PrivateApi\Order::getList() | YES | https://docs.Tiger.com/#get-v1-historical-orders-list |
| Tiger\SDK\PrivateApi\Order::getStopOrders() | YES | https://docs.Tiger.com/#get-an-order |
| Tiger\SDK\PrivateApi\Order::getRecentDoneOrders() | YES | https://docs.Tiger.com/#recent-orders |
| Tiger\SDK\PrivateApi\Order::getDetail() | YES | https://docs.Tiger.com/#recent-orders |
| Tiger\SDK\PrivateApi\Order::getOpenOrderStatistics() | YES | https://docs.Tiger.com/#recent-orders |

</details>

<details>
<summary>Tiger\SDK\PrivateApi\WebSocketFeed</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| Tiger\SDK\PrivateApi\WebSocketFeed::getPublicServer() | NO | https://docs.Tiger.com/#apply-connect-token |
| Tiger\SDK\PrivateApi\WebSocketFeed::getPrivateServer() | YES | https://docs.Tiger.com/#apply-connect-token |
| Tiger\SDK\PrivateApi\WebSocketFeed::subscribePublicChannel() | NO | https://docs.Tiger.com/#public-channels |
| Tiger\SDK\PrivateApi\WebSocketFeed::subscribePublicChannels() | NO | https://docs.Tiger.com/#public-channels |
| Tiger\SDK\PrivateApi\WebSocketFeed::subscribePrivateChannel() | YES | https://docs.Tiger.com/#private-channels |
| Tiger\SDK\PrivateApi\WebSocketFeed::subscribePrivateChannels() | YES | https://docs.Tiger.com/#private-channels |

</details>

<details>
<summary>Tiger\SDK\PrivateApi\Withdrawal</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| Tiger\SDK\PrivateApi\Withdrawal::getQuotas() | YES | https://docs.Tiger.com/#get-withdrawal-quotas |
| Tiger\SDK\PrivateApi\Withdrawal::getList() | YES | https://docs.Tiger.com/#get-withdrawals-list |
| Tiger\SDK\PrivateApi\Withdrawal::apply() | YES | https://docs.Tiger.com/#apply-withdraw |
| Tiger\SDK\PrivateApi\Withdrawal::cancel() | YES | https://docs.Tiger.com/#cancel-withdrawal |

</details>

<details>
<summary>Tiger\SDK\PublicApi\Symbol</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| Tiger\SDK\PublicApi\Symbol::getTicker() | NO | https://docs.Tiger.com/#get-ticker |
| Tiger\SDK\PublicApi\Symbol::getLevel2Snapshot() | NO | https://docs.Tiger.com/#get-full-order-book-level-2 |
| Tiger\SDK\PublicApi\Symbol::getLevel3Snapshot() | NO | https://docs.Tiger.com/#get-full-order-book-level-3 |
| Tiger\SDK\PublicApi\Symbol::getLevel2Message() | NO | https://docs.Tiger.com/##level-2-pulling-messages |
| Tiger\SDK\PublicApi\Symbol::getLevel3Message() | NO | https://docs.Tiger.com/##level-3-pulling-messages |
| Tiger\SDK\PublicApi\Symbol::getTradeHistory() | NO | https://docs.Tiger.com/#get-trade-histories |

</details>

<details>
<summary>Tiger\SDK\PublicApi\Time</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| Tiger\SDK\PublicApi\Time::timestamp() | NO | https://docs.Tiger.com/#server-time |

</details>

## Run tests
> Modify your API key in `phpunit.xml` first.

```shell
# Add your API configuration items into the environmental variable first
export API_BASE_URI=https://api.Tiger.com
export API_KEY=key
export API_SECRET=secret
export API_PASSPHRASE=passphrase

composer test
```

## License

[MIT](LICENSE)
