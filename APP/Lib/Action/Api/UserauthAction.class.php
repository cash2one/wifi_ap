<?php
class UserAuthAction extends BaseApiAction{

	private  $shop=false;
	private  $tplkey="";

	public function index(){
		
	}

    /**
     * 加载商户信息
     */
    public function load_shopinfo(){
        if( cookie('gw_id')!=null){
            $sql="select a.id,a.gw_id,a.shopid,a.routename,b.shopname,b.jifei,b.authmode,b.messageflag,b.messnum,b.timelimit,b.save_path,b.pid,b.share,b.tpl_path,b.vip,b.app,b.key,b.shopids,b.ssid from ".C('DB_PREFIX')."routemap a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id ";
            $sql.=" where a.gw_id='".cookie('gw_id')."' limit 1";
            $dbmap=D('Routemap');
            $info=$dbmap->query($sql);

            if($info!=false){
                cookie('shopid',$info[0]['shopid']);//商户id
                cookie('pid',$info[0]['pid']);//代理商
                cookie('jifei',$info[0]['jifei']);
                $this->shop=$info;
                $this->tplkey=$info[0]['tpl_path'];
                $this->assign("shopinfo",$info);
				$this->assign("messageflag",$info[0]['messageflag']);
            }
            $dbmap=null;
        }
    }

public function upinfo(){
		if(cookie('mac')!=null){
			$meminfo=D('member');
			$call['shopid']=cookie('shopid');
			$call['mac']=cookie('mac');
			$meminfo->where($call)->setField('update_time',time());
		}
	}

    /**
     * 微信认证页面
     */
	public function wxauth(){
		$this->load_shopinfo();
		
		
		
		
			$authlist=D('Authlist');
			$token=md5(uniqid());
			$now=time();
			$tranDb=new Model();
			$arrdata['add_date']=getNowDate();
			$arrdata['over_time']=$now+300;
			$arrdata['update_time']=$now;//更新时间
			$arrdata['login_time']=$now;//首次登录时间
			$arrdata['last_time']=$now;//最后在线时间
			$arrdata['shopid']=$this->shop[0]['shopid'];
			$arrdata['routeid']=$this->shop[0]['id'];
			$arrdata['browser']=$this->browser;
			$arrdata['token']=$token;
			$arrdata['mac']=cookie('mac');
			$arrdata['mode']='3';//微信
			$arrdata['agent']=$this->agent;
			$arrdata['link']=0;
			$arrdata['forweixin']=1;
			$flagauth=$authlist->add($arrdata);
			if($flagauth){
				$hour=date('H');
				$date=date('Y-m-d', time());
				$cxdays=D('cxdays');
				$ways=D('ways');
				if(isset($_COOKIE['shopid'])){
				$where['shopid']=cookie('shopid');
				$where['date']=$date;
				$result=$cxdays->where($where)->find();
				if($result==null){
					
					
				$data['shopid']=cookie('shopid');
				$data['date']=$date;
				$data["$hour"]=1;
				$data['pid']=cookie('pid');
				
						$datas['pid']=cookie('pid');
				$datas['shopid']=cookie('shopid');
				$datas['03']=1;	
				$cxdays->data($data)->add();
				
					
				
			
				$ways->add($datas);
			
				}else{
				$num1=$cxdays->where($where)->getField("$hour");
				$num2=$num1+1;
				$cxdays->where($where)->setField("$hour",$num2);
				$num3=$ways->where(array('shopid'=>cookie('shopid')))->getField('03');
				$num4=$num3+1;
				$ways->where(array('shopid'=>cookie('shopid')))->setField('03',$num4);
				}
				}
				cookie('token',$token);
				$this->redirect("index.php/api/userauth/showtokenforweixin");
			
	}
			
		
	}

public function wxuserauth(){
		$this->load_shopinfo();
		$savepath=$this->shop[0]['save_path'];
		$authmode=$this->shop[0]['authmode'];
		$tmp=explode('#', $authmode);
            foreach($tmp as $v){
                if($v!='#'&&$v!=''){
                    $arr[]=$v;
                }
            }
            foreach($arr as $v){

                $temp=explode('=', $v);

                if(count($temp)>1&&$temp[0]=='3'){

                    $auth['wx']=$temp[1];
                    break;
                }
            }
		$wx=json_decode($auth['wx']);
		$this->assign('savepath',$savepath);
		$this->assign('wxname',$wx->user);
		if(empty($this->tplkey)||$this->tplkey==""||strtolower($this->tplkey)=="default"){
				$this->display("wxauth");
		}else{
			$this->display("wxauth$".$this->tplkey);
		}
	}
public function weixin(){
	$this->load_shopinfo();
		$savepath=$this->shop[0]['save_path'];
		$authmode=$this->shop[0]['authmode'];
		$tmp=explode('#', $authmode);
            foreach($tmp as $v){
                if($v!='#'&&$v!=''){
                    $arr[]=$v;
                }
            }
            foreach($arr as $v){

                $temp=explode('=', $v);

                if(count($temp)>1&&$temp[0]=='3'){

                    $auth['wx']=$temp[1];
                    break;
                }
            }
		$wx=json_decode($auth['wx']);
		$this->assign('savepath',$savepath);
		$this->assign('wxname',$wx->user);
		$this->assign('token',cookie('token'));
		$time = explode ( " ", microtime () );  
		$time = $time [1] . substr($time [0],3,3);
		$this->assign('timestamp',$time);
		$string=$this->shop[0]['app'].cookie('token').$time.$this->shop[0]['shopids'].'http://ap.wifi01.cn/index.php/api/userauth/weixinchangeaa:aa:aa:aa:aa:aa'.$this->shop[0]['ssid'].'ff:ff:ff:ff:ff:ff'.$this->shop[0]['key'];
  		$sign=md5($string);
		
		$this->assign('app',$this->shop[0]['app']);
		$this->assign('shopid',$this->shop[0]['shopids']);
		$this->assign('sign',$sign);
		$this->assign('ssid',$this->shop[0]['ssid']);
		
		$this->display();
}
    /**
     * 微信密码认证处理
     */
	public function dowxauth(){
			$this->load_shopinfo();
			$authlist=D('Authlist');
			$token=md5(uniqid());
			$now=time();
			$tranDb=new Model();
			$arrdata['add_date']=getNowDate();
			$arrdata['over_time']=$this->getLimitTime();
			$arrdata['update_time']=$now;//更新时间
			$arrdata['login_time']=$now;//首次登录时间
			$arrdata['last_time']=$now;//最后在线时间
			$arrdata['shopid']=$this->shop[0]['shopid'];
			$arrdata['routeid']=$this->shop[0]['id'];
			$arrdata['browser']=$this->browser;
			$arrdata['token']=$token;
			$arrdata['mac']=cookie('mac');
			
			$arrdata['mode']='3';//微信
			$arrdata['agent']=$this->agent;
			$arrdata['link']=0;
			$flagauth=$authlist->add($arrdata);
			if($flagauth){
				$hour=date('H');
				$date=date('Y-m-d', time());
				$cxdays=D('cxdays');
				$ways=D('ways');
				if(isset($_COOKIE['shopid'])){
				$where['shopid']=cookie('shopid');
				$where['date']=$date;
				$result=$cxdays->where($where)->find();
				if($result==null){
				$data['shopid']=cookie('shopid');
				$data['date']=$date;
				$data["$hour"]=1;
				$data['pid']=cookie('pid');
				$datas['pid']=cookie('pid');
				$datas['shopid']=cookie('shopid');
				$datas['03']=1;	
				$cxdays->add($data);	
				$ways->add($datas);
				}else{
			$num1=$cxdays->where($where)->getField("$hour");
				$num2=$num1+1;
				$cxdays->where($where)->setField("$hour",$num2);
				$num3=$ways->where(array('shopid'=>cookie('shopid')))->getField('03');
				$num4=$num3+1;
				$ways->where(array('shopid'=>cookie('shopid')))->setField('03',$num4);
				}
				}
              	cookie('token',$token);
            }
	}
    /*
     * 登陆
     */
	public function login(){
		$this->load_shopinfo();
		
	if(empty($this->tplkey)||$this->tplkey==""||strtolower($this->tplkey)=="default"){
				$this->display();
		}else{
			
			$this->display("login$".$this->tplkey);
		}
	}


public function find(){
	
	if(IS_POST){
		
	}else{
		$this->display();
		
	}
	
}
public function resetpass(){
	
	if(IS_POST){
		$this->assign('username',I('post.username'));
	
		$this->display();
		
	}
	
}
public function doresetpass(){
	
	if(IS_POST){
		$where['username']=I('post.username');
		$password=md5(I('post.pass'));
		$vipmem=D('Vipmember');
		$flag=$vipmem->where($where)->setField('password',$password);
		if($flag){
			$this->success("修改成功！跳转至登录页",U('Userauth/viplogin'));
		}else{
			$this->error("修改失败！跳转至登录页",U('Userauth/viplogin'));
		}
		
	}else{
		$this->error("您无权访问本页面");
		
	}
	
}
public function forfind(){
	$this->load_shopinfo();
	import("ORG.Util.String");
	$code=String::randString(6,'1');	
	if(IS_POST){
		$username=I("post.username");
		$phonenum=I("post.phonenum");
		$vipmem=D("Vipmember");
		$info=$vipmem->where(array('username'=>$username,'phonenum'=>$phonenum))->find();
		if($info){
			if($this->shop[0]['messnum']>0){
			if(session('?smscode')){
				$phone=json_decode(session('smscode'));
					$data['msg']=$phone->code;
					$data['error']=0;
					$this->ajaxReturn($data);
					}
					else{
					$url="http://120.24.167.205/msg/HttpSendSM?account=szsxtkj&pswd=SZsxtkj25&mobile=$phonenum&msg=神行通计费系统密码找回验证码:{$code}，注册码有效时间为25分钟！输入验证码完成密码重置【神行通】&needstatus=true&product="; 
					$result=$this->lin($url);
					$arr=explode("\n", $result);
					$flag=explode(",", $arr[0]);
				 if($flag[1]==0){
				 	$shopinfo=D('shop');
					 $shopinfo->where(array('id'=>$this->shop[0]['shopid']))->setDec('messnum');
					
				}
				 $mdata['phone']=$phone;
				  $mdata['code']=$code;
				 session('smscode',json_encode($mdata));
					$data['msg']=$code;
					$data['error']=0;
					$this->ajaxReturn($data);	
				
			
		}
		}else{
			$data['error']=1;
			$data['msg']="服务商可用短信不足";
			$this->ajaxReturn($data);
			
		}
		}else{
			$data['error']=1;
			$data['msg']="填写的手机号与注册时所用手机号不同";
			$this->ajaxReturn($data);
		}
	}
}


//获取短信验证码回调信息 


