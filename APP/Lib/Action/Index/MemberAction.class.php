<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/20
 * Time: 9:23
 */
Class MemberAction extends HomeAction{

    public function index(){
        $this->display();
    }

    public function order(){

        $this->assign('a','member');
        $this->display();
    }
}