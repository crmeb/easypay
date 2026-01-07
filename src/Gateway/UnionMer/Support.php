<?php

namespace Crmeb\Gateway\UnionMer;

use Crmeb\Easypay\Exception\PayException;
use Crmeb\Easypay\Exception\PayResponseException;
use Crmeb\Easypay\UnionMerConfig;
use Crmeb\Enum\PayUnionMerEnum;
use Crmeb\Gateway\AbstractPay;
use Crmeb\Support\Tools;
use GuzzleHttp\Exception\GuzzleException;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class Support
 * @package Crmeb\Gateway\UnionMer
 */
class Support
{
    /**
     *
     * @var UnionMerConfig
     */
    private $config;

    /**
     *
     * @var AbstractPay
     */
    private $abstractPay;


    /**
     *
     * @param AbstractPay $abstractPay
     */
    public function __construct(AbstractPay $abstractPay)
    {
        $this->abstractPay = $abstractPay;
        $this->config = $abstractPay->getConfig();
    }

    /**
     * 获取token
     * @return mixed
     * @throws PayException
     * @throws GuzzleException
     */
    protected function getToken()
    {
        $timestamp = date("YmdHis", time());
        $nonce = Tools::createUuid();
        $signatureAttr = [
            $this->config->getAppid(),
            $timestamp,
            $nonce,
            $this->config->getAppKey(),
        ];

        $body = [
            'appId'     => $this->config->getAppid(),
            'appKey'    => $this->config->getAppKey(),
            'timestamp' => $timestamp,
            'nonce'     => $nonce,
            'signature' => sha1(implode('', $signatureAttr)),
        ];

        $response = $this->abstractPay->jsonSendRequest(PayUnionMerEnum::TOKEN_API_URL, 'post', $body);

        if ($response['errCode'] == 'SUCCESS' && isset($response['accessToken'])) {
            return $response['accessToken'];
        } else {
            throw new PayException('获取token失败：'($response['errMsg'] ?? ''));
        }
    }

    /**
     * @return mixed
     * @throws PayException
     * @throws GuzzleException
     * @throws InvalidArgumentException
     */
    protected function getCacheToken()
    {
        $key = $this->config->getAppid() . '_UNIONMER_TOKEN';
        if ($this->abstractPay->getCache()->has($key)) {
            $accessToken = $this->abstractPay->getCache()->get($key);
        } else {
            $accessToken = $this->getToken();
            $this->abstractPay->getCache()->set($key, $accessToken, 3600);
        }

        return $accessToken;
    }

    /**
     * 发送json请求
     * @param string $url
     * @param array $data
     * @param bool $isToken
     * @return array
     * @throws PayException
     * @throws GuzzleException
     * @throws InvalidArgumentException|PayResponseException
     */
    public function jsonSendRequest(string $url, array $data = [], bool $isToken = true)
    {
        $headers = [];

        if ($isToken) {
            $accessToken = $this->getCacheToken();
            $headers = [
                'Authorization' => 'OPEN-ACCESS-TOKEN AccessToken=' . $accessToken,
            ];
        }

        $response = $this->abstractPay->jsonSendRequest($url, 'post', $data, $headers);

        if ($response['errCode'] == 'SUCCESS') {
            return $response;
        } else {
            throw new PayResponseException('请求失败：'($response['errMsg'] ?? ''), 0, null, $response);
        }
    }
}