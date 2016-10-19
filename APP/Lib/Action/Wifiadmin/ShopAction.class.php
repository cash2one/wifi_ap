<?php
/**
 * Class wifiadmin 商户信息
 */
class ShopAction extends  AdminAction{
    /**
     * 商户信息
     */
	public function index(){
		$this->doLoadID(300);
		import('@.ORG.AdminPage');
		$db=D('Shop');
		if(IS_POST){

			if(isset($_POST['sname'])&&$_POST['sname']!=""){
					$map['sname']=$_POST['sname'];
					$where =" and a.shopname like '%%%s%%'";
			}
			if(isset($_POST['slogin'])&&$_POST['slogin']!=""){
					$map['slogin']=$_POST['slogin'];
					$where.=" and a.account like '%%%s%%'";
			}
			if(isset($_POST['phone'])&&$_POST['phone']!=""){
					$map['phone']=$_POST['phone'];
					$where.=" and a.phone like '%%%s%%'";
			}
			if(isset($_POST['agent'])&&$_POST['agent']!=""){
					$map['agent']=$_POST['agent'];
					$where.=" and b.name like '%%%s%%'";
			}
            if(isset($_POST['trade'])&&$_POST['trade']!=""){
                if($_POST['trade'] == 0){
                    $map['trade']=$_POST['trade'];
                    $where .="";
                }else{
                    $map['trade']=$_POST['trade'];
                    $where .=" and a.trade like '%%%s%%'";
                }
            }
			
			$_GET['p']=0;
		}else{

			if(isset($_GET['sname'])&&$_GET['sname']!=""){
					$map['sname']=$_GET['sname'];
					$where =" and a.shopname like '%%%s%%'";
			}
			if(isset($_GET['slogin'])&&$_GET['slogin']!=""){
					$map['slogin']=$_GET['slogin'];
					$where.=" and a.account like '%%%s%%'";
			}
			if(isset($_GET['phone'])&&$$_GET['phone']!=""){
					$map['phone']=$_GET['phone'];
					$where.=" and a.phone like '%%%s%%'";
			}
			if(isset($_GET['agent'])&&$_GET['agent']!=""){
					$map['agent']=$_GET['agent'];
					$where.=" and b.name like '%%%s%%'";
			}
            if(isset($_GET['trade'])&&$_GET['trade']!=""){
                if($_POST['trade'] == 0){
                    $map['trade']=$_POST['trade'];
                    $where .=" or a.trade like '%%%s%%'";
                }else{
                    $map['trade']=$_POST['trade'];
                    $where .=" and a.trade like '%%%s%%'";
                }
            }
		}
		
		$sqlcount=" select count(*) as ct from ". C('DB_PREFIX')."shop a left join ". C('DB_PREFIX')."agent b on a.pid=b.id ";
		if(!empty($where)){
			$sqlcount.=" where true ".$where;
		}
		$rs=$db->query($sqlcount,$map);
//        $sql = $db->getLastSql();p($sql);
		
		$count=$rs[0]['ct'];
		$page=new AdminPage($count,C('ADMINPAGE'));
		foreach($map as $k=>$v){
			$page->parameter.=" $k=".urlencode($v)."&";//赋值给Page";
		}
		
		$sql=" select a.id,a.shopname,a.messnum,a.add_time,a.linker,a.phone,a.trade,a.account,a.maxcount,a.linkflag,a.app_renzheng,b.name as agname from ". C('DB_PREFIX')."shop a left join ". C('DB_PREFIX')."agent b on a.pid=b.id ";
		if(!empty($where)){
			$sql.=" where true ".$where;
		}
		$sql.=" order by a.add_time desc limit ".$page->firstRow.','.$page->listRows." ";
		$result = $db->query($sql,$map);

        include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('enumdata',$enumdata);
        
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
public function jifei(){
		$this->doLoadID(300);
		import('@.ORG.AdminPage');
		$db=D('Shop');
		if(IS_POST){

			if(isset($_POST['sname'])&&$_POST['sname']!=""){
					$map['sname']=$_POST['sname'];
					$where =" and a.shopname like '%%%s%%'";
			}
			if(isset($_POST['slogin'])&&$_POST['slogin']!=""){
					$map['slogin']=$_POST['slogin'];
					$where.=" and a.account like '%%%s%%'";
			}
			if(isset($_POST['phone'])&&$_POST['phone']!=""){
					$map['phone']=$_POST['phone'];
					$where.=" and a.phone like '%%%s%%'";
			}
			if(isset($_POST['agent'])&&$_POST['agent']!=""){
					$map['agent']=$_POST['agent'];
					$where.=" and b.name like '%%%s%%'";
			}
            if(isset($_POST['trade'])&&$_POST['trade']!=""){
                if($_POST['trade'] == 0){
                    $map['trade']=$_POST['trade'];
                    $where .="";
                }else{
                    $map['trade']=$_POST['trade'];
                    $where .=" and a.trade like '%%%s%%'";
                }
            }
			
			$_GET['p']=0;
		}else{

			if(isset($_GET['sname'])&&$_GET['sname']!=""){
					$map['sname']=$_GET['sname'];
					$where =" and a.shopname like '%%%s%%'";
			}
			if(isset($_GET['slogin'])&&$_GET['slogin']!=""){
					$map['slogin']=$_GET['slogin'];
					$where.=" and a.account like '%%%s%%'";
			}
			if(isset($_GET['phone'])&&$$_GET['phone']!=""){
					$map['phone']=$_GET['phone'];
					$where.=" and a.phone like '%%%s%%'";
			}
			if(isset($_GET['agent'])&&$_GET['agent']!=""){
					$map['agent']=$_GET['agent'];
					$where.=" and b.name like '%%%s%%'";
			}
            if(isset($_GET['trade'])&&$_GET['trade']!=""){
                if($_POST['trade'] == 0){
                    $map['trade']=$_POST['trade'];
                    $where .=" or a.trade like '%%%s%%'";
                }else{
                    $map['trade']=$_POST['trade'];
                    $where .=" and a.trade like '%%%s%%'";
                }
            }
		}
		$where .=" and a.jifei=1";
		$sqlcount=" select count(*) as ct from ". C('DB_PREFIX')."shop a left join ". C('DB_PREFIX')."agent b on a.pid=b.id ";
		if(!empty($where)){
			$sqlcount.=" where true ".$where;
		}
		$rs=$db->query($sqlcount,$map);
//        $sql = $db->getLastSql();p($sql);
		
		$count=$rs[0]['ct'];
		$page=new AdminPage($count,C('ADMINPAGE'));
		foreach($map as $k=>$v){
			$page->parameter.=" $k=".urlencode($v)."&";//赋值给Page";
		}
		
		$sql=" select a.id,a.shopname,a.messnum,a.jiesuan,a.add_time,a.linker,a.phone,a.trade,a.account,a.end,a.linkflag,a.shebeinum,a.feiyong,a.manager,b.name as agname from ". C('DB_PREFIX')."shop a left join ". C('DB_PREFIX')."agent b on a.pid=b.id ";
		if(!empty($where)){
			$sql.=" where true ".$where;
		}
		$sql.=" order by a.add_time desc limit ".$page->firstRow.','.$page->listRows." ";
		$result = $db->query($sql,$map);

        include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('enumdata',$enumdata);
        
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
public function shenhe(){
		$this->doLoadID(300);
		import('@.ORG.AdminPage');
		$db=D('Shop');
		if(IS_POST){

			if(isset($_POST['sname'])&&$_POST['sname']!=""){
					$map['sname']=$_POST['sname'];
					$where =" and a.shopname like '%%%s%%'";
			}
			if(isset($_POST['slogin'])&&$_POST['slogin']!=""){
					$map['slogin']=$_POST['slogin'];
					$where.=" and a.account like '%%%s%%'";
			}
			if(isset($_POST['phone'])&&$_POST['phone']!=""){
					$map['phone']=$_POST['phone'];
					$where.=" and a.phone like '%%%s%%'";
			}
			if(isset($_POST['agent'])&&$_POST['agent']!=""){
					$map['agent']=$_POST['agent'];
					$where.=" and b.name like '%%%s%%'";
			}
            if(isset($_POST['trade'])&&$_POST['trade']!=""){
                if($_POST['trade'] == 0){
                    $map['trade']=$_POST['trade'];
                    $where .="";
                }else{
                    $map['trade']=$_POST['trade'];
                    $where .=" and a.trade like '%%%s%%'";
                }
            }
			
			$_GET['p']=0;
		}else{

			if(isset($_GET['sname'])&&$_GET['sname']!=""){
					$map['sname']=$_GET['sname'];
					$where =" and a.shopname like '%%%s%%'";
			}
			if(isset($_GET['slogin'])&&$_GET['slogin']!=""){
					$map['slogin']=$_GET['slogin'];
					$where.=" and a.account like '%%%s%%'";
			}
			if(isset($_GET['phone'])&&$$_GET['phone']!=""){
					$map['phone']=$_GET['phone'];
					$where.=" and a.phone like '%%%s%%'";
			}
			if(isset($_GET['agent'])&&$_GET['agent']!=""){
					$map['agent']=$_GET['agent'];
					$where.=" and b.name like '%%%s%%'";
			}
            if(isset($_GET['trade'])&&$_GET['trade']!=""){
                if($_POST['trade'] == 0){
                    $map['trade']=$_POST['trade'];
                    $where .=" or a.trade like '%%%s%%'";
                }else{
                    $map['trade']=$_POST['trade'];
                    $where .=" and a.trade like '%%%s%%'";
                }
            }
		}
		$where .="and a.vip=1 and a.vipflag>=0";
		$sqlcount=" select count(*) as ct from ". C('DB_PREFIX')."shop a left join ". C('DB_PREFIX')."agent b on a.pid=b.id ";
		if(!empty($where)){
			$sqlcount.=" where true ".$where;
		}
		$rs=$db->query($sqlcount,$map);
//        $sql = $db->getLastSql();p($sql);
		
		$count=$rs[0]['ct'];
		$page=new AdminPage($count,C('ADMINPAGE'));
		foreach($map as $k=>$v){
			$page->parameter.=" $k=".urlencode($v)."&";//赋值给Page";
		}
		
		$sql=" select a.id,a.shopname,a.messnum,a.add_time,a.linker,a.vipflag,a.phone,a.trade,a.account,a.jifei,a.linkflag,a.vip_expire,a.shebeinum,a.feiyong,a.manager,c.name as managername,a.app_renzheng,b.name as agname from ". C('DB_PREFIX')."shop a left join ". C('DB_PREFIX')."agent b on a.pid=b.id left join wifi_manager c on a.manager=c.id";
		if(!empty($where)){
			$sql.=" where true ".$where;
		}
		$sql.=" order by a.add_time desc limit ".$page->firstRow.','.$page->listRows." ";
		$result = $db->query($sql,$map);

        include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('enumdata',$enumdata);
        
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
   public function jiesuan(){
   	$uid=I('get.id');
	$zfbvip=D('zfbvip');
	$where['uid']=$uid;
	$where['zhichi']=0;
	$where['jiesuan']=1;
	$where['notify_time']=array('exp','is not null');
	$jiesuan=$zfbvip->where($where)->select();
	
	foreach($jiesuan as $k=>$v){
		if($v['buyer_email']=='Payed'){
			$jiesuan[$k]['total_fee']=floatval($v['total_fee']/100);
			$arrw[]=$jiesuan[$k];
			$weixinsy+=$jiesuan[$k]['total_fee'];
		}else{
			$arrz[]=$jiesuan[$k];
			$zhifubaosy+=$jiesuan[$k]['total_fee'];
		}
	}
	if($arrw){
		if($arrz){
			$arr=array_merge($arrw,$arrz);
		}else{
			$arr=$arrw;
		}
		
	}else{
		$arr=$arrz;
	}
//	$arr=array_merge($arrw,$arrz);
//	print_r($arrz);
$this->assign('zhifubaosy',$zhifubaosy);
$this->assign('weixinsy',$weixinsy);
$this->assign('lists',$arr);
$this->assign('shopid',$uid);
$this->display();
   }


public function jiesuand(){
	$id=I('get.id');
	$zfbvip=D('zfbvip');
	$where['uid']=$id;
	$where['zhichi']=0;
	$where['jiesuan']=1;
	$where['notify_time']=array('exp','is not null');
	$shop=D('shop');
	$shop->where(array('id'=>$id))->setField('jiesuan',0);
	$jiesuan=$zfbvip->where($where)->setField('jiesuand',1);
	$zfbvip->where($where)->setField('jiesuan_time',time());
	if($jiesuan){
		$this->success('结算成功！',U('shop/jifei'));
	}else{
		$this->error('没有要结算的款项',U('shop/jifei'));
	}
}


    /**
     * 管理后台 添加商户信息
     */
    public function addshop(){
        $this->doLoadID(300);
        if(IS_POST){
            $user   = D('Shop');
            $now    = time();
            $_POST['pid'] = 0;
            $_POST['authmode'] = '#0#';
            $_POST['password']=md5($_POST['password']);
			$_POST['add_time']=time();
			if($_POST['vip']==1){
				$_POST['vipflag']=1;
				$_POST['vip_expire']=strtotime("+1 year");
			}
            if($user->create($_POST,1)){
                $id = $user->add();
                if($id>0){
                    /*
                    $rs['shopid']=$id;
                    $rs['sortid']=0;
                    $rs['routename']=$_POST['shopname'];
                    $rs['gw_id']=$_POST['account'];

                    M("Routemap")->data($rs)->add();
                    */
                    $data['error']=0;
                    $data['url']=U('index');
                    return $this->ajaxReturn($data);
                }else{
                    $data['error']=1;
                    $data['msg']=$user->getDbError();
                    return $this->ajaxReturn($data);
                }
            }else{
                $data['error']=1;
                $data['msg']=$user->getError();
                return $this->ajaxReturn($data);
            }
        }else{
            include CONF_PATH.'enum/enum.php';//$enumdata
            $db=D('manager');
			$info=D('manager')->select();
			$this->assign('manager',$info);
            $this->assign('enumdata',$enumdata);
            $this->display();
        }
    }
    /**
     * 管理后台 编辑商户信息
     */
	public function editshop()
	{
		$this->doLoadID(300);
		if(IS_POST){
			$order=D('morderlist');
			$user = D('Shop');
			$id=I('post.id','0','int');
	        $where['id']=$id;
	        $info=$user->where($where)->find();
	        if(!$info){
	        	$data['error']=1;
	    		$data['msg']="服务器忙，请稍候再试";
	    		return $this->ajaxReturn($data);
	        }
	       $_POST['messnum']=$_POST['messnum']+$_POST['messplus'];
		    $_POST['qunfa']=$_POST['qunfa']+$_POST['qunfaplus'];
	       $_POST['update_time']=time();
		   $sql="select * from wifi_morderlist where qunfa is not null order by id desc limit 0,1";
	 			$db=M();
	 				$result=$db->query($sql);
					
					$data['qunfaczh']=C('qunfa')-$_POST['qunfaplus'];
					$data['messnumczh']=C('yanzhengma')-$_POST['messplus'];
					$arr['qunfa']=$data['qunfaczh'];
					$arr['yanzhengma']=$data['messnumczh'];
					$data['uid']=0;
					$data['shopname']=$info['shopname'];
					$data['user']=$info['account'];
					$data['orderid']='zptcz'.time();
					$data['notify_time']=date("Y-m-d,H:i:s");
					$data['trade_status']='TRADE_SUCCESS';
					$data['message']="平台充值短信:群发充值".$_POST['qunfaplus']."!验证码充值".$_PSOT['messplus'];
					$order->data($data)->add();
					update_config($arr,CONF_PATH."site.php");
					if($_POST['jifei']==0){
						unset($_POST['start']);
						
						unset($_POST['end']);
					}
					$_POST['update_time']=time();
	        if($user->create($_POST,2)){
	        	if($user->where($where)->save($_POST)){
	        		$data['error']=0;
		    		$data['url']=U('index');
		    		return $this->ajaxReturn($data);
					
	        	}else{
	        		$data['error']=1;
		    		$data['msg']=$user->getError();
		    		return $this->ajaxReturn($data);
	        	}
	        }else{
                $data['error']=1;
                $data['msg']=$user->getError();
                return $this->ajaxReturn($data);
	        }
		}else{
			$id=I('get.id','0','int');
			$where['id']=$id;
			$db=D('Shop');
			$info=$db->where($where)->find();
			if(!$info){
				$this->error("参数不正确");
			}
			$this->assign("shop",$info);
			include CONF_PATH.'enum/enum.php';//$enumdata
	        $this->assign('enumdata',$enumdata);
	        $this->display();
		}
		
	}
    /**
     * 删除商户信息以及商户下面的路由信息
     */
    public function delshop(){
        $this->doLoadID(300);
        if(IS_GET){
            $shop = D('Shop');
            $id = I('id','0','int');
            $vo  = $shop->where(array('id'=>$id))->delete();
            $res = M('routemap')->where(array('shopid'=>$id))->delete();
            if($vo && $res){
                $this->success('删除商户信息',U('Shop/index'));
            }else{
                $this->error('删除成功',U('Shop/index'));
            }
        }else{
            halt('非法请求！');
        }
    }
	  public function permit(){
        $this->doLoadID(300);
        if(IS_GET){
            $shop = D('Shop');
            $id = I('id','0','int');
			$is=I('shenhe');
			if($is=='yes'){
            $vo  = $shop->where(array('id'=>$id))->setField('vipflag',1);
			$time=strtotime("+ 1 year");
			$shop->where(array('id'=>$id))->setField('vip_expire',$time);
			if($vo){
                $this->success('操作成功',U('Shop/shenhe'));
            }else{
                $this->error('操作失败');
            }
			}else{
			$vo  = $shop->where(array('id'=>$id))->setField('vip',0);
			$shop->where(array('id'=>$id))->setField('vipflag',0);
			 if($vo){
                $this->success('操作成功',U('Shop/shenhe'));
            }else{
                $this->error('操作失败');
            }
			}
          
           
        }else{
            halt('非法请求！');
        }
    }
	

  public function vipchange(){
        $this->doLoadID(300);
        if(IS_GET){
            $shop = D('Shop');
            $id = I('id','0','int');
			$is=I('shenhe');
			if($is=='yes'){
            $vip_expire  = $shop->where(array('id'=>$id))->getField('vip_expire');
			$time=strtotime("+ 1 year",$vip_expire);
			$shop->where(array('id'=>$id))->setField('vip_expire',$time);
			 $vo  = $shop->where(array('id'=>$id))->setField('vipflag',1);
			if($vo){
                $this->success('操作成功',U('Shop/shenhe'));
            }else{
                $this->error('操作失败');
            }
			}else{
			$shop->where(array('id'=>$id))->setField('vipflag',0);
			$shop->where(array('id'=>$id))->setField('vip_expire','');
			$vo  = $shop->where(array('id'=>$id))->setField('vip',0);
			 if($vo){
                $this->success('操作成功',U('Shop/shenhe'));
            }else{
                $this->error('操作失败');
            }
			}
          
           
        }else{
            halt('非法请求！');
        }
    }
  public function appkaiqi(){
        $this->doLoadID(300);
        if(IS_GET){
            $shop = D('Shop');
            $id = I('id','0','int');
			$vo  = $shop->where(array('id'=>$id))->setField('app_renzheng',1);
			if($vo){
                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }
		}else{
            halt('非法请求！');
        }
    }
    public function appguanbi(){
        $this->doLoadID(300);
        if(IS_GET){
            $shop = D('Shop');
            $id = I('id','0','int');
			$vo  = $shop->where(array('id'=>$id))->setField('app_renzheng',0);
			$authmode=$shop->where(array('id'=>$id))->getField('authmode');
			$auth=str_replace('#5#', "", $authmode);
			$shop->where(array('id'=>$id))->setField('authmode',$auth);
			if($vo){
                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }
		}else{
            halt('非法请求！');
        }
    }



public function manager(){
	$db=D('manager');
	if(IS_POST){
		$db->create();
		if($db->add()){
			$this->success('添加成功！');
		}else{
			$this->error('添加失败！');
		}
		
	}else{
	$info=$db->select();
	$this->assign('lists',$info);
	$this->display();
	}
	
}
public function managerc(){
	$db=D('manager');
	if(IS_POST){
		$db->create();
		if($db->where(array('id'=>$_POST['id']))->save()){
			$this->success('编辑成功!','manager');
		}else{
			$this->error('编辑失败!');
		}
	}else{
	$caozuo=I('get.caozuo');
	$id=I('get.id');
	switch ($caozuo){
		case 'bianji':
			$info=$db->where(array('id'=>$id))->find();
			$this->assign('info',$info);
			$this->display();
	       break;
		case 'shanchu':
			$result=$db->where(array('id'=>$id))->delete();
			if($result){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}
			break;
	}
	}
}
    /**
     * 修改商户密码
     */
    public function modpwd(){
        $this->doLoadID(300);
        $shop = D('Shop');
        $id = I('id','0','int');
        if(IS_POST){
            $pass = isset($_POST['password']) ? $_POST['password'] : '';
            $account = $_POST['account'];
            if($shop->where(array('account'=>$account))->setField('password',md5($pass)))
            {
                $this->success('修改密码成功',U('shop/index'));
            }else{
                $this->error('修改密码错误');
            }
        }else{
            $info = $shop->where(array('id'=>$id))->getField('account');
            $this->assign('info',$info);
            $this->display();
        }
    }
    /**
     * 管理后台 添加路由信息
     */
	public  function addroute(){
		$this->doLoadID(300);
		if(IS_POST){
			$db=D('Routemap');
			if($db->create()){
				if($db->add()){
					$this->success('添加成功',U('index'));
				}else{
					$this->error("操作失败");
				}
			}else{
				$this->error($db->getError());
			
			}
		}else{
			$id=I('get.id','0','int');
			
			if(empty($id)){
				$this->error("参数不正确");
			}
			$where['id']=$id;
			$db=D('Shop');
			$info=$db->where($where)->field('id,shopname')->find();
			if(!$info){
				$this->error("参数不正确");
			}
			$this->assign("shop",$info);
			$this->display();        
		}
	}
    /**
     * 管理后台 路由列表
     */
	public  function routelist(){
			$this->doLoadID(300);
			$id=I('get.id','0','int');
			
			if(empty($id)){
				$this->error("参数不正确");
			}
			import('@.ORG.AdminPage');
			$db=D('Routemap');
			$where['shopid']=$id;
			$count=$db->where($where)->count();
			$page=new AdminPage($count,C('ADMINPAGE'));
		
			$sql=" select a.* ,b.shopname from ". C('DB_PREFIX')."routemap a left join ". C('DB_PREFIX')."shop b on a.shopid=b.id where a.shopid=".$id." order by a.id desc limit ".$page->firstRow.','.$page->listRows." ";
			
			//$sql=" select a.id,a.shopname,a.add_time,a.linker,a.phone,a.account,a.maxcount,a.linkflag,b.name as agname from ". C('DB_PREFIX')."shop a left join ". C('DB_PREFIX')."agent b on a.pid=b.id order by a.add_time desc limit ".$page->firstRow.','.$page->listRows." ";
	        
			$result = $db->query($sql);
	     
	        
	        $this->assign('page',$page->show());
	        $this->assign('lists',$result);
			$this->display();
	}
    /**
     * 删除路由信息
     */
    public function delroute()
    {
     	
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $where['id']=$id;
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
     public function yuancheng(){
  			$id=I('get.id');		
  			$shopinfo=D('shop')->where(array('id'=>$id))->select();									
  		  	session('uid',$id);
  		  	session('jifei',$shopinfo[0]['jifei']);
	     	session('user',$shopinfo[0]['account']);
         	session('shop_name',$shopinfo[0]['shopname']);
           	session('pid',$shopinfo[0]['pid']);	
			if($shopinfo[0]['jifei']==1){
				$this->success('正在跳转中',U('index/login/jifei'));
			}else{
				$this->success('正在跳转中',U('index/login/index'));	
			}								
  				
			
									
  }
    /**
     * 编辑路由
     */
	public function editroute(){
		if(IS_POST){
			$db= D('Routemap');
        	$id = I('post.id','0','int');
	        $where['id']=$id;
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
	        		   $this->success('更新成功',U('index'));
	        		}else{
	        			$this->error("操作失败");
	        		}
        	}else{
        		$this->error($db->getError());
        	}
		}else{
			$id=I('get.id','0','int');
			
			$where['id']=$id;
			$db=D('Routemap');
			$info=$db->where($where)->find();
			
			if(!$info){
				$this->error("参数不正确");
			}
			$this->assign("info",$info);
			$whereshop['id']=$info['shopid'];
			$db=D('Shop');
			$shopinfo=$db->where($whereshop)->find();
            $this->assign("shop",$shopinfo);
	        
	        $this->display();    
		}
	}
}