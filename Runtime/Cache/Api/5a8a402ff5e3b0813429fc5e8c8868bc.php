<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1; minimum-scale=1; user-scalable=no;" />
    <title><?php echo ($shopinfo[0]['shopname']); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/css/css.css"><!--风格-->
    <link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/css/media.css"><!--自适应-->
    <link href="<?php echo ($Theme['P']['js']); ?>/swiper/swiper.min.css" rel="stylesheet" />
    <script type="text/javascript">
//        url = "<?php echo U('Userauth/noauth');?>";
//        if(isWeiXin()){
//            window.location.href = url;
//        }
//        function isWeiXin(){
//            var ua = window.navigator.userAgent.toLowerCase();
//            if(ua.match(/MicroMessenger/i) == 'micromessenger'){
//                return true;
//            }else{
//                return false;
//            }
//        }
    </script>
    <style type="text/css">
    p{
    	text-align: center;
    	font-family: "arial black";
    	color: black;
    	font-size: 20px;
    	line-height:20px ;
    }
    #dh
    {
    	width:100%;
		height:20px;
	    border: 1px solid darkgray;
		border-radius: 5px;
		box-shadow: 3px 3px 5px #fff;
		opacity: 0.5;
		background-color: powderblue;
	
/*animation:myfirst 5s linear 0s 1 normal forwards;

-moz-animation:myfirst 5s linear 0s 1 normal forwards;

-webkit-animation:myfirst 5s linear 0s 1 normal forwards;

-o-animation:myfirst 5s linear 0s 1 normal forwards;*/
}
/*@keyframes myfirst
{

from  {width:0%;background: dodgerblue;}
to {width:100%;background: cyan;}
}

@-moz-keyframes myfirst 
{
from   {width:0%;background: dodgerblue;}
to {width:100%;background: cyan;}
}
@-webkit-keyframes myfirst 
{
from   {width:0%;background: dodgerblue;}
to {width:100%;background: cyan;}
}
@-o-keyframes myfirst 
{
from  {width:0%;background: dodgerblue;}
to {width:100%;background: cyan;}
    }*/
    </style>
</head>
<body>
<!-- 头部 BOF-->

<div class="swiper-container">
        <div class="swiper-wrapper">
          <?php if(!empty($ad)): if(is_array($ad)): $i = 0; $__LIST__ = $ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div  class="swiper-slide">
                            <?php if(($vo['mode']) == "1"): ?><a href="<?php echo U('userauth/showad',array('id'=>$vo['id']));?>"><img src="<?php echo ($vo["ad_thumb"]); ?>" width="100%" height="100%"></a>
                            <?php else: ?>
                                <img src="<?php echo ($vo["ad_thumb"]); ?>" width="100%" height="100%"><?php endif; ?>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    <?php else: ?>
                        <div  class="swiper-slide">
                            <img src="<?php echo ($Theme['P']['root']); ?>/images/ad/noad01.jpg" >
                        </div>
                        <div  class="swiper-slide">
                            <img src="<?php echo ($Theme['P']['root']); ?>/images/ad/noad02.jpg" >
                        </div><?php endif; ?>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div><!-- 头部 EOF-->
<div id="dianji" class="mainbox bgindex clearfix" style="position:absolute;top:90%;left:0;z-index:1;">
    <!-- 轮播广告 BOF-->
    
    <!-- 轮播广告 EOF -->

    <!-- 功能菜单 BOF-->
    <div id="dh">
       <p >点击上网</p>
    </div>

    <!-- 功能菜单 BOF-->
    
</div>

<script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
<script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/swiper/swiper.min.js"></script>
<script>
       var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        autoplay: 1000
    });
</script>
<script>
    $(document).ready(function() {

        url_redirect(3000);

        $("#dianji").click(function () {
            url_redirect(1);
       
        })
    });
   
    function url_redirect(time){
        var gw_address = "<?php echo cookie('gw_address');?>";
        var gw_port = "<?php echo cookie('gw_port');?>";
        var gw_id = "<?php echo cookie('gw_id');?>";
        var mac = "<?php echo cookie('mac');?>";
        var curl = "<?php echo cookie('gw_url');?>";
        var url = "<?php echo ($_SERVER['HTTP_HOST']); ?>";
        
        setTimeout(function(){
            window.location ="http://" + url + "/login/?gw_address="+gw_address+"&gw_port="+gw_port+"&gw_id="+gw_id+"&mac="+mac+"&url="+curl+"&state=1";
        },time)
    }
</script>
</body>
</html>