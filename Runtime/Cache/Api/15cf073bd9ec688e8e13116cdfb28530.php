<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<title><?php echo ($shopinfo[0]['shopname']); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/tmpl/default3//bootstrap/bootstrap.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/tmpl/default3/css/libs/font-awesome.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/tmpl/default3/css/supersized.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/tmpl/default3/css/buttons.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/tmpl/default3/css/globle.css"/>
	<link href="<?php echo ($Theme['P']['js']); ?>/swiper/swiper.min.css" rel="stylesheet" />
	 <?php if($shopinfo[0]['focus'] == 1): ?><script type="text/javascript">	
      url = "<?php echo U('Userauth/noauthforweixin');?>";
        if(isWeiXin()){
        	
            window.location.href = url;
        }
        function isWeiXin(){
            var ua = window.navigator.userAgent.toLowerCase();
            if((ua.match(/MicroMessenger/i) == 'micromessenger')&&('<?php echo ($url); ?>'=='http://www.baidu.com/')){
                return true;
            }else{
                return false;
            }
        }
  </script>
  <?php else: endif; ?>
</head>
<body id="login-page">
<div class="container" style="background-color: silver;">
	<div class="swiper-container">
        <div class="swiper-wrapper">
          <?php if(!empty($ad)): if(is_array($ad)): $i = 0; $__LIST__ = $ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div  class="swiper-slide">
                            <?php if(($vo['mode']) == "1"): ?><a href="<?php echo U('userauth/showad',array('id'=>$vo['id']));?>"><img src="<?php echo ($vo["ad_thumb"]); ?>" width="100%" ></a>
                            <?php else: ?>
                                <img src="<?php echo ($vo["ad_thumb"]); ?>" width="100%" ><?php endif; ?>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    <?php else: ?>
                        <div  class="swiper-slide">
                            <img src="<?php echo ($Theme['P']['root']); ?>/images/ad/noad01.jpg" >
                        </div>
                        <div  class="swiper-slide">
                            <img src="<?php echo ($Theme['P']['root']); ?>/images/ad/noad02.jpg" >
                        </div><?php endif; ?>
        </div>
   
        <div class="swiper-pagination"></div>
    </div>
        	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12  login_buttons" id="renzheng">
			<?php if(($show) == "1"): if($authmode['open'] == true): if(($authmode['overmax']) == "0"): if(($authmode['phone']) == "1"): ?><div class="center-block">
                        		
                        	
              <div  class="center-block">
           <a href="<?php echo U('userauth/mobile');?>" class="button  button-rounded button-3d button-large" style="margin: 10px auto;width: 250px;display: block;"><i class="fa fa-envelope-o"></i>手机认证</a><?php endif; ?>
			</div>
			<div class="center-block">
			 <?php if(($authmode['duanyu']) == "1"): ?><a href="<?php echo U('userauth/duanyu');?>" class="button  button-rounded button-3d button-royal button-large"style="margin: 10px auto;width: 250px;display: block;"><i class="fa fa-hand-o-up"></i>短语认证</a><?php endif; ?>
				</div>
			<div class="center-block">
			 <?php if(($authmode['qq']) == "1"): ?><a href="<?php echo U('userauth/fenxiang');?>" onclick="return test()" class="button  button-rounded button-3d button-primary button-large" style="margin: 10px auto;width: 250px;display: block;"><i class="fa fa-wechat"></i>微信分享</a><?php endif; ?>
			</div>
			<div class="center-block">
			<?php if(($authmode['wx']) == "1"): if($app == 1): ?><a href="<?php echo U('userauth/wxauth');?>" class="button  button-rounded button-3d button-action button-large" style="margin: 10px auto;width: 250px;display: block;"><i class="fa fa-wechat"></i>微信认证</a>
		
			<?php else: ?>
			<a href="<?php echo U('userauth/wxuserauth');?>" onclick="return test()" class="button  button-rounded button-3d button-action button-large" style="margin: 10px auto;width: 250px;display: block;"><i class="fa fa-wechat"></i>微信认证</a><?php endif; ?>
			</div><?php endif; ?>
			<div class="center-block">
			  <?php if(($authmode['reg']) == "1"): ?><a href="<?php echo U('userauth/reg');?>" class="button  button-rounded button-3d button-caution button-large"style="margin: 10px auto;width: 250px;display: block;"><i class="fa fa-weibo" ></i>注册认证</a><?php endif; ?>
			</div>
			<div class="center-block">
			  <?php if(($authmode['app']) == "1"): ?><a href="<?php echo U('login/app');?>" class="button  button-rounded button-3d button-caution button-large"style="margin: 10px auto;width: 250px;display: block;"><i class="fa fa-weibo" ></i>APP下载</a><?php endif; ?>
			</div>
				<div class="center-block">
			 <?php if(($authmode['allow']) == "1"): ?><a href="<?php echo U('userauth/noAuth');?>" class="button  button-rounded button-3d button-royal button-large"style="margin: 10px auto;width: 250px;display: block;"><i class="fa fa-hand-o-up"></i>一键认证</a><?php endif; ?>
				</div><?php endif; endif; endif; ?>	

		</div>
		</div>
		<div style="width: 100%;text-align: center;z-index: 99;">
					<!--<h4>免责声明</h4>
	<p id="mianze" style="display: none;">
						尊敬的客户，您在免费使用神行通科技提供的上网服务时，请保护好您的个人信息与资料，以免被他人非法获得或使用。请遵守国家相关法律法规，不得利用互联网从事危害国家和社会稳定、泄露国家秘密和商业秘密、危害市场经济秩序、妨碍社会治安、传播淫秽色情、侵犯他人合法权益等犯罪活动。您在网络上泄露个人信息资料。或者从事违法犯罪行为而导致的相关损失与责任，均由您自行承担，神行通对此不承担任何责任。
					</p>-->
			
			<label for="remember-me">
			<input type="checkbox" id="remember-me"  name="rem" checked="checked"/>
			<span onclick="mianze()" style="color: cornflowerblue;">我已阅读并同意免责声明的服务</span>
			</label>
		
		</div>
