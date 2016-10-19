<?php
class WepayAction extends BaseApiAction{
private function isLogin(){
			if(!session('vipid')||session('vipid')==null||session('vipid')==''){
			$this->redirect('api/login/jifei');
			}
			}
	
	public function index(){
		 $vip=D('Vipmember');
		 $result=$vip->where(array('id'=>session('vipid')))->find();
		 $shop=D('shop');
		 $shopinfo=$shop->where(array('id'=>$result['uid']))->find();
		 if(($shopinfo['APPID']!='')&&($shopinfo['MECHID']!='')&&($shopinfo['APPSECRET']!='')&&($shopinfo['WEKEY']!='')){
		 	$datas['zhichi']=1;
		 }else{
		 	
		 	$datas['zhichi']=0;
		 }
		if(isset($_POST['baoyue'])){
			$total_fee=floatval($_POST['baoyue'])*$shopinfo['month']*100;
			$date=time();
			$num=$_POST['baoyue'];
			$datas['type']=2;
			$datas['num']=$num;
			$datas['uid']=$result['uid'];
			$datas['total_fee']=$total_fee;
			$datas['vipid']=session('vipid');
			$datas['account']=$result['username'];
			if($date>$result['expire_time']){
			   
				$expire_time=date('Y-m-d H:i:s',strtotime("+$num months"));
				
			}else{
				
				$expire_time=date('Y-m-d H:i:s',strtotime("+$num months",$result['expire_time']));
				
			}
			
		}else{
			$total_fee=floatval($_POST['baoshi'])*$shopinfo['hour']*100;
			$num=$_POST['baoshi'];
			$datas['type']=1;
			$datas['num']=$num;
			$datas['uid']=$result['uid'];
			$datas['total_fee']=$total_fee;
			$datas['vipid']=session('vipid');
			$datas['account']=$result['username'];
			$expire_time=$result['laston_time']+$_POST['baoshi']*3600;
			
			
		}
		$datas['trade_no']=session('vipid').'sxt'.time();
		$datas['buyer_email']='Unpay';
	 	$zfbvip=D('zfbvip');
	 if($num!=null){
	 	session('trade_no',$datas['trade_no']);
		session('total_fee',$datas['total_fee']);
	 	$zfbvip->add($datas);
	 }
	 
	
//		$this->assign('num',$num);
//		$this->assign('info',$result);
//		$this->assign('expire_time',$expire_time);
		
		

		
	
		
//		

//		
//		  
//      //使用jsapi接口
        $jsApi = new JsApi_pub();
		
        
        //=========步骤1：网页授权获取用户openid============
        //通过code获得openid
        if (!isset($_GET['code']))
        {
            //触发微信返回code码
            $url = $jsApi->createOauthUrlForCode('http://ap.wifi01.cn/index.php/api/wepay/index');
			
            Header("Location: $url");
        }else
        {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $jsApi->setCode($code);
            $openid = $jsApi->getOpenId();
        }
     
        //=========步骤2：使用统一支付接口，获取prepay_id============
        //使用统一支付接口
        $unifiedOrder = new UnifiedOrder_pub();
        
        //设置统一支付接口参数
        //设置必填参数
        //appid已填,商户无需重复填写
        //mch_id已填,商户无需重复填写
        //noncestr已填,商户无需重复填写
        //spbill_create_ip已填,商户无需重复填写
        //sign已填,商户无需重复填写
        $unifiedOrder->setParameter("openid",$openid);//商品描述
        $unifiedOrder->setParameter("body","网费充值");//商品描述
        //自定义订单号，此处仅作举例
        $timeStamp = time();
		
        $out_trade_no = session('trade_no');
		
        $unifiedOrder->setParameter("out_trade_no",$out_trade_no);//商户订单号
        $unifiedOrder->setParameter("total_fee",session('total_fee'));//总金额
        $unifiedOrder->setParameter("notify_url",'http://ap.wifi01.cn/index.php/api/wepay/notify');//通知地址
        $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
        //非必填参数，商户可根据实际情况选填
        //$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
        //$unifiedOrder->setParameter("device_info","XXXX");//设备号
        //$unifiedOrder->setParameter("attach","XXXX");//附加数据
        //$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
        //$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
        //$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
        //$unifiedOrder->setParameter("openid","XXXX");//用户标识
        //$unifiedOrder->setParameter("product_id","XXXX");//商品ID
        
        $prepay_id = $unifiedOrder->getPrepayId();
		
        //=========步骤3：使用jsapi调起支付============
        $jsApi->setPrepayId($prepay_id);
        
        $jsApiParameters = $jsApi->getParameters();
        
        $this->assign('jsApiParameters',$jsApiParameters);
		$zfbvip->add($data);
		 
			$this->display();
		}
	
