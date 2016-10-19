<?php
class UserAction extends HomeAction{
    
    public function __construct(){
        parent::__construct();
        header("Content-type:text/html;charset=utf-8");
    }

    /**
     * 商户信息
     */
    public function info(){
//    	$this->isLogin();
        $uid = session('uid');
        $info = D('Shop')->where("id = {$uid}")
                ->field('shopname,province,city,area,address,shopgroup,shoplevel,trade,linker,phone,jumpurl,start,end')
                ->find();
               
        include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('enumdata',$enumdata);
		if($info['end']){
		$date=strtotime($info['end']);
		if($date-time()<3600*7){
			$this->assign('daoqi',1);
		}
		}
        $this->assign('info',$info);
        $this->assign('a','info');

        $this->display();      
    }

    /**
     * 保存商户信息
     */
    public function doindex()
    {
//        $this->isLogin();
        $user = D('Shop');
        $uid = session('uid');
        if($_POST['phone']){
        	if(!isPhone($_POST['phone'])){
        		$this->error('手机号码不正确');
        	}
        }
        $where['id']=$uid;
        if($user->create($_POST,2)){
	        if($user->where($where)->save())
	        {
	            $this->success('修改成功');
	        }else{
	        	Log::write($user->getError());
	            $this->error('操作出错，请重新操作');
	        }
        }else{
               $this->error($user->getError());
        }
    }

public function rztongji(){
	$cxdays=D('cxdays');
	 $today=$cxdays->where(array('shopid'=>session('uid'),'date'=>date("Y-m-d")))->field('00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23')->find();
		if($today==null){
		$today=array(
			'00'=>0,
			'01'=>0,
			'02'=>0,
			'03'=>0,
			'04'=>0,
			'05'=>0,
			'06'=>0,
			'07'=>0,
			'08'=>0,
			'09'=>0,
			'10'=>0,
			'11'=>0,
			'12'=>0,
			'13'=>0,
			'14'=>0,
			'15'=>0,
			'16'=>0,
			'17'=>0,
			'18'=>0,
			'19'=>0,
			'20'=>0,
			'21'=>0,
			'22'=>0,
			'23'=>0
			);
			$todays=json_encode($today);
	}else{
		
		$key = array_search(max($today),$today); 
		$todays=json_encode($today);
	 		foreach($today as $k=>$v){
          	$todaynum=$todaynum+$v;
			}
		}//toady report
		$this->assign('todayjs',$todays);
		$this->assign('todaynum',$todaynum);
		$yesterday=$cxdays->where(array('shopid'=>session('uid'),'date'=>date("Y-m-d",strtotime('-1 day'))))->field('00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23')->find();
		if($yesterday==null){
		$yesterday=array(
			'00'=>0,
			'01'=>0,
			'02'=>0,
			'03'=>0,
			'04'=>0,
			'05'=>0,
			'06'=>0,
			'07'=>0,
			'08'=>0,
			'09'=>0,
			'10'=>0,
			'11'=>0,
			'12'=>0,
			'13'=>0,
			'14'=>0,
			'15'=>0,
			'16'=>0,
			'17'=>0,
			'18'=>0,
			'19'=>0,
			'20'=>0,
			'21'=>0,
			'22'=>0,
			'23'=>0
			);
			$yesterdays=json_encode($yesterday);
	}else{
		
		$yesterdaymax = array_search(max($yesterday),$yesterday); 
		$yesterdays=json_encode($yesterday);
	 		foreach($yesterday as $k=>$v){
          	$yesterdaynum=$yesterdaynum+$v;
			}
		}
		
	$this->assign('yesterdayjs',$yesterdays);
	$this->assign('yesterdaynum',$yesterdaynum);
	$this->assign('yesterdaymax',$yesterdaymax);
	//上个星期认证统计
	$date=date('Y-m-d');
	$weekday=array();
	$weekday[1]="星期一";
 	$weekday[2]="星期二";
	$weekday[3]="星期三";
 	$weekday[4]="星期四";
 	$weekday[5]="星期五";
 	$weekday[6]="星期六";
 	$weekday[7]="星期天";
	$start=date("N")-1;
	$xingqi=date('Y-m-d',strtotime("$date-".$start."days"));
	$shangzhous=date('Y-m-d',strtotime("$xingqi-7 days"));
	$weeksum=0;
	$week=array();
	for($i=1;$i<8;$i++){
		$data["date"]=date('Y-m-d',strtotime("$xingqi-$i days"));
		$data['shopid']=session('uid');
		$result=$cxdays->where($data)->field('date,00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23')->find();
		if($result==null){
			$week[$data['date']]=0;
		}else{
		array_shift($result);
		$week[$data['date']]=array_sum($result);
		}
		$weeksum+=$week[$data['date']];
	}
		ksort($week);
		$weekmax=array_search(max($week), $week);
		$weekdaymax=date('N',strtotime($weekmax));
		$weekjs=json_encode($week);
	$this->assign('weekjs',$weekjs);
	$this->assign('weekmax',$weekmax);
	$this->assign('weeksum',$weeksum);
	$this->assign('weekdaymax',$weekday[$weekdaymax]);
	//上一个月统计
	if(date('n')==1){
	$months=date('Y-12-01',strtotime('-1 year'));
	}else{
	$months=date('Y-m-01',strtotime('-1 month'));
	}
	$monthe=date('Y-m-01',strtotime('-1 day'));
	$tianshu=date('t',strtotime($monthe))-1;
	$month=array();
	$monthsum=0;
	$monthweek=array();
	for($i=0;$i<$tianshu;$i++){
		$data["date"]=date('Y-m-d',strtotime("$months+$i days"));
		$data['shopid']=session('uid');
		$result=$cxdays->where($data)->field('date,00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23')->find();
		if($result==null){
			$month[$data['date']]=0;
		}else{
		array_shift($result);
		$month[$data['date']]=array_sum($result);
		}
		$monthsum+=$month[$data['date']];
	}
	//每个月的周访问量
	$monthmax=array_search(max($month), $month);
	$monthjs=json_encode($month);
	
	
	
	if(date('n')==1){
	$months=date('Y-12-01',strtotime('-1 year'));
	}else{
	$months=date('Y-m-01');
	}
	$monthe=date('Y-m-d');
	$tianshu=date('t',strtotime($monthe))-1;
	$nowmonth=array();
	$nowmonthsum=0;
	for($i=0;$i<$tianshu;$i++){
		$data["date"]=date('Y-m-d',strtotime("$months+$i days"));
		$data['shopid']=session('uid');
		$result=$cxdays->where($data)->field('date,00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23')->find();
		if($result==null){
			$nowmonth[$data['date']]=0;
		}else{
		array_shift($result);
		$nowmonth[$data['date']]=array_sum($result);
		}
		$nowmonthsum+=$nowmonth[$data['date']];
	}
	//每个月的周访问量
	$nowmonthmax=array_search(max($nowmonth), $nowmonth);
	$nowmonthjs=json_encode($nowmonth);
	
	$this->assign('nowmonthmax',$nowmonthmax);
	$this->assign('nowmonthsum',$nowmonthsum);
	$this->assign('nowmonthjs',$nowmonthjs);
	 $year=date("Y");
	 for($i=1;$i<13;$i++){
	 	if($i<10){
	 	$datas["date"]=array('like',"$year-0".$i.'%');
		$datas['shopid']=session('uid');
		}else{
		$datas["date"]=array('like',"$year-$i%");
		$datas['shopid']=session('uid');
		}
		$monthinfo=$cxdays->where($datas)->field('date,00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23')->select();
		if($monthinfo){
			foreach($monthinfo as $k=>$v){
			array_shift($v);
			$m[$i]+=array_sum($v);
		}
		}else{
		$m[$i]=0;
		}
		}
   $this->assign('mm',$m);
	$this->assign('monthmax',$monthmax);
	$this->assign('monthsum',$monthsum);
	$this->assign('monthjs',$monthjs);
	$ways=D('ways');
	$waysinfo=$ways->where(array('shopid'=>session('uid')))->find();
	$rzway=array();
	foreach($waysinfo as $k=>$v){
		if($k=='00'){
			$rzway['注册认证']=$v;
		}
        if($k=='01'){
			$rzway['手机认证']=$v;
		}
		 if($k=='02'){
			$rzway['无需认证']=$v;
		}
		  if($k=='03'){
			$rzway['微信认证']=$v;
		}
		  if($k=='04'){
			$rzway['微信分享']=$v;
		}
		
		
	}

$this->assign('ways',json_encode($rzway));

	
	
	
	$this->assign('nav','rztongji');
	$this->display();
}
public function sbtongji(){
	//设备在线
	$routemap=D('routemap');
	$timestamp=time()-200;
	$where['shopid']=session('uid');
	$where['update_yuancheng']=array('gt',$timestamp);
	$a=$routemap->where($where)->count();
	$sum=$routemap->where(array('shopid'=>session('uid')))->count();
	$b=$sum-$a;
	$num=$routemap->where($where)->sum('wlan_user_num');
	$list=$routemap->where($where)->field('routename,wlan_user_num')->select();
	$code=json_encode($list);
    $this->assign('a',$a);
	$this->assign('b',$b);
	$this->assign('list',$code);
 //-end 设备在线
 $renshu=$routemap->where(array('shopid'=>session('uid')))->field('routename,renzheng_num')->select();
 $renshujs=json_encode($renshu);

	$this->assign('renshujs',$renshujs);
	$this->assign('nav','sbtongji');
	$this->display();
}
public function yxtongji(){
	$db=M();
		$shopid=session('uid');
	 $sql="select * from wifi_member where shopid=$shopid and login_count>1 order by login_count desc ";
	  $info=$db->query($sql);
	  $this->assign('lists',$info);

foreach($info as $k=>$v){
	$arr[]=$v['login_count'];
}
	  $riqi=array_count_values($arr);
	  arsort($riqi);
	
	  $max=json_encode($riqi);

	  $this->assign('max',$max);

	$this->assign('nav','yxtongji');
	$this->display();
}
    /**
     * 商户 应用设置
     */
    public function application()
    {
        
       $renzheng=array(
	    array('key'=>'4','txt'=>'微信分享','code'=>'qq'),
	    array('key'=>'3','txt'=>'微信认证','code'=>'wecha'),
		array('key'=>'1','txt'=>'手机认证','code'=>'phone'),
		array('key'=>'2','txt'=>'无需认证','code'=>'allow'),
		array('key'=>'0','txt'=>'注册认证','code'=>'reg'),
		array('key'=>'5','txt'=>'app下载认证','code'=>'app'),
	);
	 $renzhengs=array(
	    array('key'=>'4','txt'=>'微信分享','code'=>'qq'),
	    array('key'=>'3','txt'=>'微信认证','code'=>'wecha'),
		array('key'=>'1','txt'=>'手机认证','code'=>'phone'),
		array('key'=>'2','txt'=>'无需认证','code'=>'allow'),
		array('key'=>'0','txt'=>'注册认证','code'=>'reg'),
		
	);
       
        $db=D('Shop');
        $uid = session('uid');
     	$where['id']=$uid;
        $info=$db->where($where)->field('pid,authmode,authaction,jumpurl,timelimit,sh,eh,countflag,countmax,save_path,focus,messageflag,messnum,app,key,ssid,shopids,app_renzheng,app_name') ->find();
		$preuser=D('agent')->where('id='.$info['pid'])->field('id,pushlink,dltourl') ->find();
		//exit(print_r($preuser));
		if($preuser){
        $this->assign('dltourl',$preuser['dltourl']);
		}else{
        $this->assign('dltourl',0);
		}
		preg_match_all('/\#(\d)/', $info['authmode'],$arr);
	
	 if(in_array('0', $arr[1])){
	 	 $this->assign('reg',1);
	 }else{
	 	 $this->assign('reg',0);
	 }
	  if(in_array('1', $arr[1])){
	 	 $this->assign('phone',1);
	 }else{
	 	 $this->assign('phone',0);
	 }
	  if(in_array('2', $arr[1])){
	 	 $this->assign('allow',1);
	 }else{
	 	 $this->assign('allow',0);
	 }
	  if(in_array('3', $arr[1])){
		  if($info['app']==''){
			  $this->assign('wecha',1);
			  $this->assign('wechagao',0);
		  }else{
			  $this->assign('wecha',0);
			  $this->assign('wechagao',1);
		  }
	  }else{
	 	 $this->assign('wecha',0);
	 }
	  if(in_array('4', $arr[1])){
	 	 $this->assign('qq',1);
	 }else{
	 	 $this->assign('qq',0);
	 }
	  if(in_array('6', $arr[1])){
	 	 $this->assign('duanyu',1);
	 }else{
	 	 $this->assign('duanyu',0);
	 }
	 if(in_array('5', $arr[1])){
	 	 $this->assign('apprenzheng',1);
	 }else{
	 	 $this->assign('apprenzheng',0);
	 }
		if($info['app_renzheng']==1){
			$this->assign('apppermit',1);
			$this->assign('authmode',$renzheng);
		}else{
			$this->assign('apppermit',0);
			$this->assign('authmode',$renzhengs);
		}
		
// 	echo $info['save_path'];
        $this->assign('uid',session('uid'));    	
        $this->assign('a','application');    	
        $this->assign('info',$info);
		$this->assign('nav','shezhi');
		
        $this->display();
    }


public function chongzhixq(){
	$type=I('get.zhonglei');
	$jine=I('get.jine');
	$shop=D('shop');
	if($type==0){
		$yanzhengma=C('yanzhengma');
		switch ($jine){
			case 50:
				if($yanzhengma<500){
					$this->error('平台短信数量不足,无法购买');
				}
            break;
          case 270:
				if($yanzhengma<3000){
					$this->error('平台短信数量不足,无法购买');
				}
				break;
		 case 560:
				if($yanzhengma<7000){
					$this->error('平台短信数量不足,无法购买');
				}
				break;
		case 1050:
				if($yanzhengma<15000){
					$this->error('平台短信数量不足,无法购买');
				}
				break;
		}
	}else{
		$qunfa=C('qunfa');
		switch ($jine){
			case 50:
				if($qunfa<500){
					$this->error('平台短信数量不足,无法购买');
				}
            break;
          case 270:
				if($qunfa<3000){
					$this->error('平台短信数量不足,无法购买');
				}
				break;
		 case 560:
				if($qunfa<7000){
					$this->error('平台短信数量不足,无法购买');
				}
				break;
		case 1050:
				if($qunfa<15000){
					$this->error('平台短信数量不足,无法购买');
				}
				break;
		case 1800:
				if($qunfa<30000){
					$this->error('平台短信数量不足,无法购买');
				}
				break;
		}
		
	}
	$info=$shop->where(array('id'=>session('uid')))->find();
	$this->assign('info',$info);
	$this->assign('type',$type);
	$this->assign('jine',$jine);
	$this->assign('time',date("Y-m-d H:i:s"));
	$this->display();
	
	
}
    /**
     * 保存 商户应用设置
     */
    public function doapp()
    {
 
    	import('ORG.Net.UploadFile');
        $uid = session('uid');
     	$db=D('Shop');
     	$where['id']=$uid;
        $info=$db->where($where)->find();
        $authmode="";
       	$upload  = new UploadFile();
		$upload->maxSize    = C('AD_SIZE') ;
        $upload->allowExts  = C('AD_IMGEXT');
        $upload->savePath   =  C('AD_ERCODESAVE');
		if (!is_null($_FILES['img']['name'])&& $_FILES['img']['name']!="") {
			$info=$upload->uploadOne($_FILES['img']);
			if(empty($info)){
			$this->error($upload->getErrorMsg());
			}else{
			$_POST['save_path'] = trim( $info[0]['savepath'],'.').$info[0]['savename'];
		}
		}
		$upload  = new UploadFile();
		$upload->maxSize    = C('AD_SIZE') ;
        $upload->allowExts  = C('AD_IMGEXT');
        $upload->savePath   =  C('AD_ERCODESAVE');
		if (!is_null($_FILES['imgp']['name'])&& $_FILES['imgp']['name']!="") {
			$info=$upload->uploadOne($_FILES['imgp']);
			if(empty($info)){
			$this->error($upload->getErrorMsg());
			}else{
			$_POST['save_path'] = trim( $info[0]['savepath'],'.').$info[0]['savename'];
		}
		}
		$uploads  = new UploadFile();
		$uploads->allowExts  = array('apk');
        $uploads->savePath   =  C('APP_SAVE');
		if (!is_null($_FILES['apk']['name'])&& $_FILES['apk']['name']!="") {
			$info=$uploads->uploadOne($_FILES['apk']);
			if(empty($info)){
			$this->error($uploads->getErrorMsg());
			}else{
			$_POST['apk_path'] = trim( $info[0]['savepath'],'.').$info[0]['savename'];
			
			}
		}
		$uploadt  = new UploadFile();
		$uploadt->allowExts  = array('ipa');
        $uploadt->savePath   =  C('APP_SAVE');
		if (!is_null($_FILES['ipa']['name'])&& $_FILES['ipa']['name']!="") {
			$info=$uploadt->uploadOne($_FILES['ipa']);
			if(empty($info)){
			$this->error($uploadt->getErrorMsg());
			}else{
			$_POST['ipa_path'] = trim( $info[0]['savepath'],'.').$info[0]['savename'];
		}
		}
		if(isset($_POST['reg'])){
			$_POST['authmode'][]=0;
			
		}
		if(isset($_POST['phone'])){
			$_POST['authmode'][]=1;
			unset($_POST['phone']);
			
		}
		if(isset($_POST['allow'])){
			$_POST['authmode'][]=2;
			
		}
		if(isset($_POST['wecha'])||isset($_POST['wechagao'])){
			$_POST['authmode'][]=3;
			
		}
		if(!isset($_POST['wechagao'])){
			$_POST['ssid']='';
			$_POST['shopids']='';
			$_POST['app']='';
			$_POST['key']='';
		}
		if(isset($_POST['apprenzheng'])){
			$_POST['authmode'][]=5;
			
		}
		if(isset($_POST['duanyu'])){
			$_POST['authmode'][]=6;
			
		}
        foreach($_POST['authmode'] as $K=>$v )
        {
        	
        	if($v=='3'){
        	if(!empty($_POST['wx'])){
        		$ac=$_POST['wx'];
        	}else{
        		$ac=$_POST['wxx'];
        	}
        	$wx['user']=$ac;
        	$authmode.="#".$v."=".json_encode($wx)."#";
        	}else{
        		if($v=='6'){
        			$duanyu['question']=$_POST['question'];
					$duanyu['answer']=$_POST['answer'];
					$duanyu['alert']=$_POST['alert'];
					$authmode.="#".$v."=".json_encode($duanyu)."#";
        		}
        	 else{
        		$authmode.="#".$v."#";
        	}
			} 
		
			if($v=='4'){
				
			
				$wap=D('Wap');
				$where['uid']=session('uid');
    			$info=$wap->where($where)->find();
			
		if($info){
			//更新
			
			D('Wap')->where($where)->save(array('home_tpl'=>11,'home_tpl_path'=>'home_hotel','list_tpl'=>12,'list_tpl_path'=>'list_hotel','info_tpl'=>13,'info_tpl_path'=>'info_hotel'));
		}else{
			//添加
			$time=time();
			$data['uid']=session('uid');
			$data['home_tpl']=11;
			$data['home_tpl_path']='home_hotel';
			$data['list_tpl']=12;
			$data['list_tpl_path']='list_hotel';
			$data['info_tpl']=13;
			$data['info_tpl_path']='info_hotel';
			$data['add_time']=$time;
			$data['update_time']=$time;
			$data['state']=1;
			$wap->add($data);
			
			}
			}      	
        }        
//      $isCount=(int)$_POST['countflag'];
//      if($isCount>0){
//      	if(empty($_POST['countmax'])){
//      		$this->error('上网允许认证次数不能为空');
//      	}
//      	if(!isNumber($_POST['countmax'])){
//      		$this->error('上网允许认证次数必须是数字');
//      	}
//      	$maxcount=(int)$_POST['countmax'];
//      	if($maxcount<0||$maxcount>300){
//      		$this->error('上网允许认证次数范围在1-300');
//      	}
//      }else{
//      	$_POST['countmax']=0;
//      }
//		if(in_array('4',$_POST['authmode'])){
//      	$_POST['authaction']=4;
//		
//		}else{
//		 
//			$_POST['authaction']=1;
//			if(isset($_POST['jumpurl'])){
//				
//			}else{
//				$_POST['jumpurl']="http://www.baidu.com";
//			}
//			
//		}
		if(in_array('5',$_POST['authmode'])){
        	
        		
				$app=D('app');
				$result=$app->where(array('uid'=>$uid))->find();
				
				if($result){
				if($_POST['app_name']==$result['appname']){
					
				}else{
				$app->where(array('uid'=>$uid))->setField('appname',$_POST['app_name']);
				}
				}else{
					
					$data['uid']=$uid;
					$data['appname']=$_POST['app_name'];
					$app->add($data);
				}
        	}
		$_POST['authmode']=$authmode;
	
        if($_POST['authmode']==null||$_POST['authmode']==''){
        	$_POST['authmode']="#1#";
        }
     	if(!$_POST['timelimit']==""){
        	if(!is_numeric($_POST['timelimit'])){
        		$this->error('输入的上网时间必须是数字类型');
        	}
        }
			
        if(isset($_POST['authaction'])){
        	
        }else{
        	$_POST['authaction']=2;
        }
	
      
            $_POST['update_time']=time();
        if($db->where($where)->save($_POST)){
        	   $this->success('操作成功');
        }else{
        	$this->error('操作失败');
        }
    }

