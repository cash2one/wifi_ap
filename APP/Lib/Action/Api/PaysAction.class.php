<?php
class PaysAction extends BaseApiAction{

	    public function _initialize(){
        vendor('Alipay.Corefunction');
        vendor('Alipay.Md5function');
        vendor('Alipay.Notify');
        vendor('Alipay.Submit');
    	}
	private function isLogin(){
			if(!session('vipid')||session('vipid')==null||session('vipid')==''){
			$this->redirect('api/login/jifei');
			}
			}
	
	 public function doalipay(){
	 	$this->isLogin();
	   	$alipay_config=C('alipayuser_config');
		 $vip=D('Vipmember');
		 $result=$vip->where(array('id'=>$_POST['userid']))->find();
        $shop=D('shop');
		$shopinfo=$shop->where(array('id'=>$result['uid']))->find();
     

        $payment_type = "1"; //支付类型
        $notify_url   = C('alipayuser.notify_url'); //服务器异步通知页面路径
        $return_url   = C('alipayuser.return_url'); //页面跳转同步通知页面路径
        $seller_email = C('alipayuser.seller_email');//卖家支付宝帐户必填

        $out_trade_no = md5(time() . mt_rand(1,1000000));//商户订单号 通过支付页面的表单进行传递，注意要唯一！
        $subject = '神行通上网计费系统充值';  //订单名称
         $orderlist=D('zfbvip');
        if($_POST['type']==1){
        $total_fee = $_POST['baoshi']*$shopinfo['hour']; 
		$data['num']=$_POST['baoshi'];	
			  //付款金额
		}else{
		$total_fee = $_POST['baoyue']*$shopinfo['month']; 
		$data['num']=$_POST['baoyue'];	
			
		}
      	 $body = '神行通上网计费系统充值';
        $show_url = "http://ap.wifi01.cn/";  //商品展示地址 通过支付页面的表单进行传递
        $anti_phishing_key = "";//防钓鱼时间戳 //若要使用请调用类文件submit中的query_timestamp函数
        $exter_invoke_ip = get_client_ip(); //客户端的IP地址
		$data['orderid']=$out_trade_no;
		$data['uid']=$shopinfo['id'];
		$data['vipid']=$_POST['userid'];
		$data['account']=$result['username'];
		$data['type']=$_POST['type'];
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
			"anti_phishing_key"    => $anti_phishing_key,
            "exter_invoke_ip"      => $exter_invoke_ip,
            "_input_charset"       => trim(strtolower($alipay_config['input_charset']))
        );
        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
        echo $html_text;
    }
	public function ceshi(){
		
		 $vip=D('Vipmember');
		 $result=$vip->where(array('id'=>$_POST['userid']))->find();
        $shop=D('shop');
		$shopinfo=$shop->where(array('id'=>$result['uid']))->find();
		print_r($result);
		print_r($shopinfo);
		print_r($_POST);
		
		
		
	}
    
	 function notifyurl(){
        $alipay_config = C('alipayuser_config');
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
                    //进行订单处理，并传送从支付宝返回的参数；
                    $orderlist=D('zfbvip');
					$userinfo=$orderlist->where(array('orderid'=>$_POST['out_trade_no']))->find();
					$data['trade_no']=$_POST['trade_no'];
					$data['notify_time']=strtotime($_POST['notify_time']);
					$data['buyer_email']=$_POST['buyer_email'];
					$data['total_fee']=$_POST['total_fee'];
					$vipuser=D('vipmember');
					$vipinfo=$vipuser->where(array('id'=>$userinfo['vipid']))->find();	
					if($userinfo['type']==1){
						$datas['last_time']=$vipinfo['on_time']+$userinfo['num']*3600+$vipinfo['last_time'];
						$datas['laston_time']=$vipinfo['laston_time']+$userinfo['num']*3600;
						$datas['type']=1;	
						$vipuser->where(array('id'=>$userinfo['vipid']))->save($datas);
					}else{
						$now=time();
						if($now>$vipinfo['expire_time']){
							$time=strtotime("+{$userinfo['num']} month");
							$vipuser->where(array('id'=>$userinfo['vipid']))->setField('expire_time',$time);
							$vipuser->where(array('id'=>$userinfo['vipid']))->setField('type',2);		
						}else{
							$time=strtotime("+{$userinfo['num']} month",$vipinfo['expire_time']);
							$vipuser->where(array('id'=>$userinfo['vipid']))->setField('expire_time',$time);
							$vipuser->where(array('id'=>$userinfo['vipid']))->setField('type',2);	
						}
						
					}
					$orderlist->data($data)->where(array('orderid'=>$_POST['out_trade_no']))->save();
					}
				echo "success";
 				}
 				}
        		else {
            	echo "fail";
        		}
    	
    }
    function returnurl(){
        $alipay_config = C('alipayuser_config');
        $alipayNotify = new AlipayNotify($alipay_config);//计算得出通知验证结果
        $verify_result = $alipayNotify->verifyReturn();
//      file_put_contents("pay.txt",$verify_result,FILE_APPEND);
        if($verify_result) {
            //验证成功
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
            $out_trade_no   = $_GET['out_trade_no'];      //商户订单号
            $trade_no       = $_GET['trade_no'];          //支付宝交易号
            $trade_status   = $_GET['trade_status'];      //交易状态
            $total_fee      = $_GET['total_fee'];         //交易金额
            $notify_id      = $_GET['notify_id'];         //通知校验ID。
            $notify_time    = $_GET['notify_time'];       //通知的发送时间。
            $buyer_email    = $_GET['buyer_email'];       //买家支付宝帐号；
//          $sign_id_ext    = $_GET['sign_id_ext'];
//          $sign_name_ext  = $_GET['sign_name_ext'];

            $parameter = array(
                "out_trade_no"     => $out_trade_no,      //商户订单编号；
                "trade_no"         => $trade_no,          //支付宝交易号；
                "total_fee"        => $total_fee,         //交易金额；
                "trade_status"     => $trade_status,      //交易状态
                "notify_id"        => $notify_id,         //通知校验ID。
                "notify_time"      => $notify_time,       //通知的发送时间
                "buyer_email"      => $buyer_email,       //买家支付宝帐号
//              "sign_id_ext"       => $sign_id_ext,
//              "sign_name_ext"     => $sign_name_ext,
            );
//          file_put_contents("pay1.txt",$_GET,FILE_APPEND);
            if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
                if(!checkorderstatus($out_trade_no)){
                    orderhandle($parameter);
                    //进行订单处理，并传送从支付宝返回的参数
                }
                $this->redirect(C('alipayuser.successpage'));//跳转到配置项中配置的支付成功页面；
            }else {
                echo "trade_status=".$_GET['trade_status'];
                $this->redirect(C('alipayuser.errorpage'));//跳转到配置项中配置的支付失败页面；
            }
        }else {
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            echo "支付失败！";
        }
    }
	
	
	
	
	
}