    /**
     * 计费用户注册
     */
     
     public function vipreg(){
     	import("ORG.Util.String");
		$this->load_shopinfo();
     	if(IS_POST){
     		$phone=I('post.phone');
			if(isPhone($phone)){
				$code=String::randString(6,'1');
				if($this->shop[0]['messnum']>0){
//				$post_data = array();
//				$post_data['userid'] =112;	
//				$post_data['account'] ='sxtkj';
//				$post_data['password'] ='123456';
//				$post_data['content'] ="【".$this->shop[0]['shopname']."】您的验证码:{$code}，请尽快完成注册！【神行通】";
//				$post_data['mobile'] ="$phone";
//				$post_data['sendtime'] = ''; 
//				$url='http://115.29.242.32:8888/sms.aspx?action=send';
//				$o='';
//				foreach ($post_data as $k=>$v)
//				{
//					 $o.="$k=".urlencode($v).'&';
//				}
//				$post_data=substr($o,0,-1);
//				$ch = curl_init();
//				curl_setopt($ch, CURLOPT_POST, 1);
//				curl_setopt($ch, CURLOPT_HEADER, 0);
//				curl_setopt($ch, CURLOPT_URL,$url);
//				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
//				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//			
//              $result = curl_exec($ch);
//				 if($result){
//				 	$shopinfo=D('shop');
//					 $shopinfo->where(array('id'=>$this->shop[0]['shopid']))->setDec('messnum');
//				}
                $vip=D('vipmember');
				$info=$vip->where(array('phonenum'=>$phone))->find();
				if($info){
					$data['msg']='该手机号已经使用！';
					$data['error']=1;
					$this->ajaxReturn($data);
					exit;
				}else{
					if(session('?smscode')){
					$data['msg']='验证码已经发送！请注意查收';
					$data['error']=1;
					$this->ajaxReturn($data);
					}
					else{
					$url="http://120.24.167.205/msg/HttpSendSM?account=szsxtkj&pswd=SZsxtkj25&mobile=$phone&msg=神行通计费系统注册码:{$code}，注册码有效时间为25分钟！输入注册码完成注册【神行通】&needstatus=true&product="; 
					$result=$this->lin($url);
					$arr=explode("\n", $result);
					$flag=explode(",", $arr[0]);
				 if($flag[1]==0){
				 	$shopinfo=D('shop');
					 $shopinfo->where(array('id'=>$this->shop[0]['shopid']))->setDec('messnum');
				}	
				
				 $mdata['phone']=$phone;
				 $mdata['code']=$code;
				 session('smscode',json_encode($mdata));
					$data['msg']=$code;
					$data['error']=0;
					$this->ajaxReturn($data);
					exit;
				}
				}
		
				
				}else{
					$data['msg']='运营商可用短信不足！';
					$data['error']=1;
					$this->ajaxReturn($data);
					exit;
					
				}
			}else{
					$data['msg']="请填写有效的11位手机号码";
						$data['error']=1;
						$this->ajaxReturn($data);
						exit;
			}
		}
		else{
		$this->display();
		}
     
     
	}
			public function dovipreg(){
				$this->load_shopinfo();
				$vip=D('Vipmember');
				$phone=I('post.phonenum');
				$user=I('post.username');
				$smscode=I('post.smscode');
				$code=json_decode(session('smscode'));
				if($code->phone!=$phone||$code->code!=$smscode){
						$data['msg']='验证码不正确！';
						$data['error']=1;
						$this->ajaxReturn($data);
						exit;
				}
				$_POST['laston_time']=600;
				$_POST['online']=0;
				$_POST['uid']=$this->shop[0]['shopid'];
				$_POST['add_time']=time();
				$_POST['password']=md5($_POST['password']);
				$res=$vip->where(array('phonenum'=>$phone))->find();
				
				if($res){
					$data['error']=1;
					$data['msg']='该手机号已经使用！';
					$this->ajaxReturn($data);
				}
				else{
					if(!preg_match('/^[a-zA-Z0-9]{3,20}/', $user)){
					$data['error']=1;
					$data['msg']='用户名用户名必须由3到20位数字或字母组成';
					$this->ajaxReturn($data);
					}else{
					$res2=$vip->where(array('username'=>$user))->find();
					if($res2){
					$data['error']=1;
					$data['msg']='该账号已被注册！';
					$this->ajaxReturn($data);
					}else{	
					$vip->create();
					if($vipid){
						session('vipid',$vipid);
					$data['error']=0;
					$data['url']=U("userauth/userinfo");
					$this->ajaxReturn($data);
					}
					}
				   }
				}
	
					
	
	}
			public function viplogin(){
			$this->display();
					
			}
			public function select(){
				if(!session('vipid')||session('vipid')==null||session('vipid')==''){
			$this->redirect('api/login/jifei');
			}
	$vip=D('vipmember');
	$info=$vip->where(array('id'=>session('vipid')))->find();
	$this->assign('info',$info);
	$this->display();
}
			public function select2(){
		if(!session('vipid')||session('vipid')==null||session('vipid')==''){
			$this->redirect('api/login/jifei');
			}
	$vip=D('vipmember');
	$info=$vip->where(array('id'=>session('vipid')))->field('username,uid')->find();
	$shop=D('shop');
	$shopinfo=$shop->where(array('id'=>$info['uid']))->field('hour,month')->find();
	$this->assign('shopinfo',$shopinfo);
	$this->assign('info',$info);
	$this->display();
}
			public function change(){
				if(!session('vipid')||session('vipid')==null||session('vipid')==''){
				$this->redirect('api/login/jifei');
				}
				$type=I('get.type');
				$vip=D('vipmember');
				$flag=$vip->where(array('id'=>session('vipid')))->setField('type',$type);
				if($flag){
					$this->redirect('api/userauth/select');
				}
				
				
			}
		public function doviplogin(){
		    $username=I('post.user');
			$password=I('post.password');
			$db=D('Vipmember');
			$result=$db->where(array('username'=>$username,'password'=>md5($password)))->find();
			if($result){
				$now=time();
				session('vipid',$result['id']);
//				$db->where(array('id'=>$result['id']))->setField('update_time',$now);
				$data['error']=0;
				$data['url']=U("userauth/userinfo");
				$this->ajaxReturn($data);
				
			}else{
				$data['error']=1;
				$data['msg']='用户名或密码错误！';
				$this->ajaxReturn($data);
			}
					
			}
		public function userinfo(){
			if(!session('vipid')||session('vipid')==null||session('vipid')==''){
			$this->redirect('api/login/jifei');
			}else{
			$db=D('Vipmember');
			$result=$db->where(array('id'=>session('vipid')))->find();
			 $shop=D('shop');
			$shopinfo=$shop->where(array('id'=>$result['uid']))->find();
//			$where['vid']=session('vipid');
//			$where['update_time']=array('NEQ','null');
//			$list=D('viplineinfo')->where($where)->order('update_time desc')->limit('5')->select();
//			$now=time()-600;
			$now2=time();
//			$dbs=M();
//			$sql="select * from wifi_viplineinfo where update_time<$now and vid=session('vipid') order by update_time desc limit 0,1";
//			$info=$dbs->query($sql);
//			if($info){
//			    $this->assign('line',$info);
//				$this->assign('online',1);
//			}else{
//			$this->assign('online',0);
//			
//			}
		if($now2>$result['expire_time']){
			$this->assign('expire',1);
		}else{
			$this->assign('expire',0);
			
		}
		
		if($shopinfo['WEKEY']==''){
			$this->assign('wepay',0);
		}else{
			$this->assign('wepay',1);
		}
		
		
//			$this->assign('lists',$list);
			$this->assign('info',$result);
			$this->display();
			}
			}
     public function viponline(){
     	
     		if(!session('vipid')||session('vipid')==null||session('vipid')==''){
			$this->redirect('api/login/jifei');
			}else{
			$token=md5(uniqid());
//			$_POST['vid']=session('vipid');
//			$_POST['add_time']=time();
//			$_POST['mac']=cookie('mac');
//			$_POST['token']=$token;
//			$vipinfo=D('Viplineinfo');
//			$vipinfo->create();
//			if($vipinfo->add()){
//			$_POST['online']=1;
			$_POST['mac']=cookie('mac');
			$_POST['update_time']=time();
			$_POST['token']=$token;
			cookie('token',$token);
			$vipmember=D('Vipmember');
			$vipmember->create();
			$vipmember->where(array('id'=>session('vipid')))->save();
			
			}
			$this->redirect('api/userauth/showtoken');
		}								
	 
     
	 public function pay(){
	 	
		$this->display();
		
	 }
    /**
     * 短信登陆
     */
	public function smslogin(){
             $this->load_shopinfo();
		if(IS_POST){
				
				$userdb=D('Member');
				$user=I('post.user');
				$pwd=I('post.smscode');
				if(empty($user)||empty($pwd)||$user==""||$pwd==""){
						$data['msg']="提交参数不完整，登录失败";
						$data['error']=1;
						$this->ajaxReturn($data);
						exit;
				}
				if(!isPhone($user)){
						$data['msg']="请填写有效的11位手机号码";
						$data['error']=1;
						$this->ajaxReturn($data);
						exit;
				}
				if($pwd==""||!isSmsCode($pwd)){
						$data['msg']="验证码不正确";
						$data['error']=1;
						$this->ajaxReturn($data);
						exit;
				}
			
//				$se=cookie('smscode');
//				if(empty($se)){
//						$data['msg']="验证码不正确11111111111,请重新输入";
//						$data['error']=1;
//						$this->ajaxReturn($data);
//						exit;
//				}
//				if($se!=$pwd){
//						$data['msg']="验证码不正确222222222222,请重新输入";
//						$data['error']=1;
//						$this->ajaxReturn($data);
//						exit;
//				}
//				
				$se=session('smscode');
				if(empty($se)){
						$data['msg']="验证码不正确,请重新获取";
						$data['error']=1;
						$this->ajaxReturn($data);
						exit;
				}
				$code=json_decode(session('smscode'));
				if($code->phone!=$user||$code->code!=$pwd){
						$data['msg']=session('smscode');
						$data['error']=1;
						$this->ajaxReturn($data);
						exit;
				}
				
				$where['user']=$user;
				$where['shopid']=$this->shop[0]['shopid'];
				$info=$userdb->where($where)->find();
				
				if($info==false){
					//添加用户
					C('TOKEN_ON',false);
					$token=md5(uniqid());
					$now=time();
					$tranDb=new Model();
					$_POST['user']=$user;
//					$_POST['token']=$token;
//					$_POST['password']=md5($user);
					$_POST['phone']=$user;
					$_POST['mac']=cookie('mac');
//					$_POST['browser']=$this->browser;
					$_POST['mode']='1';//注册认证
					$_POST['messageflag']=$this->shop[0]['messageflag'];
					$_POST['add_time']=$now;
					$_POST['update_time']=$now;
					$_POST['login_time']=$now;
					$_POST['login_count']=1;
					$_POST['add_date']=getNowDate();
					unset($_POST['__hash__']);
					unset($_POST['smscode']);
					if($this->shop!=false){
						$_POST['shopid']=$this->shop[0]['shopid'];
						$_POST['routeid']=$this->shop[0]['id'];
//						$_POST['online_time']=$this->getLimitTime();
					}
					$tranDb->startTrans();
					$flag=$tranDb->table(C('DB_PREFIX').'member')->add($_POST);  
					$newid=$tranDb->getLastInsID();
					$arrdata['uid']=$newid;
					$arrdata['add_date']=getNowDate();
					$arrdata['over_time']=$this->getLimitTime();
					$arrdata['update_time']=$now;//更新时间
					$arrdata['login_time']=$now;//首次登录时间
					$arrdata['last_time']=$now;//最后在线时间
					$arrdata['shopid']=$this->shop[0]['shopid'];
					$arrdata['routeid']=$this->shop[0]['id'];
					$arrdata['browser']=$this->browser;
					$arrdata['token']=$token;
					$arrdata['mac']=cookie('mac');
					$arrdata['mode']='1';//手机认证
					$arrdata['agent']=$this->agent;
					$arrdata['link']=0;
					$arrdata['messageflag']=$this->shop[0]['messageflag'];
					$flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);  
					
					
				if($flag&$flagauth){
							$tranDb->commit();
							cookie('token',$token);
							$hour=date('H');
							$date=date('Y-m-d', time());
							$cxdays=D('cxdays');
							$ways=D('ways');
							if(isset($_COOKIE['shopid'])){
						$where['shopid']=cookie('shopid');
						$where['date']=$date;
						$result=$cxdays->where($where)->find();
						if($result==null){
						$data['shopid']=cookie('shopid');
						$data['date']=$date;
						$data["$hour"]=1;
						$data['pid']=cookie('pid');
						$datas['pid']=cookie('pid');
						$datas['shopid']=cookie('shopid');
						$datas['01']=1;	
						$cxdays->add($data);	
						$ways->add($datas);
						}else{
						$num1=$cxdays->where($where)->getField("$hour");
				$num2=$num1+1;
				$cxdays->where($where)->setField("$hour",$num2);
				$num3=$ways->where(array('shopid'=>cookie('shopid')))->getField('01');
				$num4=$num3+1;
				$ways->where(array('shopid'=>cookie('shopid')))->setField('01',$num4);
						}
							}	
							$data['error']=0;
							$data['url']=U("userauth/showtoken");
							$this->ajaxReturn($data);
					}else{
						$tranDb->rollback();
						Log::write("手机认证错误:".$tranDb->getLastSql());
						$data['msg']="验证失败，请稍候再试";
							$data['error']=1;
							$this->ajaxReturn($data);
					}

				
				}else{
					//更新用户
					$token=md5(uniqid());
					$now=time();
					$tranDb=new Model();
					
//					$save['token']=$token;
//					$save['online_time']=$this->getLimitTime();
					$save['mac']=cookie('mac');
					$save['update_time']=$now;
					$save['login_time']=$now;
					$save['login_count']=$info['login_count']+1;
					$arrdata['uid']=$info['id'];
					$arrdata['add_date']=getNowDate();
					$arrdata['over_time']=$this->getLimitTime();
					$arrdata['update_time']=$now;//更新时间
					$arrdata['login_time']=$now;//首次登录时间
					$arrdata['last_time']=$now;//最后在线时间
					$arrdata['shopid']=$this->shop[0]['shopid'];
					$arrdata['routeid']=$this->shop[0]['id'];
					$arrdata['browser']=$this->browser;
					$arrdata['token']=$token;
					$arrdata['mode']='1';//无需认证
					$arrdata['agent']=$this->agent;
					$arrdata['link']=0;
					$flag=$tranDb->table(C('DB_PREFIX').'member')->where($where)->save($save); 
					$flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);  
					cookie('token',$token);
					
					if($flag&&$flagauth){
						$tranDb->commit();
						cookie('token',$token);
					$hour=date('H');
				$date=date('Y-m-d', time());
				$cxdays=D('cxdays');
				$ways=D('ways');
				if(isset($_COOKIE['shopid'])){
				$where['shopid']=cookie('shopid');
				$where['date']=$date;
				$result=$cxdays->where($where)->find();
				if($result==null){
				$data['shopid']=cookie('shopid');
				$data['date']=$date;
				$data["$hour"]=1;
				$data['pid']=cookie('pid');
					$datas['pid']=cookie('pid');
				$datas['shopid']=cookie('shopid');
				$datas['03']=1;	
				$cxdays->add($data);	
				$ways->add($datas);
				}else{
				$num1=$cxdays->where($where)->getField("$hour");
				$num2=$num1+1;
				$cxdays->where($where)->setField("$hour",$num2);
				$num3=$ways->where(array('shopid'=>cookie('shopid')))->getField('03');
				$num4=$num3+1;
				$ways->where(array('shopid'=>cookie('shopid')))->setField('03',$num4);
				}
				}
						$data['error']=0;
						$data['url']=U("userauth/showtoken");
						$this->ajaxReturn($data);
					}else{
						$tranDb->rollback();
						$data['msg']="验证失败，请稍候再试";
							$data['error']=1;
							$this->ajaxReturn($data);
					}
				}
			}
	}
