<?php

use Crmeb\Easypay\Facade;
use Crmeb\Easypay\Config\UnionMerConfig;
use PHPUnit\Framework\TestCase;


class UnionMerServiceTest extends TestCase
{

    public function testScan()
    {
        $facade = new Facade();

        $redisConfig = new \Crmeb\Easypay\Config\RedisConfig([
            'host'     => '127.0.0.1',
            'port'     => '6379',
            'password' => '',
            'select'   => 1
        ]);

        $facade->registerCache(new \Crmeb\Easypay\Cache\RedisCache($redisConfig->toArray()));
        $facade->registerLogger(new \Crmeb\Easypay\Log\FileLogger(dirname(__DIR__) . '/logs'));

        $config = new UnionMerConfig([
            'appId'  => '',
            'appKey' => '',
            'mchId'  => '',
            'tid'    => ''
        ]);


        $res = $facade->unionmer($config)->scan('uni45457845122244545664445', '0.01', 'unionmer', '测试商品支付');

        var_dump($res);
    }
}