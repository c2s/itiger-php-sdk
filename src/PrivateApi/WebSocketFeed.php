<?php

namespace Tiger\SDK\PrivateApi;

use Tiger\SDK\Exceptions\BusinessException;
use Tiger\SDK\Exceptions\NoAvailableWebSocketServerException;
use Tiger\SDK\Http\Request;
use Tiger\SDK\TigerApi;
use Ratchet\Client\WebSocket;
use React\EventLoop\Factory;
use React\Socket\Connector as SocketConnector;
use Ratchet\Client\Connector as RatchetConnector;
use Ratchet\RFC6455\Messaging\MessageInterface;

/**
 * Class WebSocketFeed
 * @package Tiger\SDK\PublicApi
 * @see https://docs.Tiger.com/#websocket-feed
 */
class WebSocketFeed extends TigerApi
{

    /**
     * Subscribe multiple channels by url
     * @param array $server
     * @param array $channels
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     * @throws \Exception|\Throwable
     */
    public function subscribeChannels(array $server, array $channels, callable $onMessage, callable $onClose = null, array $options = [])
    {
        if (!isset($options['tls']['verify_peer'])) {
            $options['tls']['verify_peer'] = !static::isSkipVerifyTls();
        }

        $loop = Factory::create();
        $reactConnector = new SocketConnector($loop, $options);
        $connector = new RatchetConnector($loop, $reactConnector);
        /**
         * @var \Exception|\Throwable $exception
         */
        $exception = null;
        $connector($server['connectUrl'])->then(function (WebSocket $ws) use ($server, $channels, $onMessage, $onClose, $loop) {
            // Add timer to send ping message
            $pingTimer = $loop->addPeriodicTimer($server['pingInterval'] / 1000 - 1, function () use ($ws) {
                try {
                    $ping = $this->createPingMessage();
                    $pingStr = json_encode($ping);
                    if (self::isDebugMode()) {
                        static::getLogger()->debug(sprintf('Sent a WebSocket message: %s', $pingStr));
                    }
                    // fputs(STDIN, print_r($ping, true));
                    $ws->send($pingStr);
                } catch (\Exception $e) {
                    // Ignore this exception
                }
            });
            $ws->on('message', function (MessageInterface $msg) use ($server, $ws, $channels, $onMessage, $loop, $pingTimer) {
                $msgStr = $msg->__toString();
                if (self::isDebugMode()) {
                    static::getLogger()->debug(sprintf('Received a WebSocket message: %s', $msgStr));
                }
                $msgArray = json_decode($msgStr, true);
                if (!isset($msgArray['type'])) {
                    throw new BusinessException('Invalid format of message without type: ' . $msgStr);
                }
                switch ($msgArray['type']) {
                    case 'welcome':
                        // Do subscribe
                        if (!isset($msgArray['id']) || $msgArray['id'] === $server['connectId']) {
                            foreach ($channels as $channel) {
                                $ws->send(json_encode($channel));
                            }
                        }
                        break;
                    case 'ack':
                    case 'ping':
                    case 'pong':
                        // fputs(STDIN, print_r($msgArray, true));
                        break;
                    case 'error':
                        $loop->cancelTimer($pingTimer);
                        throw new BusinessException('Error: ' . $msg);
                    case 'message':
                        call_user_func($onMessage, $msgArray, $ws, $loop);
                        break;
                    default:
                        throw new BusinessException('Unknown type: ' . $msgArray['type']);
                }
            });
            $ws->on('close', function ($code = null, $reason = null) use ($onClose, $loop, $pingTimer) {
                if (is_callable($onClose)) {
                    call_user_func($onClose, $code, $reason);
                }
                $loop->cancelTimer($pingTimer);
            });
        }, function ($e) use ($loop, &$exception) {
            $exception = $e;
        });

        $loop->run();

        if ($exception !== null) {
            throw $exception;
        }
    }

    /**
     * Subscribe multiple public channels
     * @param array $query The query of websocket url
     * @param array $channels
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     * @throws \Exception|\Throwable
     */
    public function subscribePublicChannels(array $query, array $channels, callable $onMessage, callable $onClose = null, array $options = [])
    {
        if (!isset($channels[0])) {
            $channels = [$channels];
        }
        array_walk($channels, function (&$channel) {
            if (!isset($channel['id'])) {
                $channel['id'] = uniqid('', true);
            }
            $channel['type'] = 'subscribe';
            $channel['privateChannel'] = false;
        });
        if (!isset($query['connectId'])) {
            $query['connectId'] = uniqid('', true);
        }
        $server['connectUrl'] = parent::$wsBaseUri;
        $server['connectId'] = $query['connectId'];
        $this->subscribeChannels($server, $channels, $onMessage, $onClose, $options);
    }

    /**
     * Subscribe multiple private channels
     * @param array $query The query of websocket url
     * @param array $channels
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     * @throws \Exception|\Throwable
     */
    public function subscribePrivateChannels(array $query, array $channels, callable $onMessage, callable $onClose = null, array $options = [])
    {
        if (!isset($channels[0])) {
            $channels = [$channels];
        }
        array_walk($channels, function (&$channel) {
            if (!isset($channel['id'])) {
                $channel['id'] = uniqid('', true);
            }
            $channel['type'] = 'subscribe';
            $channel['privateChannel'] = true;
        });
        if (!isset($query['connectId'])) {
            $query['connectId'] = uniqid('', true);
        }
        $server = $this->getPrivateServer($query);
        $server['connectId'] = $query['connectId'];
        $this->subscribeChannels($server, $channels, $onMessage, $onClose, $options);
    }

    /**
     * Subscribe one public channel
     * @param array $query The query of websocket url
     * @param array $channel
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     * @throws \Exception|\Throwable
     */
    public function subscribePublicChannel(array $query, array $channel, callable $onMessage, callable $onClose = null, array $options = [])
    {
        $this->subscribePublicChannels($query, [$channel], $onMessage, $onClose, $options);
    }

    /**
     * Subscribe one private channel
     * @param array $query The query of websocket url
     * @param array $channel
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     * @throws \Exception|\Throwable
     */
    public function subscribePrivateChannel(array $query, array $channel, callable $onMessage, callable $onClose = null, array $options = [])
    {
        $this->subscribePrivateChannels($query, [$channel], $onMessage, $onClose, $options);
    }

    /**
     * Create message for ping
     * @param string $id
     * @return array
     */
    public function createPingMessage($id = null)
    {
        return ['id' => $id ?: uniqid('', true), 'type' => 'ping'];
    }

    /**
     * Create message for unsubscribe
     * @param string $topic
     * @param bool $privateChannel
     * @param bool $response
     * @param string $id
     * @return array
     */
    public function createUnsubscribeMessage($topic, $privateChannel = false, $response = true, $id = null)
    {
        return ['id' => $id ?: uniqid('', true), 'type' => 'unsubscribe', 'topic' => $topic, 'privateChannel' => $privateChannel, 'response' => $response];
    }
}
