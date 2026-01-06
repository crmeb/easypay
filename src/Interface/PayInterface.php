<?php

namespace Crmeb\Interface;

interface PayInterface
{
    /**
     * Pay an order.
     * @param $gateway
     * @param $params
     * @return mixed
     */
    public function pay($gateway, array $params = []);

    /**
     * Query an order.
     * @param string|array $order
     * @return array
     */
    public function find($order, string $type);

    /**
     * Refund an order.
     * @return array
     */
    public function refund(array $order);

    /**
     * Cancel an order.
     * @param string|array $order
     *
     * @return array
     */
    public function cancel($order);

    /**
     * Close an order.
     * @param string|array $order
     * @return array
     */
    public function close($order);

    /**
     * Verify a request.
     * @param array $content
     * @return array
     */
    public function verify(array $content);
}