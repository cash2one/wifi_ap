<?php
class AuthsetAction extends  HomeAction{
	
	public  $tplset;
	
	private function getShop(){
		$id=session('uid');
		$shop=D('Shop')->where(array('id'=>$id))->field('tpl_id,tpl_path')->find();
		$this->tplset= $shop;
	}
	
	public function tplset(){
		$this->getShop();
		$this->assign('info',$this->tplset);
  
		$list=D('Authtpl')->where('state')->order('id asc')->select();
		
		$this->assign('tpl',$list);
		$this->assign('nav','authtplset');
		
		$this->display();
	}
	
	public function dotplset(){
		$this->getShop();
		
    	$id=session('uid');
		$tplid=I('get.tpl','int');

		$tpl=D('Authtpl')->where(array('id'=>$tplid))->find();
		if($tpl==false){
			exit;
		}
		$where['id']=$id;
		if($this->tplset){
			//更新
			D('Shop')->where($where)->save(array('tpl_id'=>$tplid,'tpl_path'=>$tpl['keyname']));
		}
	   $this->success('保存成功');
	}
}