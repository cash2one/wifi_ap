<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content=" initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title><?php echo ($shopinfo[0]['shopname']); ?></title>
<link href="<?php echo ($Theme['T']['css']); ?>/ui-base.css"  rel="stylesheet" />
<link href="<?php echo ($Theme['T']['css']); ?>/ui-color.css"  rel="stylesheet"/>
<link href="<?php echo ($Theme['T']['css']); ?>/login.css"  rel="stylesheet"/>
<link href="<?php echo ($Theme['T']['css']); ?>/media.css"  rel="stylesheet"/>
<link rel="stylesheet" href="<?php echo ($Theme['T']['css']); ?>/bootstrap.min.css" />
</head>
<body style="background-image: url('<?php echo ($Theme["T"]["img"]); ?>/jifeibg.jpg');background-position:center;">
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<h1 class="text-center" style="color: white;">
				神行通上网计费系统
	</h1>
	<hr>
	<br>
	<br>
	<br>
	<br>
		<br>
			<br>
				<br>
			<a href="<?php echo U('Userauth/vipreg');?>" class="btn btn-block btn-lg btn-info">	
			
				
				新用户注册
				
			
			</a>
			<br>
				<a href="<?php echo U('Userauth/viplogin');?>" class="btn btn-block btn-lg btn-default">
			  
			
				用户登录
		
			</a>
			
			
		</div>
		
</div>
	<br>
	<br>
	<br>
	
	<p  class="text-center"  style="font-size: 12px;color: white;">本地运营商联系人：<?php echo ($shopinfo[0]['linker']); ?></p>
	<a href="tel:<?php echo ($shopinfo[0]['phone']); ?>" class="btn btn-block btn-danger"><?php echo ($shopinfo[0]['phone']); ?>(点此联系我)</a>
	<br>
	
	
	<div class="col-sm-12 col-md-12">
	<p  class="text-center"  style="font-size: 12px;color: white;">©神行通为您提供安全无线服务</p>
	</div>

	</div>
	



</body>
</html>