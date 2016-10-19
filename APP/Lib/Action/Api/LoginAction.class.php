<?php
class LoginAction extends BaseApiAction{

	private $gw_address = null;
	private $gw_port = null;
	private $gw_id = null;
	private $mac = null;
	private $url = null;
	private $logout = null;

    /**
     * ��֤��תҳ��
     */
	public function index(){

        if (isset($_REQUEST["gw_address"])) {
            $this->gw_address = $_REQUEST['gw_address'];
            cookie('gw_address', $_REQUEST['gw_address']);
        }

        if (isset($_REQUEST["gw_port"])) {
            $this->gw_port = $_REQUEST['gw_port'];
            cookie('gw_port', $_REQUEST['gw_port']);
        }

        if (isset($_REQUEST["gw_id"])) {
            $this->gw_id = $_REQUEST['gw_id'];
            cookie('gw_id', $_REQUEST['gw_id']);
        }

        if (isset($_REQUEST["url"])) {
            $this->url = $_REQUEST['url'];
            cookie('gw_url', $_REQUEST['url']);
        }

        if (isset($_REQUEST["mac"])) {
            cookie('mac', $_REQUEST['mac']);
          $this->$mac = $_REQUEST['mac'];
        }

        if (isset($_REQUEST["state"])) {
            cookie('state', 1);
           
        }else{
        	 cookie('state',0);
        }

        $sql = "select a.*,b.shopname,b.authmode,b.linker,b.phone,b.jifei,b.maxcount,b.linkflag,b.sh,b.eh,b.pid,b.countflag,b.countmax,b.tpl_path,b.focus,b.end,b.app from " . C('DB_PREFIX') . "routemap a left join " . C('DB_PREFIX') . "shop b on a.shopid=b.id ";
        $sql .= " where a.gw_id='" . $this->gw_id . "' limit 1";
        $db   = D('Routemap');
        $addb = D('Ad');
		D('Routemap')->where(array('gw_id'=>$this->gw_id))->setInc('renzheng_num');
        $info = $db->query($sql);
		$this->assign("shopinfo", $info);
        $nowdate = getNowDate();//��ǰ����
//   if(!isset($info[0]['area'])){
////   	
//	   $areadata=getCity();
//	   $area['area']=$areadata['country'].$areadata['area'].$areadata['region'].$areadata['city'];
//	   $area['ipout']=$areadata['ip'];
//	   $area['isp']=$areadata['isp'];
//	   $db->where(array('gw_id'=>cookie('gw_id')))->save($area);
//	  }
        if(cookie('state') == 0){
            $str = 1;
        }else{
            $str = 0;
        }
        if($str){
            //���λ������֤ǰҳ��
//            $ad = M('pushadv')->where(array('aid'=>1))->field('id,pic,aid')->limit(3)->select();
//            $this->assign('ad', $ad);
//            $this->display('pad');
            //��ӹ��
            $where['uid'] = $info[0]['shopid'];
            $where['ad_pos'] = 2;
            $ad = $addb->where($where)->field('id,ad_thumb,mode')->select();
            $ids = "";
            foreach ($ad as $k => $v) {
                $ids .= $v['id'] . ",";
            }
            $this->assign('ad', $ad);
            $this->assign('adid', $ids);
			if($info[0]['jifei']==1){
				
				$this->display('jifei');
			}else{
			
            $this->display('pad');
            }
        }else{
            if (!empty($this->gw_id)){
                if($info != false){
                    $show = 1;
                    $max = $info[0]['maxcount'];
                    $limit = $info[0]['linkflag'];
                    if ($limit == 0) {
                        $where['shopid'] = $info[0]['shopid'];
                        $count = D('member')->where($where)->count();
                        if ($count > $max) {
                            $show = 0;
                        }
                    }
                    cookie('shopid', $info[0]['shopid']);

                    $authmode = $info[0]['authmode'];
                    $where['uid'] = $info[0]['shopid'];
                    $where['ad_pos'] = 0;
                    $ad = $addb->where($where)->field('id,ad_thumb,mode')->order("ad_sort desc")->select();
                    $ids = "";
                    foreach ($ad as $k => $v) {
                        $ids .= $v['id'] . ",";
                    }
                    /*�ж��Ƿ�����������ʱ��*/
                    $hour = (int)date("H");
                    if (!empty($info[0]['sh']) && !empty($info[0]['eh']) && $info[0]['sh'] != "" && $info[0]['eh'] != "") {
                        $sh = (int)$info[0]['sh']; $eh = (int)$info[0]['eh'];
                        $auth['opensh'] = $sh; $auth['openeh'] = $eh;
                        $auth['openflag'] = true;//����ʱ��
                        if ($hour >= $sh && $hour <= $eh) {
                            $auth['open'] = true;
                        } else {
                            $auth['open'] = false;
                        }
                    } else {
                        $auth['open'] = true;
                        $auth['openflag'] = false;//δ����
                    }
                    /*��֤��ʽ*/
                    if ($authmode == null || $authmode == "") {
                        $auth['reg'] = 1;
                    } else {
                        $tmp = explode('#', $authmode);
                        foreach ($tmp as $v) {
                            if ($v != '#' && $v != '') {
                                $arr[] = $v;
                            }
                        }
                        foreach ($arr as $v) {
                            $temp = explode('=', $v);
                            if (count($temp) > 1) {
                            	if($temp[0]=='3'){
                            		$auth['wx'] = 1;
                            	}else{
                            		$auth['duanyu'] = 1;
                            	}
                                
                            } else {
                                if ($v == '0') {
                                    $auth['reg'] = 1;
                                }
                                if ($v == '1') {
                                    $auth['phone'] = 1;
                                }
                                if ($v == '2') {
                                    $auth['allow'] = 1;
                                }
                                if ($v == '4') {
                                    $auth['qq'] = 1;
                                }
								 if ($v == '5') {
                                    $auth['app'] = 1;
                                }
								
                            }
                        }
                    }

                    /*�ж��Ƿ�������֤����*/
                    if ($info[0]['countflag'] > 0) {
                        $maxcount = $info[0]['countmax'];
                        $authdb = D('Authlist');
                        $countwhere['mac'] = $mac;
                        $countwhere['shopid'] = $info[0]['shopid'];
                        $countwhere['add_date'] = $nowdate;
                        $auth_count = $authdb->where($countwhere)->count();

                        if (($maxcount - $auth_count) <= 0) {
                            $auth['overmax'] = 1;
                        } else {
                            $auth['overmax'] = 0;
                        }
                    } else {
                        $auth['overmax'] = 0;
                    }
                     $wifi=D('Agent');
					 $wifiphone=$wifi->where(array('id'=>$info[0]['pid']))->getField('wifiphone');
//				  $wheres['mode']=1;
//				  $wheres['messageflag']=1;
//				  $wheres['mac']=cookie('mac');
//				 $wheres['shopid']=$info[0]['shopid'];
//				if(D('authlist')->where($wheres)->select()){
//					$this->assign('move','1');
//					
//				}
		      
					$this->assign('wifiphone',$wifiphone);
                    $this->assign('ad', $ad);
					$this->assign('ads', $ad[0]['ad_thumb']);
                    $this->assign('adid', $ids);
                    $this->assign('show', $show);
					
                    $this->assign("authmode", $auth);
                    $this->assign("shopinfo", $info);
					$this->assign("url",$this->url);
					if($info[0]['app']!=''){
						$this->assign("app",1);
						
						
					}else{
						$this->assign("app",0);
					}
                     //ģ��ѡ��
                     if($info[0]['jifei']==1){
                     	if($info[0]['end']){
                     	if(strtotime($info[0]['end'])-time()>0){
                     		$this->display('jifei');
                     	}else{
                     		$this->display('daoqi');
                     	}
                     	}else{
						$this->display('jifei');
						}
                     }else{
                    $tplkey = $info[0]['tpl_path'];
                    switch($tplkey){
                        case "wifiadv":
                            $this->display('index$' . $tplkey);
                            break;
                        case "default3":
                            $this->display($tplkey);
                            break;
                        case "default4":
                            $this->display($tplkey);
                            break;
                        default:
                            $this->display();
                    	}
					 }
                }else{
                    $this->assign('gw_id',$_COOKIE['gw_id']);
                    $this->display("gw");
                }
            }else{
                echo '������ȷ2';
            }
        }
	}
public function app(){
	  $sql = "select b.apk_path,b.ipa_path,b.app_name from " . C('DB_PREFIX') . "routemap a left join " . C('DB_PREFIX') . "shop b on a.shopid=b.id ";
      $sql .= " where a.gw_id='" . cookie('gw_id') . "' limit 1";
      $db   = M();
	cookie('wancheng',0);
	  $info = $db->query($sql);
	   $this->assign('app_name',$info[0]['app_name']);
	  $this->assign('apk',$info[0]['apk_path']);
	  $this->assign('ipa',$info[0]['ipa_path']);
	  $this->display();
	 
	 
}
public function apk(){
	 $sql = "select b.apk_path,b.ipa_path,b.app_name from " . C('DB_PREFIX') . "routemap a left join " . C('DB_PREFIX') . "shop b on a.shopid=b.id ";
      $sql .= " where a.gw_id='" . cookie('gw_id') . "' limit 1";
      $db   = M();
	 $info = $db->query($sql);
	 cookie('sys','and');
	
	$file_path=".".$info[0]['apk_path'];
	$fp=fopen($file_path,"r"); 
	$file_size=filesize($file_path); 

header("Content-Transfer-Encoding: binary");
header("Content-type: application/vnd.android.package-archive"); 
header("Accept-Ranges: bytes"); 
header("Content-Length:$file_size"); 
header("Content-Disposition:attachment;filename=".$info[0]['app_name'].".apk");
$buffer=1024; 
$file_count=0; 
//向浏览器返回数据 
//while(!feof($fp) && $file_count<$file_size){ 
//$file_con=fread($fp,$buffer); 
//$file_count+=$buffer; 
//
//echo $file_con; 
//}
$file_con=fread($fp,$file_size); 
echo $file_con; 
fclose($fp); 
//if($file_count>=$file_size){
cookie('wancheng',1);
//}

}
public function ipa(){
	 $sql = "select b.apk_path,b.ipa_path,b.app_name from " . C('DB_PREFIX') . "routemap a left join " . C('DB_PREFIX') . "shop b on a.shopid=b.id ";
      $sql .= " where a.gw_id='" . cookie('gw_id') . "' limit 1";
      $db   = M();
	 $info = $db->query($sql);
	  cookie('sys','ios');
	 
	
	$file_path=".".$info[0]['ipa_path'];
	$fp=fopen($file_path,"r"); 
	$file_size=filesize($file_path); 
//下载文件需要用到的头 
header("Content-Transfer-Encoding: binary");
header("Content-type: application/octet-stream"); 
header("Accept-Ranges: bytes"); 
header("Content-Length:".$file_size); 
header("Content-Disposition:attachment;filename=".$info[0]['app_name'].".ipa");
$buffer=1024; 
$file_count=0; 
//向浏览器返回数据 
//while(!feof($fp) && $file_count<$file_size){ 
//$file_con=fread($fp,$buffer); 
//$file_count+=$buffer; 
//
//echo $file_con; 
//}
//
//if($file_count>=$file_size){
//cookie('wancheng',1);
$file_con=fread($fp,$file_size); 
echo $file_con; 
fclose($fp); 
//if($file_count>=$file_size){
cookie('wancheng',1);
//}
}
public function wancheng(){
	$biaozhi=cookie('wancheng');
	if($biaozhi==1){
		$app_name=I('get.name');
		$shop=D('shop');
		$info=$shop->where(array('app_name'=>$app_name))->find();
		$data['uid']=$info['id'];
		$data['pid']=$info['pid'];
		$data['time']=time();
		$data['appname']=$app_name;
		$data['sys']=cookie('sys');
		$app=D('app');
		$app->add($data);
		$data['error']=1;
		$data['msg']='完成';
		$this->ajaxReturn($data);
	}else{
		$data['error']=0;
		$data['msg']='未完成';
		$this->ajaxReturn($data);
	}
}


public function online(){

	$time=I('post.time');
	$ip=I('post.ip');
	$port=I('post.port');
	$token=I('post.token');
	$yanzheng=md5(md5("110|".$time."|lin|".$time."|SXT"));
	if($token==$yanzheng){
		$data['allow']=1;
		$data['url']="http://$ip:$port/wifidog/auth?token=SXT".$token;
		$json=stripslashes(json_encode($data));
		echo $json;
	}else{
		$data['allow']=0;
		$data['url']="";
		$json=json_encode($data);
		echo $json;
	}
	
}
public function offline(){
	$time=I('post.time');
	$ip=I('post.ip');
	$port=I('post.port');
	$token=I('post.token');
	$yanzheng=md5(md5("110|".$time."|lin|".$time."|SXT"));
	if($token==$yanzheng){
		$data['forb']=1;
		$data['url']="http://$ip:$port/wifidog/auth?token=FOR".$token;
		$json=stripslashes(json_encode($data));
		echo $json;
	}else{
		$data['forb']=0;
		$data['url']="";
		$json=json_encode($data);
		echo $json;
	}
	
}
    /**
     *
     */
	public function countad(){
		$gid=cookie('gw_id');
		$sid=cookie('shopid');
		if(empty($gid)||empty($sid)){			
			exit;
		}
		
		$ids=I('post.ids');
		$idarr=explode(',', $ids);
		/*ͳ��չʾ*/
        $tr=new Model();
        $time=time();
        $tr->startTrans();
        $arrdata['showup']=1;
        $arrdata['hit']=0;
        $arrdata['shopid']=$sid;
        $arrdata['add_time']=$time;
        $arrdata['add_date']=getNowDate();
        $arrdata['mode']=1;
        foreach($idarr as $v){
            if($v!=""){
                $arrdata['aid']=$v;
                $tr->table(C('DB_PREFIX')."adcount")->add($arrdata);
            }
        }
        $tr->commit();
	}
}