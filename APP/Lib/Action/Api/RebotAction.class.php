<?php 
class RebotAction extends BaseApiAction{
  	
	//设备设置参数
  public function SetInfo(){
//	   $rebot=D('rebot');
       $requst=json_decode($_POST['Eq_info'],true);
// 		$data['product_name']=$requst['product_name'];
// 		$data['product_sw_ver']=$requst['product_sw_ver'];
// 		$data['product_mac']=$requst['product_mac'];
// 		$data['info']=$_REQUEST['Eq_info'];	
// 		$data['add_time']=time();	
// 	$rebot->data($data)->add();
	$Isrebot=D('routemap');
	$where['gw_id']=array('like','%'.$requst['product_mac'].'%');
	$info=$Isrebot->where($where)->find();
   if($info['manage']==1){
   	$baimingdan=C('Baimingdan');
    $manage=array();
	$manage['wlan_channel']=$info['wlan_channel'];
	$manage['wlan_txpower']=$info['wlan_txpower'];
	$manage['wlan_mode']=$info['wlan_mode'];
	
    $manage['vap']=array();
	$manage['vap'][0]['vap_id']='0';
	$manage['vap'][0]['vap_enable']=$info['vap_enable'];
	$manage['vap'][0]['vap_hide_ssid']=$info['vap_hide_ssid'];
	$manage['vap'][0]['vap_ssid']=urlencode($info['vap_ssid']);
	$manage['vap'][0]['vap_authmode']=$info['vap_authmode'];
	$manage['vap'][0]['vap_psk_key']=$info['vap_psk_key'];
	$manage['vap'][1]['vap_id']='1';
	$manage['vap'][1]['vap_enable']='0';
	$manage['vap'][1]['vap_hide_ssid']='1';
	$manage['vap'][1]['vap_ssid']='shenxingtong';
	$manage['vap'][1]['vap_authmode']='7';
	$manage['vap'][1]['vap_psk_key']='123456789';
	$manage['vap'][2]['vap_id']='2';
	$manage['vap'][2]['vap_enable']='0';
	$manage['vap'][2]['vap_hide_ssid']='1';
	$manage['vap'][2]['vap_ssid']='shenxingtong';
	$manage['vap'][2]['vap_authmode']='7';
	$manage['vap'][2]['vap_psk_key']='123456789';
	$manage['vap'][3]['vap_id']='3';
	$manage['vap'][3]['vap_enable']='0';
	$manage['vap'][3]['vap_hide_ssid']='1';
	$manage['vap'][3]['vap_ssid']='shenxingtong';
	$manage['vap'][3]['vap_authmode']='7';
	$manage['vap'][3]['vap_psk_key']='123456789';
	$manage['TimeRebootEnable']=strval($info['TimeRebootEnable']);
	$manage['TimeReboot_Time']=strval($info['TimeReboot_Time']);
	$manage['authEnable']=strval($info['authEnable']);
	$manage['authHost']=$info['rzserver'];
	$manage['authPort']='80';
	$manage['authHostPath']='/';
	$manage['LocalAuthPort']='2060';
	$manage['Now']=date("m").date("d").date("H").date("i").date("Y");
	$manage['TrustUrl']='itools.info,ibook.info,captive.apple.com,appleiphonecell.com,thinkdifferent.us,www.apple.com,short.weixin.qq.com,long.weixin.qq.com,szshort.weixin.qq.com,szlong.weixin.qq.com,'.trim($baimingdan,',').','.trim($info['TrustUrl'],',');
	
	$d=urldecode(str_replace ( "\/", "/", json_encode ( $manage ) ));
    echo $d;
	$where['gw_id']=array('like','%'.$requst['product_mac'].'%');
	$Isrebot->where($where)->setField('manage','0');
	}else{
		if($info['reboot']==1){
		$a=array('reboot'=>'1');
		$b=json_encode($a);
		echo $b;
		$where['gw_id']=array('like','%'.$requst['product_mac'].'%');
		$Isrebot->where($where)->setField('reboot','0');
	
		}
	else{
	$a=array('error'=>'DownEqInfo no info');
    $b=json_encode($a);
	echo $b;
	}
	}
}
public function ceshi(){
	$requst["product_mac"]='111111111111111111';
		$requst['product_sw_ver']='sxtg720-AP-V3.1-B20150729170514';
		preg_match('/.*(\d{3})\-(AP)\-.*/i', $requst['product_sw_ver'],$arrs);
		print_r($arrs);
		 $gujian=D('gujian');
		$wheres['xinghao']=array('like','%'.$arrs[1].'%');	
		$infos=$gujian->where($wheres)->find();
		preg_match('/.*?(\/upload\/gujian.*)/i', $infos['path'],$arr);
	
		
		$b='{"product_mac":"'.$requst["product_mac"].'","url":"/UpdateSw?gujian='.$arr[1].'"}';
		echo  $b;
		
	
		 $now=array('result'=>'1','Now'=>date("m").date("d").date("H").date("i").date("Y"));
    $b=json_encode($now);
	echo $b;
   

}



