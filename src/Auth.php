<?php

namespace Tiger\SDK;

use Tiger\SDK\Exceptions\BusinessException;

class Auth implements IAuth
{
    private $publicKey;
    private $privateKey;

    public function __construct($publicKey, $privateKey)
    {
        $this->publicKey  = $publicKey;
        $this->privateKey = $privateKey;
    }

    /**
     * @param $params
     * @param $bizContent
     * @param $timestamp
     * @param $version
     * @return string
     * @throws BusinessException
     */
    public function signature($params, $bizContent, $timestamp, $version)
    {
        $privateKey = openssl_pkey_get_private($this->privateKey);
        if (!$privateKey) {
            throw new BusinessException("key not available");
        }
        if (is_array($params)) {
            $params = sprintf('biz_content=%s&%s&timestamp=%s&version=%s', $bizContent,
                http_build_query($params), $timestamp, $version);
            $err = openssl_sign($params, $sign, $privateKey);
            openssl_free_key($privateKey);
            $sign = base64_encode($sign);
            if (!$err) {
                throw new BusinessException("failed");
            }
        }
        return $sign;
    }
    /**
     * @param array $inParams
     * @return array
     * @throws BusinessException
     */
    public function getBody(array $inParams)
    {
        $params              = [
            'charset'   => "UTF-8",
            'method'    => $inParams['method'],
            'sign_type' => 'RSA',
            'tiger_id'  => '20150419',
        ];
        $bizContent          = $inParams['biz_content'];
        $timestamp           = date('Y-m-d H:i:s');
        $version             = '1.0';
        $params['sign']      = $this->signature($params, $bizContent, $timestamp, $version);
        $params['timestamp'] = $timestamp;
        $params['version']   = $version;
        return $params;
    }
}