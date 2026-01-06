<?php

namespace Crmeb\Enum;

/**
 * 支付网关枚举
 * Class PayGatewayEnum
 * @package Crmeb\Enum
 */
class PayGatewayEnum
{
    // APP支付
    const APP_PAY = 'app';
    // APP支付接口
    const APP_PAY_URL = 'alipay.trade.app.pay';

    // 扫码支付
    const NATIVE_PAY = 'scan';
    // 扫码支付接口
    const NATIVE_PAY_URL = 'alipay.trade.precreate';

    // H5支付
    const WAP_PAY = 'wap';
    // H5支付接口
    const WAP_PAY_URL = 'alipay.trade.wap.pay';

    // 公众号支付
    const JSAPI_PAY = 'jsapi';
    // 公众号支付接口
    const JSAPI_PAY_URL = 'alipay.trade.create';

    // 单笔转账接口
    const TRANSFER_PAY = 'transfer';
    // 单笔转账接口
    const TRANSFER_PAY_URL = 'alipay.fund.trans.uni.transfer';

    // 网页支付
    const PAGE_PAY = 'page';
    // 网页支付接口
    const PAGE_PAY_URL = 'alipay.trade.page.pay';

    // 商家扣款
    const POS_PAY = 'pos';
    // 商家扣款接口
    const POS_PAY_URL = 'alipay.trade.pay';

    // 退款接口
    const REFUND_QUERY_URL = 'alipay.trade.fastpay.refund.query';
    // 订单查询接口
    const ORDER_URL = 'alipay.trade.query';
    // 退款接口
    const REFUND_URL = 'alipay.trade.refund';

    // 网关映射
    const GATEWAY_MAP = [
        self::APP_PAY      => self::APP_PAY_URL,
        self::NATIVE_PAY   => self::NATIVE_PAY_URL,
        self::WAP_PAY      => self::WAP_PAY_URL,
        self::JSAPI_PAY    => self::JSAPI_PAY_URL,
        self::TRANSFER_PAY => self::TRANSFER_PAY_URL,
        self::PAGE_PAY     => self::PAGE_PAY_URL,
        self::POS_PAY      => self::POS_PAY_URL,
    ];
}