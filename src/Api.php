<?php

namespace Tiger\SDK;

use Tiger\SDK\Http\GuzzleHttp;
use Tiger\SDK\Http\IHttp;
use Tiger\SDK\Http\Request;
use Tiger\SDK\Http\Response;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

abstract class Api
{
    /**
     * @var string SDK Version
     */
    const VERSION = '1.0.1';

    /**
     * @var string
     */
    protected static $baseUri = 'https://openapi.itiger.com/gateway';

    /**
     * @var string
     */
    protected static $wsBaseUri = 'wss://openapi.itiger.com:8887/stomp';

    /**
     * @var bool
     */
    protected static $skipVerifyTls = false;

    /**
     * @var bool
     */
    protected static $debugMode = false;

    /**
     * @var string
     */
    protected static $logPath = '/tmp';

    /**
     * @var LoggerInterface $logger
     */
    protected static $logger;

    /**
     * @var int
     */
    protected static $logLevel = Logger::DEBUG;

    /**
     * @var IAuth $auth
     */
    protected $auth;

    /**
     * @var IHttp $http
     */
    protected $http;

    public function __construct(IAuth $auth = null, IHttp $http = null)
    {
        if ($http === null) {
            $http = new GuzzleHttp(['skipVerifyTls' => &self::$skipVerifyTls]);
        }
        $this->auth = $auth;
        $this->http = $http;
        date_default_timezone_set('Asia/Shanghai');
    }

    /**
     * @return string
     */
    public static function getBaseUri()
    {
        return static::$baseUri;
    }

    /**
     * @param string $baseUri
     */
    public static function setBaseUri($baseUri)
    {
        static::$baseUri = $baseUri;
    }

    /**
     * @return bool
     */
    public static function isSkipVerifyTls()
    {
        return static::$skipVerifyTls;
    }

    /**
     * @param bool $skipVerifyTls
     */
    public static function setSkipVerifyTls($skipVerifyTls)
    {
        static::$skipVerifyTls = $skipVerifyTls;
    }

    /**
     * @return bool
     */
    public static function isDebugMode()
    {
        return self::$debugMode;
    }

    /**
     * @param bool $debugMode
     */
    public static function setDebugMode($debugMode)
    {
        self::$debugMode = $debugMode;
    }

    /**
     * @param LoggerInterface $logger
     */
    public static function setLogger(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    /**
     * @return Logger|LoggerInterface
     * @throws \Exception
     */
    public static function getLogger()
    {
        if (self::$logger === null) {
            self::$logger = new Logger('Tiger-sdk');
            $handler      = new RotatingFileHandler(static::getLogPath() . '/Tiger-sdk.log', 0, static::$logLevel);
            $formatter    = new LineFormatter(null, null, false, true);
            $handler->setFormatter($formatter);
            self::$logger->pushHandler($handler);
        }
        return self::$logger;
    }

    /**
     * @return string
     */
    public static function getLogPath()
    {
        return self::$logPath;
    }

    /**
     * @param string $logPath
     */
    public static function setLogPath($logPath)
    {
        self::$logPath = $logPath;
    }

    /**
     * @return int
     */
    public static function getLogLevel()
    {
        return self::$logLevel;
    }

    /**
     * @param int $logLevel
     */
    public static function setLogLevel($logLevel)
    {
        self::$logLevel = $logLevel;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $params
     * @param array $headers
     * @param int $timeout
     * @return Response
     * @throws Exceptions\HttpException
     * @throws Exceptions\InvalidApiUriException
     */
    public function call($requestMethod, $method, array $bizParams = [], array $headers = [], $timeout = 30)
    {
        $params['biz_content'] = empty($bizParams) ? '' : json_encode($bizParams, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $params['method']      = $method;
        $request               = new Request();
        $request->setMethod($requestMethod);
        $request->setBaseUri(static::getBaseUri());
        $request->setParams($params);
        if ($this->auth) {
            $authBody = $this->auth->getBody($params);
            $params   = array_merge($params, $authBody);
        }
        $headers['User-Agent'] = 'ITIGER-PHP-SDK/' . static::VERSION;
        $request->setHeaders($headers);
        $request->setParams($params);

        $requestId = uniqid();

        if (self::isDebugMode()) {
            static::getLogger()->debug(sprintf('Sent a HTTP request#%s: %s', $requestId, $request));
        }
        $response = $this->http->request($request, $timeout);
        if (self::isDebugMode()) {
            static::getLogger()->debug(sprintf('Received a HTTP response#%s: %s', $requestId, $response));
        }

        return $response;
    }
}