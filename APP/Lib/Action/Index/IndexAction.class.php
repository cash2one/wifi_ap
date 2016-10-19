<?php

class IndexAction extends BaseAction {

    /**
     * 登录页面
     */
    public function index(){
    	$this->display('log');
    }

    /**
     * 指向登录页面
     */
    public function log(){
    	$this->display();
    }

    /**
     * 代理商登录
     * @return mixed
     */
	public function doagentlog(){
		if(IS_POST){
	    	$user = isset($_POST['user']) ? strval($_POST['user']) : '';
	        $pass = isset($_POST['password']) ? strval($_POST['password']) : '';
	        $userM = D('Agent');
	        $pass  = md5($pass);
	        $uid = $userM->where("account = '{$user}' AND password = '{$pass}'")->field('id,account,name')->find();
	        if($uid)
	        {
	            session('aid',$uid['id']);
	            session('account',$uid['account']);
	            session('agentname',$uid['name']);
	            $data['error']=0;
	    		$data['msg']="";
	    		$data['url']=U('agent/index/index');
	    		return $this->ajaxReturn($data);
	        }else{
	        	$data['error']=1;
	    		$data['msg']="帐号信息不正确";
	    		return $this->ajaxReturn($data);
	        }
    	}else{
    		$data['error']=1;
    		$data['msg']="服务器忙1，请稍候再试";
    		return $this->ajaxReturn($data);
    	}
	}

    /**
     * 代理商退出
     */
	public function alogout(){
		    session(null);
//        	$this->redirect('index/index/alog');
header('Location: http://ap.wifi01.cn');
	}
}