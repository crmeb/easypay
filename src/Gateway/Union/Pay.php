<?php

namespace Crmeb\Gateway\Union;


use Crmeb\Gateway\AbstractPay;
use Crmeb\Interface\PayInterface;

/**
 * 银联支付
 */
class Pay extends AbstractPay implements PayInterface
{

    public function pay($gateway, array $params = [])
    {
        // TODO: Implement pay() method.
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

    public function verify($content, bool $refund)
    {
        // TODO: Implement verify() method.
    }

    public function success()
    {
        // TODO: Implement success() method.
    }
}