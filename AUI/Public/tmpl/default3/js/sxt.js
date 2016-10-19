/**
 * Created by imjzq on 2015/7/23.
 */
$(function () {
	moment.locale('zh-cn');
	get_Shopinfo();
	get_RoutesInfo();
})
//TODO:获取店面信息
function get_Shopinfo() {
	$.ajax({
		type:"POST",
		url:"json/shop.json",
		data:"name=John&location=Boston",
		dataType:"json",
		cache:false,
		success:function (data) {
			$('#info_account').text(data.account);
			$('#info_shopname').text(data.shopname);
			$('#info_linker').text(data.linker);
			$('#info_phone').text(data.phone);
			$('#info_address').text(data.address);
		}
	});
}
function FashionTime(code) {
	var unixTimestamp = new Date(code * 1000) ; //本地时间格式
	var _fation=moment.unix(code).fromNow();    //个性时间
	var _num=parseInt(_fation); //个性时间数字部分
	return {
		time:unixTimestamp.toLocaleString(),    //当前标准时间
		Fashion: _fation,   //个性时间
		num:parseInt(_fation),  //个性时间整数部分
		unit:_fation.replace(_num,"")   //个性时间单位
	};
}
//TODO:获取路由器列表
function get_RoutesInfo() {
	$.ajax({
		type:"POST",
		url:"json/routes.json",
		data:"name=John&location=Boston",
		dataType:"json",
		cache:false,
		success:function (data) {
			$('#s_online').text(data.route.online); //路由器在线数
			$('#s_total').text(data.route.num); //路由器总数

			$('#routers').empty();
			$.each(data.route.routeinfo,function () {
				$('#routers').append($('#RoutesTemplate').html());  //插入一个空白模版然后填充内容
				if (!this.isonline) {   //启动停止按钮
					$('#routers .Status_button:last ').addClass('button-caution');
					$('#routers .Status_icon:last').addClass('fa-power-off');
					$('#routers .user-link:last').addClass('gray'); //灰色标题显示
					$('#routers .status:last').text('离线');//在线小标签
					$('#routers .status:last').addClass('label-default');

				} else {
					$('#routers .Status_button:last').addClass('button-primary');
					$('#routers .Status_icon:last').addClass('fa-play');
					$('#routers .status:last').text("在线");//在线小标签
					$('#routers .status:last').addClass('label-success');
				}

				$('#routers .user-link:last').text(this.routename); //路由器名称
			//在线时间显示
				$('#routers .heart_beat:last').text(FashionTime(this.last_heartbeat_time).num);
				$('#routers .ago:last').text(FashionTime(this.last_heartbeat_time).unit);

			});
		},
		error:function (XMLHttpRequest,textStatus,errorThrown) {
			// 通常 textStatus 和 errorThrown 之中
			// 只有一个会包含信息
			console.log(XMLHttpRequest + textStatus + errorThrown); // 调用本次AJAX请求时传递的options参数
		}
	});
}