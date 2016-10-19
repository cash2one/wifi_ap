<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/16
 * Time: 11:09
 */
Class LoginAction extends BaseAction{

    public function index()
	{     $date=array();
		  if(IS_POST){
    	  $dt_start = strtotime($_POST['sdate']);
  		  $dt_end   = strtotime($_POST['edate']);
    do { 
        //将 Timestamp 转成 ISO Date 输出
       $cxdays=D('cxdays');
	   $lineinfo=$cxdays->where(array('shopid'=>session('uid'),'date'=>date('Y-m-d', $dt_start)))->field('00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23')->find();
	if($lineinfo){
			$date[]=array(date('Y-m-d', $dt_start),array_sum($lineinfo));
		
	}else{
		$date[]=array(date('Y-m-d', $dt_start),0);
	}
    } while (($dt_start += 86400) <= $dt_end); 
	$this->ajaxReturn($date);
    }
    	
		
		
		    $yesnum=0;
	     $todaynum=0;
		 $monthnum=0;
        $uid = $_SESSION['uid'];
        $res = M('shop')->where(array('id'=>$uid))->getField('mode');
        $this->assign('res',$res);
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
//         $date=date("Y-m-d",strtotime('-1 days'));
		   $cxdays=D('cxdays');
		   $tiaojian['shopid']=session('uid');
		   $tiaojian['update_yuancheng']=array('gt',$timestamp);
		   $lineinfo=$routemap->where($tiaojian)->field('wlan_user_num')->select();
		   if($lineinfo){
		   
		   	foreach($lineinfo as $k=>$v){
          	$yesnum=$yesnum+$v['wlan_user_num'];
			}
			}else{
		   	$yesnum=0;
		   	
		   }
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
	$todays=json_encode($today);
	 foreach($today as $k=>$v){
          	$todaynum=$todaynum+$v;
			}
}
		$months=array();
         $start=date('Y-m-01', strtotime(date("Y-m-d")));
		 $end=date('Y-m-d',strtotime('-1 days'));
		 $map['date']=array('between',array($start,$end));
		 $map['shopid']=session('uid');
		$month=$cxdays->where($map)->field('date,00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23')->select();
		foreach($month as $k=>$v){
			$months[]=array(array_shift($v),array_sum($v));
			$monthnum=$monthnum+array_sum($v);
		}
        $monthjs=json_encode($months);
		$ways=D('ways');
		$waysinfo=$ways->where(array('shopid'=>session('uid')))->find();
		$mem=D('member');
	  	$db=M();
		$shopid=session('uid');
		$sql="select count(*) from (select count(*) from wifi_member where shopid=$shopid group by user) a";
		$vipsums=$db->query($sql);
		$vipsum=$vipsums[0]['count(*)'];
//	  $map['shopid']=session('uid');
//	  $map['add_date']=array('lt',date('Y-m-d'));
//	  $map['update_time']=array('gt',strtotime(date('Y-m-d')));
	  $add_date=date('Y-m-d');
	  $update_time=strtotime(date('Y-m-d'));
	  $sql="select count(*) from (select count(*) from wifi_member where shopid=$shopid and add_date<'$add_date' and update_time>$update_time group by user) a";
	  $todayvips=$db->query($sql);
	 $todayvip=$todayvips[0]['count(*)'];

//	  
	  
	
	 $add_date=date('Y-m-d',strtotime('-7 days'));
	$update_time=strtotime('-7 days');
	  $sql="select count(*) from (select count(*) from wifi_member where shopid=$shopid and add_date<'$add_date' and update_time>$update_time group by user) a";
	  $yesvips=$db->query($sql);
	  $yesvip=$yesvips[0]['count(*)'];
	   $sql="select count(*) from(select count(*) from (select * from wifi_member where shopid=$shopid and add_date<'$add_date' and update_time<$update_time order by update_time desc) a group by a.user) b";
	  $qtliushis=$db->query($sql);
	  $qtliushi=$qtliushis[0]['count(*)'];

	 
	 
	  $add_date=date('Y-m-d',strtotime('-30 days'));
	  $update_time=strtotime('-30 days');
	  $sql="select count(*) from(select count(*) from (select * from wifi_member where shopid=$shopid and add_date<'$add_date' and update_time<$update_time order by update_time desc) a group by a.user) b";
	  $yueliushis=$db->query($sql);
	  $yueliushi=$yueliushis[0]['count(*)'];
	  $tmap['shopid']=session('uid');
	  $tmap['add_date']= array('lt',date('Y-m-d',strtotime('-90 days')));
	  $tmap['update_time']=array('lt',strtotime('-90 days'));
	  $jiduliushi=$mem->where($tmap)->Distinct(true)->field('user')->count();
	 
	  $add_date=date('Y-m-d',strtotime('-30 days'));
//	  array('lt',date('Y-m-d',strtotime('-30 days')));
	  $update_time=strtotime('-30 days');
	   $sql="select count(*) from (select count(*) from wifi_member where shopid=$shopid and add_date<'$add_date' and update_time>$update_time group by user ) a";
	  $monthvips=$db->query($sql);
	  $monthvip=$monthvips[0]['count(*)'];
	  $sql="select * from wifi_member where shopid=$shopid order by login_count desc limit 0,5;";
	  $info=$db->query($sql);
	  $app=D('app');
	  $appinfo=$app->where(array('uid'=>$shopid))->count();
	
	  $this->assign('app',$appinfo);
	  $this->assign('lists',$info);

		$this->assign('c',$waysinfo['00']);
		$this->assign('d',$waysinfo['01']);
		$this->assign('e',$waysinfo['02']);
		$this->assign('f',$waysinfo['03']);
		$this->assign('g',$waysinfo['04']);
			$this->assign('a',$a);
			$this->assign('b',$b);
			$this->assign('num',$num);
			$this->assign('list',$code);
			$this->assign('todays',$todays);
			$this->assign('todaynum',$todaynum);
//			$this->assign('yesterdays',$yesterdays);
			$this->assign('yesnum',$yesnum);
			$this->assign('monthjs',$monthjs);
			$this->assign('monthnum',$monthnum);
			$this->assign('vipsum',$vipsum);
			$this->assign('todayvip',$todayvip);
			$this->assign('yesvip',$yesvip);
			$this->assign('monthvip',$monthvip);
			$this->assign('qtliushi',$qtliushi);
			$this->assign('yueliushi',$yueliushi);
			$this->assign('jiduliushi',$jiduliushi);
			
		$ends=date('Y-m-d');
		 $starts=date('Y-m-d',strtotime('-30 days'));
		 $map['date']=array('between',array($starts,$ends));
		 $map['shopid']=session('uid');
		$monthinfo=$cxdays->order('id desc')->limit(30)->where($map)->field('00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23')->select();
		
		foreach($monthinfo as $k=>$v){
			arsort($v);	
			$new=array_slice($v,0,3,true);
			ksort($new);
		foreach($new as $x=>$y){
			$riqi[]=$x;
		}
		}
		$riqi=array_count_values($riqi);
		arsort($riqi);
		$maxtime=key($riqi);
		$maxtimes=current($riqi);
		$this->assign('maxtime',$maxtime);
		$this->assign('maxtimes',$maxtimes);
		$max=array_slice($riqi,0,5,true);
		$address=$routemap->where(array('shopid'=>session('uid')))->field('routename,renzheng_num')->select();
		foreach($address as $k=>$v){
			$arr[$v['routename']]=$v['renzheng_num'];
		}
        arsort($arr);
		$arr=array_slice($arr,0,5,true);
		$addressmax=array_search(max($arr), $arr);
		$this->assign('addressmax',$addressmax);
		$this->assign('address',$arr);
		
		
		
		
		$member=M();
		$shopid=session('uid');
	 $sql="select * from wifi_member where shopid=$shopid order by login_count desc limit 0,5 ";
	  $info=$member->query($sql);
foreach($info as $k=>$v){
	$arrs[$v['user']]=$v['login_count'];
}
		
		$yxmax=array_search(max($arrs), $arrs);
		$this->assign('yxmax',$yxmax);
		$this->assign('yx',$arrs);
		
		
		
		
		$this->assign('max',$max);
		$this->assign('nav','gailan');
		
			
        if(IS_GET){
            $type = $_GET['ordtype'];
            if($type == "payed"){
                M('shop')->where(array('id'=>$uid))->setField('mode',1);
			
                $this->display();
            }else{
                $this->display();
            }
        }else{
        	
			 $this->display();
        }
    }

    /**
     * 注册
     */
    public function reg()
    {
        $this->display();
    }
	 public function vipup()
    {
    	  $id=I('get.id');
		  $jifei=session('jifei');
		  $shop=D('shop');
		  if($jifei==1){
		  	if(isset($id)){
			$shop=D('shop');
			$vo=$shop->where(array('id'=>$id))->setField('vip',1);
			if($vo){
			$this->success('后台正在审核中！');
			}else{
			$this->success('正在审核中！');
			}
			}else{
			$this->error('参数错误');
			}
		  }else{
		  $routemap=D('routemap');
		  $info=$routemap->where(array('shopid'=>$id))->field('gw_id')->select();
		  if(empty($info)){
		  	$this->error('该商户名下还未绑定设备！请先绑定设备');
		  }else{
		  	$route=array();
		  	foreach($info as $k=>$v){
		  		preg_match('/(.*?)-(\(.*?\)|(.*?))/i', $v['gw_id'],$arr);
			    $route[]=$arr[1];
		  	}
			
			$a=array_count_values($route);
			$b=array();
			$c=array();
			$f=0;
			$e=array('sxtg720'=>80,
					'sxtg730'=>80,
					'sxtg350'=>80,
					'sxtg580'=>100,
					'sxtg760'=>100,
					'sxtg980'=>100,
					'sxtgac300'=>200,
					'sxtgac500'=>300,
					'sxtg318'=>300,
					'sxtg518'=>500,
					'sxtg518l'=>800,
					'sxtg818'=>1000,
					'sxtg818l'=>1000
					);
			foreach($a as $x=>$y){
				$b['xinghao']=$x;
				$xiaoxie=strtolower($x);
				$b['num']=$y;
				$b['biaozhun']=$e[$xiaoxie];
				$b['zongji']=$e[$xiaoxie]*$y;
				$f+=$b['zongji'];
				$d=array_push($c,$b);
				
			}
			$shopinfo=$shop->where(array('id'=>$id))->find();
			$this->assign('info',$shopinfo);
			$this->assign('time',date("Y-m-d H:i:s"));
		 	$this->assign('chongzhi',$f);
		  	$this->assign('lists',$c);
			$this->display();
		  }
 }
}

