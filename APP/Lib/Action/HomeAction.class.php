<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/16
 * Time: 10:11
 */
Class HomeAction extends BaseAction{

    protected function _initialize()
    {
        parent::_initialize();
        $this->isLogin();
    }

    private function isLogin()
    {
        if(!session('uid'))
        {
            $this->redirect('index/index/log');
        }
        $uid = $_SESSION['uid'];
        $res = M('shop')->where(array('id'=>$uid))->field('mode,countmax')->find();
        if($res['mode'] == 0 ){
            $this->redirect('index/login/index');
        }
    }

}