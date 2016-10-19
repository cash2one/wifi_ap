<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/1/6
 * Time: 15:40
 */
Class WeixinAction extends BaseApiAction{

    public function index(){
        redirect("/index.php/api/userauth/noAuthforweixin");
    }
}