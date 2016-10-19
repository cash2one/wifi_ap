<?php
/*
 * 高级收费功能 3G
 */
class JifeiAction extends HomeAction{

 	private function isLogin()
    {
        if(!session('uid'))
        {
            $this->redirect('index/index/log');
        }    
        $this->assign('a','jifei');
    }

  public function biaozhun(){
  
  	$this->isLogin();
  	$db=D('shop');
	$result=$db->where(array('id'=>session('uid')))->select();
	 $this->assign('nav','jifei');
	 $this->assign('navbar','biaozhun');
	$this->assign('info',$result[0]);
	$this->display();
	
	
 }
   public function weixin(){
  
  	$this->isLogin();
  	$db=D('shop');
	$result=$db->where(array('id'=>session('uid')))->select();
	if(($result[0]['APPID']!='')&&($result[0]['MECHID']!='')&&($result[0]['APPSECRET']!='')&&($result[0]['WEKEY']!='')){
		 	$this->assign('weixin',1);
		 }
	if(IS_POST){
		if(isset($_POST['weixin'])){
		$data['APPID']=I('post.APPID');
		$data['MECHID']=I('post.MECHID');
		$data['WEKEY']=I('post.WEKEY');
		$data['APPSECRET']=I('post.APPSECRET');
	
	if((strlen((string)$data['WEKEY'])!=32)||(strlen((string)$data['APPSECRET'])!=32)){
			$this->error('保存失败！参数不合法');
		}
	$flag=$db->where(array('id'=>session('uid')))->save($data);
		if($flag){
			$this->success('保存成功！');
		}else{
			$this->error('保存失败，服务器繁忙！');
		}
}else{
		$data['APPID']='';
		$data['MECHID']='';
		$data['WEKEY']='';
		$data['APPSECRET']='';
		$flag=$db->where(array('id'=>session('uid')))->save($data);
		$this->success('微信自主结算已关闭，收益由平台结算');
}

	}else{
	$this->assign('nav','jifei');
	 $this->assign('navbar','weixin');
	$this->assign('info',$result[0]);
	$this->display();
	}
	 
	
	
 }
   public function weixinpay(){
   	$this->isLogin();
   	$zfb=D('zfbvip');
	$result=$zfb->where(array('uid'=>session('uid'),'buyer_email'=>'Payed','zhichi'=>1))->select();

	$this->assign('lists',$result);
	$this->assign('nav','jifei');
	 $this->assign('navbar','weixin');
	$this->display();
	
	
	
   }
   
   
   
   
   
