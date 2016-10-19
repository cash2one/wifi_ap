<?php

/**
 * Class Wifiadmin 代理商信息管理
 */
class AgentAction extends  AdminAction{

	protected function _initialize(){
		parent::_initialize();
		$this->doLoadID(400);
	}

    /**
     * 代理商信息列表
     */
	public function index(){
		import('@.ORG.AdminPage');
		$db=D('Agent');
		$count=$db->count();

		$page=new AdminPage($count,C('ADMINPAGE'));

        $result = $db->field('id,account,name,add_time,linker,phone,money')->limit($page->firstRow.','.$page->listRows)->order('id desc') -> select();

        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
	
	private  function ShowAjaxError($msg){
        $data['msg']=$msg;
        $data['error']=1;
        $this->ajaxReturn($data);
	}
  //路由器在线统计人数
  public function zaixian(){
  	    global $jieguo;
  		import('@.ORG.AdminPage');
		$db=D('Agent');
		$count=$db->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
	    $agentinfo=$db->field('id,name')->limit($page->firstRow.','.$page->listRows)->select();
	  
	foreach($agentinfo as $k=>$v){
		$time=time();
	  $sql="select biao.pid,sum(nowstate),sum(todaystate),sum(sevenstate),sum(monthstate) from (select month1.aid as pid,month1.shopname,sum(now1.now) as nowstate,sum(today1.today) as todaystate,sum(seven1.seven) as sevenstate,sum(month1.month) as monthstate from((((select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,a.pid as aid,b.id,count(mac) as now from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$v['id']}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60) group by b.id) as now1 right join (select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,a.pid as aid,b.id,count(mac) as today from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$v['id']}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24) group by b.id) as today1 on now1.gw_id=today1.gw_id)right join (select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,a.pid as aid,b.id,count(mac) as seven from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$v['id']}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24*7) group by b.id) as seven1 on seven1.gw_id = today1.gw_id ) right join(select b.gw_id as gw_id,a.shopname as shopname,b.routename as routename,a.pid as aid,b.id ,count(mac) as month from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$v['id']}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24*7*30) group by b.id) as month1 on seven1.gw_id = month1.gw_id  ) group by month1.shopname) as biao where 1=1";
      $online=M();
      $onlineinfo=$online->query($sql);
//    print_r($onlineinfo);
     $zaixianinfo=array_merge($v,$onlineinfo[0]);
	    $jieguo[]=$zaixianinfo;
	}
	 $online=M();
	 $time=time();
     $sql="select biao.pid,sum(nowstate),sum(todaystate),sum(sevenstate),sum(monthstate) from (select month1.aid as pid,month1.shopname,sum(now1.now) as nowstate,sum(today1.today) as todaystate,sum(seven1.seven) as sevenstate,sum(month1.month) as monthstate from((((select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,a.pid as aid,b.id,count(mac) as now from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='0') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60) group by b.id) as now1 right join (select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,a.pid as aid,b.id,count(mac) as today from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='0') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24) group by b.id) as today1 on now1.gw_id=today1.gw_id)right join (select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,a.pid as aid,b.id,count(mac) as seven from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='0') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24*7) group by b.id) as seven1 on seven1.gw_id = today1.gw_id ) right join(select b.gw_id as gw_id,a.shopname as shopname,b.routename as routename,a.pid as aid,b.id ,count(mac) as month from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='0') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24*7*30) group by b.id) as month1 on seven1.gw_id = month1.gw_id  ) group by month1.shopname) as biao where 1=1";
     $onlineinfo=$online->query($sql);
     $zarr=array('id'=>0,'name'=>'神行通运营总平台');
     $zaixianinfo=array_merge($zarr,$onlineinfo[0]);
	 $jieguo[]=$zaixianinfo;
//   print_r($jieguo);
	 $this->assign('page',$page->show());
	
	 $this->assign('lists',$jieguo);
	
	$this->display();
	
	
	
	}
  
  
  
  public function yuancheng(){
  			$id=I('get.id');		
  			$agentinfo=D('agent')->where(array('id'=>$id))->select();									
  		  	session('aid',$id);
	        session('account',$agentinfo[0]['account']);
	        session('agentname',$agentinfo[0]['name']);											
  			$this->success('正在跳转中',U('agent/index/index'));								
  }
    /**
     * 添加代理信息
     */
	public function add(){
		if(IS_POST){
			if(empty($_POST['level'])){
				$this->ShowAjaxError('请选择代理商级别');
			}
			$lvid=intval( $_POST['level']);
			if($lvid<0){
				$this->ShowAjaxError('请选择代理商级别');
			}
			$db=D('Agent');
			$res=$db->where(array('account'=>$_POST['account']))->count();
			if($res!=0){
		     $this->ShowAjaxError('用户名已存在');	
			}
			$pass=$_POST['password'];
			$_POST['password']=md5($pass);
			$_POST['add_time']=time();			
			
			
			if($db->create()){
				$insertid=$db->add();
				if($insertid){
					$route=D('route');//数据库添加积分信息
					$routenum=D('routenum');
			        $aid['aid']=$insertid;
			        $routenum->data($aid)->add();
				    $route->data($aid)->add();
					$arr=$_POST['route'][0];
				    $arrnum=$_POST['routenum'][0];
					 foreach($arrnum as $x=>$y){
				    $data["$x"]=$y;
				    $routenum->where(array('aid'=>$insertid))->data($data)->save();
			          }
			        foreach($arr as $k=>$v){
				    $data["$k"]=$v;
				    $route->where(array('aid'=>$insertid))->data($data)->save();
			          }
					$data['url']=U('index');
					$data['error']=0;
					$this->ajaxReturn($data);
				}else{
					$this->ShowAjaxError('添加代理商操作失败');
				}
			}else{
				$this->ShowAjaxError($db->getError());
			}
		}else{
			include CONF_PATH.'enum/enum.php';//$enumdata
			$lvdb=D('Agentlevel');
			$where['state']=1;
			$lvinfo=$lvdb->where($where)->field('id,title') ->select();			 
            $this->assign('enumdata',$enumdata);
			$this->assign('lvlist',$lvinfo);
			$this->display();
		}
	}

    /**
     * 编辑代理商信息
     */
	public function edit(){
		$db=D('Agent');
		if(IS_POST){
				if(empty($_POST['level'])){
					$this->ShowAjaxError('请选择代理商级别');
				}
				$lvid=intval( $_POST['level']);
				if($lvid<0){
					$this->ShowAjaxError('请选择代理商级别');
				}
			     if(!empty($_POST['password']) ){
	               	$password = $_POST['password'];
	               	$_POST['password']=md5($password);
	            }else{
	            	unset($_POST['password']);
	            }
				$id=I('post.id','0','int');
				$where['id']=$id;
				$info=$db->where($where)->find();
				$route=D('route');
			       $arr=$_POST['route'];
			        foreach($arr as $k=>$v){
			        	$b=strtolower($k);
				    $data["$b"]=$v;
				    $route->where(array('aid'=>$id))->data($data)->save();
			}

				if($info!=false){
					if($db->create($_POST,2)){
						if($db->where($where)->save()){
							$data['url']=U('index');
							$data['error']=0;
							$this->ajaxReturn($data);
						}else{
							$this->ShowAjaxError('编辑失败');
						}
					}else{
						$this->ShowAjaxError($db->getError());
					}
				}else{
					$this->ShowAjaxError('没有此代理商信息');
				}
		}else{
			include CONF_PATH.'enum/enum.php';//$enumdata
			$this->assign('enumdata',$enumdata);
			$lvdb=D('Agentlevel');
			$level = array('state' =>1);
			$lvinfo=$lvdb->where($level)->field('id,title') ->select();
			$this->assign('lvlist',$lvinfo);

			
		    $id=I('get.id','0','int');
			$ros=D('route');
			$dos=D('routenum');
			$routeinfo=$ros->where(array('aid'=>$id))->find();
			$routenum=$dos->where(array('aid'=>$id))->find();
//			print_r($routeinfo);
//			print_r($routenum);
//			$c=array_intersect_assoc($routeinfo,$routenum);
//			foreach($routeinfo as $k=>$v){//jifen
//			if($k!==''&&$k!=='id'&&$k!=='aid'){
//			}
//			}
//			print_r($c);
	        $this->assign('routenum',$routenum);
			$this->assign('routeinfo',$routeinfo);
			$agent = array('id'=>$id);
			$info=$db->where($agent)->find();
			if($info!=false){
				$this->assign('info',$info);
				$this->display();
			}else{
				$this->error("没有此等级信息");
			}
		}
	}

    /**
     * 删除代理商信息
     */
	public  function del(){
			$id=I('get.id','0','int');
			$db=D('Agent');
			$dbshop=D('shop');
			$agwhere['pid']=$id;
			$count=$dbshop->where($agwhere)->count();
			if($count>0){
				$this->error("当前代理商包含商户账号，不能删除");
			}
			$where['id']=$id;
			$routenum=D('Routenum');
			$routenum->where(array('aid'=>$id))->delete();
			$route=D('Route');
			$route->where(array('aid'=>$id))->delete();
			$db->where($where)->delete();
			$this->success("操作成功",U('index'));
			
	}

    /**
     * 代理商等级
     */
	public function level(){
		import('@.ORG.AdminPage');
		$db=D('Agentlevel');
		
		$count=$db->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
        $result = $db->field('id,title,openpay,add_time,state')->limit($page->firstRow.','.$page->listRows)->order('add_time desc') -> select();
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
	
	public function addlevel(){
		if(IS_POST){
			$db=D('Agentlevel');
			
			if($db->create()){
				if($db->add()){
					$this->success("添加成功",U('level'));
				}else{
					$this->error("操作失败");
				}
			}else{
				$this->error($db->getError());
			}
		}else{
			$this->display();
		}
	}

    /**
     * 编辑代理商等级
     */
	public function editlevel(){
		$db=D('Agentlevel');
		if(IS_POST){
			$id=I('post.id','0','int');
				$where['id']=$id;
				$info=$db->where($where)->find();
				if($info!=false){
					if($db->create($_POST,2)){
						if($db->where($where)->save()){
							$this->success("操作成功",U('level'));
						}else{
							$this->error("没有此角色信息");
						}
					}else{
						$this->error($db->getError());
					}
				}else{
					$this->error("没有此等级信息");
				}
		}else{
			$id=I('get.id','0','int');
			$where['id']=$id;

			$info=$db->where($where)->find();
			if($info!=false){
				$this->assign('info',$info);
				$this->display();
			}else{
				$this->error("没有此等级信息");
			}
		}
	}

    /**
     * 删除代理商级别
     */
	public function delevel(){
		$id=I('get.id','0','int');
		$db=D('Agentlevel');
		$dbag=D(Agent);
		$agwhere['level']=$id;
		$count=$dbag->where($agwhere)->count();
		if($count>0){
			$this->error("当前等级包含代理商账号，不能删除");
		}
		$where['id']=$id;
		$db->where($where)->delete();
		$this->success("操作成功",U('level'));
	}

public function online(){
		$id=I('get.id','0','int');
		import('@.ORG.AdminPage');
		$db=M();
		$sql="select month1.gw_id,month1.routename,month1.shopname,now1.now,today1.today,seven1.seven,month1.month from((((select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,b.id,count(mac) as now from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$id}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60) group by b.id) as now1 right join (select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,b.id,count(mac) as today from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$id}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24) group by b.id) as today1 on now1.gw_id=today1.gw_id)right join (select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,b.id,count(mac) as seven from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$id}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24*7) group by b.id) as seven1 on seven1.gw_id = today1.gw_id ) right join(select b.gw_id as gw_id,a.shopname as shopname,b.routename as routename,b.id ,count(mac) as month from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$id}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24*7*30) group by b.id) as month1 on seven1.gw_id = month1.gw_id  ) group by month1.gw_id";
		$count=$db->query($sql);
        $a=count($count);
		$page=new AdminPage($a,C('ag_page'));
		$sqlex=$sql="select month1.gw_id,month1.routename,month1.shopname,now1.now,today1.today,seven1.seven,month1.month from((((select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,b.id,count(mac) as now from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$id}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60) group by b.id) as now1 right join (select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,b.id,count(mac) as today from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$id}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24) group by b.id) as today1 on now1.gw_id=today1.gw_id)right join (select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,b.id,count(mac) as seven from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$id}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24*7) group by b.id) as seven1 on seven1.gw_id = today1.gw_id ) right join(select b.gw_id as gw_id,a.shopname as shopname,b.routename as routename,b.id ,count(mac) as month from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$id}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24*7*30) group by b.id) as month1 on seven1.gw_id = month1.gw_id  ) group by month1.gw_id %LIMIT%";
        $db->limit($page->firstRow.','.$page->listRows);
        $result=$db->query($sql,true);
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
     
		$this->display();
		
		
}
    /**
     * 消费记录
     */
	public function paylist(){
		import('ORG.Util.Page');
		$db=D('Agentpay');
		
		$count=$db->count();
		$page=new Page($count,C('ADMINPAGE'));
		$sql="select a.*,b.account as acounts,b.name from ".C('DB_PREFIX')."agentpay a left join ".C('DB_PREFIX')."agent b on a.aid=b.id order by a.add_time desc limit ".$page->firstRow.','.$page->listRows." ";
		$result=$db->query($sql);

		$this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
	

    /**
     * //TODO
     * $paydata
     */
	public function dopay(){
		if(IS_POST){
			$dbagent=D('Agent');
			$id=I('post.aid','0','int');
			$where['id']=$id;
			$info=$dbagent->where($where)->field('id,account,money')->find();
			if(!$info){
				$this->error("没有此代理商信息");
			}
			
			$db = D('Agentpay');
			$do = $_POST['do'];
			$money = $info['money'] == null ? 0 : $info['money'];
			$pay   = $_POST['paymoney'] == null ? 0 : $_POST['paymoney'];
			
			$paydata['oldmoney'] = $oldmoney;
	        $paydata['nowmoney'] = $nowmoney;
	        if($db->create()){
	        	if($do=="0"){
					if($money<$pay){
						$this->error("当前帐号余额不足，无法扣款");
					}
					$oldmoney=$money;
					$nowmoney=$money-$pay;
				
				}else{
					$oldmoney=$money;
					$nowmoney=$money+$pay;
				}
				
				if($db->add()){
					if($do=="0"){
						$dbagent->where($where)->setDec('money',$pay);
					}else{
						$dbagent->where($where)->setInc('money',$pay);
					}
					$this->success("操作成功",U('index'));
				}else{
					$this->error("操作失败");
				}
	        }else{
	        	$this->error($db->getError());
	        }
		}else{
			$dbagent=D('Agent');
			$id=I('get.id','0','int');
			$where['id']=$id;
			$info=$dbagent->where($where)->field('id,account,money')->find();
			if(!$info){
				$this->error("没有此代理商信息");
			}
			$this->assign('info',$info);
			$this->display();
		}
	}
}