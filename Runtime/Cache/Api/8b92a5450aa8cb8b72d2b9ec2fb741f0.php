<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1; minimum-scale=1; user-scalable=no;" />
    <title><?php echo ($shopinfo[0]['shopname']); ?></title>
    <link href="<?php echo ($Theme['T']['css']); ?>/login.css"  rel="stylesheet"/>
     <!--<link rel="stylesheet" type="text/css" href="__CSS__/Auth/1/style.css" media="all">-->
    <link href="<?php echo ($Theme['P']['js']); ?>/swiper/swiper.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/images/1/defaultstyle.css" media="all">
    <link href="<?php echo ($Theme['T']['css']); ?>/media.css"  rel="stylesheet"/>

   <style>
        body{/*position:fixed;*/  height:100%; width:100%; /*background:url(<?php echo ($Theme['P']['root']); ?>/images/1/images/bg.jpg);*/ background-size:120% auto; font-family:"微软雅黑"; font-size:16px;}
        .newa{
        	color:#333;
        	text-decoration:none;
        	background-color: none;
        }
    </style>
 
    <script type="text/javascript">	
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
 
</head>
<body>


    <!-- Swiper -->

    <div class="swiper-container" style="height:auto">
        <div class="swiper-wrapper" style="height:100%">
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
<div class="content" style="position: absolute;top:20%;z-index:50;margin:0 auto;width: 100%;text-align: center;" id="renzheng">
	<!--<div class="protocol">
	<div class="protocol-img"></div>
	<div class="protocol-text" style="padding:0.5em;">
	为了提供安全可靠的免费无线服务,您需要注册确认。
	</div></div>-->
	<?php if($move == 1): ?><div class="ugbox">
                    <div class="ugbox-son"><a  href="<?php echo U('userauth/noAuth');?>"><div class="pub">
                                <span><img src="<?php echo ($Theme['P']['root']); ?>/images/1/images/ico_04.png" height="30" width="30" /></span>
                                <span class="text">点击上网</span>
                            </div>	</a></div>
             
                    </div>
        		 
                 <?php else: ?>
	
	

    <?php if(($show) == "1"): if($authmode['open'] == true): if(($authmode['overmax']) == "0"): ?><div class="ugbox">
                    <?php if(($authmode['wx']) == "1"): switch($app): case "0": ?><div class="ugbox-son">
                        	<a href="<?php echo U('userauth/wxuserauth');?>" onclick="return test1()">
                        	 <div class="pub">
                                <span><img src="<?php echo ($Theme['P']['root']); ?>/images/1/images/ico_05.png" height="30" width="30" /></span>
                                <span  class="text"> 微信认证</span>
                            </div>
                        	</a>
                        </div><?php break;?>
                        <?php case "1": ?><div class="ugbox-son">
                        	<a href="<?php echo U('userauth/wxauth');?>">
                        	 <div class="pub">
                                <span><img src="<?php echo ($Theme['P']['root']); ?>/images/1/images/ico_05.png" height="30" width="30" /></span>
                                <span  class="text"> 微信认证</span>
                            </div>
                        	</a>
                        </div><?php break; endswitch; endif; ?>
				</div>
				<div class="ugbox">
                    <?php if(($authmode['duanyu']) == "1"): ?><div class="ugbox-son"><a  href="<?php echo U('userauth/duanyu');?>"><div class="pub">
                                <span><img src="<?php echo ($Theme['P']['root']); ?>/images/1/images/ico_04.png" height="30" width="30" /></span>
                                <span class="text">短语认证</span>
                            </div>	</a></div><?php endif; ?>
                  </div>
				<div class="ugbox">
                    <?php if(($authmode['allow']) == "1"): ?><div class="ugbox-son"><a  href="<?php echo U('userauth/noAuth');?>"><div class="pub">
                                <span><img src="<?php echo ($Theme['P']['root']); ?>/images/1/images/ico_04.png" height="30" width="30" /></span>
                                <span class="text">直接上网</span>
                            </div>	</a></div><?php endif; ?>
                  </div>
                  
                  
                    	<div class="ugbox">
                    <?php if(($authmode['qq']) == "1"): ?><div class="ugbox-son"><a  href="<?php echo U('userauth/fenxiang');?>" onclick="return test1()"><div class="pub">
                                <span><img src="<?php echo ($Theme['P']['root']); ?>/images/1/images/ico_03.png" height="30" width="30" /></span>
                                <span class="text">微信分享</span>
                            </div>	</a></div><?php endif; ?>
                    </div>
                    
                    <?php if(($authmode['phone']) == "1"): ?><div class="ugbox">
                        <div class="ugbox-son"><a href="<?php echo U('userauth/mobile');?>">
                          <div class="pub">
                                <span><img src="<?php echo ($Theme['P']['root']); ?>/images/1/images/ico_04.png" height="30" width="30" /></span>
                                <span class="text">手机认证</span>
                            </div>	
                        	
                        	
                        </a></div><?php endif; ?>
				</div>
				<?php if(($authmode['reg']) == "1"): ?><div class="ugbox">
                        <div class="ugbox-son"><a  href="<?php echo U('userauth/reg');?>">
                        	<div class="pub">
                                <span><img src="<?php echo ($Theme['P']['root']); ?>/images/1/images/ico_03.png" height="30" width="30" /></span>
                                <span class="text">注册认证</span>
                            </div>
                        	</a></div>
                       </div>
                       
                   <div class="ugbox"><div class="ugbox-son"><a  href="<?php echo U('userauth/login');?>">
                        	<div class="pub">
                                <span><img src="<?php echo ($Theme['P']['root']); ?>/images/1/images/ico_02.png" height="30" width="30" /></span>
                                <span class="text">会员登录</span>
                          </div></a></div></div><?php endif; ?>
				<div class="ugbox">
                    <?php if(($authmode['app']) == "1"): ?><div class="ugbox-son"><a  href="<?php echo U('login/app');?>"><div class="pub">
                                <span><img src="<?php echo ($Theme['P']['root']); ?>/images/1/images/ico_03.png" height="30" width="30" /></span>
                                <span class="text">APP下载</span>
                            </div>	</a></div><?php endif; ?>
                    </div>
				
			<?php else: ?>
					<div class="ugbox">
                        <div class="ugbox-son">
                            <h2 style="text-align: center;line-height:40px;">每日免费上网次数是<?php echo ($shopinfo[0]['countmax']); ?>次 </br></h2>
                        </div>
					</div><?php endif; ?>
			
		<?php else: ?>
			<div class="ugbox">
                <div class="ugbox-son">
                    <h2 style="text-align: center;line-height:40px;">当前时间不提供上网服务.</br>
                        <?php if(($authmode['openflag']) == "true"): ?>上网开放时间为每日 <?php echo ($authmode["opensh"]); ?>:00点至<?php echo ($authmode["openeh"]); ?>:00点<?php endif; ?>
                    </h2>
                </div>
			</div><?php endif; endif; endif; ?>

	<div class="ugbox">
    	<div class="ugbox-son">
        	<a  href="<?php echo U('userauth/comments');?>">
                              <div class="pub">
                                <span><img src="<?php echo ($Theme['P']['root']); ?>/images/1/images/ico_03.png" height="30" width="30" /></span>
                                <span class="text">客户留言</span>
                            </div></a></div>
 
    </div>
    </div>
    
    <div style="width: 100%;text-align: center;position: absolute;top: 90%;z-index:15">
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
   <div style="position: fixed;top:80%;text-align:center;z-index:99;width: 100%;">
    	<?php if($wifiphone != ''): ?><p style="color: darkred;text-align: center;">wifi广告营销：<?php echo ($wifiphone); ?></p>
    	<?php else: endif; ?>
    	<hr>
    <p style="font-size: 12px;text-align: center;">©神行通为您提供安全无线服务</p>
   </div>



    <script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/swiper/swiper.min.js"></script>
    <script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
