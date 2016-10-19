<?php
class UserAction extends BaseApiAction{
	private $userinfo;
	private  $tplkey="";
	private  function CheckUser(){
		if(cookie('token')!=""&&cookie('mid')!=""){
			$db=D('Member');
			$where['token']=session('token');
			$info=$db->where($where)->find();
			if($info){
				$this->userinfo=$info;
				$this->assign('user',$info);
			}else{
				$this->redirect(U('login',array('gw_addresssssssssssss'=>cookie('gw_address'),'gw_id'=>cookie('gw_id'),'gw_port'=>cookie('gw_port'))));
			}
		}else{
			$this->redirect(U('login',array('gw_address5'=>cookie('gw_address'),'gw_id'=>cookie('gw_id'),'gw_port'=>cookie('gw_port'))));
		}
	}
	
	private  function load_shopinfo(){
		if( cookie('gw_id')!=null){
			$sql="select a.*,b.shopname,b.authaction,b.share,b.jumpurl,b.tpl_path,b.pid,b.trade,b.vip from ".C('DB_PREFIX')."routemap a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id ";
			$sql.=" where a.gw_id='".cookie('gw_id')."' limit 1";
			$dbmap=D('Routemap');
			$info=$dbmap->query($sql);

			if($info!=false){
				cookie('shopid',$info[0]['id']);
				$this->tplkey=$info[0]['tpl_path'];
				$this->shop=$info;
				$this->assign("shopinfo",$info);
			}
			$dbmap=null;
		}
	}
	