private function lin($url){
	if(function_exists('file_get_contents')){
		$file_contents=file_get_contents($url);
	}else{
		$ch=curl_init();
		$timeout=5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_contents=curl_exec($ch);
		curl_close($ch);
}
	return $file_contents;
}
    /**
     * 短信验证码认证
     */
	public function smsCode(){
		import("ORG.Util.String");
		if(IS_POST){
			
			$this->load_shopinfo();
			$phone=I('post.phone');
						
			if(isPhone($phone)){
			
				
				
				$code=String::randString(6,'1');
				if($this->shop[0]['messageflag']&&($this->shop[0]['messnum']>0)){
//				$post_data = array();
//				$post_data['userid'] =112;	
//				$post_data['account'] ='sxtkj';
//				$post_data['password'] ='123456';
//				$post_data['content'] ="【".$this->shop[0]['shopname']."】验证码:{$code}，欢迎光临{$this->shop[0]['shopname']}，输入验证码即可使用免费wifi！【神行通】";
//				$post_data['mobile'] ="$phone";
//				$post_data['sendtime'] = ''; 
//				$url='http://115.29.242.32:8888/sms.aspx?action=send';
//				$o='';
//				foreach ($post_data as $k=>$v)
//				{
//					 $o.="$k=".urlencode($v).'&';
//				}
//				$post_data=substr($o,0,-1);
//				$ch = curl_init();
//				curl_setopt($ch, CURLOPT_POST, 1);
//				curl_setopt($ch, CURLOPT_HEADER, 0);
//				curl_setopt($ch, CURLOPT_URL,$url);
//				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
//				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//			
//              $result = curl_exec($ch);
    if(session('?smscode')){
    	$codejs=json_decode(session('smscode'));
		$code=$codejs->code;
    }else{
    			$userdb=D('Member');
				$where['user']=$phone;
				$where['shopid']=$this->shop[0]['shopid'];
				$where['messageflag']=1;
				$info=$userdb->where($where)->find();
				if($info){
    	
				}else{
					$url="http://120.24.167.205/msg/HttpSendSM?account=szsxtkj&pswd=SZsxtkj25&mobile=$phone&msg={$this->shop[0]['shopname']}验证码:{$code}，欢迎光临{$this->shop[0]['shopname']}，验证码有效时间为25分钟！输入验证码即可使用免费wifi！【神行通】&needstatus=true&product="; 
					$result=$this->lin($url);
					$arr=explode("\n", $result);
					$flag=explode(",", $arr[0]);
				 if($flag[1]==0){
				 	$shopinfo=D('shop');
					 $shopinfo->where(array('id'=>$this->shop[0]['shopid']))->setDec('messnum');
				}	
				
				 $mdata['phone']=$phone;
				 $mdata['code']=$code;
				 session('smscode',json_encode($mdata));
				 exit;
				}
    }
    }else{
 		 if(session('?smscode')){
		$codejs=json_decode(session('smscode'));
		$oldphone=$codejs->phone;
			 if($oldphone!=$phone){
				 $sdata['phone']=$phone;
				 $sdata['code']=$code;
				 session('smscode',json_encode($sdata));
			 }else{
				 $code=$codejs->code;
			 }
		}else{
			$sdata['phone']=$phone;
			$sdata['code']=$code;
			session('smscode',json_encode($sdata));
		}
	}
				$data['msg']=$code;
				$data['error']=0;
				$this->ajaxReturn($data);
				exit;
	
			}else{
					$data['msg']="请填写有效的11位手机号码";
						$data['error']=1;
						$this->ajaxReturn($data);
						exit;
			}
		}
	}
    /**
        /**
     * 手机认证
     */
	public function mobile(){
		$this->load_shopinfo();
		import("ORG.Util.String");
//		if($this->shop[0]['messageflag']==0){
//		cookie('smscode',String::randString(6,'1'));
//		$this->assign('smscode',cookie('smscode'));
//		}

	if(empty($this->tplkey)||$this->tplkey==""||strtolower($this->tplkey)=="default"){
				$this->display();
		}else{
			$this->display('mobile$'.$this->tplkey);
		}
	}

