<?php

namespace Crmeb\Easypay;

/**
 * 银联商务支付配置
 * Class UnionMerConfig
 * @package Crmeb\Easypay
 * @property string appid
 * @property string appKey
 * @property string mchId
 * @property string tid
 * @method string getAppid()
 * @method string getAppKey()
 * @method string getMchId()
 * @method string getTid()
 * @method UnionMerConfig setAppid(string $appid)
 * @method UnionMerConfig setAppKey(string $appKey)
 * @method UnionMerConfig setMchId(string $mchId)
 * @method UnionMerConfig setTid(string $tid)
 */
class UnionMerConfig extends CommonConfig
{
    /**
     * 正式环境
     * @var string
     */
    protected $baseUrl = 'https://api-mop.chinaums.com';

    /**
     * 测试环境
     * @var string
     */
    protected $devBaseUrl = 'https://test-api-open.chinaums.com';

    /**
     * 是否测试环境
     * @var bool
     */
    public $isDev = false;

    /**
     * @var string[]
     */
    protected $rule = [
        'appid',
        'app_key',
        'mch_id',
        'tid'
    ];
}