	 public function notify()
    {
	   
        //使用通用通知接口
        $notify = new Notify_pub();
        
        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
         $notify->saveData($xml);
       
       
        
        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
        echo $returnXml;
        
        //==商户根据实际情况设置相应的处理流程，此处仅作举例=======
        
        //以log文件形式记录回调信息
   //         $log_ = new Log_();
       
        
        if($notify->checkSign() == true)
        {
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                log_result($log_name,"【通信出错】:\n".$xml."\n");
            }
            elseif($notify->data["result_code"] == "FAIL"){
                //此处应该更新一下订单状态，商户自行增删操作
                log_result($log_name,"【业务出错】:\n".$xml."\n");
            }
            else{
                $zfbvip=D('zfbvip');
				$info=$zfbvip->where(array('trade_no'=>$notify->data['out_trade_no']))->find();
				$num=$info['num'];
				$vip=D('Vipmember');
				$result=$vip->where(array('id'=>$info['vipid']))->find();
				if($info['type']==2){
					$date=time();
					
					if($date>$result['expire_time']){
			   
					$expire_time=strtotime("+$num months");
				
					}else{
				
					$expire_time=strtotime("+$num months",$result['expire_time']);
				
					}
					$vip->where(array('id'=>$info['vipid']))->setField('expire_time',$expire_time);
					
				}else{
					$time=$num*3600+$result['laston_time'];
					
					$vip->where(array('id'=>$info['vipid']))->setField('laston_time',$time);
				}
				
				$zfbvip->where(array('trade_no'=>$notify->data['out_trade_no']))->setField('buyer_email','Payed');
				$zfbvip->where(array('trade_no'=>$notify->data['out_trade_no']))->setField('notify_time',time());
				
				
				
				
				
				
				
				
				
				
				
				
				
				
               
            }
        
            //商户自行增加处理流程,
            //例如：更新订单状态
            //例如：数据库操作
            //例如：推送支付完成信息
        }
    }

public function select(){
	 	$vip=D('Vipmember');
		 $result=$vip->where(array('id'=>session('vipid')))->find();
		 session('shopid',$result['uid']);
		 $shop=D('shop');
		 $shopinfo=$shop->where(array('id'=>$result['uid']))->find();
	
		 $this->assign('shopinfo',$shopinfo);
		 $this->assign('info',$result);
	
$this->display();
}





















































	
	 
}


class  SDKRuntimeException extends Exception {
	public function errorMessage()
	{
		return $this->getMessage();
	}

}

class Common_util_pub
{
	var $APPID;
	var $MECHID;
	var $KEY;
	var $APPSECRET;
	
	function __construct() {
		$shopid=$_SESSION['shopid'];
	 	$con=mysql_connect('localhost',"root","xuezhehan1121");
		$db=mysql_select_db("wifiadv",$con);
		$row=mysql_query("select APPID,MECHID,WEKEY,APPSECRET from wifi_shop where id=$shopid");
		$reslist = array();
		$i=0;
		while($rows = mysql_fetch_assoc($row)){
  		 $reslist[$i] = $rows;
  		 $i++;
  }
  if(($reslist[0]['APPID']!='')&&($reslist[0]['MECHID']!='')&&($reslist[0]['APPSECRET']!='')&&($reslist[0]['WEKEY']!='')){
		 	$this->APPID=$reslist[0]['APPID'];
			$this->MECHID="$reslist[0]['MECHID']";
			$this->KEY=$reslist[0]['WEKEY'];
			$this->APPSECRET=$reslist[0]['APPSECRET'];
		 }else{
		 $this->APPID='wx8257b51b83960e0a';
		$this->MECHID='1313099701';
		$this->KEY='chenchen1121taotao1209chenzhiyun';
		$this->APPSECRET='3ec278db9e49e6732b412a7d2589b8fa';
		 	
		 }
//		$this->APPID=$reslist[0]['APPID'];
//		$this->MECHID=$reslist[0]['MECHID'];
//		$this->KEY=$reslist[0]['WEKEY'];
//		$this->APPSECRET=$reslist[0]['APPSECRET'];
	mysql_close($con);
		
	}
	