public function duanyu(){
		$this->load_shopinfo();
		$answer=echojsonkey(showauthdata('6',$this->shop[0]['authmode']),'answer');
		$this->assign('answer',$answer);
		$this->display();
	
	}
    /**
     * 登录认证
     */
	public function dologin(){
		import("ORG.Util.String");
		$this->load_shopinfo();
		if(IS_POST){
				$db=D('Member');
				$user=I('post.user');
				$pwd=I('post.password');
				if(empty($user)||empty($pwd)||$user==""||$pwd==""){
						$data['msg']="提交参数不完整，登录失败";
						$data['error']=1;
						$this->ajaxReturn($data);
						exit;
				}
				if(!validate_user($user)){
						$data['msg']="用户名不合法";
						$data['error']=1;
						$this->ajaxReturn($data);
						exit;
				}
				$where['user']=$user;
				$info=$db->where($where)->find();
				if($info==false){
						$data['msg']="用户名不存在";
						$data['error']=1;
						$this->ajaxReturn($data);
						exit;
				}else{
					if($info['password']!=md5($pwd)){
						$data['msg']="用户名密码不正确";
						$data['error']=1;
						$this->ajaxReturn($data);
						exit;
					}
							
					$token=md5(uniqid());
					$tranDb=new Model();
//					$save['token']=$token;
					$save['browser']=$this->browser;
					$save['mac']=cookie('mac');
//					$save['online_time']=$this->getLimitTime();
					$save['update_time']=$now;
					$save['login_time']=$now;
					$save['login_count']=$info['login_count']+1;
					$arrdata['uid']=$info['id'];
					$arrdata['add_date']=getNowDate();
					$arrdata['over_time']=$this->getLimitTime();
					$arrdata['update_time']=$now;//更新时间
					$arrdata['login_time']=$now;//首次登录时间
					$arrdata['last_time']=$now;//最后在线时间
					$arrdata['shopid']=$this->shop[0]['shopid'];
					$arrdata['routeid']=$this->shop[0]['id'];
					$arrdata['browser']=$this->browser;
					$arrdata['token']=$token;
					$arrdata['mac']=cookie('mac');
					$arrdata['mode']='0';//无需认证
					$arrdata['agent']=$this->agent;
					$arrdata['link']=0;
					$flag=$tranDb->table(C('DB_PREFIX').'member')->where($where)->save($save); 
					$flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata); 
					cookie('token',$token); 
					if($flag&&$flagauth){
						$tranDb->commit();
						cookie('token',$token);
						$hour=date('H');
				$date=date('Y-m-d', time());
				$cxdays=D('cxdays');
				$ways=D('ways');
				if(isset($_COOKIE['shopid'])){
				$where['shopid']=cookie('shopid');
				$where['date']=$date;
				$result=$cxdays->where($where)->find();
				if($result==null){
				$data['shopid']=cookie('shopid');
				$data['date']=$date;
				$data["$hour"]=1;
				$data['pid']=cookie('pid');
				$datas['pid']=cookie('pid');
				$datas['shopid']=cookie('shopid');
				$datas['00']=1;	
				$cxdays->add($data);	
				$ways->add($datas);
				}else{
				$num1=$cxdays->where($where)->getField("$hour");
				$num2=$num1+1;
				$cxdays->where($where)->setField("$hour",$num2);
				$num3=$ways->where(array('shopid'=>cookie('shopid')))->getField('00');
				$num4=$num3+1;
				$ways->where(array('shopid'=>cookie('shopid')))->setField('00',$num4);
				}
				}
						$data['error']=0;
						$data['url']=U("userauth/showtoken");
						$this->ajaxReturn($data);
					}else{
						$tranDb->rollback();
						$data['msg']="登录失败，请稍候再试";
							$data['error']=1;
							$this->ajaxReturn($data);
					}
					//Log::write($token);
				}
			}
	}
	
	public function regu(){
		$this->load_shopinfo();
		if(IS_POST){
			$user=I('post.user');
				$pwd=I('post.password');
				$phone=I('post.phone');
				$qq=I('post.qq');
				if(!validate_user($user)){
						$data['msg']="用户名必须是3到20位数字或字母组成";
						$data['error']=1;
						$this->ajaxReturn($data);
						exit;
				}
					if(!isPhone($phone)){
						$data['msg']="请填写有效的11位手机号码";
						$data['error']=1;
						$this->ajaxReturn($data);
						exit;
					}
					if(!isQQ($qq)){
						$data['msg']="请填写有效的QQ号码";
						$data['error']=1;
						$this->ajaxReturn($data);
						exit;
					}
			$db=D('Member');
			
			$where['user']=$user;
			$where['shopid']=$this->shop[0]['shopid'];
	
			$info=$db->where($where)->find();
			if($info!=false){
				$data['msg']="当前帐号已存在";
					$data['error']=1;
					$this->ajaxReturn($data);
			}
			$token=md5(uniqid());
			cookie('token',$token);
			$now=time();
			$tranDb=new Model();
			
//			$_POST['token']=$token;
					
					unset($_POST['__hash__']);
					unset($_POST['smscode']);
			$_POST['user']=$user;
			$_POST['password']=md5($_POST['password']);		
			$_POST['shopid']=$this->shop[0]['shopid'];
			$_POST['phone']=$phone;
			$_POST['qq']=$qq;
			$_POST['mac']=cookie('mac');
			$_POST['routeid']=$this->shop[0]['id'];
//			$_POST['browser']=$this->browser;
			$_POST['mode']='0';//注册认证
			$_POST['add_time']=$now;
			$_POST['update_time']=$now;
			$_POST['login_time']=$now;
			$_POST['add_date']=getNowDate();
			$_POST['login_count']=1;
//			$_POST['online_time']=$this->getLimitTime();
			$tranDb->startTrans();
			$flag=$tranDb->table(C('DB_PREFIX').'member')->add($_POST);  
			$newid=$tranDb->getLastInsID();
			$arrdata['uid']=$newid;
					$arrdata['add_date']=getNowDate();
					$arrdata['over_time']=$this->getLimitTime();
					$arrdata['update_time']=$now;//更新时间
					$arrdata['login_time']=$now;//首次登录时间
					$arrdata['last_time']=$now;//最后在线时间
					$arrdata['shopid']=$this->shop[0]['shopid'];
					$arrdata['routeid']=$this->shop[0]['id'];
//					$arrdata['browser']=$this->browser;
					$arrdata['token']=$token;
					$arrdata['mode']='0';//
					$arrdata['mac']=cookie('mac');
					
					$arrdata['agent']=$this->agent;
					$arrdata['link']=0;
					$flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);  
					

					if($flag&$flagauth){
							$tranDb->commit();
							cookie('token',$_POST['token']);
						$hour=date('H');
				$date=date('Y-m-d', time());
				$cxdays=D('cxdays');
				$ways=D('ways');
				if(isset($_COOKIE['shopid'])){
				$where['shopid']=cookie('shopid');
				$where['date']=$date;
				$result=$cxdays->where($where)->find();
				if($result==null){
				$data['shopid']=cookie('shopid');
				$data['date']=$date;
				$data["$hour"]=1;
				$data['pid']=cookie('pid');
						$datas['pid']=cookie('pid');
				$datas['shopid']=cookie('shopid');
				$datas['00']=1;	
				$cxdays->add($data);	
				$ways->add($datas);
				}else{
				$num1=$cxdays->where($where)->getField("$hour");
				$num2=$num1+1;
				$cxdays->where($where)->setField("$hour",$num2);
				$num3=$ways->where(array('shopid'=>cookie('shopid')))->getField('00');
				$num4=$num3+1;
				$ways->where(array('shopid'=>cookie('shopid')))->setField('00',$num4);
				}
				}		
					cookie('token',$token);
							$data['error']=0;
							$data['url']=U("userauth/showtoken");
							$this->ajaxReturn($data);
					}else{
						$tranDb->rollback();
						Log::write("注册认证错误:".$tranDb->getLastSql());
						$data['msg']="注册失败，请稍候再试";
							$data['error']=1;
							$this->ajaxReturn($data);
					}
		}
	}

	public function showtoken(){
		$this->load_shopinfo();
		$this->upinfo();

		$url="http://".cookie('gw_address').":".cookie('gw_port')."/wifidog/auth?token=".cookie('token');
		//exit(print_r($url));
		$jump=U('User/index');

        //   Conf/adv.php   广告推送开关
		$wait = C('WAITSECONDS');
		$open = C('OPENPUSH');
		$way  = C('SHOWWAY');//展示时间标准
		$shopid  = $this->shop[0]['pid'];
		$agentpush = true;

		if(empty($shopid)||$shopid <= 0){
			//无代理
			$wait = C('WAITSECONDS');
		}else{
			//获取代理商广告信息
			$adset = D('Agentpushset');

            $where = array('aid'=>$shopid);
			$adinfo = $adset->where($where)->find();
			if($adinfo == false){
				//无设定
			}else{
				if($adinfo['pushflag']==1){
					$agentpush=true;
				}
				if($way==1){
					$wait = $adinfo['showtime'];//展示时间
				}
			}
		}
		if($open == 1){
			$where['state'] = 1;
			$where['startdate'] = array('elt',time());
			$where['enddate'] = array('egt',time());
			
			if($agentpush){
				$where['aid'] = array('in','0,'.$shopid);
			}else{
				$where['aid'] = 0;
			}
			$ads = D('Pushadv')->where($where)->field("id,pic,aid,trade")->select();
			
			/*统计展示*/
            $tr = new Model();
            $time = time();
            $tr->startTrans();
            $arrdata['showup']=1;
            $arrdata['hit']=0;
            $arrdata['shopid']=$this->shop[0]['shopid'];
            $arrdata['add_time']=$time;
            $arrdata['add_date']=getNowDate();

            foreach($ads as $k=>$v){
                if($v['aid'] > 0){
                    $arrdata['mode'] = 50;
                    $arrdata['agent'] = $v['aid'];
                }else{
                    $arrdata['mode'] = 99;
                    $arrdata['agent'] = 0;
                }
                $arrdata['aid']=$v['id'];
                $tr->table(C('DB_PREFIX')."adcount")->add($arrdata);
            }
            $tr->commit();
			$this->assign('ad',$ads);
		}
		$this->assign('vip',$this->shop[0]['vip']);
		$this->assign('waitsecond',$wait);
		$this->assign('wifiurl',$url);
		$this->assign('jumpurl',$jump);


		if(empty($this->tplkey)||$this->tplkey==""||strtolower($this->tplkey)=="default"){
				$this->display();
		}else{
			$this->display("showtoken$".$this->shop[0]['tpl_path']);
		}
	


	}
