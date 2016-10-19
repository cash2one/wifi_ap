<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1; minimum-scale=1; user-scalable=no;" />
    <title><?php echo ($shopinfo[0]['shopname']); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/css/css.css"><!--风格-->
    <link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/css/media.css"><!--自适应-->
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
<body>
	<!-- 头部 BOF-->

	<!-- 头部 EOF-->
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
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>
<div class="mainbox bgindex clearfix">
	<!-- 轮播广告 BOF-->
	</div>
	<!-- 轮播广告 EOF -->
	
	<!-- 功能菜单 BOF-->
	<div class="bbox" style="z-index:50;margin:0 auto;width: 100%;text-align: center;" id="renzheng">
        <div class="boxcontent">
        	<?php if($move == 1): ?><a href="<?php echo U('userauth/noAuth');?>">
                            <div class="btnbox">
                                <img src="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/img/02.png"/>
                                <div class="btntitle">
                               	 点击上网
                                </div>
                            </div>
                        </a>
                 <?php else: ?>
            <?php if(($show) == "1"): if($authmode['open'] == true): if(($authmode['overmax']) == "0"): if(($authmode['wx']) == "1"): switch($app): case "1": ?><a href="<?php echo U('userauth/wxauth');?>">
                                <div class="btnbox">
                                    <img src="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/img/01.png"/>
                                    <div class="btntitle">
                               微信认证  
                                    </div>
                                </div>
                            </a><?php break;?>
                     <?php case "0": ?><a href="<?php echo U('userauth/wxuserauth');?>" onclick="return test()">
                                <div class="btnbox">
                                    <img src="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/img/01.png"/>
                                    <div class="btntitle">
                               微信认证  
                                    </div>
                                </div>
                            </a><?php break; endswitch; endif; ?>
                         <?php if(($authmode['duanyu']) == "1"): ?><a href="<?php echo U('userauth/duanyu');?>">
                                    <div class="btnbox">
                                        <img src="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/img/02.png"/>
                                        <div class="btntitle">
                                                     短语认证
                                        </div>
                                    </div>
                                </a><?php endif; ?>
                        <?php if(($authmode['allow']) == "1"): ?><a href="<?php echo U('userauth/noAuth');?>">
                            <div class="btnbox">
                                <img src="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/img/02.png"/>
                                <div class="btntitle">
                                直接上网
                                </div>
                            </div>
                        </a><?php endif; ?>
                            <?php if(($authmode['phone']) == "1"): ?><a href="<?php echo U('userauth/mobile');?>">
                                    <div class="btnbox">
                                        <img src="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/img/03.png"/>
                                        <div class="btntitle">
                                        手机认证
                                        </div>
                                    </div>
                                </a><?php endif; ?>

                            <?php if(($authmode['qq']) == "1"): ?><a href="<?php echo U('userauth/noAuth');?>" onclick="return test1()">
                                    <div class="btnbox">
                                        <img src="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/img/02.png"/>
                                        <div class="btntitle">
                                                      微信分享
                                        </div>
                                    </div>
                                </a><?php endif; ?>
                            
                             <?php if(($authmode['app']) == "1"): ?><a href="<?php echo U('login/app');?>" >
                                    <div class="btnbox">
                                        <img src="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/img/02.png"/>
                                        <div class="btntitle">
                                                      APP下载
                                        </div>
                                    </div>
                                </a><?php endif; ?>

                            <?php if(($authmode['reg']) == "1"): ?><a href="<?php echo U('userauth/reg');?>">
                                    <div class="btnbox">
                                        <img src="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/img/04.png"/>
                                        <div class="btntitle">
                                        注册认证
                                        </div>
                                    </div>
                                </a>

                                <a href="<?php echo U('userauth/login');?>">
                                    <div class="btnbox">
                                        <img src="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/img/05.png"/>
                                        <div class="btntitle">
                                        用户登录
                                        </div>
                                    </div>
                                </a><?php endif; ?>
                                <a href="<?php echo U('userauth/comments');?>">
                                    <div class="btnbox">
                                        <img src="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/img/06.png"/>
                                        <div class="btntitle">
                                        客户留言
                                        </div>
                                    </div>
                                </a>
                        <div class="clear"></div>
                    <?php else: ?>
                        <h2 style="text-align: center;line-height:40px;">每日免费上网次数是<?php echo ($shopinfo[0]['countmax']); ?>次 </br><?php endif; ?>
                <?php else: ?>
                    <h2 style="text-align: center;line-height:40px;">当前时间不提供免费上网服务.</br>
                        <?php if(($authmode['openflag']) == "true"): ?>上网开放时间为每日 <?php echo ($authmode["opensh"]); ?>:00点至<?php echo ($authmode["openeh"]); ?>:00点<?php endif; ?>
                    </h2><?php endif; endif; endif; ?>
        </div>
	</div>
	
	
 <div style="width: 100%;text-align: center;position: fixed;top:85%;z-index: 99;">
					<!--<h4>免责声明</h4>
	<p id="mianze" style="display: none;">
						尊敬的客户，您在免费使用神行通科技提供的上网服务时，请保护好您的个人信息与资料，以免被他人非法获得或使用。请遵守国家相关法律法规，不得利用互联网从事危害国家和社会稳定、泄露国家秘密和商业秘密、危害市场经济秩序、妨碍社会治安、传播淫秽色情、侵犯他人合法权益等犯罪活动。您在网络上泄露个人信息资料。或者从事违法犯罪行为而导致的相关损失与责任，均由您自行承担，神行通对此不承担任何责任。
					</p>-->
			
			<label for="remember-me">
			<input type="checkbox" id="remember-me"  name="rem" checked="checked"/>
			<span onclick="mianze()" style="color: cornflowerblue;">我已阅读并同意免责声明的服务</span>
			</label>
		
		</div>
	<br/>   
	<div style="position: fixed;top:90%;text-align:center;z-index:30;margin:0 0 0 25%;">
		<?php if($wifiphone != ''): ?><p style="color: darkred;text-align: center;">wifi广告营销：<?php echo ($wifiphone); ?></p>
    	<?php else: endif; ?>
    	<hr>
    <p style="font-size: 12px;text-align: center;">©神行通为您提供安全无线服务</p>
	</div>
	<!-- 功能菜单 BOF-->
		

    <script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
    <script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/swiper/swiper.min.js"></script>
    <script type="text/javascript">
    var useragent = navigator.userAgent;
    function test(){
   if (useragent.match(/MicroMessenger/i) != 'MicroMessenger') {
        confirm("请到微信中打开任意链接，关注本店认证上网");
        
        return false;
       }else{
	   return true;
	  }
// 以下代码是用javascript强行关闭当前页面
//      var opened = window.open('www.baidu.com', '_self');
//      opened.opener = null; 
//      opened.close();
    }
     function test1(){
    if (useragent.match(/MicroMessenger/i) != 'MicroMessenger') {
        confirm("请到微信中打开任意链接，分享本店上网");
       return false;
       }else{
	   return true;
	  }
// 以下代码是用javascript强行关闭当前页面
//      var opened = window.open('www.baidu.com', '_self');
//      opened.opener = null; 
//      opened.close();
    }
           
       function mianze(){
      	window.location.href="http://ap.wifi01.cn/mianze.html";
      }
$('#remember-me').bind('click',function(){
   $('#renzheng').toggle();
	
});    
           
           
           $(function(){
	    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        autoplay: 3000
    });

                $.ajax({
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
    </script>

</body>
</html>