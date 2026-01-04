<?php

namespace Crmeb\Gateway;

use Crmeb\Easypay\CommonConfig;
use Crmeb\Easypay\Exception\PayException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * AbstractPay
 * @package Crmeb\Gateway
 */
class AbstractPay
{
    /**
     * 配置
     * @var CommonConfig
     */
    protected $config;

    /**
     * 日志
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * 缓存
     * @var CacheInterface
     */
    protected $cache;

    /**
     * http客户端
     * @var Client
     */
    protected $client;

    /**
     * 基础url
     * @var string
     */
    protected $baseUri = '';

    /**
     * @param CommonConfig $config
     * @param LoggerInterface $logger
     * @param CacheInterface $cache
     */
    public function __construct(CommonConfig $config, LoggerInterface $logger, CacheInterface $cache)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->cache = $cache;
        $this->client = new Client([
            'base_uri'              => $this->baseUri,
            RequestOptions::TIMEOUT => $this->config->getHttpTimeout() ?: 5,
            RequestOptions::VERIFY  => $this->config->getHttpVerify() ?: false,
        ]);
    }

    /**
     * http请求日志
     * @param string $url
     * @param string $method
     * @param array $data
     * @param $result
     * @return void
     */
    protected function httpRequestLog(string $url, string $method, array $options = [], $result = null)
    {
        if ($this->config->getLogger()) {
            $this->logger->info('easypay http request {url}/{method} options: {options} result: {result}', [
                'url'     => $url,
                'method'  => $method,
                'options' => $options,
                'result'  => $result
            ]);
        }
    }

    /**
     * 发送请求
     * @param string $url
     * @param string $method
     * @param array $options
     * @return string
     * @throws GuzzleException
     */
    public function abstractSendRequest(string $url, string $method, array $options = [])
    {
        $response = $this->client->request($method, $url, $options);

        $content = $response->getBody()->getContents();

        $this->httpRequestLog($url, $method, $options, $content);

        return $content;
    }

    /**
     * json请求
     * @param string $url
     * @param string $method
     * @param array $options
     * @param array $headers
     * @return string
     * @throws GuzzleException|PayException
     */
    public function jsonSendRequest(string $url, string $method, array $options = [], array $headers = [])
    {
        $options[RequestOptions::JSON] = $options;
        $options[RequestOptions::HEADERS] = $headers;
        $content = $this->abstractSendRequest($url, $method, $options);

        if ($content === null) {
            $content = '';
        }

        if (!is_string($content)) {
            throw new PayException('请求返回的数据不是字符串');
        }

        return $this->jsonDecode($content);
    }

    /**
     * 表单请求
     * @param string $url
     * @param string $method
     * @param array $options
     * @param array $headers
     * @return string
     * @throws GuzzleException
     */
    public function formSendRequest(string $url, string $method, array $options = [], array $headers = [])
    {
        $options[RequestOptions::FORM_PARAMS] = $options;
        $options[RequestOptions::HEADERS] = $headers;
        return $this->abstractSendRequest($url, $method, $options);
    }

    /**
     * GET请求
     * @param string $url
     * @param string $method
     * @param array $options
     * @param array $headers
     * @return string
     * @throws GuzzleException
     */
    public function querySendRequest(string $url, string $method, array $options = [], array $headers = [])
    {
        $options[RequestOptions::QUERY] = $options;
        $options[RequestOptions::HEADERS] = $headers;
        return $this->abstractSendRequest($url, $method, $options);
    }

    /**
     * json解码
     * @param string $json
     * @return mixed
     * @throws PayException
     */
    protected function jsonDecode(string $json)
    {
        $result = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new PayException('json_decode error:' . json_last_error());
        }

        return $result;
    }
}