public function showtokenforweixin(){
		$this->load_shopinfo();
		$this->upinfo();
		$url="http://".cookie('gw_address').":".cookie('gw_port')."/wifidog/auth?token=".cookie('token');
		$this->assign('wifiurl',$url);
		$this->display('showtoken$forweixin');
	}
public function weixinchange(){
		$token=I('get.extend');
		$rebot=D('authlist');
		$rebot->where(array('token'=>$token))->setField('over_time','');
		echo 1111111111;
	}
	
	public function reg(){
		$this->load_shopinfo();
		if(empty($this->tplkey)||$this->tplkey==""||strtolower($this->tplkey)=="default"){
				$this->display();
		}else{
			$this->display("reg$".$this->tplkey);
		}
	}
	
	public function comments(){
		$this->load_shopinfo();
	if(empty($this->tplkey)||$this->tplkey==""||strtolower($this->tplkey)=="default"){
				$this->display();
		}else{
			$this->display("comments$".$this->tplkey);
		}
	}
	
	private  function getLimitTime(){
		if($this->shop[0]['timelimit']!=""&&$this->shop[0]['timelimit']!="0"){
			import("ORG.Util.Date");
			$dt=new Date(time());
			$date=$dt->dateAdd($this->shop[0]['timelimit'],'n');//默认7天试用期
			return strtotime($date);
		}
		return "";
	}

    /**
     * 一键认证
     */
	public function noAuth(){
		$this->load_shopinfo();
			$authlist=D('Authlist');
			$token=md5(uniqid());
			$now=time();
			$tranDb=new Model();
			$arrdata['add_date']=getNowDate();
			$arrdata['over_time']=$this->getLimitTime();
			$arrdata['update_time']=$now;//更新时间
			$arrdata['login_time']=$now;//首次登录时间
			$arrdata['last_time']=$now;//最后在线时间
			$arrdata['shopid']=$this->shop[0]['shopid'];
			$arrdata['routeid']=$this->shop[0]['id'];
			$arrdata['browser']=$this->browser;
			$arrdata['token']=$token;
			$arrdata['mode']='2';//微信
			$arrdata['mac']=cookie('mac');
			$arrdata['agent']=$this->agent;
			$arrdata['link']=0;
			$flagauth=$authlist->add($arrdata);
			if($flagauth){
				$hour=date('H');
				$date=date('Y-m-d', time());
				$cxdays=D('cxdays');
				$ways=D('ways');
				if(isset($_COOKIE['shopid'])){
				$where['shopid']=cookie('shopid');
				$where['date']=$date;
				$result=$cxdays->where($where)->find();
				if($result==null){
					
				$data['shopid']=cookie('shopid');
				$data['date']=$date;
				$data["$hour"]=1;
				$data['pid']=cookie('pid');
				
						$datas['pid']=cookie('pid');
				$datas['shopid']=cookie('shopid');
				$datas['02']=1;	
				$cxdays->data($data)->add();
				
					
				
			
				$ways->add($datas);
				}else{
				$num1=$cxdays->where($where)->getField("$hour");
				$num2=$num1+1;
				$cxdays->where($where)->setField("$hour",$num2);
				$num3=$ways->where(array('shopid'=>cookie('shopid')))->getField('02');
				$num4=$num3+1;
				$ways->where(array('shopid'=>cookie('shopid')))->setField('02',$num4);
				}
				}
				cookie('token',$token);
				$this->redirect("index.php/api/userauth/showtoken");
			
	}
	}