<div   style="text-align:center;z-index:30;margin:0 auto;">
		<?php if($wifiphone != ''): ?><p style="color: darkred;text-align: center;">wifi广告营销：<?php echo ($wifiphone); ?></p>
    	<?php else: endif; ?>
    	
    <p style="font-size: 15px;text-align: center; color: white;">©神行通为您提供安全无线服务</p>
	</div>
	</div>
</div>
 <script type="text/javascript"  src="<?php echo ($Theme['P']['root']); ?>/tmpl/default3/js/jquery.js"></script>
<!--<script type="text/javascript" src="<?php echo ($Theme['P']['root']); ?>/tmpl/default3/js/supersized.3.2.7.min.js"></script>
<script type="text/javascript" src="<?php echo ($Theme['P']['root']); ?>/tmpl/default3/js/supersized-init.js"></script>-->
 <script type="text/javascript"  src="<?php echo ($Theme['P']['js']); ?>/swiper/swiper.min.js"></script>
<script>
//	jQuery(function($){
//
//		$.supersized({
//
//			// Functionality
//			slide_interval     : 6000,    // Length between transitions
//			transition         : 1,    // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
//			transition_speed   : 3000,    // Speed of transition
//			performance        : 1,    // 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
//
//			// Size & Position
//			min_width          : 0,    // Min width allowed (in pixels)
//			min_height         : 0,    // Min height allowed (in pixels)
//			vertical_center    : 1,    // Vertically center background
//			horizontal_center  : 1,    // Horizontally center background
//			fit_always         : 0,    // Image will never exceed browser width or height (Ignores min. dimensions)
//			fit_portrait       : 1,    // Portrait images will not exceed browser height
//			fit_landscape      : 0,    // Landscape images will not exceed browser width
//
//			// Components
//			slide_links        : 'blank',    // Individual links for each slide (Options: false, 'num', 'name', 'blank')
//			slides             : [    // Slideshow Images
//				{image : "<?php echo ($Theme['P']['root']); ?>/tmpl/default3/img/login/1.jpg"},
//				{image : "<?php echo ($Theme['P']['root']); ?>/tmpl/default3/img/login/2.jpg"},
//				{image : "<?php echo ($Theme['P']['root']); ?>/tmpl/default3/img/login/3.jpg"}
//			]
//
//		});
//	});
    $(function(){
	    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        autoplay: 3000
    }); $.ajax({
                    url: "<?php echo U('login/countad');?>",
                    type: "post",
                    data:{
                        'ids':"<?php echo ($adid); ?>"
                        },
                    dataType:'json',
                    error:function(){},
                    success:function(data){}
                  });
            });

 var useragent = navigator.userAgent;
    function test(){
    if (useragent.match(/MicroMessenger/i) != 'MicroMessenger') {
        confirm("请到微信中打开任意链接，关注本店认证上网");
       return false;
       }else{
	   return true;
	  }
      }
      function mianze(){
      	window.location.href="http://ap.wifi01.cn/mianze.html";
      }
$('#remember-me').bind('click',function(){
   $('#renzheng').toggle();
	
});    
</script>
</body>
</html>