    /**
     *广告管理
     */
    public function adv()
    {
        import('@.ORG.UserPage');
        $this->assign('nav','adv');
		 $this->assign('navbar','advmana');
        $uid = session('uid');
        $where['uid']=$uid;
        $ad = D('Ad');
        $count = $ad->where($where)->count();
		$page = new UserPage($count,C('ad_page'));
        $result = $ad->where($where)->field('id,ad_pos,ad_thumb,ad_sort,mode')->order('ad_sort desc,id asc')->select();
       
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
        $this->display();        
    }

    /**
     * 添加广告
     */
    public function addadv()
    {
    	 $this->assign('nav','adv');
		 $this->assign('navbar','advadd');
        $this->display();
    }

 public function chongzhi()
    {
        $this->display();
    }
	 public function chongzhi2()
    {
        $this->display();
    }
    /**
     * 处理添加广告
     */
    public function doadv()
    {
        $uid  = session('uid');
        import('ORG.Net.UploadFile');
        $upload  = new UploadFile();
        $upload->maxSize    = C('AD_SIZE') ;
        $upload->allowExts  = C('AD_IMGEXT');
        $upload->savePath   =  C('AD_SAVE');
        if(!$upload->upload()) {
            $this->error($upload->getErrorMsg());
        }else{
            $info           =  $upload->getUploadFileInfo();
            $ad             = D('Ad');
            $_POST['uid']   = $uid;
            $_POST['ad_thumb'] = trim( $info[0]['savepath'],'.').$info[0]['savename'];
            $_POST['ad_sort'] = isset($_POST['adsort']) ? $_POST['adsort'] : 0;
            if($ad->create()){
                if($ad->add())
                {
                    $this->success('添加广告成功',U('user/adv'));
                }else{
                    $this->error('添加失败，请重新添加');
                }
            }else{
                $this->error('添加失败，请重新添加');
            }
        }
    }
    /**
     * 编辑广告
     */
    public function editad()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $uid = session('uid');
        $where['id']=$id;
        $where['uid']=$uid;
        if($id)
        {
            $result = D('Ad')->where($where)->find();
            $this->assign('info',$result);
            $this->display();
        }else{
            $this->error('无此广告信息');
        }

    }
    /**
     * 删除广告
     */
    public function delad()
    {
        $id = isset($_GET['id']) ? intval($_GET[id]) : 0;
        if($id)
        {
            $thumb = D('ad')->where("id={$id}")->field("ad_thumb")->select();
            if(D('ad')->delete($id))
            {
                if(file_exists( ".{$thumb[0]['ad_thumb']}"))
                {
                    unlink(".{$thumb[0]['ad_thumb']}");
                }
                
                $this->success('删除成功',U('user/adv'));
            }else{
                $this->error('操作出错');
            }
        }
    }

    /**
     * 执行编辑广告
     */
    public function doeditad()
    {
        $uid = session('uid');
        $id = I('post.id','0','int');
        $where['id']=$id;
        $where['uid']=$uid;
        $db=D('Ad');
        $result =$db->where($where)->field('id')->find();
         if($result==false){
         	$this->error('无此广告信息');
         	exit;
         }
        import('ORG.Net.UploadFile');
        $upload    = new UploadFile();
        $upload->maxSize    = C('AD_SIZE');
        $upload->allowExts  = C('AD_IMGEXT');
        $upload->savePath   = C('AD_SAVE');

      	if (!is_null($_FILES['img']['name'])&& $_FILES['img']['name']!="") {
        	if(!$upload->upload()) {
            	$this->error($upload->getErrorMsg());
	        }else{
	            $info =  $upload->getUploadFileInfo();
	            $_POST['ad_thumb'] = trim( $info[0]['savepath'],'.').$info[0]['savename'];
	        }
    	}
        if($result)
        {
            $_POST['uid']=$uid;
            if($db->create()){
                 if($db->where($where)->save()){
                     $this->success('修改成功',U('user/adv'));
                 }else{
                     $this->error('操作出错');
                 }
            }else{
                 $this->error($db->getError());
            }
        }
    }

    /**
     *路由
     */
    public function route(){
    	$this->assign('nav','route');
		$this->assign('navbar','routemap');
    	$db= D('Routemap');
    	$uid=session('uid');
    	$where['shopid']=$uid;
    	$info= $db->where($where)->find();
    	$this->assign('info',$info);
    	$this->display();
    }