	function trimString($value)
	{
		$ret = null;
		if (null != $value) 
		{
			$ret = $value;
			if (strlen($ret) == 0) 
			{
				$ret = null;
			}
		}
		return $ret;
	}
	
	/**
	 * 	作用：产生随机字符串，不长于32位
	 */
	public function createNoncestr( $length = 32 ) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		}  
		return $str;
	}
	
	/**
	 * 	作用：格式化参数，签名过程需要使用
	 */
	function formatBizQueryParaMap($paraMap, $urlencode)
	{
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
		    if($urlencode)
		    {
			   $v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0) 
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	
	/**
	 * 	作用：生成签名
	 */
	public function getSign($Obj)
	{
		foreach ($Obj as $k => $v)
		{
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
		$String = $this->formatBizQueryParaMap($Parameters, false);
		//echo '【string1】'.$String.'</br>';
		//签名步骤二：在string后加入KEY
		$String = $String."&key=".$this->KEY;
		//echo "【string2】".$String."</br>";
		//签名步骤三：MD5加密
		$String = md5($String);
		//echo "【string3】 ".$String."</br>";
		//签名步骤四：所有字符转为大写
		$result_ = strtoupper($String);
		//echo "【result】 ".$result_."</br>";
		return $result_;
	}
	
	/**
	 * 	作用：array转xml
	 */
	function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
        	 if (is_numeric($val))
        	 {
        	 	$xml.="<".$key.">".$val."</".$key.">"; 

        	 }
        	 else
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
        }
        $xml.="</xml>";
        return $xml; 
    }
	
	/**
	 * 	作用：将xml转为array
	 */
	public function xmlToArray($xml)
	{		
        //将XML转为array        
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
		return $array_data;
	}

	/**
	 * 	作用：以post方式提交xml到对应的接口url
	 */
	public function postXmlCurl($xml,$url,$second=30)
	{		
        //初始化curl        
       	$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//post提交方式
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		//运行curl
        $data = curl_exec($ch);
		curl_close($ch);
		//返回结果
		if($data)
		{
			curl_close($ch);
			return $data;
		}
		else 
		{ 
			$error = curl_errno($ch);
			echo "curl出错，错误码:$error"."<br>"; 
			echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
			curl_close($ch);
			return false;
		}
	}

	/**
	 * 	作用：使用证书，以post方式提交xml到对应的接口url
	 */
//	function postXmlSSLCurl($xml,$url,$second=30)
//	{
//		$ch = curl_init();
//		//超时时间
//		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
//		//这里设置代理，如果有的话
//      //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
//      //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
//      curl_setopt($ch,CURLOPT_URL, $url);
//      curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
//      curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
//		//设置header
//		curl_setopt($ch,CURLOPT_HEADER,FALSE);
//		//要求结果为字符串且输出到屏幕上
//		curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
//		//设置证书
//		//使用证书：cert 与 key 分别属于两个.pem文件
//		//默认格式为PEM，可以注释
//		curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
//		curl_setopt($ch,CURLOPT_SSLCERT, WxPayConf_pub::SSLCERT_PATH);
//		//默认格式为PEM，可以注释
//		curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
//		curl_setopt($ch,CURLOPT_SSLKEY, WxPayConf_pub::SSLKEY_PATH);
//		//post提交方式
//		curl_setopt($ch,CURLOPT_POST, true);
//		curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
//		$data = curl_exec($ch);
//		//返回结果
//		if($data){
//			curl_close($ch);
//			return $data;
//		}
//		else { 
//			$error = curl_errno($ch);
//			echo "curl出错，错误码:$error"."<br>"; 
//			echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
//			curl_close($ch);
//			return false;
//		}
//	}
	
	/**
	 * 	作用：打印数组
	 */
	function printErr($wording='',$err='')
	{
		print_r('<pre>');
		echo $wording."</br>";
		var_dump($err);
		print_r('</pre>');
	}
}

/**
   * 请求型接口的基类
   */
