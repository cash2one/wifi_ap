<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content=" initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <!--<meta http-equiv="refresh" content="<?php echo ($waitsecond); ?>;URL=<?php echo ($wifiurl); ?>">-->
    <title></title>
    <link href="<?php echo ($Theme['T']['css']); ?>/login.css"  rel="stylesheet"/>
    <link href="<?php echo ($Theme['P']['js']); ?>/swiper/swiper.css" rel="stylesheet" />
    <link href="<?php echo ($Theme['T']['css']); ?>/media.css"  rel="stylesheet"/>
</head>
<body>

<div class="content">
    <div style="text-align: center;margin:0 auto;">
    <div class="loadtext">正在跳转中，请稍等...</div>
        <div class="" id="load"></div>
        <div class="loading"></div>
    </div>
</div>
</body>
<script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
<script type="text/javascript">
   	var url = "<?php echo ($wifiurl); ?>";
    var time = "<?php echo ($waitsecond); ?>";
    setTimeout(function(){
        window.location = url;
    },1000);
</script>

<!--<script>
    _guanggao_pub = "66392f5f401b24edb424";
    _guanggao_slot = "6f1e9102493358b7f86e";
</script>
<script src="http://ggj.haiyunx.com/js/66392f5f401b24edb424"></script>-->


</html>