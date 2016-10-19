<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/17
 * Time: 10:07
 */
Class PaxAction extends HomeAction{

    public function _initialize(){
        vendor('Alipay.Corefunction');
        vendor('Alipay.Md5function');
        vendor('Alipay.Notify');
        vendor('Alipay.Submit');
    }

	

    public function doalipay(){
    
        $alipay_config=C('alipay_config');

        $payment_type = "1"; //支付类型
        $notify_url   = 'http://ap.wifi01.cn/index.php/Index/Pax/notifyurl'; //服务器异步通知页面路径
        $return_url   = 'http://ap.wifi01.cn/index.php/index/pax/returnurl'; //页面跳转同步通知页面路径
        $seller_email = C('alipay.seller_email');//卖家支付宝帐户必填

        $out_trade_no = md5(time() . mt_rand(1,1000000));//商户订单号 通过支付页面的表单进行传递，注意要唯一！
        $subject = '神行通VIP商户客户年费';  //订单名称
        $total_fee = $_POST['chongzhi'];   //付款金额
        $body = '神行通VIP商户客户年费';
        $show_url = "http://ap.wifi01.cn/";  //商品展示地址 通过支付页面的表单进行传递
        $anti_phishing_key = "";//防钓鱼时间戳 //若要使用请调用类文件submit中的query_timestamp函数
        $exter_invoke_ip = get_client_ip(); //客户端的IP地址
//      $sign_id_ext = $_SESSION['uid'];
//      $sign_name_ext = $_SESSION['user'];
        /************************************************************/
        $orderlist=D('shopvip');
		$data['orderid']=$out_trade_no;
		$data['uid']=session('uid');
		$data['shopname']=session('shop_name');
		$data['chongzhi']=$_POST['chongzhi'];
		$orderlist->data($data)->add();
    	 //构造要请求的参数数组，无需改动
        $parameter = array(
            "service"         => "create_direct_pay_by_user",
            "partner"         => trim($alipay_config['partner']),
            "payment_type"    => $payment_type,
            "notify_url"      => $notify_url,
            "return_url"      => $return_url,
            "seller_email"    => $seller_email,
            "out_trade_no"    => $out_trade_no,
            "subject"         => $subject,
            "total_fee"       => $total_fee,
            "body"            => $body,
            "show_url"        => $show_url,
//          "sign_id_ext"     => $sign_id_ext,
//          "sign_name_ext"   => $sign_name_ext,
            "anti_phishing_key"    => $anti_phishing_key,
            "exter_invoke_ip"      => $exter_invoke_ip,
            "_input_charset"       => trim(strtolower($alipay_config['input_charset']))
        );
        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
        echo $html_text;
    }

    function notifyurl(){
        $alipay_config = C('alipay_config');
        $alipayNotify  = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        $doc = new DOMDocument();
        $res = $doc->loadXML($verify_result);
        file_put_contents("pay.txt",$res,FILE_APPEND);

        if($verify_result) {
            //验证成功
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            $out_trade_no   = $_POST['out_trade_no'];      //商户订单号
            $trade_no       = $_POST['trade_no'];          //支付宝交易号
            $trade_status   = $_POST['trade_status'];      //交易状态
            $total_fee      = $_POST['total_fee'];         //交易金额
            $notify_id      = $_POST['notify_id'];         //通知校验ID。
            $notify_time    = $_POST['notify_time'];       //通知的发送时间。格式为yyyy-MM-dd HH:mm:ss。
            $buyer_email    = $_POST['buyer_email'];       //买家支付宝帐号；
//          $sign_id_ext    = $_POST['sign_id_ext'];
//          $sign_name_ext  = $_POST['sign_name_ext'];
            $parameter = array(
                "out_trade_no"      => $out_trade_no, //商户订单编号；
                "trade_no"          => $trade_no,     //支付宝交易号；
                "total_fee"         => $total_fee,    //交易金额；
                "trade_status"      => $trade_status, //交易状态
                "notify_id"         => $notify_id,    //通知校验ID。
                "notify_time"       => $notify_time,  //通知的发送时间。
                "buyer_email"       => $buyer_email,  //买家支付宝帐号；
//              "sign_id_ext"       => $sign_id_ext,
//              "sign_name_ext"     => $sign_name_ext,
            );

            if($_POST['trade_status'] == 'TRADE_FINISHED') {
                //
            }else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                if(!checkorderstatus($out_trade_no)){
                    orderhandle($parameter);

                    $orderlist=D('shopvip');
					$uid=$orderlist->where(array('orderid'=>$_POST['out_trade_no']))->getField('uid');
					$data['buyer_email']=$_POST['buyer_email'];
					$data['flag']=1;
					$orderlist->data($data)->where(array('orderid'=>$_POST['out_trade_no']))->save();
					$shop=D('shop');
					$datas['vip']=1;
					$datas['vipflag']=1;
					$datas['vip_expire']=strtotime('+1 year');
					$shop->where(array('id'=>$uid))->save($datas);
					}
				echo "success";
 			}
        }else {
            echo "fail";
        }
    }

    function returnurl(){
    	
	
        $alipay_config = C('alipay_config');
        $alipayNotify = new AlipayNotify($alipay_config);//计算得出通知验证结果
        $verify_result = $alipayNotify->verifyReturn();
		

        if($verify_result) {
////          //验证成功
////          //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
            $out_trade_no   = $_GET['out_trade_no'];      //商户订单号
            $trade_no       = $_GET['trade_no'];          //支付宝交易号
            $trade_status   = $_GET['trade_status'];      //交易状态
            $total_fee      = $_GET['total_fee'];         //交易金额
            $notify_id      = $_GET['notify_id'];         //通知校验ID。
            $notify_time    = $_GET['notify_time'];       //通知的发送时间。
            $buyer_email    = $_GET['buyer_email'];       //买家支付宝帐号；
//
            $parameter = array(
                "out_trade_no"     => $out_trade_no,      //商户订单编号；
                "trade_no"         => $trade_no,          //支付宝交易号；
                "total_fee"        => $total_fee,         //交易金额；
                "trade_status"     => $trade_status,      //交易状态
                "notify_id"        => $notify_id,         //通知校验ID。
                "notify_time"      => $notify_time,       //通知的发送时间
                "buyer_email"      => $buyer_email,       //买家支付宝帐号

            );
       
            if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
                if(!checkorderstatus($out_trade_no)){
                    orderhandle($parameter);
                    //进行订单处理，并传送从支付宝返回的参数
                }
               header('Location: http://ap.wifi01.cn/');//跳转到配置项中配置的支付成功页面；
            }else {
                echo "trade_status=".$_GET['trade_status'];
                $this->redirect('Login/index');//跳转到配置项中配置的支付失败页面；
            }
        }else {
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
//        
            echo "支付失败！";
        }

    }
}