<!--     <script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/wechatutil.js"></script>-->
    <script type="text/javascript">
	    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        autoplay: 3000
    });

// alert(navigator.userAgent);
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
        confirm("请到微信中打开任意链接，扫描二维码关注公众号上网");
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
/**
		 * 微信连Wi-Fi协议3.1供运营商portal呼起微信浏览器使用
		 */
		 var loadIframe = null;
		 
		function putNoResponse(ev){
			 clearTimeout(noResponse);
		}	
		
		 function errorJump()
		 {
			 alert('您的手机浏览器可能无法跳转到微信，如果已跳转请忽略此提示');
		 }
		 
		 myHandler = function(error) {
			 errorJump();
		 };
		 
		 function createIframe(){
			 var iframe = document.createElement("iframe");
		     iframe.style.cssText = "display:none;width:0px;height:0px;";
		     document.body.appendChild(iframe);
		     loadIframe = iframe;
		 }
		//注册回调函数
		function jsonpCallback(result){  
			if(result && result.success){
			    alert('WeChat will call up : ' + result.success + '  data:' + result.data);
			    createIframe();
			    loadIframe.src=result.data;
				noResponse = setTimeout(function(){
					errorJump();
		      	},3000);
			}else if(result && !result.success){
				alert(result.data);
			}
		}
		
		function Wechat_GotoRedirect(appId, extend, timestamp, sign, shopId, authUrl, mac, ssid, bssid){
			
			//将回调函数名称带到服务器端
			var url = "https://wifi.weixin.qq.com/operator/callWechatBrowser.xhtml?appId=" + appId 
																				+ "&extend=" + extend 
																				+ "&timestamp=" + timestamp 
																				+ "&sign=" + sign;	
			
			//如果sign后面的参数有值，则是新3.1发起的流程
			if(authUrl && shopId && mac && ssid && bssid){
				
				
				url = "https://wifi.weixin.qq.com/operator/callWechat.xhtml?appId=" + appId 
																				+ "&extend=" + extend 
																				+ "&timestamp=" + timestamp 
																				+ "&sign=" + sign
																				+ "&shopId=" + shopId
																				+ "&authUrl=" + encodeURIComponent(authUrl)
																				+ "&mac=" + mac
																				+ "&ssid=" + ssid
																				+ "&bssid=" + bssid;
				
			}			
			
			//通过dom操作创建script节点实现异步请求  
			var script = document.createElement('script');  
			script.setAttribute('src', url);  
			document.getElementsByTagName('head')[0].appendChild(script);
		}


function callWechatBrowser(){
		Wechat_GotoRedirect(
		'wx72775b34537262b0',
		'demo', 
		'<?php echo ($timestamp); ?>', 
		'<?php echo ($sign); ?>', 
		'8232665', 
		'http://wifi.weixin.qq.com/assistant/wifigw/auth', 
		'<?php echo ($mac); ?>',
		'shenxingtong', 
		'<?php echo ($gw_id); ?>');
}
//document.addEventListener('visibilitychange', putNoResponse, false);

//callWechatBrowser();

           $(function(){
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
 $("#remember-me").bind('click',function(){
	$('#mianze').show();
});

     </script>
</body>
</html>