public function noAuthforweixin(){
		$this->load_shopinfo();
			$authlist=D('Authlist');
			$token=md5(uniqid());
			$now=time();
			$tranDb=new Model();
			$arrdata['add_date']=getNowDate();
			$arrdata['over_time']=$this->getLimitTime();
			$arrdata['update_time']=$now;//更新时间
			$arrdata['login_time']=$now;//首次登录时间
			$arrdata['last_time']=$now;//最后在线时间
			$arrdata['shopid']=$this->shop[0]['shopid'];
			$arrdata['routeid']=$this->shop[0]['id'];
			$arrdata['browser']=$this->browser;
			$arrdata['token']=$token;
			$arrdata['mac']=cookie('mac');
			$arrdata['mode']='3';//微信
			$arrdata['agent']=$this->agent;
			$arrdata['link']=0;
			$flagauth=$authlist->add($arrdata);
			if($flagauth){
				$hour=date('H');
				$date=date('Y-m-d', time());
				$cxdays=D('cxdays');
				$ways=D('ways');
				if(isset($_COOKIE['shopid'])){
				$where['shopid']=cookie('shopid');
				$where['date']=$date;
				$result=$cxdays->where($where)->find();
				if($result==null){
					
				$data['shopid']=cookie('shopid');
				$data['date']=$date;
				$data["$hour"]=1;
				$data['pid']=cookie('pid');
				
						$datas['pid']=cookie('pid');
				$datas['shopid']=cookie('shopid');
				$datas['03']=1;	
				$cxdays->data($data)->add();
				
					
				
			
				$ways->add($datas);
				}else{
				$num1=$cxdays->where($where)->getField("$hour");
				$num2=$num1+1;
				$cxdays->where($where)->setField("$hour",$num2);
				$num3=$ways->where(array('shopid'=>cookie('shopid')))->getField('03');
				$num4=$num3+1;
				$ways->where(array('shopid'=>cookie('shopid')))->setField('03',$num4);
				}
				}
				cookie('token',$token);
				$this->redirect("index.php/api/userauth/showtoken");
			
	}
	}
