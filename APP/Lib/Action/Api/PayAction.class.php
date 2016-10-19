<?php
class PayAction extends BaseApiAction{

	
	private function isLogin(){
			if(!session('vipid')||session('vipid')==null||session('vipid')==''){
			$this->redirect('api/login/jifei');
			}
			}
	
	public function index(){
		 $this->isLogin();
		 $vip=D('Vipmember');
		 $result=$vip->where(array('id'=>session('vipid')))->find();
		 $shop=D('shop');
		 $shopinfo=$shop->where(array('id'=>$result['uid']))->find();
		 $this->assign('shopinfo',$shopinfo);
		 $this->assign('info',$result);
		if($result['type']==0){
			$this->display('use');
		}else{
			$this->display();
		}
		}
	 
}