class Wxpay_client_pub extends Common_util_pub 
{
	var $parameters;//请求参数，类型为关联数组
	public $response;//微信返回的响应
	public $result;//返回参数，类型为关联数组
	var $url;//接口链接
	var $curl_timeout;//curl超时时间
	
	/**
	 * 	作用：设置请求参数
	 */
	function setParameter($parameter, $parameterValue)
	{
		$this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
	}
	
	/**
	 * 	作用：设置标配的请求参数，生成签名，生成接口参数xml
	 */
	function createXml()
	{
	   	$this->parameters["appid"] = $this->APPID;//公众账号ID
	   	$this->parameters["mch_id"] = $this->MECHID;//商户号
	    $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
	    $this->parameters["sign"] = $this->getSign($this->parameters);//签名
	    return  $this->arrayToXml($this->parameters);
	}
	
	/**
	 * 	作用：post请求xml
	 */
	function postXml()
	{
	    $xml = $this->createXml();
		
		$this->response = $this->postXmlCurl($xml,$this->url,$this->curl_timeout);
		return $this->response;
	}
	
	/**
	 * 	作用：使用证书post请求xml
	 */
	function postXmlSSL()
	{	
	    $xml = $this->createXml();
		$this->response = $this->postXmlSSLCurl($xml,$this->url,$this->curl_timeout);
		return $this->response;
	}

	/**
	 * 	作用：获取结果，默认不使用证书
	 */
	function getResult() 
	{		
		$this->postXml();
		$this->result = $this->xmlToArray($this->response);
		return $this->result;
	}
}


/**
   * 统一支付接口类
   */
class UnifiedOrder_pub extends Wxpay_client_pub
{	
	function __construct() 
	{
		parent::__construct();
		//设置接口链接
		$this->url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
		//设置curl超时时间
		$this->curl_timeout = 30;
	}
	
