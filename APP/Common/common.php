<?php
    /**
     * @param $arr
     * 打印函数
     */
    function p($arr){
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }

	/**
	 * 设置全局过滤函数
	 */
	function filterAll(&$value){
		$value = htmlspecialchars($value);
	}

    /**
     * @param $new_config
     * @param string $config_file
     * @return bool
     */
	function update_config($new_config, $config_file = '') {
	
		!is_file($config_file) && $config_file = CONF_PATH . 'temp.php';
		if (is_writable($config_file)) {
			$config = require $config_file;
			$config = array_merge($config, $new_config);
			file_put_contents($config_file, "<?php \nreturn " . stripslashes(var_export($config, true)) . ";", LOCK_EX);
			@unlink(RUNTIME_FILE);
			return true;
		} else {
			return false;
		}
	}

    /**
     * 数字判断
     * @param $val
     * @return bool
     */
	function isNumber($val)
    {
            if(ereg("^[0-9]+$", $val))return true;
            return false;
     }
    function zhen(){
    	
		return true;
		
    }
    /**
     * 用户名判断
     * @param $val
     *
     * @return bool
     */
	function validate_user($val){
		if(ereg("^[a-zA-Z0-9]{4,20}$", $val))return true;
		return false;
	}
	
//判断手机型号
	
	function phonelist($str){
 	$phonead='';
 	$phone=array('vivo',
 	'HUAWEI',
 	'Coolpad',
 	'GT',
 	'HM',
 	'SM',
 	'ZTE',
 	'Haier',
 	'oppo',
 	'K-TOUCH',
 	'iPhone',
 	'iPad',
 	'MI',
 	'BlackBerry',
 	'LG',
 	'LENOVO',
 	'ChangHong',
 	'Windows Phone',
 	'Bird',
 	'BenQ',
 	'HTC',
 	'KTOUCH',
 	'SAMSUNG',
 	'Samsung',
 	'GIONEE',
 	'DOOV',
 	'KONKA',
 	'Dell',
 	'meizu',
 	'moto',
 	'Sony Ericsson',
 	'Lenovo');
	foreach($phone as $k=>$v){
	if(stripos($str,$v)){
	   $phonead=$v;
		break;
		
        }	
	}

switch (strtolower($phonead))
{
case 'iphone':
  return "iPhone";
  break;
case 'ipad':
  return "iPad";
  break;
case 'vivo':
  return "vivo";
  break;
case 'blackberry':
  return "黑莓手机";
  break;
case 'huawei':
  return "华为";
  break;
case 'coolpad':
  return "酷派";
  break;
case 'sm':
  return "三星";
  break;
 case 'samsung':
  return "三星";
  break;
case 'gt':
  return "三星Galaxy";
  break;
case 'hm':
  return "红米手机";
  break;
case 'mi':
  return "小米手机";
  break;
case 'zte':
  return "中兴手机";
  break;
case 'haier':
  return "海尔手机";
  break;
case 'oppo':
  return "Oppo手机";
  break;
case 'k-touch':
  return "天语手机";
  break;
case 'ktouch':
  return "天语手机";
  break;
case 'lg':
  return "LG手机";
  break;
case 'lenovo':
  return "联想手机";
  break;
case 'changhong':
  return "长虹手机";
  break;
case 'windows phone':
  return "诺基亚Windows Phone";
  break;
case 'bird':
  return "波导手机";
  break;
case 'benq':
  return "明基手机";
  break;
case 'htc':
  return "htc手机";
  break;
case 'gionee':
  return "金立手机";
  break;
 case 'doov':
  return "朵唯手机";
  break;
  case 'konka':
  return "康佳手机";
  break;
  case 'dell':
  return "戴尔手机";
  break;
   case 'meizu':
  return "魅族手机";
  break;
   case 'moto':
  return "摩托罗拉手机";
  break;
   case 'sony ericsson ':
  return "索尼手机";
  break;
default:
  return "未知类型";
}
	
} 
	/**
     * 地理位置获取
     * @param $val
     *
    
     */
	
 function getIp(){
     static $realip;
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }

