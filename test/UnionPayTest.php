<?php

use PHPUnit\Framework\TestCase;
use Crmeb\Easypay\Exception\PayResponseException;

class UnionPayTest extends TestCase
{

    public function testRequest()
    {
        try {

            throw new PayResponseException('error', 0, null, ['code' => 500, 'message' => '错误']);
        } catch (PayResponseException $e) {
            var_dump($e->getResponse());
        }
    }
}