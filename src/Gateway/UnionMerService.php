<?php

namespace Crmeb\Easypay\Gateway;

use Crmeb\Easypay\Config\UnionMerConfig;
use Crmeb\Easypay\Enum\PayGatewayTypeEnum;
use Crmeb\Easypay\Enum\PayUnionMerEnum;
use Crmeb\Easypay\Exception\PayException;
use Crmeb\Easypay\Exception\PayResponseException;
use Crmeb\Easypay\Gateway\UnionMer\Pay;
use Crmeb\Easypay\Support\Tools;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

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
     * @param string $orderId 订单号
     * @param string $amount 支付金额
     * @param string $subject 标记原样返回
     * @param string $bodyDesc 商品描述
     * @param array $goods 商品列表
     * @param array $subOrders 子订单列表
     * @return array
     * @throws PayException
     * @throws PayResponseException
     * @throws GuzzleException
     * @throws InvalidArgumentException
     */
    public function scan(string $orderId, string $amount, string $subject, string $bodyDesc = '', array $goods = [], array $subOrders = [])
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
            'billDate'    => date('Y-m-d'),
            'subOrders'   => $subOrders,
        ];

        return $this->payGateway->pay(PayGatewayTypeEnum::NATIVE_PAY, $params);
    }

    /**
     * H5支付
     * @param string $orderId
     * @param string $amount
     * @param string $subject
     * @param string $bodyDesc
     * @param array $goods
     * @param array $subOrders
     * @param string $unionType
     * @return array|string
     * @throws GuzzleException
     * @throws InvalidArgumentException
     * @throws PayException
     * @throws PayResponseException
     */
    public function h5Pay(string $orderId, string $amount, string $subject, string $bodyDesc = '', array $goods = [], array $subOrders = [], string $unionType = PayUnionMerEnum::UNION_TYPE_WECHAT)
    {
        $params = [
            'union_type'  => $unionType,
            'instMid'     => 'H5DEFAULT',
            'msgId'       => Tools::guid(),
            'srcReserve'  => $subject,
            'billNo'      => $orderId,
            'totalAmount' => bcmul($amount, 100, 0),
            'billDesc'    => $bodyDesc,
            'billDate'    => date('Y-m-d'),
        ];

        if ($goods) {
            $params['goods'] = $goods;
        }
        if ($subOrders) {
            $params['subOrders'] = $subOrders;
        }

        return $this->payGateway->pay(PayGatewayTypeEnum::WAP_PAY, $params);
    }
}