public function vipxu()
    {
    	  $id=I('get.id');
	if(isset($id)){
		$shop=D('shop');
		$vo=$shop->where(array('id'=>$id))->setField('vipflag',2);
		if($vo){
			$this->success('后台正在审核中！');
			
		}else{
			$this->success('正在审核中！');
		}
		
	}else{
		$this->error('参数错误');
	}
    	
    }

public function appjishu()
    {
   import('@.ORG.UserPage');
	$vip=D('app');
	$where['uid']=session('uid');
	 $count=$vip->where($where)->count();
	 $page=new UserPage($count,20);
	$info=$vip->where($where)->limit($page->firstRow.','.$page->listRows)->order('time desc')->select();
	 $this->assign('page',$page->show());
	
	$this->assign('lists',$info);

	$this->display();
	
}
    	
    


	public function nouse(){
	
	if($_GET['ordtype']=='payed'){
		
		
		$this->display('pay');
	}else{
		
		$this->display('unpay');
	}
	}
	public function paihang(){
		$db=M();
		$shopid=session('uid');
	 $sql="select * from wifi_member where shopid=$shopid and login_count>1 order by login_count desc ";
	  $info=$db->query($sql);
	  $this->assign('lists',$info);
	$this->display();
	}
    /**
     * 执行注册操作
     */
    public function doregist()
    {
        if(IS_POST){
            C('TOKEN_ON',false);
            $hid = isset($_POST['doact']) ? strval($_POST['doact']) : '';
            if($hid == 'doreg')
            {
                $user = D('Shop');
                $_POST['mode']=0;//注册用户
                $_POST['authmode']='#0#';//注册用户
                $_POST['authaction']='1';//认证后调整模式
                $account = $_POST['account'];
                $res = $user->where(array('account'=>$account))->find();
                if($res){
                    $data['error']=1;
                    $data['msg']="用户名已存在";
                    return $this->ajaxReturn($data);
                }
                if ($user->create()){
                    $aid=$user->add();
                    if($aid)
                    {
                        session('uid',$aid);
                        session('user',$_POST['account']);
                        session('shop_name',$_POST['shopname']);

                        $data['error']=0;
                        $data['msg']="OK";
                        $data['url']=U('Index/login/index');

                        return $this->ajaxReturn($data);
                    }else{
                        $data['error']=1;
                        $data['msg']="服务器忙，请稍候再试";
                        return $this->ajaxReturn($data);
                    }
                }else{
                    $data['error']=1;
                    $data['msg']=$user->getError();
                    return $this->ajaxReturn($data);
                }
            }else{
                $data['error']=1;
                $data['msg']="服务器忙，请稍候再试";
                return $this->ajaxReturn($data);
            }
        }else{
            $data['error']=1;
            $data['msg']="服务器忙，请稍候再试";
            return $this->ajaxReturn($data);
        }

    }

    /**
     * 登录展示
     */
    public function login()
    {
        $this->display();
    }

  public function xiangqing()
    {
     $riqi=I('get.riqi');
	 $mem=D('member');
	switch ($riqi){
		case 1:
      $map['shopid']=session('uid');
	  $map['add_date']=array('lt',date('Y-m-d'));
	  $map['update_time']=array('gt',strtotime(date('Y-m-d')));
	  $info=$mem->where($map)->select();
	 $tianshu="1天";
	  break;
		case 7:
	  $map['shopid']=session('uid');
	  $map['add_date']= array('lt',date('Y-m-d',strtotime('-7 days')));
	  $map['update_time']=array('gt',strtotime('-7 days'));
	  $info=$mem->where($map)->select();
	 $tianshu="7天";
	 break;
	 case 30:
	  $map['shopid']=session('uid');
	  $map['add_date']= array('lt',date('Y-m-d',strtotime('-30 days')));
	  $map['update_time']=array('gt',strtotime('-30 days'));
	  $info=$mem->where($map)->select();
	 $tianshu="30天";
	 break;
	}
	    $this->assign('lists',$info);
		$this->assign('tianshu',$tianshu);
        $this->display();
    }
	 public function liushi()
    {
     $riqi=I('get.riqi');
	 $mem=D('member');
	switch ($riqi){
		
	  case 7:
	  $map['shopid']=session('uid');
	  $map['add_date']= array('lt',date('Y-m-d',strtotime('-7 days')));
	  $map['update_time']=array('lt',strtotime('-7 days'));
	  $info=$mem->where($map)->order('update_time desc')->group('user')->select();
	 $tianshu="7天";
	 break;
	 case 30:
	  $map['shopid']=session('uid');
	  $map['add_date']= array('lt',date('Y-m-d',strtotime('-30 days')));
	  $map['update_time']=array('lt',strtotime('-30 days'));
	  $info=$mem->where($map)->order('update_time desc')->group('user')->select();
	 $tianshu="30天";
	 break;
	  case 90:
	  $map['shopid']=session('uid');
	  $map['add_date']= array('lt',date('Y-m-d',strtotime('-90 days')));
	  $map['update_time']=array('lt',strtotime('-90 days'));
	  $info=$mem->where($map)->order('update_time desc')->group('user')->select();
	 $tianshu="90天";
	 break;
	}
	    $this->assign('lists',$info);
		$this->assign('tianshu',$tianshu);
        $this->display();
    }
    /**
     * 执行登录操作
     */
    public function dologin()
    {
        if(IS_POST){
            $user = isset($_POST['user']) ? strval($_POST['user']) : '';
            $pass = isset($_POST['password']) ? strval($_POST['password']) : '';
			$shoptype=isset($_POST['shoptype']) ? strval($_POST['shoptype']) : '';

            $userM = M('Shop');
            $uid = $userM->where(array('account'=>$user,'password'=>md5($pass)))->field('id,account,jifei,shopname,pid,vip,vipflag,vip_expire,app_renzheng,save_path')->find();
			
            //log::write($userM->getLastSql());
            if($uid)
            {
            	if(($uid['vip']==1)&&($uid['vipflag']==0)){
            		  $data['error']=1;
               		  $data['msg']="该账户正在审核中,请联系QQ：".C('qq');;
                	return $this->ajaxReturn($data);
					
            	}
				if($uid['vip']==1){
					if(($uid['vip_expire']-time()<2592000)&&($uid['vip_expire']-time()>0)){
						session('expire',1);
						session('expiredate',date("Y-m-d",$uid['vip_expire']));
					}else{
						if($uid['vip_expire']-time()<0){
							$data['vip']=0;
							$data['vipflag']=0;
							$data['vip_expire']='';
							$userM->where(array('id'=>$uid['id']))->save($data);
							}else{
						  session('expire',0);
						}
					}
				}
				if($shoptype!=$uid['jifei']){
					if($shoptype==1){
					 $datas['error']=1;
               		 $datas['msg']="开通计费平台请联系QQ:".C('qq');;
               		 return $this->ajaxReturn($datas);
               		 }else{
               		 $datas['error']=1;
               		 $datas['msg']="请选择计费平台登录";
               		 return $this->ajaxReturn($datas);
               		 }
					
				}
				if($uid['app_renzheng']==1){
					
					 session('app',1);
				}
                session('uid',$uid['id']);
				session('vip',$uid['vip']);
				session('jifei',$uid['jifei']);
                session('user',$uid['account']);
                session('shop_name',$uid['shopname']);
				session('save_path',$uid['save_path']);
			
                session('pid',$uid['pid']);
                $data['error']=0;
                $data['msg']="";
				if($uid['jifei']==1){
				$data['url']=U('Index/Login/jifei');
				}else{
				$data['url']=U('Index/login/index');
				}
               
                return $this->ajaxReturn($data);
            }else{
                $data['error']=1;
                $data['msg']="帐号信息不正确";
                return $this->ajaxReturn($data);
            }
        }else{
            $data['error']=1;
            $data['msg']="服务器忙，请稍候再试";
            return $this->ajaxReturn($data);
        }
    }
