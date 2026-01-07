<?php

namespace Crmeb\Gateway;

use Crmeb\Easypay\UnionMerConfig;
use Crmeb\Gateway\UnionMer\Pay;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

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
}