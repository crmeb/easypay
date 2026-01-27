<?php

use Crmeb\Easypay\Facade;
use Crmeb\Easypay\Config\UnionMerConfig;
use PHPUnit\Framework\TestCase;


class UnionMerServiceTest extends TestCase
{

    public function testScan()
    {
        date_default_timezone_set('Asia/Shanghai');

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

//        $res = $facade->unionmer($config)->scan('uni4545784351222442252462', '0.01', '测试商品支付');
//        $res = $facade->unionmer($config)->h5Pay('wxssdd2233445566', '0.01', '测试商品支付');
//        $res = $facade->unionmer($config)->miniPay('345Xuni123345sssaaas45664446', '0.01', 'wx3b82801238ca1b57', 'o9qvr4ni8lBUJT8ySiSDxsidRuoE','测试商品支付');
//        $res = $facade->unionmer($config)->jsapiPay('uni45457845122244545664446', '0.01', 'wxa815e4f2ef7bdb0b', 'oQsPz6Ese-XE63PRAeSjggUbtjlU','测试商品支付');
        $res = $facade->unionmer($config)->close('wxssdd2233445566', 'h5');
        var_dump($res);
    }
}
