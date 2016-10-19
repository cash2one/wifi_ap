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
    $manage=array();
	$manage['wlan_channel']=$info['wlan_channel'];
	$manage['wlan_txpower']=$info['wlan_txpower'];
	$manage['wlan_mode']=$info['wlan_mode'];
	$manage['TimeRebootEnable']=0;
	$manage['TimeReboot_Time']=15;
	$manage['authEnable']=1;
	$manage['authHost']='ap.wifi01.cn';
	$manage['authPort']=80;
	$manage['authHostPath']='/';
	$manage['LocalAuthPort']=2060;
	$manage['TrustUrl']='itools.info,ibook.info,captive.apple.com,appleiphonecell.com,thinkdifferent.us,www.apple.com,short.weixin.qq.com,long.weixin.qq.com,szshort.weixin.qq.com,szlong.weixin.qq.com,'.$info['TrustUrl'];
	$manage['Now']=date("m").date("d").date("H").date("i").date("Y");;
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
	$manage['vap'][1]['vap_ssid']='林子饺子';
	$manage['vap'][1]['vap_authmode']='7';
	$manage['vap'][1]['vap_psk_key']='123456789';
	$manage['vap'][2]['vap_id']='2';
	$manage['vap'][2]['vap_enable']='0';
	$manage['vap'][2]['vap_hide_ssid']='1';
	$manage['vap'][2]['vap_ssid']='林子饺子';
	$manage['vap'][2]['vap_authmode']='7';
	$manage['vap'][2]['vap_psk_key']='123456789';
	$manage['vap'][3]['vap_id']='3';
	$manage['vap'][3]['vap_enable']='0';
	$manage['vap'][3]['vap_hide_ssid']='1';
	$manage['vap'][3]['vap_ssid']='林子饺子';
	$manage['vap'][3]['vap_authmode']='7';
	$manage['vap'][3]['vap_psk_key']='123456789';
	$d=urldecode(json_encode($manage));
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
		preg_match('/.*(G.*?)\-(SXT)\-.*/i', $requst['product_sw_ver'],$arrs);
		 $gujian=D('gujian');
		$wheres['xinghao']=array('like','%'.$arrs[1].'%');	
		$infos=$gujian->where($wheres)->find();
		preg_match('/.*?(\/upload\/gujian.*)/i', $infos['path'],$arr);
		$wherep['gw_id']=array('like','%'.$requst['product_mac'].'%');
		$route->where($wherep)->setField('shengji','0');
		$b='{"product_mac":"'.$requst["product_mac"].'","url":"/UpdateSw?gujian='.$arr[1].'"}';
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
	header("Content-Disposition:attachment;filename=IP7620N-COULD-SPI-GW-2T2R-V1.4-Build20150615171441-ZHCN.ubin");
	readfile(".$id");

	}  
	
	}	
//设备更新信息
public function UpInfo(){
	 	$rebot=D('rebot');
		$routeM=D('routemap');
		$online=D('online');
		$authlist=D('authlist');
       $requst=json_decode($_POST['Eq_info'],true);
// 		$data['product_name']=$requst['product_name'];
// 		$data['product_sw_ver']=$_POST['Eq_info'];
// 		$data['product_mac']=$requst['gw_id'];
// 		$data['info']=$requst['wlan_user_num'];
// 		$data['add_time']=time();	
// 		$rebot->data($data)->add();
		$where['gw_id']=array('like','%'.$requst['gw_id'].'%');
   		$manage=$routeM->where($where)->getField('manage');
		$routeM->where($where)->setField('ontime',$requst['product_dev_runtime']);
		$routeM->where($where)->setField('update_yuancheng',time());
		$routeM->where($where)->setField('wlan_user_num',$requst['wlan_user_num']);
		$online->where($where)->delete();
		foreach($requst['wlan_user'] as $k=>$v){
	            $about=$authlist->where(array('mac'=>strtolower($v['wlan_user_mac'])))->order('update_time desc')->limit(1)->field('incoming,outgoing,login_ip,login_time,agent')->select();
				$datat['xinghao']=$about[0]['agent'];
				$datat['incoming']=$about[0]['incoming'];
				$datat['outgoing']=$about[0]['outgoing'];
				$datat['login_ip']=$about[0]['login_ip'];
				$datat['login_time']=$about[0]['login_time'];
				$datat['gw_id']=$requst['gw_id'];
				$datat['wlan_user_mac']=$v['wlan_user_mac'];
				$datat['wlan_user_connect_time']=$v['wlan_user_connect_time'];
				$datat['wlan_user_signal']=$v['wlan_user_signal'];
			$online->data($datat)->add();		
		}
		
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
	
	
	}
	
	

?>