  public function bzsave(){
  	  
  	$_POST['hour']=I('post.hour');
	$_POST['month']=I('post.month');
	$db=D('shop');
	$db->create();
	if($db->where(array('id'=>session('uid')))->save()){
		$this->success('保存成功');
		
	}else{
		
		$this->error('保存失败，未知错误');
	}
	 }
  

  
  public function addaccount(){
  	  
  	if(IS_POST){
  		 if($_POST['phone']){
        	if(!isPhone($_POST['phone'])){
        		$this->error('手机号码不正确');
        	}
        }
		 $_POST['username']=I('post.username');
		 $_POST['password']=md5(I('post.password'));
		 $_POST['phonenum']=I('post.phone');
		 $_POST['type']=I('post.type');
		 $_POST['uid']=session('uid');
		 $_POST['add_time']=time();
		 if($_POST['type']==1){
		 	$_POST['last_time']=$_POST['baoshi']*3600;
			 $_POST['laston_time']=$_POST['baoshi']*3600;
			 unset($_POST['baoyue']);
		}else{
			$time=date('Y-m-d His',strtotime("+{$_POST['baoyue']} month"));
			$_POST['expire_time']=strtotime($time);
			unset($_POST['baoshi']);
		}
		$vip=D('Vipmember');
		$vipnum=$vip->where(array('uid'=>session('uid')))->count();
		$shop=D('shop');
		$renshu=$shop->where(array('id'=>session('uid')))->getField('renshu');
		if($renshu!=''&&$renshu!=0&&$vipnum>=$renshu){
			$this->error('注册人数已达上限!添加失败!');
		}else{
		if($vip->create()){
		if($vip->add()){
			$this->success('添加成功');
		}else{
			
			$this->error('添加失败');
		}
		}else{
			
		 $this->error($vip->getError());
		}
		}
  	}else{
  		$vip=D('Vipmember');
		$vipnum=$vip->where(array('uid'=>session('uid')))->count();
		$shop=D('shop');
		$renshu=$shop->where(array('id'=>session('uid')))->getField('renshu');
		
  		$this->assign('nav','jifei');
		$this->assign('navbar','add');
  		$this->assign('vipnum',$vipnum);
		$this->assign('renshu',$renshu);
		$this->display();
  	}

	
	
	
  }
  
  
  
public function member(){
	import('@.ORG.UserPage');
 	$this->assign('a','jifei');
	
	if(IS_POST){
		$key=I('post.key');
		$sql="select * from wifi_vipmember where (username like '%$key%' or phonenum like '%$key%') and uid=".session('uid')."";
		$db=M();
		$info=$db->query($sql);
	  
	}else{
	$this->isLogin();
	$vip=D('Vipmember');
	$where['uid']=session('uid');
	 $count=$vip->where($where)->count();
	 $page=new UserPage($count,20);
	$info=$vip->where($where)->limit($page->firstRow.','.$page->listRows)->order('add_time desc')->select();
	 $this->assign('page',$page->show());
	}
	$this->assign('lists',$info);
    $this->assign('nav','jifei');
	$this->assign('navbar','member');
	$this->display();
	
}
public function daoqid(){
	$this->isLogin();
	import('@.ORG.UserPage');
	$expire=time();
	$vip=D('Vipmember');
	$whered['expire_time']=array('lt',$expire);
	$whered['uid']=session('uid');
	$whered['type']=2;
    $daoqi=$vip->where($whered)->count();
	 
	 $page=new UserPage($daoqi,20);
	$info=$vip->where($whered)->limit($page->firstRow.','.$page->listRows)->order('add_time desc')->select();
	$this->assign('lists',$info);
	$this->assign('page',$page->show());
	$this->display();
}
public function daoqi(){
	$this->isLogin();
	import('@.ORG.UserPage');
	$today=time();
	$expire=strtotime("+ 7 days");
	$vip=D('Vipmember');
	$whered['expire_time']=array('between',"$today,$expire");
	$whered['uid']=session('uid');
	$whered['type']=2;
    $daoqi=$vip->where($whered)->count();
	 
	 $page=new UserPage($daoqi,20);
	$info=$vip->where($whered)->limit($page->firstRow.','.$page->listRows)->order('add_time desc')->select();
	$this->assign('lists',$info);
	$this->assign('page',$page->show());
	$this->display();
}
public function zaixian(){
	$this->isLogin();
	import('@.ORG.UserPage');
	$update=time()-300;
	$vip=D('Vipmember');
	$whered['update_time']=array('gt',$update);
	$whered['uid']=session('uid');
	$daoqi=$vip->where($whered)->count();
	$page=new UserPage($daoqi,20);
	$info=$vip->where($whered)->limit($page->firstRow.','.$page->listRows)->order('update_time desc')->select();
	$this->assign('lists',$info);
	$this->assign('page',$page->show());
	$this->display();
}
public function huoyue(){
	$this->isLogin();
	import('@.ORG.UserPage');
	$update=strtotime(date("Y-m-d"));
	$vip=D('Vipmember');
	$whered['update_time']=array('gt',$update);
	$whered['uid']=session('uid');
	$daoqi=$vip->where($whered)->count();
	$page=new UserPage($daoqi,20);
	$info=$vip->where($whered)->limit($page->firstRow.','.$page->listRows)->order('update_time desc')->select();
	$this->assign('lists',$info);
	$this->assign('page',$page->show());
	$this->display();
}
public function chongzhi(){
	import('@.ORG.UserPage');
	 $this->assign('a','jifei');
	$this->isLogin();
	$vip=D('Vipchongzhi');
	$where['uid']=session('uid');
	$count=$vip->where($where)->count();
	$page=new UserPage($count,10);
	$info=$vip->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
	$this->assign('nav','jifei');
	$this->assign('mavbar','chongzhi');
	$this->assign('page',$page->show());
	$this->assign('lists',$info);
	$this->display();
	
}
public function zfbvip(){
	import('@.ORG.UserPage');
	 $this->assign('a','jifei');
	$this->isLogin();
	$vip=D('zfbvip');
	$where['uid']=session('uid');
	$where['trade_no']=array('neq','');
	$count=$vip->where($where)->count();
	$page=new UserPage($count,10);
	$info=$vip->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
	$this->assign('page',$page->show());
	$this->assign('lists',$info);
	
	$this->display();
	
}





public function vipchongzhi(){
	$this->isLogin();
	if(IS_POST){
		
			$vip=D('vipmember');
			$vipinfo=$vip->where(array('id'=>$_POST['id']))->find();
		if($_POST['type']){
		
			if($_POST['type']==1){
			$data['type']=1;
			$data['last_time']=$vipinfo['on_time']+$_POST['baoshi']*3600+$vipinfo['last_time'];
			$data['laston_time']=$vipinfo['laston_time']+$_POST['baoshi']*3600;
			$chongzhi=D('vipchongzhi');
			$datas['uid']=session('uid');
			$datas['type']=1;
			$datas['laston_time']=$vipinfo['laston_time'];
			$datas['lastons_time']=$vipinfo['laston_time']+$_POST['baoshi']*3600;
			$datas['add_time']=time();
			$datas['account']=$vipinfo['username'];
			$datas['chongzhi']=$_POST['baoshi'].'个小时';
			$chongzhi->data($datas)->add();
			$result=$vip->where(array('id'=>$_POST['id']))->save($data);
			if($result){
			$this->success('充值成功',U('Jifei/member'));
			}	
			else{
			$this->error('服务器繁忙');
			}
			}else{
			$time=date('Y-m-d His',strtotime("+{$_POST['baoyue']} month"));
			$expire=strtotime($time);
			$chongzhi=D('vipchongzhi');
			$datas['uid']=session('uid');
			$datas['type']=2;
			$datas['expire_time']=$vipinfo['expire_time'];
			$datas['expires_time']=$expire;
			$datas['add_time']=time();
			$datas['account']=$vipinfo['username'];
			$datas['chongzhi']=$_POST['baoyue']."个月";
			$chongzhi->data($datas)->add();
			$vipinfost=$vip->where(array('id'=>$_POST['id']))->setField('expire_time',$expire);
			$vip->where(array('id'=>$_POST['id']))->setField('type',2);
			if($vipinfost){
			$this->success('充值成功',U('Jifei/member'));
			}	
			else{
			$this->error('服务器繁忙');
			}
			}
			}
			else{
		if($_POST['hour']){
			
		$data['last_time']=$vipinfo['on_time']+$_POST['baoshi']*3600;
		$data['laston_time']=$vipinfo['laston_time']+$_POST['baoshi']*3600;
		$chongzhi=D('vipchongzhi');
			$datas['uid']=session('uid');
			$datas['type']=1;
			$datas['laston_time']=$vipinfo['laston_time'];
			$datas['lastons_time']=$vipinfo['laston_time']+$_POST['baoshi']*3600;
			$datas['add_time']=time();
			$datas['account']=$vipinfo['username'];
			$datas['chongzhi']=$_POST['baoshi'].'个小时';
			$chongzhi->data($datas)->add();
		$result=$vip->where(array('id'=>$_POST['id']))->save($data);
		if($result){
			$this->success('充值成功',U('Jifei/member'));
			}	else{
			$this->error('服务器繁忙');
		}
		} 
		if($_POST['month']){
		
			if($_POST['expire']){
			$time=date('Y-m-d His',strtotime("+{$_POST['baoyue']} month"));
			$expire=strtotime($time);
			$chongzhi=D('vipchongzhi');
			$datas['uid']=session('uid');
			$datas['type']=2;
			$datas['expire_time']=$vipinfo['expire_time'];
			$datas['expires_time']=$expire;
			$datas['add_time']=time();
			$datas['account']=$vipinfo['username'];
			$datas['chongzhi']=$_POST['baoyue']."个月";
			$chongzhi->data($datas)->add();
			$vipinfos=$vip->where(array('id'=>$_POST['id']))->setField('expire_time',$expire);
			if($vipinfos){
			$this->success('充值成功',U('Jifei/member'));
			}	
			else{
			$this->error('服务器繁忙');
			}
			}else{
			$times=strtotime("+{$_POST['baoyue']} month",$vipinfo['expire_time']);
			$chongzhi=D('vipchongzhi');
			$datas['uid']=session('uid');
			$datas['type']=2;
			$datas['expire_time']=$vipinfo['expire_time'];
			$datas['expires_time']=$times;
			$datas['add_time']=time();
			$datas['account']=$vipinfo['username'];
			$datas['chongzhi']=$_POST['baoyue']."个月";
			$chongzhi->data($datas)->add();
			$res=$vipinfo=$vip->where(array('id'=>$_POST['id']))->setField('expire_time',$times);
			if($res){
			$this->success('充值成功',U('Jifei/member'));
			}	
			else{
			$this->error('服务器繁忙');
			}
			}
			}
			}
			}
			else{
		$id=I('get.id');
		$list=D('Vipmember')->where(array('id'=>$id,'uid'=>session('uid')))->find();
		
		if($list==false){
		$this->error('没有此账户信息');
		}else{
		if($list['type']==2){
		$now=time();
		if($now>$list['expire_time']){
			$this->assign('expire',1);
		}
		}
	$this->assign('info',$list);
	$this->display();
	
	
}
}
}
public function delmem(){
	$vip=D('Vipmember');
	$id=I('get.id');
	$list=D('Vipmember')->where(array('id'=>$id))->delete();
	if($list){
	$this->success('账户删除成功');
	}else{
	$this->error('没有该账户信息删除失败！');
}
}
public function  vipedit(){
	$this->isLogin();
	if(IS_POST){
	
		if(!isPhone($_POST['phonenum'])){
			$this->error('请输入正确的手机号码');
			
		}
		
		if(!empty($_POST['password'])){
			
			$_POST['password']=md5($_POST['password']);
			
		}else{
			
			unset($_POST['password']);
		}
	
		$vip=D('Vipmember');
	
		if(!$vip->create()){
		$this->error($vip->getError());
		}
		else{
			$vip->where(array('id'=>$_POST['id']))->save();
			$this->success('更新成功');
		
		}
		
	
	}
	else{
	$id=I('get.id');
	$list=D('Vipmember')->where(array('id'=>$id,'uid'=>session('uid')))->find();
	if($list==false){
	$this->error('没有此账户信息');
	}else{
	$this->assign('info',$list);
	$this->display();
	
	}
	
	}
	
}
	
	

}