/**
     * 路由信息列表
     */
	public function online2()
    {
     $mac = isset($_GET['mac']) ? intval($_GET['mac']) : 0;
		$online=D('online');
		$result=$online->where(array('gw_id'=>$mac))->select();

		$this->assign('lists',$result);
		$this->display();
		
		
       
    }

    /**
     * 路由信息列表
     */
	public function routemap()
    {
    	$this->assign('nav','route');
		$this->assign('navbar','routemap');
        import('@.ORG.UserPage');
        $this->assign('a','routemap');
        $uid = session('uid');
        $where['shopid']=$uid;
        $ad = D('routemap');
        $count=$ad->where($where)->count();

		$page=new UserPage($count,C('ad_page'));
        $result = $ad->where($where)->field('id,routename,sortid,gw_id,add_time,last_heartbeat_ip,reboot,manage,shengji,product_sw_ver,ontime,update_yuancheng')->order('sortid asc ,add_time asc')->select();
       	$stamp=time()-300;
		

        $this->assign('stamp',$stamp);
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
 
        $this->display();
    }
	public function denglu()
    {
    	$this->assign('nav','route');
		$this->assign('navbar','routemap');
        import('@.ORG.UserPage');
        $this->assign('a','routemap');
        $uid = session('uid');
        $where['shopid']=$uid;
		$where['last_heartbeat_ip']=array('exp','is not null');
        $ad = D('routemap');
        $count=$ad->where($where)->count();

		$page=new UserPage($count,C('ad_page'));
        $result = $ad->where($where)->field('id,routename,sortid,gw_id,add_time,last_heartbeat_ip,reboot,manage,shengji,product_sw_ver,ontime,update_yuancheng')->order('sortid asc ,add_time asc')->select();
       	$stamp=time()-300;
		

        $this->assign('stamp',$stamp);
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
 
        $this->display();
    }