	public function share(){
	
//			$route=D('routemap');
//			$shopId=$route->where(array('gw_id'=>cookie('gw_id')))->getField('shopid');
            $shopId=$_GET['sid'];
			$adv=D('Ad');
			$ad=$adv->Field('id,mode,ad_thumb')->where(array('uid'=>$shopId))->select();
			$this->assign('ad',$ad);
			$shopinfo=D('shop');
			$shopname=$shopinfo->where(array('id'=>$shopId))->getField('shopname');
			$address=$shopinfo->where(array('id'=>$shopId))->getField('address');
			$savepath=$shopinfo->where(array('id'=>$shopId))->getField('save_path');
			$message=$shopinfo->where(array('id'=>$shopId))->getField('message');
			$this->assign('shopname',$shopname);
			$this->assign('address',$address);
			$this->assign('savepath',$savepath);
			$this->assign('message',$message);
			$this->display();

	
		
//		    $where['uid'] = $info[0]['shopid'];
//           $ad = $addb->where($where)->field('id,ad_thumb,mode')->select();
//           $this->assign('ad', $ad);
//      
//	     	$this->display();
	}
	
	
	public function index(){
		
		$vip=D('vipmember');
		$authlist=D('authlist');
		$forweixin=$authlist->where(array('token'=>cookie('token')))->getField('forweixin');
		if($forweixin==1){
			$this->redirect('api/userauth/weixin');
		}
		$vipresult=$vip->where(array('token'=>cookie('token')))->find();
		if($vipresult){
			$jump=C('Jifei');
			$this->assign('jumpurl',$jump);
			$this->display();
		}
		else{
//		$this->CheckUser();
		$this->load_shopinfo();
		$agentpush = true;
		if(C('OPENPUSHLINK')){
//	  
	$agentpush = false;
		
		}
	    	
	if($agentpush){
		if($this->shop[0]['authaction']==4){
			
			$jump=U('api/user/share',array('sid'=>$this->shop[0]['shopid']));
			echo $jump;
		}else{
			 	$nowdate=getNowDate();
		    	$auth=M();
		   		$sql1="select mac from wifi_authlist where token='".cookie('token')."' and mac is not null and add_date='".$nowdate."'";
		    	$macinfo=$auth->query($sql1);
				$mac=$macinfo[0]['mac'];
				$a=D('Authlist');
				$sumnum=$a->where(array('mac'=>$mac,'add_date'=>$nowdate))->count();
				if($sumnum==1){
        		$sql="select shopid,link from wifi_authlist where mac='".$mac."' and add_date='".$nowdate."' order by login_time desc limit 1";
				}else{
				$sql="select shopid,link from wifi_authlist where mac='".$mac."' and add_date='".$nowdate."' order by login_time desc limit 1,1";
				}
				$linkinfo=$auth->query($sql);
				
				$agentid=D('shop');
				$aid=$agentid->where(array('id'=>$linkinfo[0]['shopid']))->getField('pid');
				$pushflag=D('agent');
				$pushlinks=$pushflag->where(array('id'=>$aid))->getField('pushlink');
				$trade=$agentid->where(array('id'=>$linkinfo[0]['shopid']))->getField('trade');
				if($pushlinks){
	    		$pushlink=M();
				$time=time();
				$spl3="select pic,id from wifi_pushadvlink where aid='".$aid."' and $time>startdate and $time<enddate and (trade=$trade or trade=0)";
				$url=$pushlink->query($spl3);
				$num=count($url);
				$numcout=$num-1;
				$jump=$url[$linkinfo[0]['link']]['pic'];
			    $picid=$url[$linkinfo[0]['link']]['id'];
				$a=D('Authlist');
			
			if(intval($linkinfo[0]['link'])<$numcout){
		    $data['link']=intval($linkinfo[0]['link'])+1;
			$a->where(array('token'=>cookie('token')))->save($data);
			}else{
	       $data['link']='0';
		    $a->where(array('token'=>cookie('token')))->save($data);
		   }
		}
		}	
		}else{
			if($this->shop[0]['authaction']==4){
			
			$jump=U('api/user/share',array('sid'=>$this->shop[0]['shopid']));
				
				}else{
				
				$pushlinks=1;	
				$nowdate=getNowDate();
		    	$auth=M();
		   		$sql1="select mac from wifi_authlist where token='".cookie('token')."' and mac is not null and add_date='".$nowdate."'";
		    	$macinfo=$auth->query($sql1);
				$mac=$macinfo[0]['mac'];
        		$a=D('Authlist');
				$sumnum=$a->where(array('mac'=>$mac,'add_date'=>$nowdate))->count();
				if($sumnum==1){
        		$sql="select shopid,link from wifi_authlist where mac='".$mac."' and add_date='".$nowdate."' order by login_time desc limit 1";
				}else{
				$sql="select shopid,link from wifi_authlist where mac='".$mac."' and add_date='".$nowdate."' order by login_time desc limit 1,1";
				}
				$linkinfo=$auth->query($sql);
			
				$agentid=D('shop');
				$aid=$agentid->where(array('id'=>$linkinfo[0]['shopid']))->getField('pid');
				$trade=$agentid->where(array('id'=>$linkinfo[0]['shopid']))->getField('trade');
	    		$pushlink=M();
				$time=time();
			    $spl3="select pic,id from wifi_pushadvlink where (aid=0 or aid=$aid) and startdate<$time and enddate>$time and (trade=$trade or trade=0) and state=1";
				$url=$pushlink->query($spl3);
				$num=count($url);
				$numcout=$num-1;

				$jump=$url[$linkinfo[0]['link']]['pic'];
				
				$picid=$url[$linkinfo[0]['link']]['id'];
				$a=D('Authlist');
			if(intval($linkinfo[0]['link'])<$numcout){
		    $data['link']=intval($linkinfo[0]['link'])+1;
			$a->where(array('token'=>cookie('token')))->save($data);
			}else{
	       
			$data['link']='0';
		     $a->where(array('token'=>cookie('token')))->save($data);
		   }
		   }
		}
if($this->shop[0]['vip']==1){//VIP跳转链接
	if(C('OPENPUSHLINK')){
				$pushlink=M();
				$time=time();
			    $spl3="select pic,id from wifi_pushadvlink where (aid=0 or aid=$aid) and startdate<$time and enddate>$time and (trade=$trade or trade=0) and state=1 and vip=1";
				$url=$pushlink->query($spl3);
				$num=count($url);
				if($num){
				$pushlinks=1;	
				$nowdate=getNowDate();
		    	$auth=M();
		   		$sql1="select mac from wifi_authlist where token='".cookie('token')."' and mac is not null and add_date='".$nowdate."'";
		    	$macinfo=$auth->query($sql1);
				$mac=$macinfo[0]['mac'];
        		$a=D('Authlist');
				$sumnum=$a->where(array('mac'=>$mac,'add_date'=>$nowdate))->count();
				if($sumnum==1){
        		$sql="select shopid,link from wifi_authlist where mac='".$mac."' and add_date='".$nowdate."' order by login_time desc limit 1";
				}else{
				$sql="select shopid,link from wifi_authlist where mac='".$mac."' and add_date='".$nowdate."' order by login_time desc limit 1,1";
				}
				$linkinfo=$auth->query($sql);
			
				$agentid=D('shop');
				$aid=$agentid->where(array('id'=>$linkinfo[0]['shopid']))->getField('pid');
				$trade=$agentid->where(array('id'=>$linkinfo[0]['shopid']))->getField('trade');
				$numcout=$num-1;
				$jump=$url[$linkinfo[0]['link']]['pic'];
			
				$picid=$url[$linkinfo[0]['link']]['id'];
				$a=D('Authlist');
			if(intval($linkinfo[0]['link'])<$numcout){
		    $data['link']=intval($linkinfo[0]['link'])+1;
			$a->where(array('token'=>cookie('token')))->save($data);
				}else{
	       
				$data['link']='0';
		   		$a->where(array('token'=>cookie('token')))->save($data);
		   		}
				}else{
				$pushlinks= false;
				}
		}else{
			 $pushlinks= false;
		}
}
	
	if($num&&$pushlinks){
			//exit(print_r($ads));
						/*ͳ��չʾ*/
            $tr = new Model();
            $time = time();
            $tr->startTrans();
            $arrdata['showup']=1;
            $arrdata['hit']=0;
            $arrdata['shopid']=$this->shop[0]['shopid'];
            $arrdata['add_time']=$time;
            $arrdata['add_date']=getNowDate();
             if($agentid > 0){
                    $arrdata['mode'] = 50;
                    $arrdata['agent'] = $aid;
                }else{
                    $arrdata['mode'] = 99;
                    $arrdata['agent'] = 0;
                }
                $arrdata['aid']=$picid;
                $tr->table(C('DB_PREFIX')."adcountlink")->add($arrdata);
            
            $tr->commit();
			//$this->assign('ad',$ads);
	}else{
		switch($this->shop[0]['authaction']){
			case "1":
				$jump=$this->shop[0]['jumpurl'];
				break;
			case "0":

				break;
			case "2":
				if(cookie('gw_url')!=null){
			    	$jump=cookie('gw_url');
			    }
				break;
			case "3":
				$jump=U('api/wap/index',array('sid'=>$this->shop[0]['shopid']));
				break;
            case "4":
				$jump=U('api/user/share',array('sid'=>$this->shop[0]['shopid']));
//              $jump="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=".$this->shop[0]['share']."&desc=&style=&otype=share&showcount=";
                break;
		}
        //$jump = "http://www.wifi01.cn";
		}
              
	    $this->assign('jumpurl',$jump);

		if(empty($this->tplkey)||$this->tplkey==""||strtolower($this->tplkey)=="default"){
				$this->display();
		}else{
			$this->display("index$".$this->tplkey);
		}
	}
	}
	}