	/**
	 * 生成接口参数xml
	 */
	function createXml()
	{
		try
		{
			//检测必填参数
			if($this->parameters["out_trade_no"] == null) 
			{
				throw new SDKRuntimeException("缺少统一支付接口必填参数out_trade_no！"."<br>");
			}elseif($this->parameters["body"] == null){
				throw new SDKRuntimeException("缺少统一支付接口必填参数body！"."<br>");
			}elseif ($this->parameters["total_fee"] == null ) {
				throw new SDKRuntimeException("缺少统一支付接口必填参数total_fee！"."<br>");
			}elseif ($this->parameters["notify_url"] == null) {
				throw new SDKRuntimeException("缺少统一支付接口必填参数notify_url！"."<br>");
			}elseif ($this->parameters["trade_type"] == null) {
				throw new SDKRuntimeException("缺少统一支付接口必填参数trade_type！"."<br>");
			}elseif ($this->parameters["trade_type"] == "JSAPI" &&
				$this->parameters["openid"] == NULL){
				throw new SDKRuntimeException("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！"."<br>");
			}
		   	$this->parameters["appid"] = $this->APPID;//公众账号ID
		   	$this->parameters["mch_id"] = $this->MECHID;//商户号
		   	$this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR'];//终端ip	    
		    $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
		    $this->parameters["sign"] = $this->getSign($this->parameters);//签名
		    return  $this->arrayToXml($this->parameters);
		}catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}
	}
	
	/**
	 * 获取prepay_id
	 */
	function getPrepayId()
	{
		
		  
		$this->postXml();
		
		$this->result = $this->xmlToArray($this->response);
		
		$prepay_id = $this->result["prepay_id"];
		return $prepay_id;

	}
	
}
///**
// * 订单查询接口
// */
//class OrderQuery_pub extends Wxpay_client_pub
//{
//	function __construct() 
//	{
//		//设置接口链接
//		$this->url = "https://api.mch.weixin.qq.com/pay/orderquery";
//		//设置curl超时时间
//		$this->curl_timeout = 30;		
//	}
//
//	/**
//	 * 生成接口参数xml
//	 */
//	function createXml()
//	{
//		try
//		{
//			//检测必填参数
//			if($this->parameters["out_trade_no"] == null && 
//				$this->parameters["transaction_id"] == null) 
//			{
//				throw new SDKRuntimeException("订单查询接口中，out_trade_no、transaction_id至少填一个！"."<br>");
//			}
//		   	$this->parameters["appid"] = $this->APPID;//公众账号ID
//		   	$this->parameters["mch_id"] = $this->MECHID;//商户号
//		    $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
//		    $this->parameters["sign"] = $this->getSign($this->parameters);//签名
//		    return  $this->arrayToXml($this->parameters);
//		}catch (SDKRuntimeException $e)
//		{
//			die($e->errorMessage());
//		}
//	}
//
//}
//
///**
// * 退款申请接口
// */
//class Refund_pub extends Wxpay_client_pub
//{
//	
//	function __construct() {
//		//设置接口链接
//		$this->url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
//		//设置curl超时时间
//		$this->curl_timeout = 30;		
//	}
//	
//	/**
//	 * 生成接口参数xml
//	 */
//	function createXml()
//	{
//		try
//		{
//			//检测必填参数
//			if($this->parameters["out_trade_no"] == null && $this->parameters["transaction_id"] == null) {
//				throw new SDKRuntimeException("退款申请接口中，out_trade_no、transaction_id至少填一个！"."<br>");
//			}elseif($this->parameters["out_refund_no"] == null){
//				throw new SDKRuntimeException("退款申请接口中，缺少必填参数out_refund_no！"."<br>");
//			}elseif($this->parameters["total_fee"] == null){
//				throw new SDKRuntimeException("退款申请接口中，缺少必填参数total_fee！"."<br>");
//			}elseif($this->parameters["refund_fee"] == null){
//				throw new SDKRuntimeException("退款申请接口中，缺少必填参数refund_fee！"."<br>");
//			}elseif($this->parameters["op_user_id"] == null){
//				throw new SDKRuntimeException("退款申请接口中，缺少必填参数op_user_id！"."<br>");
//			}
//		   	$this->parameters["appid"] = $this->APPID;//公众账号ID
//		   	$this->parameters["mch_id"] = $this->MECHID;//商户号
//		    $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
//		    $this->parameters["sign"] = $this->getSign($this->parameters);//签名
//		    return  $this->arrayToXml($this->parameters);
//		}catch (SDKRuntimeException $e)
//		{
//			die($e->errorMessage());
//		}
//	}
//	/**
//	 * 	作用：获取结果，使用证书通信
//	 */
//	function getResult() 
//	{		
//		$this->postXmlSSL();
//		$this->result = $this->xmlToArray($this->response);
//		return $this->result;
//	}
//	
//}
//
//
///**
// * 退款查询接口
// */
//class RefundQuery_pub extends Wxpay_client_pub
//{
//	
//	function __construct() {
//		//设置接口链接
//		$this->url = "https://api.mch.weixin.qq.com/pay/refundquery";
//		//设置curl超时时间
//		$this->curl_timeout = 30;		
//	}
//	
//	/**
//	 * 生成接口参数xml
//	 */
//	function createXml()
//	{		
//		try 
//		{
//			if($this->parameters["out_refund_no"] == null &&
//				$this->parameters["out_trade_no"] == null &&
//				$this->parameters["transaction_id"] == null &&
//				$this->parameters["refund_id "] == null) 
//			{
//				throw new SDKRuntimeException("退款查询接口中，out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个！"."<br>");
//			}
//		   	$this->parameters["appid"] = $this->APPID;//公众账号ID
//		   	$this->parameters["mch_id"] = $this->MECHID;//商户号
//		    $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
//		    $this->parameters["sign"] = $this->getSign($this->parameters);//签名
//		    return  $this->arrayToXml($this->parameters);
//		}catch (SDKRuntimeException $e)
//		{
//			die($e->errorMessage());
//		}
//	}
//
//	/**
//	 * 	作用：获取结果，使用证书通信
//	 */
//	function getResult() 
//	{		
//		$this->postXmlSSL();
//		$this->result = $this->xmlToArray($this->response);
//		return $this->result;
//	}
//
//}
//
///**
// * 对账单接口
// */
//class DownloadBill_pub extends Wxpay_client_pub
//{
//
//	function __construct() 
//	{
//		//设置接口链接
//		$this->url = "https://api.mch.weixin.qq.com/pay/downloadbill";
//		//设置curl超时时间
//		$this->curl_timeout = 30;		
//	}
//
//	/**
//	 * 生成接口参数xml
//	 */
//	function createXml()
//	{		
//		try 
//		{
//			if($this->parameters["bill_date"] == null ) 
//			{
//				throw new SDKRuntimeException("对账单接口中，缺少必填参数bill_date！"."<br>");
//			}
//		   	$this->parameters["appid"] = $this->APPID;//公众账号ID
//		   	$this->parameters["mch_id"] = $this->MECHID;//商户号
//		    $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
//		    $this->parameters["sign"] = $this->getSign($this->parameters);//签名
//		    return  $this->arrayToXml($this->parameters);
//		}catch (SDKRuntimeException $e)
//		{
//			die($e->errorMessage());
//		}
//	}
//	
//	/**
//	 * 	作用：获取结果，默认不使用证书
//	 */
//	function getResult() 
//	{		
//		$this->postXml();
//		$this->result = $this->xmlToArray($this->result_xml);
//		return $this->result;
//	}
//	
//	
//
//}
//
///**
// * 短链接转换接口
// */
class ShortUrl_pub extends Wxpay_client_pub
{
	function __construct() 
	{
		parent::__construct();
		//设置接口链接
		$this->url = "https://api.mch.weixin.qq.com/tools/shorturl";
		//设置curl超时时间
		$this->curl_timeout = 30;		
	}
	
