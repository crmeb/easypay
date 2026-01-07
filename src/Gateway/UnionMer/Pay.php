<?php

namespace Crmeb\Gateway\UnionMer;


use Crmeb\Gateway\AbstractPay;
use Crmeb\Interface\PayInterface;

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
     * 初始化
     * @return void
     */
    public function init()
    {
        $this->support = new Support($this);

        $this->baseUri = $this->config->getBaseUri();
    }

    public function pay($gateway, array $params = [])
    {
        
    }

    public function find($order, string $type)
    {
        // TODO: Implement find() method.
    }

    public function refund(array $order)
    {
        // TODO: Implement refund() method.
    }

    public function cancel($order)
    {
        // TODO: Implement cancel() method.
    }

    public function close($order)
    {
        // TODO: Implement close() method.
    }

    public function verify($content)
    {
        // TODO: Implement verify() method.
    }
}