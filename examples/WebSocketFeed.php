<?php
include '../vendor/autoload.php';

use Tiger\SDK\Auth;
use Tiger\SDK\TigerApi;
use Tiger\SDK\PrivateApi\WebSocketFeed;
use Ratchet\Client\WebSocket;
use React\EventLoop\LoopInterface;

// Set the base uri, default "https://api.Tiger.com" for production environment.
// TigerApi::setBaseUri('https://api.Tiger.com');

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