	/**
	 * 生成接口参数xml
	 */
	function createXml()
	{		
		try 
		{
			if($this->parameters["long_url"] == null ) 
			{
				throw new SDKRuntimeException("短链接转换接口中，缺少必填参数long_url！"."<br>");
			}
		   	$this->parameters["appid"] = $this->APPID;//公众账号ID
		   	$this->parameters["mch_id"] = $this->MECHID;//商户号
		    $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
		    $this->parameters["sign"] = $this->getSign($this->parameters);//签名
		    return  $this->arrayToXml($this->parameters);
		}catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}
	}
	
	/**
	 * 获取prepay_id
	 */
	function getShortUrl()
	{
		$this->postXml();
		$prepay_id = $this->result["short_url"];
		return $prepay_id;
	}
	
}

/**
   * 响应型接口基类
   */
class Wxpay_server_pub extends Common_util_pub 
{
	public $data;//接收到的数据，类型为关联数组
	var $returnParameters;//返回参数，类型为关联数组
	
	/**
	 * 将微信的请求xml转换成关联数组，以方便数据处理
	 */
	function saveData($xml)
	{
		$this->data = $this->xmlToArray($xml);
	}
	
	function checkSign()
	{
		$tmpData = $this->data;
		unset($tmpData['sign']);
		$sign = $this->getSign($tmpData);//本地签名
		if ($this->data['sign'] == $sign) {
			return true;
		}
		return FALSE;
	}
	
	/**
	 * 获取微信的请求数据
	 */
	function getData()
	{		
		return $this->data;
	}
	
	/**
	 * 设置返回微信的xml数据
	 */
	function setReturnParameter($parameter, $parameterValue)
	{
		$this->returnParameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
	}
	
	/**
	 * 生成接口参数xml
	 */
	function createXml()
	{
		return $this->arrayToXml($this->returnParameters);
	}
	
	/**
	 * 将xml数据返回微信
	 */
	function returnXml()
	{
		$returnXml = $this->createXml();
		return $returnXml;
	}
}
//
/**
   * 通用通知接口
   */
class Notify_pub extends Wxpay_server_pub 
{

}
//
//
//
//
/**
   * 请求商家获取商品信息接口
   */
class NativeCall_pub extends Wxpay_server_pub
{
	/**
	 * 生成接口参数xml
	 */
	function createXml()
	{
		if($this->returnParameters["return_code"] == "SUCCESS"){
		   	$this->returnParameters["appid"] = $this->APPID;//公众账号ID
		   	$this->returnParameters["mch_id"] = $this->MECHID;//商户号
		    $this->returnParameters["nonce_str"] = $this->createNoncestr();//随机字符串
		    $this->returnParameters["sign"] = $this->getSign($this->returnParameters);//签名
		}
		return $this->arrayToXml($this->returnParameters);
	}
	
