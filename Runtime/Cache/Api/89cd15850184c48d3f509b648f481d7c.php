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
     <link href="<?php echo ($Theme['T']['css']); ?>/time.css"  rel="stylesheet"/>
</head>
<body>


<?php if($vip == 0): ?><div class="content">
	<div class="game_time" style="z-index: 3;">

	<div class="hold">
		<div class="pie pie1"></div>
	</div>

	<div class="hold">
		<div class="pie pie2"></div>
	</div>

	<div class="bg"> </div>
	
	<div class="time"></div>
	
</div>
    <?php if(C('OPENPUSH') == 1): ?><div class="flash">
            <div class="swiper-container">
              <div class="swiper-wrapper">
                    <?php if(is_array($ad)): $i = 0; $__LIST__ = $ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div  class="swiper-slide">
                            <img src="<?php echo ($vo["pic"]); ?>" width="100%" height="100%">
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
              </div>
            </div>
            <div class="pagination"></div>
        </div><?php endif; ?>
<?php else: ?>
<div style="text-align: center;margin:0 auto;">
    <div class="loadtext">正在跳转中，请稍等...</div>
        <div class="" id="load"></div>
        <div class="loading"></div>
    </div><?php endif; ?>
    <!--<div style="text-align: center;margin:0 auto;">
    <div class="loadtext">正在跳转中，请稍等...</div>
        <div class="" id="load"></div>
        <div class="loading"></div>
    </div>-->
</div>
</body>
<script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
<script type="text/javascript">
   
    var url = "<?php echo ($wifiurl); ?>";
   function times(){
   		 window.location = url;
    	}
    setTimeout('times()',1000);
    var showTime = function(){
    totle = totle - 1;
    if (totle == 0) {
        clearInterval(s);
        clearInterval(t1);
        clearInterval(t2);
        $(".pie2").css("-o-transform", "rotate(" + d + "deg)");
        $(".pie2").css("-moz-transform", "rotate(" + d + "deg)");
        $(".pie2").css("-webkit-transform", "rotate(" + d + "deg)");
        
       
    } else {
        if (totle > 0 && MS > 0) {
            MS = MS - 1;
            if (MS < 10) {
                MS = "0" + MS
            }
            ;
        }
        ;
        if (MS == 0 && SS > 0) {
            MS = 10;
            SS = SS - 1;
            if (SS < 10) {
                SS = "0" + SS
            }
            ;
        }
        ;
        if (SS == 0 && MM > 0) {
            SS = 60;
            MM = MM - 1;
            if (MM < 10) {
                MM = "0" + MM
            }
            ;
        }
        ;
    }
    ;
    $(".time").html(SS);
 
    if(SS=='00'){
    window.location="<?php echo ($wifiurl); ?>";
    }

	
};
var start1 = function(){
	//i = i + 0.6;
	i = i + 360/((gameTime)*<?php echo ($waitsecond); ?>);  
	count = count + 1;
	if(count <= (gameTime/2*<?php echo ($waitsecond); ?>)){  // 一半的角度  90s 为 450
		$(".pie1").css("-o-transform","rotate(" + i + "deg)");
		$(".pie1").css("-moz-transform","rotate(" + i + "deg)");
		$(".pie1").css("-webkit-transform","rotate(" + i + "deg)");
	}else{
		$(".pie2").css("backgroundColor", "black");
		$(".pie2").css("-o-transform","rotate(" + i + "deg)");
		$(".pie2").css("-moz-transform","rotate(" + i + "deg)");
		$(".pie2").css("-webkit-transform","rotate(" + i + "deg)");
	}
};

  	i = 0;
    j = 0;
    count = 0;
    MM = 0;
    SS = <?php echo ($waitsecond); ?>+1;
    MS = 0;
    gameTime=11;
    totle = (MM + 1) * gameTime * <?php echo ($waitsecond); ?>;
    d = 180 * (MM + 1);
    MM = "0" + MM;

    showTime();

    s = setInterval("showTime()", 100);
    start1();
    //start2();
    t1 = setInterval("start1()", 100);
   </script>

<?php if(C('OPENPUSH') == 1): ?><script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/swiper/swiper.js"></script>
    <script>
       var mySwiper = new Swiper('.swiper-container',{
            pagination: '.pagination',
            loop:true,
            grabCursor: true,
            paginationClickable: true,
            calculateHeight:true,
            autoplay:3000,
          });
    </script><?php endif; ?>

</html>