public function map()
    {
    	if(IS_POST){
    		$where['shopid']=session('uid');
			$where['point_x']=I('post.x');
			$where['point_y']=I('post.y');
			$data['point_x']=I('post.nx');
			$data['point_y']=I('post.ny');
			$route=D('routemap');
			$num=$route->where($where)->save($data);
			if($num){
				$adata['error']=0;
				$adata['msg']='sucess';
			}else{
				$adata['error']=1;
				$adata['msg']='fail';
			}
           $this->ajaxReturn($adata);
			
    	}else{
    	$this->assign('nav','route');
		$this->assign('navbar','map');
        $shop=D('shop');
		$shopinfo=$shop->where(array('id'=>session('uid')))->field('city')->find();
     
        $uid = session('uid');
        $where['shopid']=$uid;
		$where['point_x']=array('exp','is not null');
		$where['point_y']=array('exp','is not null');
        $ad = D('routemap');
      	$result = $ad->where($where)->field('routename,update_yuancheng,gw_id,wlan_user_num,point_x,point_y')->select();
		$timestamp=time()-300;
       foreach($result as $k=>$v){
       	if($v['update_yuancheng']>$timestamp){
       		$result[$k]['online']=1;
       	}else{
       		$result[$k]['online']=0;
			$result[$k]['wlan_user_num']=0;
       	}
		unset($result[$k]['update_yuancheng']);
       }
		
        $result=json_encode($result);
//      $this->assign('stamp',$stamp);
//      $this->assign('page',$page->show());
		$this->assign('shop',$shopinfo);
        $this->assign('lists',$result);
// 
      $this->display();
      }
    }
    /**
     * 添加路由信息
     */
   public function addroute()
    {
    	if(session('vip')==1){
    		$this->error('您还未对该设备进行VIP充值,无法绑定');
    	}
		
		
		
        $db=D('Routemap');
        if(IS_POST){
            $_POST['shopid']=session('uid');
			  $shop=D('Shop');
			  $pid=$shop->where(array('id'=>$_POST['shopid']))->getField('pid');
			  $shopname=$shop->where(array('id'=>$_POST['shopid']))->getField('shopname');
			  $agent=D('Agent');
			  $fee=$agent->where(array('id'=>$pid))->getField('fee');
			  $gw_id=strtolower($_POST['gw_id']);
		      $pattern='/(sxtg|sxtgac|sxtcpe)\d{3}/';
			  if(preg_match($pattern,$gw_id,$arr)){
			          $a=$arr[0];
				      $routefee=D('Route');
					  $routenum=D('Routenum');
			          $route_num=$routenum->where(array('aid'=>$pid))->getField("$a");
                      $routenum->where(array('aid'=>$pid))->setDec("$a");
					  if(isset($route_num)){
					   if($route_num==0){
						$this->error("添加失败，此设备绑定个数已经到上限");
					 }
					}
					$routefee1=$routefee->where(array('aid'=>$pid))->getField("$a");
					if($routefee1==''){
					$routefee1=intval(C('Defaultfee'));
					}
			     }else{
			  	$routefee1=intval(C('Defaultfee'));
			    }
			  if($fee<$routefee1){
			  	$this->error("添加失败！！！所需积分不足。");
			  	}else{
			     if($db->create()){
                   if($db->add()){
                   if(preg_match($pattern,$gw_id,$arr)){
			        $a=$arr[0];
				    $routefee=D('Route');	
					$routefee1=$routefee->where(array('aid'=>$pid))->getField("$a");
					if($routefee1==''){
				    $routefee1=intval(C('Defaultfee'));
					$data['fee']=$fee-$routefee1;
					$agent->where(array('id'=>$pid))->data($data)->save();}
					else{
					$data['fee']=$fee-$routefee1;
					$agent->where(array('id'=>$pid))->data($data)->save();
					}
				    }else {
					$routefee1=intval(C('Defaultfee'));
					$data['fee']=$fee-$routefee1;
					$agent->where(array('id'=>$pid))->data($data)->save();
				    }
				    $routeopt=D('Routeopt');
					$num['gw_id']=$gw_id;
					$num['opt']=$shopname;
					$num['opttime']=time();
					$num['fee']=$routefee1;
					$num['state']='添加设备扣除设备积分';
					$num['feesy']=$data['fee'];
					$num['aid']=$pid;
					$routeopt->data($num)->add();

				    $this->success("添加成功,<br>扣除绑定设备积分{$routefee1}。",U('user/routemap'),3);
                    }else{
                    $this->error("操作失败");
                }
            }else{
                $this->error($db->getError());
                }
				}
        }else{
            $uid = $_SESSION['uid'];
            $shop = M('shop')->where(array('id'=>$uid))->field('mode,city')->find();
            if($shop['mode'] == 0){
                $this->error('注册用户只能绑定一台路由设备');
            }
			$this->assign('shop',$shop);
			$this->assign('nav','route');
			$this->assign('navbar','addroute');
            $this->display();
        }

    }

    /**
     * 编辑路由信息
     */
    public function editroute()
    {
//        $this->isLogin();
        if(IS_POST){
        	$db= D('Routemap');
        	$_POST['shopid']=session('uid');
        	$id = I('post.id','0','int');
	        $where['id']=$id;
	        $where['shopid']=session('uid');
         	$result =$db
                    ->where($where)
                    ->field('id')
                    ->find();
                   
                    
	         if($result==false){
	         	$this->error('没有此路由信息');
	         	exit;
	         }
	        
        	if($db->create()){
        			if($db->where($where)->save()){
	        		   $this->success('更新成功',U('user/routemap'));
	        		}else{
	        			$this->error("操作失败");
	        		}
        	}else{
        		$this->error($db->getError());
        	}
        	
        }else{
         	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	         
	        $uid = session('uid');
	        $where['id']=$id;
	        $where['shopid']=$uid;
	        
	        $r = D('Routemap')->where($where)->field('id,shopid,routename,gw_id,sortid')->find();
	        
	        if($r==false){
	        	$this->error('没有此路由信息');
	        }else{
	         	$this->assign('info',$r);
	        	$this->display();
	        }
        }
    }
