<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content=" initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
	

<link  href="<?php echo ($Theme['T']['css']); ?>/login.css"  rel="stylesheet"/>

<link href="<?php echo ($Theme['T']['css']); ?>/media.css"  rel="stylesheet"/>

</head>
<body>

<div class="content">
<div style="text-align: center;margin:0 auto;">

<div class="loadtext">认证成功</div>
</div>
</div>
</body>
<script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
<script>
<?php if(empty($jumpurl)): else: ?>
<?php if(preg_match('/wap/index/i',$jumpurl) == 1): ?>$('.loadtext').html("<p style='font-size: medium;font-style: italic;'>正在跳转至分享页面</p>");

function jump(){
	location.href='<?php echo ($jumpurl); ?>';
}
setTimeout('jump();',3000);
<?php else: ?>
$('.loadtext').html("<p style='font-size: medium;font-style: italic;'>认证成功，等待跳转达目标网站...</p>");
<?php if(preg_match('/https/i',$jumpurl) == 1): ?>function jump(){
	

	window.open('<?php echo ($jumpurl); ?>');
}
setTimeout('jump();',3000);
<?php else: ?>
function jump(){
	
	location.href='<?php echo ($jumpurl); ?>';

}
setTimeout('jump();',3000);<?php endif; endif; endif; ?>
	
</script>
</html>