	/**
	 * 获取product_id
	 */
	function getProductId()
	{
		$product_id = $this->data["product_id"];
		return $product_id;
	}
	
}
//
///**
// * 静态链接二维码
// */
//class NativeLink_pub  extends Common_util_pub
//{
//	var $parameters;//静态链接参数
//	var $url;//静态链接
//
//	function __construct() 
//	{
//	}
//	
//	/**
//	 * 设置参数
//	 */
//	function setParameter($parameter, $parameterValue) 
//	{
//		$this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
//	}
//	
//	/**
//	 * 生成Native支付链接二维码
//	 */
//	function createLink()
//	{
//		try 
//		{		
//			if($this->parameters["product_id"] == null) 
//			{
//				throw new SDKRuntimeException("缺少Native支付二维码链接必填参数product_id！"."<br>");
//			}			
//		   	$this->parameters["appid"] = $this->APPID//公众账号ID
//		   	$this->parameters["mch_id"] = $this->MECHID;//商户号
//		   	$time_stamp = time();
//		   	$this->parameters["time_stamp"] = "$time_stamp";//时间戳
//		    $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
//		    $this->parameters["sign"] = $this->getSign($this->parameters);//签名    		
//			$bizString = $this->formatBizQueryParaMap($this->parameters, false);
//		    $this->url = "weixin://wxpay/bizpayurl?".$bizString;
//		}catch (SDKRuntimeException $e)
//		{
//			die($e->errorMessage());
//		}
//	}
//	
//	/**
//	 * 返回链接
//	 */
//	function getUrl() 
//	{		
//		$this->createLink();
//		return $this->url;
//	}
//}
//
///**
//* JSAPI支付——H5网页端调起支付接口
//*/
class JsApi_pub extends Common_util_pub
{
	var $code;//code码，用以获取openid
	var $openid;//用户的openid
	var $parameters;//jsapi参数，格式为json
	var $prepay_id;//使用统一支付接口得到的预支付id
	var $curl_timeout;//curl超时时间

	function __construct() 
	{
		parent::__construct();
		//设置curl超时时间
		$this->curl_timeout = 30;
	}
	
	/**
	 * 	作用：生成可以获得code的url
	 */
	
	function createOauthUrlForCode($redirectUrl)
	{
		$urlObj["appid"] = $this->APPID;
		$urlObj["redirect_uri"] = "$redirectUrl";
		$urlObj["response_type"] = "code";
		$urlObj["scope"] = "snsapi_base";
		$urlObj["state"] = "STATE"."#wechat_redirect";
		$bizString = $this->formatBizQueryParaMap($urlObj, false);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
	}

	/**
	 * 	作用：生成可以获得openid的url
	 */
	function createOauthUrlForOpenid()
	{
		$urlObj["appid"] = $this->APPID;
		$urlObj["secret"] = $this->APPSECRET;
		$urlObj["code"] = $this->code;
		$urlObj["grant_type"] = "authorization_code";
		$bizString = $this->formatBizQueryParaMap($urlObj, false);
		return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
	}
	
	
	/**
	 * 	作用：通过curl向微信提交code，以获取openid
	 */
	function getOpenid()
	{
		$url = $this->createOauthUrlForOpenid();
        //初始化curl
       	$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOP_TIMEOUT, $this->curl_timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//运行curl，结果以jason形式返回
        $res = curl_exec($ch);
		curl_close($ch);
		//取出openid
		$data = json_decode($res,true);
		$this->openid = $data['openid'];
		return $this->openid;
	}

	/**
	 * 	作用：设置prepay_id
	 */
	function setPrepayId($prepayId)
	{
		$this->prepay_id = $prepayId;
	}

	/**
	 * 	作用：设置code
	 */
	function setCode($code_)
	{
		$this->code = $code_;
	}

	/**
	 * 	作用：设置jsapi的参数
	 */
	public function getParameters()
	{
		$jsApiObj["appId"] = $this->APPID;
		$timeStamp = time();
	    $jsApiObj["timeStamp"] = "$timeStamp";
	    $jsApiObj["nonceStr"] = $this->createNoncestr();
		$jsApiObj["package"] = "prepay_id=$this->prepay_id";
	    $jsApiObj["signType"] = "MD5";
	    $jsApiObj["paySign"] = $this->getSign($jsApiObj);
	    $this->parameters = json_encode($jsApiObj);
		
		return $this->parameters;
	}
}




  
  
  




