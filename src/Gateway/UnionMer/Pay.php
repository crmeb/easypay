<?php

namespace Crmeb\Easypay\Gateway\UnionMer;


use Crmeb\Easypay\Enum\PayGatewayTypeEnum;
use Crmeb\Easypay\Exception\PayException;
use Crmeb\Easypay\Config\UnionMerConfig;
use Crmeb\Easypay\Enum\PayUnionMerEnum;
use Crmeb\Easypay\Gateway\AbstractPay;
use Crmeb\Easypay\Interfaces\PayInterface;

/**
 * 银联商务支付
 */
class Pay extends AbstractPay implements PayInterface
{

    /**
     *  支持
     * @var Support
     */
    protected $support;

    /**
     *  支付参数
     * @var array
     */
    private $payload = [];

    /**
     * 初始化
     * @return void
     */
    public function init()
    {
        $this->support = new Support($this);

        $this->baseUri = $this->config->getBaseUri();

        /** @var UnionMerConfig $config */
        $config = $this->config;
        $this->payload = [
            'mid'       => $config->getMchId(),
            'tid'       => $config->getTid(),
            'notifyUrl' => $config->getNotifyUrl(),
            'returnUrl' => $config->getReturnUrl(),
        ];
    }

    /**
     *  支付
     * @param $gateway
     * @param array $params
     * @return array|string
     * @throws PayException
     * @throws \Crmeb\Easypay\Exception\PayResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function pay($gateway, array $params = [])
    {
        if (!in_array($gateway, array_keys(PayUnionMerEnum::GATEWAY_MAP))) {
            throw  new PayException('不支持的支付渠道接口');
        }

        $unionType = $params['union_type'] ?? PayUnionMerEnum::UNION_TYPE_WECHAT;
        unset($params['union_type']);

        if (!in_array($unionType, [PayUnionMerEnum::UNION_TYPE_WECHAT, PayUnionMerEnum::UNION_TYPE_ALIPAY])) {
            throw  new PayException('不支持的支付端口接口!');
        }

        $url = PayUnionMerEnum::GATEWAY_MAP[$gateway][$unionType] ?? null;
        if (!$url) {
            throw  new PayException(sprintf('不支持的支付接口:gateway %s unionType %s', $gateway, $unionType));
        }

        $this->payload['notifyUrl'] = $params['return_url'] ?? $this->payload['notifyUrl'];
        $this->payload['notifyUrl'] = $params['notify_url'] ?? $this->payload['notifyUrl'];
        $this->payload['requestTimestamp'] = date('Y-m-d H:i:s');

        unset($params['return_url'], $params['notify_url']);

        // H5 支付 和 公众号支付
        if (in_array($gateway, [PayGatewayTypeEnum::WAP_PAY, PayGatewayTypeEnum::JSAPI_PAY])) {
            return $this->support->querySendRequest($url, array_merge($this->payload, $params));
        } else {
            foreach ($params as $key => $value) {
                if ($value && is_array($value)) {
                    $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                }
                $this->payload[$key] = $value;
            }
            var_dump($this->payload);
            return $this->support->jsonSendRequest($url, $this->payload);
        }
    }

    public function find($order, string $type)
    {
        // TODO: Implement find() method.
    }

    public function refund(array $order)
    {
        $params = [
            'requestTimestamp' => date('Y-m-d H:i:s'),
            'merOrderId'       => $order['merOrderId'] ?? '',
            'instMid'          => 'YUEDANDEFAULT',
            'mid'              => $this->payload['mid'],
            'tid'              => $this->payload['tid'],
            'refundAmount'     => $order['refundAmount'] ?? 0,
            'refundDesc'       => $order['refundDesc'] ?? '用户退款',
        ];

        // 可选参数
        if (isset($order['msgId'])) {
            $params['msgId'] = $order['msgId'];
        }
        if (isset($order['srcReserve'])) {
            $params['srcReserve'] = $order['srcReserve'];
        }
        if (isset($order['refundOrderId'])) {
            $params['refundOrderId'] = $order['refundOrderId'];
        }
        if (isset($order['platformAmount'])) {
            $params['platformAmount'] = $order['platformAmount'];
        }
        if (isset($order['subOrders'])) {
            $params['subOrders'] = json_encode($order['subOrders'], JSON_UNESCAPED_UNICODE);
        }
        return $this->support->jsonSendRequest(PayUnionMerEnum::REFUND_ORDER_URL, $params);
    }

    public function cancel($order)
    {
        // TODO: Implement cancel() method.
    }

    public function close($order)
    {
        $params = [
            'requestTimestamp'  => date('Y-m-d H:i:s'),
            'merOrderId'        => $order['merOrderId'] ?? '',
            'instMid'           => $order['instMid'] ?? '',
            'mid'               => $this->payload['mid'],
            'tid'               => $this->payload['tid'],
        ];
        if (isset($order['srcReserve'])) {
            $params['srcReserve'] = $order['srcReserve'];
        }
        return $this->support->jsonSendRequest(PayUnionMerEnum::CLOSE_ORDER_URL, $params);
    }

    public function verify($content)
    {
        // TODO: Implement verify() method.
    }
}