public function fenxiang(){
			$this->load_shopinfo();
			$authlist=D('Authlist');
			$token=md5(uniqid());
			$now=time();
			$tranDb=new Model();
			$arrdata['add_date']=getNowDate();
			$arrdata['over_time']=$this->getLimitTime();
			$arrdata['update_time']=$now;//更新时间
			$arrdata['login_time']=$now;//首次登录时间
			$arrdata['last_time']=$now;//最后在线时间
			$arrdata['shopid']=$this->shop[0]['shopid'];
			$arrdata['routeid']=$this->shop[0]['id'];
			$arrdata['browser']=$this->browser;
			$arrdata['token']=$token;
			$arrdata['mac']=cookie('mac');
			$arrdata['mode']='3';//微信
			$arrdata['agent']=$this->agent;
			$arrdata['link']=0;
			$flagauth=$authlist->add($arrdata);
			if($flagauth){
				$hour=date('H');
				$date=date('Y-m-d', time());
				$cxdays=D('cxdays');
				$ways=D('ways');
				if(isset($_COOKIE['shopid'])){
				$where['shopid']=cookie('shopid');
				$where['date']=$date;
				$result=$cxdays->where($where)->find();
				if($result==null){
				$data['shopid']=cookie('shopid');
				$data['date']=$date;
				$data["$hour"]=1;
				$data['pid']=cookie('pid');
						$datas['pid']=cookie('pid');
				$datas['shopid']=cookie('shopid');
				$datas['04']=1;	
				$cxdays->add($data);	
				$ways->add($datas);
				}else{
				$num1=$cxdays->where($where)->getField("$hour");
				$num2=$num1+1;
				$cxdays->where($where)->setField("$hour",$num2);
				$num3=$ways->where(array('shopid'=>cookie('shopid')))->getField('04');
				$num4=$num3+1;
				$ways->where(array('shopid'=>cookie('shopid')))->setField('04',$num4);
				}
				}
              		cookie('token',$token);
					cookie('feixiang',1);
					$this->redirect("index.php/api/userauth/showtoken");
			
	}
	}

    /**
     * 客户留言
     */
	public function addmsg(){
		$this->load_shopinfo();
		if(IS_POST){
            $user   =I('post.user');
            $phone  =I('post.phone');
            $info   =I('post.content');
            if(!isPhone($phone)){
                $data['msg']="请填写有效的11位手机号码";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
					
			$db=D('Comment');
			$_POST['shopid']=$this->shop[0]['shopid'];
			$_POST['routeid']=$this->shop[0]['id'];
			if($db->create()){
				if($db->add()){
					$newid=$db->getLastInsID();
					$data['error']=0;
					$this->ajaxReturn($data);
				}else{
					$data['msg']="提交留言失败，请稍候再试";
					$data['error']=1;
					$this->ajaxReturn($data);
				}
			}else{
				$data['msg']=$db->getError();
				$data['error']=1;
				$this->ajaxReturn($data);
			}
		}
	}

    /**
     * 广告认证
     */
	public function showad(){
		$this->load_shopinfo();
		$id=I('get.id','0','int');
		$db=D('Ad');
		$where['id']=$id;
		$where['uid']=$this->shop[0]['shopid'];
		
		$info=$db->where($where)->find();

		/*统计展示*/
        $tr   = new Model();
        $time = time();
        $tr->startTrans();

        $arrdata['showup']=0;
        $arrdata['hit']=1;
        $arrdata['shopid']=$this->shop[0]['shopid'];
        $arrdata['add_time']=$time;
        $arrdata['add_date']=getNowDate();
        $arrdata['mode']=1;
        $arrdata['aid']=$id;

        $tr->table(C('DB_PREFIX')."adcount")->add($arrdata);
        $tr->commit();
		$this->assign('adinfo',$info);
		$this->display();
	}
}