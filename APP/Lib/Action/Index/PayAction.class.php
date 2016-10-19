<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/17
 * Time: 10:07
 */
Class PayAction extends HomeAction{

    public function _initialize(){
        vendor('Alipay.Corefunction');
        vendor('Alipay.Md5function');
        vendor('Alipay.Notify');
        vendor('Alipay.Submit');
    }

	

    public function doalipay(){
    
        $alipay_config=C('alipay_config');

        $payment_type = "1"; //支付类型
        $notify_url   = C('alipay.notify_url'); //服务器异步通知页面路径
        $return_url   = C('alipay.return_url'); //页面跳转同步通知页面路径
        $seller_email = C('alipay.seller_email');//卖家支付宝帐户必填

        $out_trade_no = md5(time() . mt_rand(1,1000000));//商户订单号 通过支付页面的表单进行传递，注意要唯一！
        $subject = '神行通wifi营销管理短信充值';  //订单名称
        $total_fee = $_POST['jine'];   //付款金额
        $body = '神行通wifi营销管理短信费用';
        $show_url = "http://sxt.wifi01.cn/";  //商品展示地址 通过支付页面的表单进行传递
        $anti_phishing_key = "";//防钓鱼时间戳 //若要使用请调用类文件submit中的query_timestamp函数
        $exter_invoke_ip = get_client_ip(); //客户端的IP地址
//      $sign_id_ext = $_SESSION['uid'];
//      $sign_name_ext = $_SESSION['user'];
        /************************************************************/
        $orderlist=D('morderlist');
		$data['orderid']=$out_trade_no;
		$data['uid']=session('uid');
		$data['shopname']=session('shop_name');
		$data['agentname']=D('agent')->where(array('id'=>session('pid')))->getField('name');
		$data['user']=session('user');
		$data['zhonglei']=$_POST['zhonglei'];
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
                    //进行订单处理，并传送从支付宝返回的参数；
                    $orderlist=D('morderlist');
					$uid=$orderlist->where(array('orderid'=>$_POST['out_trade_no']))->getField('uid');
					$zhonglei=$orderlist->where(array('orderid'=>$_POST['out_trade_no']))->getField('zhonglei');
					$data['trade_no']=$_POST['trade_no'];
					$data['notify_time']=$_POST['notify_time'];
					$data['buyer_email']=$_POST['buyer_email'];
					$data['trade_status']=$_POST['trade_status'];
					$data['total_fee']=$_POST['total_fee'];
					if($zhonglei==0){
					switch($_POST['total_fee']){
						case 50:
						$shop=D('shop');
						$shop->where(array('id'=>$uid))->setInc('messnum',500);
						$data['message']='验证码短信充值50元,充值短信500条';
//						$data['qunfa']=C('');
//						$data['messnum']=$result[0]['messnumczh'];
						$data['qunfaczh']=C('qunfa');
						$data['messnumczh']=C('yanzhengma')-500;
						$arr['yanzhengma']=$data['messnumczh'];
					    break;
						case 270:
						$shop=D('shop');
						$shop->where(array('id'=>$uid))->setInc('messnum',3000);
						$data['message']='验证码短信充值270元，充值短信3000条';
//						$sql="select * from wifi_morderlist where qunfa is not null order by id desc limit 0,1";
//	 					$db=M();
//	 				    $result=$db->query($sql);
//						$data['qunfa']=$result[0]['qunfaczh'];
//						$data['messnum']=$result[0]['messnumczh'];
//						$data['qunfaczh']=$result[0]['qunfaczh']-3000;
//						$data['messnumczh']=$result[0]['messnumczh']-3000;
                        $data['qunfaczh']=C('qunfa');
						$data['messnumczh']=C('yanzhengma')-3000;
						$arr['yanzhengma']=$data['messnumczh'];
						break;
						case 560:
						$shop=D('shop');
						$shop->where(array('id'=>$uid))->setInc('messnum',7000);
						$data['message']='验证码短信充值560元，充值短信7000条';
//						$sql="select * from wifi_morderlist where qunfa is not null order by id desc limit 0,1";
//	 					$db=M();
//	 				    $result=$db->query($sql);
//						$data['qunfa']=$result[0]['qunfaczh'];
//						$data['messnum']=$result[0]['messnumczh'];
//						$data['qunfaczh']=$result[0]['qunfaczh']-7000;
//						$data['messnumczh']=$result[0]['messnumczh']-7000;
 						$data['qunfaczh']=C('qunfa');
						$data['messnumczh']=C('yanzhengma')-7000;
						$arr['yanzhengma']=$data['messnumczh'];
						break;
						case 1050:
						$shop=D('shop');
						$shop->where(array('id'=>$uid))->setInc('messnum',15000);
						$data['message']='验证码短信充值1050元，充值短信15000条';
//						$sql="select * from wifi_morderlist where qunfa is not null order by id desc limit 0,1";
//	 					$db=M();
//	 				    $result=$db->query($sql);
//						$data['qunfa']=$result[0]['qunfaczh'];
//						$data['messnum']=$result[0]['messnumczh'];
//						$data['qunfaczh']=$result[0]['qunfaczh']-15000;
//						$data['messnumczh']=$result[0]['messnumczh']-15000;
 						$data['qunfaczh']=C('qunfa');
						$data['messnumczh']=C('yanzhengma')-15000;
						$arr['yanzhengma']=$data['messnumczh'];
						break;
						
					}
					}else{
						switch($_POST['total_fee'])
						{
						case 50:
						$shop=D('shop');
						$shop->where(array('id'=>$uid))->setInc('qunfa',500);
						$data['message']='群发短信充值50元,充值短信500条';
//						$sql="select * from wifi_morderlist where qunfa is not null order by id desc limit 0,1";
//	 					$db=M();
//	 				    $result=$db->query($sql);
//						$data['qunfa']=$result[0]['qunfaczh'];
//						$data['messnum']=$result[0]['messnumczh'];
//						$data['qunfaczh']=$result[0]['qunfaczh']-500;
//						$data['messnumczh']=$result[0]['messnumczh']-500;
                        $data['qunfaczh']=C('qunfa')-500;
						$data['messnumczh']=C('yanzhengma');
						$arr['qunfa']=$data['qunfaczh'];
					    break;
						case 270:
						$shop=D('shop');
						$shop->where(array('id'=>$uid))->setInc('qunfa',3000);
						$data['message']='群发短信充值270元，充值短信3000条';
//						$sql="select * from wifi_morderlist where qunfa is not null order by id desc limit 0,1";
//	 					$db=M();
//	 				    $result=$db->query($sql);
//						$data['qunfa']=$result[0]['qunfaczh'];
//						$data['messnum']=$result[0]['messnumczh'];
//						$data['qunfaczh']=$result[0]['qunfaczh']-3000;
//						$data['messnumczh']=$result[0]['messnumczh']-300;
                        $data['qunfaczh']=C('qunfa')-3000;
						$data['messnumczh']=C('yanzhengma');
						$arr['qunfa']=$data['qunfaczh'];
						break;
						case 560:
						$shop=D('shop');
						$shop->where(array('id'=>$uid))->setInc('qunfa',7000);
						$data['message']='群发短信充值560元，充值短信7000条';
//						$sql="select * from wifi_morderlist where qunfa is not null order by id desc limit 0,1";
//	 					$db=M();
//	 				    $result=$db->query($sql);
//						$data['qunfa']=$result[0]['qunfaczh'];
//						$data['messnum']=$result[0]['messnumczh'];
//						$data['qunfaczh']=$result[0]['qunfaczh']-7000;
//						$data['messnumczh']=$result[0]['messnumczh']-7000;
                        $data['qunfaczh']=C('qunfa')-7000;
						$data['messnumczh']=C('yanzhengma');
						$arr['qunfa']=$data['qunfaczh'];
						break;
						case 1050:
						$shop=D('shop');
						$shop->where(array('id'=>$uid))->setInc('qunfa',15000);
						$data['message']='群发短信充值1050元，充值短信15000条';
//						$sql="select * from wifi_morderlist where qunfa is not null order by id desc limit 0,1";
//	 					$db=M();
//	 				    $result=$db->query($sql);
//						$data['qunfa']=$result[0]['qunfaczh'];
//						$data['messnum']=$result[0]['messnumczh'];
//						$data['qunfaczh']=$result[0]['qunfaczh']-15000;
//						$data['messnumczh']=$result[0]['messnumczh']-15000;
                        $data['qunfaczh']=C('qunfa')-15000;
						$data['messnumczh']=C('yanzhengma');
						$arr['qunfa']=$data['qunfaczh'];
						break;
						case 1800:
						$shop=D('shop');
						$shop->where(array('id'=>$uid))->setInc('qunfa',30000);
						$data['message']='群发短信充值1050元，充值短信15000条';
//						$sql="select * from wifi_morderlist where qunfa is not null order by id desc limit 0,1";
//	 					$db=M();
//	 				    $result=$db->query($sql);
//						$data['qunfa']=$result[0]['qunfaczh'];
//						$data['messnum']=$result[0]['messnumczh'];
//						$data['qunfaczh']=$result[0]['qunfaczh']-15000;
//						$data['messnumczh']=$result[0]['messnumczh']-15000;
                        $data['qunfaczh']=C('qunfa')-30000;
						$data['messnumczh']=C('yanzhengma');
						$arr['qunfa']=$data['qunfaczh'];
						break;
						}
					}
					$flag=$orderlist->data($data)->where(array('orderid'=>$_POST['out_trade_no']))->save();
					if($flag){
						update_config($arr,CONF_PATH."site.php");
					}
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
                $this->redirect(C('alipay.successpage'));//跳转到配置项中配置的支付成功页面；
            }else {
                echo "trade_status=".$_GET['trade_status'];
                $this->redirect(C('alipay.errorpage'));//跳转到配置项中配置的支付失败页面；
            }
        }else {
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            echo "支付失败！";
        }
    }
}