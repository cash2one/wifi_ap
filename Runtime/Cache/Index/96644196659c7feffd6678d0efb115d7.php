<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.5
Version: 4.1.0
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>神行通</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="/AUI/assets/apfonts.css" rel="stylesheet" type="text/css"/>
<link href="/AUI/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="/AUI/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="/AUI/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="/AUI/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="/AUI/assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
<link href="/AUI/assets/admin/pages/css/login-soft.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="/AUI/assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="/AUI/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="/AUI/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="/AUI/assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css"/>
<link href="/AUI/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<!-- BEGIN LOGO -->
<div class="logo">
	
	<img src="/AUI/assets/admin/layout/img/logo.svg" alt="" width="350px"/>
	
</div>
<!-- END LOGO -->
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGIN -->
<div class="content">
	<!-- BEGIN LOGIN FORM -->
	<form class="login-form" method="post">
		<h3 class="form-title" style="text-align: center;">选择平台类型登录</h3>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span id="alertmsg">
			输入用户名或密码 </span>
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">用户名</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="登录账户" name="username" id="username"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">密码</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="密码" name="pswd" id="pswd"/>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn green pull-right" id="tijiao2" >
			计费平台登录<i class="m-icon-swapright m-icon-white"></i>
			</button>
			<button type="submit" class="btn blue pull-left" id="tijiao" style="margin-left: 0px;">
				<i class="m-icon-swapleft m-icon-white"></i>
			广告平台登录
			</button>
		</div>
	
	<div class="form-actions" style="padding:top:20px;">
			
			<a  class="btn red btn-lg btn-block" href="http://ap.wifi01.cn/index.php/agent" style="background-color: #14CC2A;">
			代理商登录
			</a>
		</div>
	
	
	</form>
	<!-- END LOGIN FORM -->
	<!-- BEGIN FORGOT PASSWORD FORM -->
	
	<!-- END FORGOT PASSWORD FORM -->
	<!-- BEGIN REGISTRATION FORM -->
	
	<!-- END REGISTRATION FORM -->
</div>
<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright">
	 深圳市神行通科技技术有限公司 粤ICP备14079968号
</div>
<!-- END COPYRIGHT -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="/AUI/assets/global/plugins/respond.min.js"></script>
<script src="/AUI/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="/AUI/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/AUI/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="/AUI/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/AUI/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/AUI/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="/AUI/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/AUI/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/AUI/assets/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/AUI/assets/global/plugins/select2/select2.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/AUI/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="/AUI/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="/AUI/assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
<script src="/AUI/assets/admin/pages/scripts/login-soft.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {     
  Metronic.init(); // init metronic core components
Layout.init(); // init current layout
 
  Demo.init();
       // init background slide images
       $.backstretch([
        "/AUI/assets/admin/pages/media/bg/1.jpg",
        "/AUI/assets/admin/pages/media/bg/2.jpg",
        "/AUI/assets/admin/pages/media/bg/3.jpg",
        "/AUI/assets/admin/pages/media/bg/4.jpg"
        ], {
          fade: 1000,
          duration: 8000
    }
    );
    
    

});
var handleLogin = function() {
		$('.login-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            rules: {
	                username: {
	                    required: true
	                },
	                pswd: {
	                    required: true
	                },
	                remember: {
	                    required: false
	                }
	            },

	            messages: {
	                username: {
	                    required: "填写用户名"
	                },
	                pswd: {
	                    required: "填写密码"
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   
	                $('.alert-danger', $('.login-form')).show();
	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                error.insertAfter(element.closest('.input-icon'));
	            },

	            submitHandler: function (form) {
//	                form.submit();
	            }
	        });

	        $('.login-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.login-form').validate().form()) {
                  


	                }
	                return false;
	            }
	        });
	}


	         

   $('#tijiao').on("click",function(e){
	var input_usr = $('#username').val();
	var input_pswd = $('#pswd').val();
	$.ajax({
		url:"http://ap.wifi01.cn/index.php/Index/Login/dologin",
		type:"POST",
		data:{	
			"user":input_usr,
			"password":input_pswd,
			"shoptype":0
		},
		dataType:"json",
		error:function(){
			$("#alertmsg").text('服务器繁忙，请稍后重试')
			 $('.alert-danger', $('.login-form')).show();
		},	
		success:function(data){
		
		if (data.error==0) {
			window.location.href = data.url;
			} else {
			$('#alertmsg').text(data.msg);
			$('.alert-danger', $('.login-form')).show();
			}
			}
	});
	
 handleLogin();
});
$('#tijiao2').on("click",function(e){
	var input_usr = $('#username').val();
	var input_pswd = $('#pswd').val();
	$.ajax({
		url:"http://ap.wifi01.cn/index.php/Index/Login/dologin",
		type:"POST",
		data:{	
			"user":input_usr,
			"password":input_pswd,
			"shoptype":1
		},
		dataType:"json",
		error:function(){
			$("#alertmsg").text('服务器繁忙，请稍后重试')
			 $('.alert-danger', $('.login-form')).show();
		},	
		success:function(data){
		
		if (data.error==0) {
			window.location.href = data.url;
			} else {
			$('#alertmsg').text(data.msg);
			$('.alert-danger', $('.login-form')).show();
			}
			}
	});
	
 handleLogin();
});
$('.login-form').keypress(function (e) {

	            if (e.which == 13) {
	              $('#tijiao').trigger('click');
	            }
	        });




</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>