public function shengji(){
	
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	         
	        $uid = session('uid');
	        $where['id']=$id;
	        $where['shopid']=$uid;
			$r= D('Routemap')->where($where)->setField('shengji',1);
			if($r){
				$this->success('路由器将在5分钟内升级完成！');
			}else{
				$this->error('未知错误');
			}
	
}
    /**
     * 更新路由信息
     */
    public function saveroute()
    {
        $uid=session('uid');
        $db= D('Routemap');
        $where['shopid']=$uid;
        $info= $db->where($where)->find();
        if(!$info){
            //添加
            $_POST['shopid']=session('uid');
            $_POST['sortid']=0;
            if($db->create()){
                if($db->add()){
                    $this->success('更新成功',U('user/route'));
                }else{
                    $this->error("操作失败");
                }
            }else{
                $this->error($db->getError());
            }
        }else{
            //更新
            $where['id']=$info['id'];
            $_POST['shopid']=session('uid');
            $_POST['sortid']=0;
            if($db->create($_POST,2)){
                if($db->where($where)->save()){
                    $this->success('更新成功',U('user/route'));
                }else{
                    $this->error("操作失败");
                }
            }else{
                $this->error($db->getError());
            }
        }
    }

    /**
     * 删除路由
     */
    public function delrout()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
         
        $uid = session('uid');
        $where['id']=$id;
        $where['shopid']=$uid;
        
        $r = D('Routemap')->where($where)->find();
        
        if($r==false){
        	$this->error('没有此路由信息');
        }else{
          if(D('Routemap')->where($where)->delete()){
          	$this->success('删除成功');
          }else{
          	$this->error('删除失败');
          }
        	
        }
    }
    
	//重启路由
     public function rebot()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
         
        $uid = session('uid');
        $where['id']=$id;
        $where['shopid']=$uid;
        
        $r = D('Routemap')->where($where)->find();
        
        if($r==false){
        	$this->error('没有此路由信息');
        }else{
        	
          if(D('Routemap')->where($where)->setField('reboot','1')){
          	$this->success('操作保存成功,设备将在2分钟内重启！','',3);
          }else{
          	$this->error('操作失败');
          }
        	
        }
    }
	//路由设置参数
	  public function routemanage(){
	  	     $route=D('Routemap');
		if(IS_POST){
//			print_r($_POST);
    		if($_POST['sub']=='保存'){
    			$_POST['manage']=1;
				unset($_POST['sub']);
			}
		$wheres['id']=$_POST['routeid'];
		if(isset($_POST['authEnable'])){
			$_POST['authEnable']=1;
		}else{
			$_POST['authEnable']=0;
		}
		if(isset($_POST['TimeRebootEnable'])){
			$_POST['TimeRebootEnable']=1;
		}else{
			$_POST['TimeRebootEnable']=0;
		}
		if(isset($_POST['vap_enable'])){
			$_POST['vap_enable']=1;
		}else{
			$_POST['vap_enable']=0;
		}
		if(isset($_POST['vap_hide_ssid'])){
			$_POST['vap_hide_ssid']=1;
		}else{
			$_POST['vap_hide_ssid']=0;
		}
		$a=$_POST['wlan_txpower'];
		if($a>0&&$a<10){
			$_POST['wlan_txpower']=5;
		}else{
			if($a>11&&$a<20){
			$_POST['wlan_txpower']=15;
		}else{
			if($a>21&&$a<40){
			$_POST['wlan_txpower']=25;
		}else{
			if($a>41&&$a<80){
			$_POST['wlan_txpower']=60;
		}else{
			$_POST['wlan_txpower']=100;
		
		}
		}
		}
		}

		
		$data=$_POST;
	  
		$flag=$route->where($wheres)->save($data);
		
		if($flag){
			$this->success('操作保存成功,参数将在2分钟之内设置完成！','routemap',3);
	
			}	
		 else{
          	$this->error('操作失败');
          }
		}
		else{
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
       		$uid = session('uid');
        	$where['id']=$id;
        	$where['shopid']=$uid; 
		$info=$route->where($where)->select();
		
		$info1=$route->where($where)->find();
		
	   if($info==false){
        $this->error('没有此路由信息');}
		else{
		$isAC=preg_match('/SXTGAC/i', $info[0]['gw_id']);
			
	  	 $this->assign('info',$info[0]);
		 $this->assign('ac',$isAC);
		$this->display();
			}
					}
		
		
		
	}
	 //路由器在线人数实时统计
    public function online(){
    	import('@.ORG.UserPage');
        $this->assign('a','online');
        $uid = session('uid');
        $where['shopid']=$uid;
        $ad = D('Authlist');
        $count=$ad->where($where)->count();
      
		$page=new UserPage($count,10);
		$pg=$page->show();

        $result = $ad->where($where)->order('login_time desc')->limit($page->firstRow.','.$page->listRows)->select();
 
        $this->assign('page',$pg);
        $this->assign('lists',$result);
        
        $this->display();        
    }
	
	public function shouyi(){
		$this->display();
		
		
		
		
		
	}
	public function sycx(){
			$zfb=D('zfbvip');
			$today=strtotime(date("Y-m-d"));
			$way=I('post.way');
			$method=I('post.method');
		if($way=='today'){
			if($method=='zfb'){
			$where['notify_time']=array('gt',$today);
			$where['orderid']=array('exp','is not null');
			$where['buyer_email']=array('exp','is not null');
			$where['uid']=session('uid');
			}else{
			$where['notify_time']=array('gt',$today);
			$where['orderid']=array('exp','is null');
			$where['buyer_email']='Payed';
			$where['uid']=session('uid');
			}
		}else{
			if($way=='js'){
				if($method=='zfb'){
			
			$where['orderid']=array('exp','is not null');
			$where['buyer_email']=array('exp','is not null');
			$where['uid']=session('uid');
			$where['jiesuand']=0;
			$where['zhichi']=0;
			}else{
			
			$where['orderid']=array('exp','is null');
			$where['buyer_email']='Payed';
			$where['uid']=session('uid');
			$where['jiesuand']=0;
			$where['zhichi']=0;
			}
			}else{
			if($method=='zfb'){
			
			$where['orderid']=array('exp','is not null');
			$where['buyer_email']=array('exp','is not null');
			$where['uid']=session('uid');
			$where['jiesuand']=1;
			$where['zhichi']=0;
			}else{
			
			$where['orderid']=array('exp','is null');
			$where['buyer_email']='Payed';
			$where['uid']=session('uid');
			$where['jiesuand']=1;
			$where['zhichi']=0;
			}		
					
					
				
			}
			
		}
		$sy=$zfb->where($where)->select();
		foreach($sy as $k=>$v){
			$sy[$k]['notify_time']=date('Y-m-d H:i',$v['notify_time']);
			if($v['buyer_email']=='Payed'){
				$sy[$k]['total_fee']=$v['total_fee']/100;
			}
		}
		$data['error']=0;
		$data['msg']=$sy;
		$this->ajaxReturn($data);
		
		
		
	}
   public function jiesuan(){
   	$zfb=D('zfbvip');
	$where['notify_time']=array('exp','is not null');
	$where['zhichi']=0;
	$where['jiesuand']=0;
	$where['uid']=session('uid');
	$num=$zfb->where($where)->select();
	if($num){
	    $shop=D('shop');
		$syflag=$zfb->where(array('uid'=>session('uid'),'zhichi'=>0))->setField('jiesuan',1);
		$shop->where(array('id'=>session('uid')))->setField('jiesuan',1);
		if($syflag){
			$this->success('结算成功！');
		}else{
			$this->error('玩命结算中！');
		}	
	}else{
		$this->error('无结算的收益');
	}
   }



	 public function report(){

    	import('@.ORG.UserPage');
        $this->assign('a','report');
        $uid = session('uid');
        $where['shopid']=$uid;
        $where['mode']  = array('in','0,1');
        
        $ad = D('Member');
        $count=$ad->where($where)->count();
		$page=new UserPage($count,10);
        $result = $ad->where($where)->limit($page->firstRow.','.$page->listRows)->order('login_time desc')->select();
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
        
        $this->display();        
    }
    
    public function rpt(){
		$this->assign('a','online');
    	$this->display();
    }
    
    public function downrpt(){

    	$way=I('get.mode');
    	$para['mode']=$way;
    	switch(strtolower($way)){
    		case "query":
    			$sdate=I('get.sdate');
    			$edate=I('get.edate');
    			$para['sdate']=$sdate;
    			$para['edate']=$edate;
    			break;
    	}
    	$sql=$this->getrptsql($para);
    	if($sql!=''){
    		$db=D('Adcount');
    		$rs=$db->query($sql);
    		switch(strtolower($way)){
    			case "today":
    				$fm=array(array('统计时段','t'),array('24小时上网流量','ct'));
    				break;
    			case "yestoday":
    				$fm=array(array('统计时段','t'),array('24小时上网流量','ct'));
    				break;
    			case "week":
    				$fm=array(array('统计日期','td'),array('日上网流量','ct'));
    				break;
    			case "query":
    				$fm=array(array('统计日期','td'),array('日上网流量','ct'));
    				break;
    		}
    		exportexcelByKey($rs,$fm,date('Y-m-d H:i:s', time()));
    	}else{
    		$this->error("参数不正确");
    	}
    }
    
 	public function adrpt(){
    	$db=D('Authlist');
    	$sql="select add_date ,count(*) as t FROM(select FROM_UNIXTIME(add_time, '%Y-%m-%d' ) as add_date from ".C('DB_PREFIX')."test) t1 group by  add_date";
    	$rs = $db->query($sql);
		$this->assign('dt',$rs);
		
    	$this->display();
    }

    /**
     * 用户统计图表
     */
    public function userchart(){

    	$this->assign('a','report');
    	$this->display();
    }
    
    public function downchart(){
    	$way=I('get.mode');
    	$para['mode']=$way;
    	switch(strtolower($way)){
    		case "query":
    			$sdate=I('get.sdate');
    			$edate=I('get.edate');
    			$para['sdate']=$sdate;
    			$para['edate']=$edate;
    			break;
    	}
    	$sql=$this->getuserchartsql($para);
    	if($sql!=''){
    		$db=D('Adcount');
    		$rs=$db->query($sql);
    		switch(strtolower($way)){
    			case "today":
    				$fm=array(array('统计时段','showdate'),array('认证总人数','totalcount'),array('注册认证','regcount'),array('手机认证','phonecount'));
    				break;
    			case "yestoday":
    				$fm=array(array('统计时段','showdate'),array('认证总人数','totalcount'),array('注册认证','regcount'),array('手机认证','phonecount'));
    				break;
    			case "week":
    				$fm=array(array('统计日期','showdate'),array('认证总人数','totalcount'),array('注册认证','regcount'),array('手机认证','phonecount'));
    				break;
    			case "query":
    				$fm=array(array('统计日期','showdate'),array('认证总人数','totalcount'),array('注册认证','regcount'),array('手机认证','phonecount'));
    				break;
    		}
    		exportexcelByKey($rs,$fm,date('Y-m-d H:i:s', time()));
    	}else{
    		$this->error("参数不正确");
    	}
    }
    
    public  function getuserchart(){
    	$way=I('get.mode');
    	$where=" where shopid=".session('uid');
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
    			$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,id,mode from ".C('DB_PREFIX')."member";
				$sql.=" where add_date='".date("Y-m-d")."' and ( mode=0 or mode=1 ) and shopid=".session('uid');
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";
    			break;
    		case "yestoday":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,id,mode from ".C('DB_PREFIX')."member";

				$sql.=" where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and ( mode=0 or mode=1 ) and shopid=".session('uid');
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";
    			break;
    		case "week":
    			$sql="  select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ";
    			$sql.=" ( select CURDATE() as td ";
    			for($i=1;$i<7;$i++){
    				$sql.="  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
    			}
    			$sql.=" ORDER BY td ) a left join ";
    			$sql.="( select add_date,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ".C('DB_PREFIX')."member ";
				$sql.=" where shopid=".session('uid')." and add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ".C('DB_PREFIX')."member ";
				$sql.=" where  shopid=".session('uid')." and  add_date >= '".date("Y-m-01")."' and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql.=" ) b on a.tname=b.td ";
				$sql.=" where a.id between 1 and  $t";
    			break;
    		case "query":
    			$sdate=I('get.sdate');
    			$edate=I('get.edate');
    			import("ORG.Util.Date");
    			//$sdt=Date("Y-M-d",$sdate);
    			//$edt=Date("Y-M-d",$edate);
    			$dt=new Date($sdate);
    			$leftday=$dt->dateDiff($edate,'d');
    			$sql=" select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount  from ";
    			$sql.=" ( select '$sdate' as td ";
    			for($i=0;$i<=$leftday;$i++){
    				$sql.="  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
    			}
    			$sql.=" ) a left join ";
    
				$sql.="( select add_date,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount  from ".C('DB_PREFIX')."member ";
				$sql.=" where  shopid=".session('uid')." and add_date between '$sdate' and '$edate'  and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
    			break;
    	}
    
    	$db=D('Adcount');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }
    
    private function getuserchartsql($para){
    	$way=$para['mode'];
    	$where=" where shopid=".session('uid');
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
    			$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,id,mode from ".C('DB_PREFIX')."member";
				$sql.=" where add_date='".date("Y-m-d")."' and ( mode=0 or mode=1 ) and shopid=".session('uid');
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";
    			break;
    		case "yestoday":
    			
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,id,mode from ".C('DB_PREFIX')."member";

				$sql.=" where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and ( mode=0 or mode=1 ) and shopid=".session('uid');
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";
    			break;
    		case "week":
    			$sql="  select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ";
    			$sql.=" ( select CURDATE() as td ";
    			for($i=1;$i<7;$i++){
    				$sql.="  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
    			}
    			$sql.=" ORDER BY td ) a left join ";
    			$sql.="( select add_date,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ".C('DB_PREFIX')."member ";
				$sql.=" where shopid=".session('uid')." and add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ".C('DB_PREFIX')."member ";
				$sql.=" where  shopid=".session('uid')." and  add_date >= '".date("Y-m-01")."' and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql.=" ) b on a.tname=b.td ";
				$sql.=" where a.id between 1 and  $t";
		
    			break;
    		case "query":
    			$sdate=$para['sdate'];
    			$edate=$para['edate'];
    			import("ORG.Util.Date");
    			//$sdt=Date("Y-M-d",$sdate);
    			//$edt=Date("Y-M-d",$edate);
    			$dt=new Date($sdate);
    			$leftday=$dt->dateDiff($edate,'d');
    			$sql=" select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount  from ";
    			$sql.=" ( select '$sdate' as td ";
    			for($i=0;$i<=$leftday;$i++){
    				$sql.="  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
    			}
    			$sql.=" ) a left join ";
    
				$sql.="( select add_date,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount  from ".C('DB_PREFIX')."member ";
				$sql.=" where  shopid=".session('uid')." and add_date between '$sdate' and '$edate'  and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
    			break;
    	}
    	return $sql;
    }

    /**
     * @param $para
     *获取报表对应的SQL语句
     * @return string
     */
    private function getrptsql($para){
    	$way=$para['mode'];
    	$where=" where shopid=".session('uid');
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t, COALESCE(ct,0)  as ct from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="( select thour ,count(*) as ct from ";
				$sql.="(select shopid,FROM_UNIXTIME(login_time,\"%H\") as thour,";
				$sql.=" FROM_UNIXTIME(login_time,\"%Y-%m-%d\") as d from ".C('DB_PREFIX')."authlist $where) a ";
				$sql.=" where d='".date("Y-m-d")."' ";
				$sql.=" group by thour ) ";
				$sql.=" b on a.t=b.thour ";

    			break;
    		case "yestoday":
    			$sql=" select t, COALESCE(ct,0)  as ct from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="( select thour ,count(*) as ct from ";
				$sql.="(select shopid,FROM_UNIXTIME(login_time,\"%H\") as thour,";
				$sql.=" FROM_UNIXTIME(login_time,\"%Y-%m-%d\") as d from ".C('DB_PREFIX')."authlist $where) a ";
				$sql.=" where d=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) ";
				$sql.=" group by thour ) ";
				$sql.=" b on a.t=b.thour ";

    			break;
    		case "week":
    			$sql="  select right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(ct,0)  as ct from ";
    			$sql.=" ( select CURDATE() as td ";
    			for($i=1;$i<7;$i++){
    				$sql.="  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
    			}
    			$sql.=" ORDER BY td ) a left join ";
    			$sql.="( select add_date,count(*) as ct  from ".C('DB_PREFIX')."authlist ";
				$sql.=" where shopid=".session('uid')." and add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as t, COALESCE(ct,0) as ct from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,count(*) as ct  from ".C('DB_PREFIX')."authlist  ";
				$sql.=" where  shopid=".session('uid')." and  add_date >= '".date("Y-m-01")."' GROUP BY  add_date";
				$sql.=" ) b on a.tname=b.td ";
				$sql.=" where a.id between 1 and  $t";
		
    			break;
    		case "query":
    			$sdate=$para['sdate'];
    			$edate=$para['edate'];
    			import("ORG.Util.Date");
    			//$sdt=Date("Y-M-d",$sdate);
    			//$edt=Date("Y-M-d",$edate);
    			$dt=new Date($sdate);
    			$leftday=$dt->dateDiff($edate,'d');
    			$sql=" select right(td,5) as td,COALESCE(ct,0)  as ct from ";
    			//$sql=" select right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(ct,0)  as ct from ";
    			$sql.=" ( select '$sdate' as td ";
    			for($i=0;$i<=$leftday;$i++){
    				$sql.="  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
    			}
    			$sql.=" ) a left join ";
    
				$sql.="( select add_date,count(*) as ct  from ".C('DB_PREFIX')."authlist ";
				$sql.=" where  shopid=".session('uid')." and add_date between '$sdate' and '$edate'  GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
    			break;
    	}
    	return $sql;
    }

    /**
     * 上网统计
     */
    public function getrpt(){
    	$way=I('get.mode');
    	$where=" where shopid=".session('uid');
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t, COALESCE(ct,0)  as ct from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="( select thour ,count(*) as ct from ";
				$sql.="(select shopid,FROM_UNIXTIME(login_time,\"%H\") as thour,";
				$sql.=" FROM_UNIXTIME(login_time,\"%Y-%m-%d\") as d from ".C('DB_PREFIX')."authlist $where) a ";
				$sql.=" where d='".date("Y-m-d")."' ";
				$sql.=" group by thour ) ";
				$sql.=" b on a.t=b.thour ";

    			break;
    		case "yestoday":
    			$sql=" select t, COALESCE(ct,0)  as ct from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="( select thour ,count(*) as ct from ";
				$sql.="(select shopid,FROM_UNIXTIME(login_time,\"%H\") as thour,";
				$sql.=" FROM_UNIXTIME(login_time,\"%Y-%m-%d\") as d from ".C('DB_PREFIX')."authlist $where) a ";
				$sql.=" where d=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) ";
				$sql.=" group by thour ) ";
				$sql.=" b on a.t=b.thour ";

    			break;
    		case "week":
    			$sql="  select right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(ct,0)  as ct from ";
    			$sql.=" ( select CURDATE() as td ";
    			for($i=1;$i<7;$i++){
    				$sql.="  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
    			}
    			$sql.=" ORDER BY td ) a left join ";
    			$sql.="( select add_date,count(*) as ct  from ".C('DB_PREFIX')."authlist ";
				$sql.=" where shopid=".session('uid')." and add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as t, COALESCE(ct,0) as ct from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,count(*) as ct  from ".C('DB_PREFIX')."authlist  ";
				$sql.=" where  shopid=".session('uid')." and  add_date >= '".date("Y-m-01")."' GROUP BY  add_date";
				$sql.=" ) b on a.tname=b.td ";
				$sql.=" where a.id between 1 and  $t";
		
    			break;
    		case "query":
    			$sdate=I('get.sdate');
    			$edate=I('get.edate');
    			import("ORG.Util.Date");
    			//$sdt=Date("Y-M-d",$sdate);
    			//$edt=Date("Y-M-d",$edate);
    			$dt=new Date($sdate);
    			$leftday=$dt->dateDiff($edate,'d');
    			$sql=" select right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(ct,0)  as ct from ";
    			$sql.=" ( select '$sdate' as td ";
    			for($i=0;$i<=$leftday;$i++){
    				$sql.="  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
    			}
    			$sql.=" ) a left join ";
    			
    
				$sql.="( select add_date,count(*) as ct  from ".C('DB_PREFIX')."authlist ";
				$sql.=" where  shopid=".session('uid')." and add_date between '$sdate' and '$edate'  GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
    			break;
    	}
    	$db=D('Authlist');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }
    
    public function advrpt(){
    	$this->assign('a','adv');
    	$this->show();
    }

    public function getadrpt(){
    	$way=I('get.mode');
    	$where=" where shopid=".session('uid');
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcount";
				$sql.=" where add_date='".date("Y-m-d")."' and mode=1 and shopid=".session('uid');
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		case "yestoday":
    			
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcount";

				$sql.=" where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and mode=1 and shopid=".session('uid');
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		case "week":
    			$sql="  select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit ,COALESCE(hit/showup*100,0) as rt from ";
    			$sql.=" ( select CURDATE() as td ";
    			for($i=1;$i<7;$i++){
    				$sql.="  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
    			}
    			$sql.=" ORDER BY td ) a left join ";
    			$sql.="( select add_date,sum(showup) as showup ,sum(hit) as hit from ".C('DB_PREFIX')."adcount";
				$sql.=" where shopid=".session('uid')." and mode=1 and add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,sum(showup) as showup ,sum(hit) as hit  from ".C('DB_PREFIX')."adcount  ";
				$sql.=" where  shopid=".session('uid')." and mode=1 and  add_date >= '".date("Y-m-01")."' GROUP BY  add_date";
				$sql.=" ) b on a.tname=b.td ";
				$sql.=" where a.id between 1 and  $t";
    			break;
    		case "query":
    			$sdate=I('get.sdate');
    			$edate=I('get.edate');
    			import("ORG.Util.Date");
    			//$sdt=Date("Y-M-d",$sdate);
    			//$edt=Date("Y-M-d",$edate);
    			$dt=new Date($sdate);
    			$leftday=$dt->dateDiff($edate,'d');
    			$sql=" select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ";
    			$sql.=" ( select '$sdate' as td ";
    			for($i=0;$i<=$leftday;$i++){
    				$sql.="  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
    			}
    			$sql.=" ) a left join ";
    
				$sql.="( select add_date,sum(showup) as showup ,sum(hit) as hit  from ".C('DB_PREFIX')."adcount ";
				$sql.=" where  shopid=".session('uid')." and mode=1 and add_date between '$sdate' and '$edate'  GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
    			break;
    	}
    
    	$db=D('Adcount');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }

	public function comment()
    {
        import('@.ORG.UserPage');
         
        $this->assign('nav','comment');
        $uid = session('uid');
        $where['shopid']=$uid;
        $ad = D('Comment');
        $count=$ad->where($where)->count();
		$page=new UserPage($count,C('ad_page'));
		
        $result = $ad->where($where)->field('id,user,phone,content,add_time')->limit($page->firstRow.','.$page->listRows)-> select();
       
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
        $this->display();        
    }

    public function delcomment(){
    	$id=I('get.id');
    	$where['id']=$id;
    	$uid = session('uid');
        $where['shopid']=$uid;
        $ad = D('Comment');
        $ad->where($where)->delete();
        $this->success('操作完成',U('user/comment'));
    }
}
