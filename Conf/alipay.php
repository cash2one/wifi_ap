<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/17
 * Time: 10:04
 */

return array(
    //支付宝配置参数
    'alipay_config'=>array(
        'partner' => '2088612815323502',
        'key'     => 'n4kimmfornfp27xn4o5asw0o7o5uursq',
        'sign_type'     => strtoupper('MD5'),
        'input_charset' => strtolower('utf-8'),
        'cacert'        => getcwd().'\\cacert.pem',
        'transport'     => 'http',
    ),

    'alipay'   =>array(
        //这里是卖家的支付宝账号，也就是你申请接口时注册的支付宝账号
        'seller_email'=>'zgsxt@139.com',

        //这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
        'notify_url'=>'http://ap.wifi01.cn/index.php/Pay/notifyurl',

        //这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
        'return_url'=>'http://ap.wifi01.cn/index.php/Pay/returnurl',

        //支付成功跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参payed（已支付列表）
        'successpage'=>'Login/index?ordtype=payed',

        //支付失败跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参unpay（未支付列表）
        'errorpage'=>'Login/index?ordtype=unpay',
    ),
);