    public function UpdateSw(){
    	
    	$id=I('get.gujian');
		if(empty($id)){
	    $rebot=D('rebot');
       $requst=json_decode($_POST['Eq_info'],true);
// 		$data['product_name']=$requst['product_name'];
// 		$data['product_sw_ver']=$requst['product_sw_ver'];
// 		$data['product_mac']=$requst['product_mac'];
// 		$data['info']=$_REQUEST['Eq_info'];
// 		$data['add_time']=time();
//   	$rebot->data($data)->add();
		$route=D('routemap');
		$where['gw_id']=array('like','%'.$requst['product_mac'].'%');
		$info=$route->where($where)->find();
		if($info['shengji']==1){
		preg_match('/.*(\d{3})\-(AP)\-.*/i', $requst['product_sw_ver'],$arrs);
		 $gujian=D('gujian');
		$wheres['xinghao']=array('like','%'.$arrs[1].'%');	
		$infos=$gujian->where($wheres)->find();
		preg_match('/.*?(\/upload\/gujian.*)/i', $infos['path'],$arr);
		$wherep['gw_id']=array('like','%'.$requst['product_mac'].'%');
		$route->where($wherep)->setField('shengji','0');
		if(preg_match('/.*(sxtgac).*/i',$arr[1])){
		$b='{"mac":"'.$requst["product_mac"].'","url":"/UpdateSw?gujian='.$arr[1].'"}';
		}else{
		$b='{"product_mac":"'.$requst["product_mac"].'","url":"/UpdateSw?gujian='.$arr[1].'"}';
		}


		echo  $b;
			
		}
		else{
		$a=array('error'=>'DownEqInfo no info');
   	 	$b=json_encode($a);
		echo $b;
		}

	}
	else{
	
//header('location:http://sxt.wifi01.cn/upload/gujian/IP7620N-COULD-SPI-GW-2T2R-V1.4-Build20150615171441-ZHCN.ubin');
//$file=fopen("./AuthAction.class.php","rb+");
//
	header("Content-Transfer-Encoding: binary");
	header('content-type=application/octet-stream');
	header("Accept-Ranges:bytes");
	header("Content-Disposition:attachment;filename=SXTGAC300-AP-V2.1-Build20150917165503-ZHCN.ubin");
	readfile(".$id");

	}  
	
	}	
//设备更新信息
public function UpInfo(){
		$routeM=D('routemap');
       $requst=json_decode($_POST['Eq_info'],true);
       if($requst['gw_id']!=''&& isset($requst['gw_id'])&&$requst['gw_id']!=null){
       	if(preg_match('/SXTGAC/', $requst['product_sw_ver'])){
		foreach($requst['dev'] as $k=>$v){
		$requst['wlan_user_num']+=$v['wlan_user_num'];
   		 }
		}
		$where['gw_id']=array('like','%'.$requst['gw_id'].'%');
   		$manage=$routeM->where($where)->getField('manage');
		$arr['ontime']=   $requst['product_dev_runtime'];
		$arr['update_yuancheng']   =time();
		$arr['wlan_user_num']   =$requst['wlan_user_num'];
		$routeM->where($where)->save($arr);
		if($manage==0){
   		$datas['product_sw_ver']=$requst['product_sw_ver'];
   		$datas['vap_enable']=$requst['vap'][0]['vap_enable'];
		$datas['vap_ssid']=$requst['vap'][0]['vap_ssid'];
		$datas['vap_hide_ssid']=$requst['vap'][0]['vap_hide_ssid'];
		$datas['vap_authmode']=$requst['vap'][0]['vap_authmode'];
		$datas['vap_psk_key']=$requst['vap'][0]['vap_psk_key'];
		$where['gw_id']=array('like','%'.$requst['gw_id'].'%');
		$routeM->where($where)->save($datas);
   		}
      
   }	
   $now=array('result'=>'1','Now'=>date("m").date("d").date("H").date("i").date("Y"));
    $b=json_encode($now);
	echo $b;
   


	}
	}
	

?>