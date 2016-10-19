<?php
class AuthAction extends BaseApiAction{
	
	private  function isonline($val){
		
			import("ORG.Util.Date");
			$dt=new Date(time());
			$left=$dt->dateDiff($val,'s');//默认7天试用期
			return $left;

		
	}
	
	private $token;
	private $mac;
	private $ip;
	private $gw_id;
	public function index(){
		if (!empty ($_REQUEST['mac'])){
			$this->mac=$_REQUEST['mac'];
		}
		if (!empty ($_REQUEST['ip'])){
			$this->ip=$_REQUEST['ip'];
			
		}
		if (!empty ($_REQUEST['gw_id'])){
			$this->gw_id=$_REQUEST['gw_id'];
		}
		
		if (!empty ($_REQUEST['token'])){
			if(preg_match('/^(SXT|FOR).*/', $_REQUEST['token'],$arr)){
            $viptime=D('Viplineinfo');
			$info=$viptime->where(array('mac'=>$this->mac))->find();
			if($info){
				if(time()>$info['list_time']){
					echo ("Auth: 0\n");
		       		echo ("Messages: No Access\n");
					$viptime->where(array('mac'=>$this->mac))->delete();
				}else{
				echo ("Auth: 1\n");
	           	echo ("Messages: Allow Access\n");
				}
			}else{
				$mac['mac']=$this->mac;
				$mac['list_time']=time()+180;
				$viptime->add($mac);
				echo ("Auth: 1\n");
	           	echo ("Messages: Allow Access\n");
			}
			}else{
			$vipinfo=D('Vipmember');
			$userinfo=$vipinfo->where(array('token'=>$_REQUEST['token']))->find();
			$now=time();
			if($userinfo){
			if($userinfo['type']==0){
				$lasttime=$now-$userinfo['add_time'];
				if($lasttime>600){
				$vipinfo->where(array('token'=>$_REQUEST['token']))->setField('used', 1);
			 	echo ("Auth: 0\n");
		        echo ("Messages: No Access\n");
				}else{
				
				echo ("Auth: 1\n");
	           	echo ("Messages: Allow Access\n");
				
				}
				}else{
					if($userinfo['type']==1){
						$times=$now-$userinfo['update_time'];
						$datas['laston_time']=$userinfo['laston_time']-$times;
						$flag=$userinfo['laston_time']-$times;
                        if($flag<0){
                            echo ("Auth: 0\n");
                            echo ("Messages: No Access\n");
                          }else{
                            echo ("Auth: 1\n");
                            echo ("Messages: Allow Access\n");
                             }
						$datas['on_time']=$userinfo['on_time']+$times;
						$datas['update_time']=$now;
						$vipinfo->where(array('token'=>$_REQUEST['token']))->save($datas);
					}else{
						if($now>$userinfo['expire_time']){
							 echo ("Auth: 0\n");
		        		     echo ("Messages: No Access\n");
						}else{
							$vipinfo->where(array('token'=>$_REQUEST['token']))->setField('update_time',$now);
							echo ("Auth: 1\n");
	           				echo ("Messages: Allow Access\n");
						}			
				    }
					
			}
		}else{
			$tk=$_REQUEST['token'];
			$authdb=D('Authlist');
			$where['token']=$tk;
			$rs=$authdb->where($where)->find();
			if($rs){
                echo ("Auth: 1\n");
				echo ("Messages: Allow Access\n");
				//update time
//				if(empty($rs['over_time'])||$rs['over_time']==""){
//					//no limit
//					$this->token=$tk;
//					echo ("Auth: 1\n");
//	                echo ("Messages: Allow Access\n");
//	                $data['mac']=$this->mac;
//					$data['login_ip']=$this->ip;
//					$data['pingcount']=$rs['pingcount']+1;
//					$data['last_time']=time();//
//					$data['incoming']=$_REQUEST['incoming'];
//					$data['outgoing']=$_REQUEST['outgoing'];
//					$data['update_time']=time();//
//					$authdb->where($where)->save($data);
//				}else{
//					//limit
//					$lf=$rs['over_time']-time();
//					if($lf<0){
//						 //log::write('超时了');
//						echo ("Auth: 1\n");
//						echo ("Messages: Allow Access\n");
//		                 exit;
//					}else{
//						$this->token=$tk;
//						echo ("Auth: 1\n");
//		                echo ("Messages: Allow Access\n");
//		                $data['mac']=$this->mac;
//						$data['login_ip']=$this->ip;
//						$data['pingcount']=$rs['pingcount']+1;
//						$data['last_time']=time();//
//						$data['incoming']=$_REQUEST['incoming'];
//					    $data['outcoming']=$_REQUEST['outcoming'];
//						$data['update_time']=time();//
//						$authdb->where($where)->save($data);
//					}
//				}
			}else{
                echo ("Auth: 0\n");
                echo ("Messages: No Access\n");
		         exit;
			}
			/*
			$tk=$_REQUEST['token'];
			$db=D('Member');
			$where['token']=$tk;
			$user=$db->where($where)->find();


			if($user==false){
				//不存在，不允许上网

				 echo ("Auth: 0\n");
                 echo ("Messages: No Access\n");
			}else{
				//存在,更新信息

				//log::write(($user['online_time']-time())."秒时间戳");
				if($user['online_time']!=""){
					$lf=$user['online_time']-time();
					if($lf<0){
						 //log::write('超时了');
						 echo ("Auth: 0\n");
		                 echo ("Messages: No Access\n");
		                 exit;
					}else{

						echo ("Auth: 1\n");
		                echo ("Messages: Allow Access\n");
		                $data['mac']=$this->mac;
						$data['login_ip']=$this->ip;
						$data['login_count']=$user['login_count']+1;
						$data['login_time']=time();
						$db->where($where)->save($data);
						exit;
					}
				}else{
					//log::write('不限制');
					$this->token=$tk;
					echo ("Auth: 1\n");
	                echo ("Messages: Allow Access\n");
	                $data['mac']=$this->mac;
					$data['login_ip']=$this->ip;
					$data['login_count']=$user['login_count']+1;
					$data['login_time']=time();
					$db->where($where)->save($data);
				}


			}
			*/
			}
			}
		}else{
			echo ("Auth: 1\n");
			echo ("Messages: Allow Access\n");
		}
	}
}