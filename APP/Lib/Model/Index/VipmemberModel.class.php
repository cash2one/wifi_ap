<?php
class VipmemberModel extends Model{
	
		protected $_validate = array(
     	  array('username','require','用户名不能为空',0),
     	   array('username','/^[a-zA-Z0-9_]{4,20}$/','用户帐号由4-20位数字，字母或下划线',0,"regex",1),
     	  array('username','require','用户名不能为空',0),
          array('password','require','密码不能为空',0),
		  array('username','','用户账号已经存在！',0,'unique',1), // 新增修改时候验证username字段是否唯一
		  array('phonenum','','该手机号已经使用',0,'unique',3), // 新增修改时候验证username字段是否唯一
	);
	
	
}