<?php

namespace Crmeb\Easypay\Gateway;

use Crmeb\Easypay\Config\UnionMerConfig;
use Crmeb\Easypay\Enum\PayGatewayTypeEnum;
use Crmeb\Easypay\Enum\PayUnionMerEnum;
use Crmeb\Easypay\Gateway\UnionMer\Pay;
use Crmeb\Easypay\Support\Tools;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * 统一收单下单并支付
 * Class UnionMerService
 * @package Crmeb\Easypay\Gateway
 */
class UnionMerService
{

    /**
     * @var UnionMerConfig
     */
    protected $config;

    /**
     * @var Pay
     */
    protected $payGateway;

    /**
     * UnionMerService constructor.
     * @param LoggerInterface $logger
     * @param CacheInterface $cache
     * @param UnionMerConfig $config
     */
    public function __construct(LoggerInterface $logger, CacheInterface $cache, UnionMerConfig $config)
    {
        $this->config = $config;
        $this->payGateway = new Pay($this->config, $logger, $cache);
    }

    /**
     * 扫码支付
     * @param string $orderId
     * @param string $amount
     * @param string $subject
     * @param string $bodyDesc
     * @param array $goods
     * @return array|mixed
     * @throws \Crmeb\Easypay\Exception\PayException
     * @throws \Crmeb\Easypay\Exception\PayResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function scan(string $orderId, string $amount, string $subject, string $bodyDesc = '', array $goods = [])
    {
        $params = [
            'union_type'  => PayUnionMerEnum::UNION_TYPE_WECHAT,
            'instMid'     => 'QRPAYDEFAULT',
            'msgId'       => Tools::guid(),
            'srcReserve'  => $subject,
            'billNo'      => $orderId,
            'goods'       => $goods,
            'totalAmount' => bcmul($amount, 100, 0),
            'billDesc'    => $bodyDesc,
            'billDate'    => date('Y-m-d')
        ];

        return $this->payGateway->pay(PayGatewayTypeEnum::NATIVE_PAY, $params);
    }
}