//  $url="http://ip.taobao.com/service/getIpInfo.php?ip=".$realip;
//  $ip=json_decode(file_get_contents($url));
//
//  if((string)$ip->code=='1'){
//     return false;
//  }
//  $data = (array)$ip->data;
      return $realip;   
}
//时间转换函数 

   function Sec2Time($time){
    if(is_numeric($time)){
    $value = array(
    "days" => 0, "hours" => 0,
      "minutes" => 0, "seconds" => 0,
    );
   
    if($time >= 86400){
      $value["days"] = floor($time/86400);
      $time = ($time%86400);
    }
    if($time >= 3600){
      $value["hours"] = floor($time/3600);
      $time = ($time%3600);
    }
    if($time >= 60){
      $value["minutes"] = floor($time/60);
      $time = ($time%60);
    }
    $value["seconds"] = floor($time);
    //return (array) $value;
    $t= $value["days"] ."天"." ". $value["hours"] ."小时". $value["minutes"] ."分".$value["seconds"]."秒";
    Return $t;
    
     }else{
    return (bool) FALSE;
    }
 }
   
   //字节数转换成带单位的
/* 原理是利用对数求出欲转换的字节数是1024的几次方。
 * 其实就是利用对数的特性确定单位。
*/
function size2mb($size,$digits=2){ //digits，要保留几位小数
    $unit= array('','K','M','G','T','P');//单位数组，是必须1024进制依次的哦。
    $base= 1024;//对数的基数
    $i   = floor(log($size,$base));//字节数对1024取对数，值向下取整。
    return round($size/pow($base,$i),$digits).' '.$unit[$i] . 'B';
}


    /**
     * 密码验证
     * @param $val
     * @return bool
     */
	function validate_pwd($val){
		if(ereg("^[a-zA-Z0-9_]{4,20}$", $val))return true;
		return false;
	}

	/**
	 * 手机号码
	 */
	function isPhone($val){
		if (ereg("^1[1-9][0-9]{9}$",$val))return true;
		return false;
	}

	/**
	 * QQ号码
	 */
	function isQQ($val){
        if (ereg("^[1-9][0-9]{4,12}$",$val)) return true;
		return false;
	}

    /**
     * @param $val
     *
     * @return bool
     */
	function isSmsCode($val){
		if (ereg("^[0-9]{6}$",$val))return true;
		return false;
	}

    /**
     * 注入检测
     * @param $sql_str
     * @return int
     */
	function inject_check($sql_str) { 
		return eregi ( 'select|inert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|UNION|into|load_file|outfile', $sql_str );
	}

    /**
     * @param $s
     * url判断
     * @return bool
     */
	function isUrl($s)  {  
		return preg_match('/^http[s]?:\/\/'.  
		    '(([0-9]{1,3}\.){3}[0-9]{1,3}'. // IP形式的URL- 199.194.52.184  
		    '|'. // 允许IP和DOMAIN（域名）  
		    '([0-9a-z_!~*\'()-]+\.)*'. // 域名- www.  
		    '([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.'. // 二级域名  
		    '[a-z]{2,6})'.  // first level domain- .com or .museum  
		    '(:[0-9]{1,4})?'.  // 端口- :80  
		    '((\/\?)|'.  // a slash isn't required if there is no file name  
		    '(\/[0-9a-zA-Z_!~\'\(\)\[\]\.;\?:@&=\+\$,%#-\/^\*\|]*)?)$/',  
		    $s) == 1;  
	}  
	
	/**
	 * 获取广告位置
	 */
	function getAdPos($pos){
		switch ($pos){
			case 0:
				return  '认证首页';
				break;
			case 1:
				return  '认证页面';
				break;
            case 2:
                return  '前页广告';
                break;
			default:
				return  '首页';
				break;
		}
	}
	
	function getStatus($id){
		switch ($id){
			case 0:
				return  '停用';
				break;
			case 1:
				return  '正常';
				break;
			default:
				return  '正常';
				break;
		}
	}

	function getAdMode($mode){
		switch ($mode){
			case 0:
				return  '图片广告';
				break;
			case 1:
				return  '图文广告';
				break;
			default:
				return  '图片广告';
				break;
		}
	}

    /**
     * 支付模式
     * @param $id
     * @return string
     */
	function getPaymodel($id){
		switch ($id){
			case 0:
				return  '扣款';
				break;
			case 1:
				return  '充值';
				break;
		}
	}

    /**
     * 认证模式
     * @param $pos
     * @return string
     */
	function getAuthWay($pos){
		switch ($pos){
			case 0:
				return  '注册认证';
				break;
			case 1:
				return  '手机认证';
				break;
			case 2:
				return  '免认证';
				break;
			default:
				return  '注册认证';
				break;
		}
	}

    /**
     * 获取Agent
     * @return mixed
     */
	function getAgent(){
		 $agent = $_SERVER['HTTP_USER_AGENT'];
		 return $agent;
	}
	
	/**
	 * 获取系统信息
	 */
	function getOS ()
	{
        $agent = $_SERVER['HTTP_USER_AGENT'];
	        $os = false;
	    if (eregi('win', $agent) && strpos($agent, '95')){
	        $os = 'Windows 95';
	    }elseif (eregi('win 9x', $agent) && strpos($agent, '4.90')){
	        $os = 'Windows ME';
	    }elseif (eregi('win', $agent) && ereg('98', $agent)){
	        $os = 'Windows 98';
	    }elseif (eregi('win', $agent) && eregi('nt 5.1', $agent)){
	        $os = 'Windows XP';
	    }elseif (eregi('win', $agent) && eregi('nt 5.2', $agent)){    
	        $os = 'Windows 2003';
	    }elseif (eregi('win', $agent) && eregi('nt 5', $agent)){
	        $os = 'Windows 2000';
	    }elseif (eregi('win', $agent) && eregi('nt', $agent)){
	        $os = 'Windows NT';
	    }elseif (eregi('win', $agent) && ereg('32', $agent)){
	        $os = 'Windows 32';
	    }elseif (eregi('linux', $agent)){
	        $os = 'Linux';
	    }elseif (eregi('unix', $agent)){
	        $os = 'Unix';
	    }elseif (eregi('sun', $agent) && eregi('os', $agent)){
	        $os = 'SunOS';
	    }elseif (eregi('ibm', $agent) && eregi('os', $agent)){
	        $os = 'IBM OS/2';
	    }elseif (eregi('Mac', $agent) && eregi('PC', $agent)){
	        $os = 'Macintosh';
	    }elseif (eregi('PowerPC', $agent)){
	        $os = 'PowerPC';
	    }elseif (eregi('AIX', $agent)){
	        $os = 'AIX';
	    }elseif (eregi('HPUX', $agent)){
	        $os = 'HPUX';
	    }elseif (eregi('NetBSD', $agent)){
	        $os = 'NetBSD';
	    }elseif (eregi('BSD', $agent)){
	        $os = 'BSD';
	    }elseif (ereg('OSF1', $agent)){
	        $os = 'OSF1';
	    }elseif (ereg('IRIX', $agent)){
	        $os = 'IRIX';
	    }elseif (eregi('FreeBSD', $agent)){
	        $os = 'FreeBSD';
	    }elseif (eregi('teleport', $agent)){
	        $os = 'teleport';
	    }elseif (eregi('flashget', $agent)){
	        $os = 'flashget';
	    }elseif (eregi('webzip', $agent)){
	        $os = 'webzip';
	    }elseif (eregi('offline', $agent)){
	        $os = 'offline';
	    }else{
	        $os = 'Unknown';
	    }
	    return $os;
	}
	
	/**
	 * 获取浏览器信息
	 */
	function getUserBrowser(){
	    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Maxthon')) {
	        $browser = 'Maxthon';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 12.0')) {
	        $browser = 'IE12.0';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 11.0')) {
	        $browser = 'IE11.0';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 10.0')) {
	        $browser = 'IE10.0';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.0')) {
	        $browser = 'IE9.0';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0')) {
	        $browser = 'IE8.0';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')) {
	        $browser = 'IE7.0';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')) {
	        $browser = 'IE6.0';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'NetCaptor')) {
	        $browser = 'NetCaptor';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape')) {
	        $browser = 'Netscape';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Lynx')) {
	        $browser = 'Lynx';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')) {
	        $browser = 'Opera';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {
	        $browser = 'Chrome';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
	        $browser = 'Firefox';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')) {
	        $browser = 'Safari';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'iphone') || strpos($_SERVER['HTTP_USER_AGENT'], 'ipod')) {
	        $browser = 'iphone';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'ipad')) {
	        $browser = 'iphone';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'android')) {
	        $browser = 'android';
	    } else {
	        $browser = 'other';
	    }
    	return $browser;
	}
	
	function showauthcheck($val,$data){
		if(strpos($data,"#".$val."#")>-1){
			echo 'checked';
		}else{
			if(strpos($data,"#".$val."=")>-1){
				echo  'checked';
			}
		}
	}
	
	function echojsonkey($val,$key){
		$json=json_decode($val);
		switch($key){
			case "pwd":
				return $json->pwd;
				break;
			case "user":
				return $json->user;
			break;
				case "question":
				return $json->question;
				break;
				case "answer":
				return $json->answer;
				break;
				case "alert":
				return $json->alert;
				break;
		}
	}
	
	function showauthdata($val,$data){
		$tmp=explode('#', $data);
		foreach($tmp as $v){
			if($v!='#'&&$v!=''){
				$arr[]=$v;
			}
		}
		foreach($arr as $v){
			$temp=explode('=', $v);
			if(count($temp)>1&&$temp[0]==$val){
				//$dt=json_decode($temp[1]);
				return $temp[1];
				break;
			}
		}
	}
	
	/**
	    * 导出数据为excel表格
	    *@param $data    一个二维数组,结构如同从数据库查出来的数组
	    *@param $title   excel的第一行标题,一个数组,如果为空则没有标题
	    *@param $filename 下载的文件名
	    *@examlpe 
	    $stu = M ('User');
	    $arr = $stu -> select();
	    exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
	*/
	 function exportexcel($data=array(),$title=array(),$filename='report'){
	    header("Content-type:application/octet-stream");
	    header("Accept-Ranges:bytes");
	    header("Content-type:application/vnd.ms-excel");  
	    header("Content-Disposition:attachment;filename=".$filename.".xls");
	    header("Pragma: no-cache");
	    header("Expires: 0");
	    //导出xls 开始
	    if (!empty($title)){
	        foreach ($title as $k => $v) {
	            $title[$k]=iconv("UTF-8", "GB2312",$v);
	        }
	        $title= implode("\t", $title);
	        echo "$title\n";
	    }
	    if (!empty($data)){
	        foreach($data as $key=>$val){
	            foreach ($val as $ck => $cv) {
	                $data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
	            }
	            $data[$key]=implode("\t", $data[$key]);
	        }
	        echo implode("\n",$data);
	    }
	 }
	
	 /**
	  * 输出execl，配置标题和匹配的字段
	  */
    function exportexcelByKey($data=array(),$title=array(),$filename='report'){
	    
	  	header("Content-type:application/octet-stream");
	    header("Accept-Ranges:bytes");
	    header("Content-type:application/vnd.ms-excel");  
	    header("Content-Disposition:attachment;filename=".$filename.".xls");
	    header("Pragma: no-cache");
	    header("Expires: 0");
	    //导出xls 开始
	    if (!empty($title)){
	        foreach ($title as $k) {
	            //$excelhead[]=iconv("UTF-8", "GB2312",$k[0]);
	            $excelhead[]=iconv("UTF-8", "GB2312",$k[0]);
	            $field[]=$k[1];
	        }
	        $excelhead= implode("\t", $excelhead);
	        echo "$excelhead\n";
	    }
	    if (!empty($data)){
	        foreach($data as $obj){
	            	$Line=null;
	            	foreach ($field as $fv){
	            		$Line[]=iconv("UTF-8", "GB2312", $obj[$fv]);
	            		//$Line[]=$data[$key][$fv];
	            	}
	            	$Line= implode("\t", $Line);
	            	echo "$Line\n";
	        }
	    }
	 }
	 
	function smsstate($pos){
		 switch ($pos){
				case 0:
					return  '等待发送';
					break;
				case 1:
					return  '已发送';
					break;
				case 2:
					return  '发送失败,等待重发';
					break;
				default:
					return  '等待发送';
					break;
			}
	 }
	 
	function getCatelog($id){
		switch ($id){
			case 0:
				return  '图文';
				break;
			case 1:
				return  '链接';
				break;
			case 2:
				return  '电话';
				break;
			case 3:
				return  '地图导航';
				break;
			default:
				return  '图文';
				break;
		}
	}
	
	function getArtCate($list,$id){
		$rs="";
		foreach ($list as $k=>$v){
			if($v['id']==$id){
				$rs=$v['title'];
				break;
			}
		}
		return $rs;
	}

    function getNewsMode($id){
		switch ($id){
			case 1:
				return  '新闻中心';
				break;
			case 2:
				return  '产品动态';
				break;
		}
	}

    function showclass_common($vo,$cid){
		switch (strtolower($vo['mode'])){
			case "0":
				$html2.=U('Api/Wap/lists',array('classid'=>$vo['id'],'sid'=>$vo['uid'],'cid'=>$cid));
				return $html2;
				break;
			case "1":
				$html2=$vo['titleurl'];
				return $html2;
				break;
			case "2":
				$html2.="tel:".$vo['tel'];
				return $html2;
				break;
			case "3":
				$url="http://api.map.baidu.com/marker?location=".$vo['point_y'].",".$vo['point_x']."&title=".'福清'."&content="."福清市"."&output=html&src=weiyibai|weiyibai";
				return $url;
				break;
		}
	}
	
	function showmapurl($vo){

		return "http://api.map.baidu.com/marker?location=".$vo['point_y'].",".$vo['point_x']."&title=".urlencode($vo['shopname'])."&content=".urlencode($vo['address'])."&output=html&src=weiyibai|weiyibai";
	}
	
	function showmapurlshop($vo){
		
		return "http://api.map.baidu.com/marker?location=".$vo['point_y'].",".$vo['point_x']."&title=".urlencode($vo['shopname'])."&content=".urlencode($vo['shopaddress'])."&output=html&src=weiyibai|weiyibai";
	}

	/**
	 * 获取当前日期
	 */
	function getNowDate(){
		return  date("Y-m-d");
	}
	
	function getvsersion(){
		$data['auth']=getserdata();
		$data['ver']='5910416';
		return json_encode( $data);
	}

    function cutNewsInfo($info){
		$str=strip_tags($info);
		return substr($str, 0,20);
	}

    /**
     * 在线交易订单支付处理函数
     * @param $ordid
     * 根据支付接口传回的数据判断该订单是否已经支付成功；
     * 如果订单已经成功支付，返回true，否则返回false；
     * @return bool
     */
    function checkorderstatus($ordid){

        $Ord = M('orderlist');
        $ordstatus = $Ord->where(array('ordid'=>$ordid))->getField('ordstatus');
        if($ordstatus == 1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 处理订单函数
     * @param $parameter
     * 更新订单状态，写入订单支付后返回的数据
     */
    function orderhandle($parameter){
        $data['ordid'] = $parameter['out_trade_no'];
        $data['payment_trade_no']      = $parameter['trade_no'];
        $data['payment_trade_status']  = $parameter['trade_status'];
        $data['payment_notify_id']     = $parameter['notify_id'];
        $data['payment_notify_time']   = $parameter['notify_time'];
        $data['payment_buyer_email']   = $parameter['buyer_email'];
        $data['userid']                = $parameter['sign_id_ext'];
        $data['username']              = $parameter['sign_name_ext'];
        $data['ordstatus']             = 1;
        $Ord = M('orderlist');
        $Ord->add($data);
    }