public function jifei(){
	$now=time()-300;
    $route=D('routemap');
	$where['shopid']=session('uid');
	$where['update_yuancheng']=array('gt',$now);
	$rtinfo=$route->where($where)->field('wlan_user_num')->select();
	$sbnum=count($rtinfo);//设备数量
	$this->assign('sbnum',$sbnum);
	foreach($rtinfo as $k=>$v){
		$ren+=$v['wlan_user_num'];
	}
	$this->assign('ren',$ren); //在线人数
	$today=strtotime(date('Y-m-d'));
	$expire=strtotime("+ 7 days");
	
	$mem=D('vipmember');
	$wherem['update_time']=array('gt',$today);
	$wherem['uid']=session('uid');
    $memnum=$mem->where($wherem)->count();
	$this->assign('memnum',$memnum);//活跃会员数
	$whered['expire_time']=array('lt',$today);
	$whered['uid']=session('uid');
	$whered['type']=2;
    $daoqid=$mem->where($whered)->count();
	
	
	$this->assign('daoqid',$daoqid);
	$wheredd['expire_time']=array('between',"$today,$expire");
	$wheredd['uid']=session('uid');
	$wheredd['type']=2;
    $daoqi=$mem->where($wheredd)->count();
	$this->assign('daoqi',$daoqi);
	$zfb=D('zfbvip');
	$wherez['notify_time']=array('gt',$today);
	$wherez['orderid']=array('exp','is not null');
	$wherez['buyer_email']=array('exp','is not null');
	$wherez['uid']=session('uid');
	$zfbtsy=$zfb->where($wherez)->field('total_fee')->select();
	foreach($zfbtsy as $k=>$v){
		$zfbshouyi+=$v['total_fee'];
	}
	
	$wherem['notify_time']=array('gt',$today);
	$wherem['orderid']=array('exp','is null');
	$wherem['buyer_email']='Payed';
	$wherem['uid']=session('uid');
	$zfbtwx=$zfb->where($wherem)->field('total_fee')->select();
	foreach($zfbtwx as $k=>$v){
		$weixinshouyi+=$v['total_fee'];
	}
	
	
	$todayshouyi=$zfbshouyi+$weixinshouyi/100;
	$this->assign('todayshouyi',$todayshouyi);//今日收益
	
	
	$wherewjs['buyer_email']='Payed';
	$wherewjs['jiesuand']=0;
	$wherewjs['zhichi']=0;
	$wherewjs['uid']=session('uid');
	$wxjs=$zfb->where($wherewjs)->field('total_fee')->select();
	foreach($wxjs as $k=>$v){
		$weixinjs+=$v['total_fee'];
	}

	$wherezjs['buyer_email']=array('exp','is not null');
	$wherezjs['orderid']=array('exp','is not null');
	$wherezjs['jiesuand']=0;
	$wherezjs['zhichi']=0;
    $wherezjs['uid']=session('uid');
	$zjs=$zfb->where($wherezjs)->field('total_fee')->select();
	foreach($zjs as $k=>$v){
		$zfbjs+=$v['total_fee'];
	}
   
	$jsshouyi=$zfbjs+$weixinjs/100;
	$this->assign('jsshouyi',$jsshouyi);//未结算收益
	
	
	$wherewjsd['buyer_email']='Payed';
	$wherewjsd['jiesuand']=1;
	$wherewjsd['zhichi']=0;
	$wherewjsd['uid']=session('uid');
	$wxjsd=$zfb->where($wherewjsd)->field('total_fee')->select();
	foreach($wxjsd as $k=>$v){
		$weixinjsd+=$v['total_fee'];
	}

	$wherezjsd['buyer_email']=array('exp','is not null');
	$wherezjsd['orderid']=array('exp','is not null');
	$wherezjsd['jiesuand']=1;
	$wherezjsd['zhichi']=0;
	$wherezjsd['uid']=session('uid');
	$zjsd=$zfb->where($wherezjsd)->field('total_fee')->select();
	foreach($zjsd as $k=>$v){
		$zfbjsd+=$v['total_fee'];
	}
	$jsshouyid=$zfbjsd+$weixinjsd/100;//结算收益
	$this->assign('jsshouyid',$jsshouyid);
	
	
	
	$this->display();
}




    /**
     * 登录跳转
     */
    private function isLogin()
    {
        if(!session('uid'))
        {
            $this->redirect('index/index/log');
        }
    }

    /**
     *修改密码
     */
    public function account()
    {
        $this->isLogin();
        $this->assign('a','account');
        $this->display();
    }

    /**
     * 执行修改密码
     */
    public function doaccount()
    {
        $this->isLogin();
        $uid = session('uid');
        $pass = isset($_POST['password']) ? $_POST['password'] : '';
        if($pass)
        {
            $user = D('Shop');
            if($user->create()){
                if($user->where("id = {$uid}")->save())
                {
                    $this->success('修改密码成功');
                }else{
                    $this->error('修改密码错误');
                }
            }
        }else{
            $this->error('密码不允许为空');
        }
    }

    /**
     * 退出
     */
    public function logout()
    {
        session(null);
//      $this->redirect('index/index/log');
	header('Location: http://'.$_SERVER['HTTP_HOST']);
    }
}