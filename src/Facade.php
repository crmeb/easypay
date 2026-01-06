<?php

namespace Crmeb\Easypay;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Facade
 * @package Crmeb\Easypay
 * @method UnionPay union(AbstractConfig $config = null)
 */
class Facade
{
    /**
     * @var array
     */
    private $register = [
        'union' => UnionPay::class,
    ];

    /**
     * 驱动
     * @var array
     */
    private $drivers = [];

    /**
     * 日志
     * @var LoggerInterface
     */
    private $logger;

    /**
     * 缓存
     * @var CacheInterface
     */
    private $cache;

    /**
     * 配置
     * @var AbstractConfig
     */
    private $config;

    /**
     * 自动注册
     * @return void
     */
    private function autoRegister(string $name)
    {
        if (isset($this->register[$name])) {
            $class = $this->register[$name];
            $this->drivers[$name] = new $class($this->logger, $this->cache, $this->config);
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Unable to resolve [%s] driver [%s].', static::class, $name
            ));
        }

        return $this->drivers[$name];
    }

    /**
     * 注册日志
     * @param LoggerInterface $logger
     * @return void
     */
    public function registerLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * 注册缓存
     * @param CacheInterface $cache
     * @return void
     */
    public function registerCache(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * 注册配置
     * @param AbstractConfig $config
     * @return void
     */
    public function registerConfig(AbstractConfig $config)
    {
        $this->config = $config;
    }

    /**
     * 动态调用
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return $this->driver($method);
    }

    /**
     * 静态调用
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        return (new static())->driver($method);
    }

    /**
     * 获取驱动实例
     * @param string $name
     * @return mixed
     */
    protected function driver(string $name)
    {
        if (!$name) {
            throw new \InvalidArgumentException(sprintf(
                'Unable to resolve NULL driver for [%s].', static::class
            ));
        }

        return $this->createDriver($name);
    }

    /**
     * 创建驱动
     *
     * @param string $name
     * @return mixed
     *
     */
    protected function createDriver(string $name)
    {
        if (!isset($this->drivers[$name])) {
            $this->autoRegister($name);
        }

        return $this->drivers[$name];
    }
}