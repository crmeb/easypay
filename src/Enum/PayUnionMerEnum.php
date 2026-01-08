<?php

namespace Crmeb\Easypay\Enum;

class PayUnionMerEnum
{
    //  获取token
    const TOKEN_API_URL = '/v1/token/access';
    //   获取二维码
    const NATIVE_PAY_URL = '/v1/netpay/bills/get-qrcode';
    //   支付宝H5支付
    const ALIPAY_H5_PAY_URL = '/v1/netpay/trade/h5-pay';
    //  微信H5支付
    const WECHAT_H5_PAY_URL = '/v1/netpay/wxpay/h5-pay';

    //   支付宝APP支付
    const ALIPAY_APP_PAY_URL = '/v1/netpay/trade/precreate';
    //   微信APP支付
    const WECHAT_APP_PAY_URL = '/v1/netpay/wx/unified-order';

    //   微信小程序支付
    const MINI_PAY_URL = '/v1/netpay/wx/unified-order';

    //  微信JSAPI支付
    const WECHAT_JSAPI_PAY_URL = '/v1/netpay/wx/unified-order';

    //  支付类型
    const UNION_TYPE_ALIPAY = 'alipay';
    const UNION_TYPE_WECHAT = 'wechat';

    //  网关映射
    const GATEWAY_MAP = [
        PayGatewayTypeEnum::APP_PAY    => [
            self::UNION_TYPE_ALIPAY => self::ALIPAY_APP_PAY_URL,
            self::UNION_TYPE_WECHAT => self::WECHAT_APP_PAY_URL,
        ],
        PayGatewayTypeEnum::NATIVE_PAY => [
            self::UNION_TYPE_ALIPAY => self::NATIVE_PAY_URL,
            self::UNION_TYPE_WECHAT => self::NATIVE_PAY_URL,
        ],
        PayGatewayTypeEnum::WAP_PAY    => [
            self::UNION_TYPE_ALIPAY => self::ALIPAY_H5_PAY_URL,
            self::UNION_TYPE_WECHAT => self::WECHAT_H5_PAY_URL,
        ],
        PayGatewayTypeEnum::JSAPI_PAY  => [
            self::UNION_TYPE_WECHAT => self::WECHAT_JSAPI_PAY_URL,
        ],
        PayGatewayTypeEnum::MINI_PAY   => [
            self::UNION_TYPE_WECHAT => self::MINI_PAY_URL,
        ],
    ];
}