
# PHP SDK for Tiger API
> The detailed document [https://openapi.itiger.com/docs/](https://openapi.itiger.com/docs/), in order to receive the latest API change notifications, please `Watch` this repository.


## Requirements

| Dependency | Requirement |
| -------- | -------- |
| [PHP](https://secure.php.net/manual/en/install.php) | `>=5.5.0` `Recommend PHP7+` |
| [guzzlehttp/guzzle](https://github.com/guzzle/guzzle) | `~6.0` |

## Install
> Install package via [Composer](https://getcomposer.org/).

```shell
composer require "c2s/itiger-php-sdk:~1.0.0"
```

## Usage

### Choose environment

| Environment | BaseUri |
| -------- | -------- |
| *Production* `DEFAULT` | https://openapi.itiger.com/gateway|
| *Sandbox* | https://openapi-sandbox.itiger.com/gateway	 |

```php
// Switch to the sandbox environment
TigerApi::setBaseUri('https://openapi-sandbox.itiger.com/gateway');
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

$auth = new Auth($publicKey, $privateKey);
$api = new Base($auth);

try {
    $result = $api->financialDaily();
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

## License

[MIT](LICENSE)
