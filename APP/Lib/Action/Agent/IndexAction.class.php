<?php
class IndexAction extends  BaseAction{

    //代理商用户ID
	private  $aid;

	protected  function _initialize(){
		parent::_initialize();
		if(!session('aid')||session('aid')==null||session('aid')==''){
			$this->redirect('index/index/alog');
		}else{
			$this->aid=session('aid');
			$this->loadMenu();
		}
	}

	public  function getuserchart(){
    	
    	$way=I('get.mode');
    	
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
    			$sql.="(select  FROM_UNIXTIME(tt.add_time,\"%H\") as thour,tt.id,tt.mode from ".C('DB_PREFIX')."member tt left join ".C('DB_PREFIX')."shop ss on tt.shopid=ss.id where ss.pid=".$this->aid."";
				$sql.=" and tt.add_date='".date("Y-m-d")."' and ( tt.mode=0 or tt.mode=1 ) ";
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		
    	}
    
    	$db=D('Member');
    	$rs=$db->query($sql);
    	
    	$this->ajaxReturn(json_encode($rs));
    }

    /**
     * 上网统计
     */
    public function getauthrpt(){
    	
    	$way=I('get.mode');
    	
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(ct,0)  as ct ,COALESCE(ct_reg,0)  as ct_reg,COALESCE(ct_phone,0)  as ct_phone,COALESCE(ct_key,0)  as ct_key,COALESCE(ct_log,0)  as ct_log from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="( select thour ,count(*) as ct ,count(case when mode=0 then 1 else null end) as ct_reg,count(case when mode=1 then 1 else null end) as ct_phone,count(case when mode=2 then 1 else null end) as ct_key,count(case when mode=3 then 1 else null end) as ct_log from ";
				$sql.="(select tt.shopid,tt.mode,FROM_UNIXTIME(tt.login_time,\"%H\") as thour,";
				$sql.=" FROM_UNIXTIME(tt.login_time,\"%Y-%m-%d\") as d from ".C('DB_PREFIX')."authlist tt left join ".C('DB_PREFIX')."shop ss on tt.shopid=ss.id where ss.pid=".$this->aid.") a ";
				$sql.=" where d='".date("Y-m-d")."' ";
				$sql.=" group by thour ) ";
				$sql.=" b on a.t=b.thour ";

    			break;
    	}
    	$db=D('Authlist');
    	$rs=$db->query($sql);
    	
    	$this->ajaxReturn(json_encode($rs));
    }
	
	private function  loadMenu(){
		$path=CONF_PATH.GROUP_NAME."/Menu.php";
		if(is_file($path)){
			$config = require $path;
		}
		$this->assign("menu",$config);
	}
	public function  app(){
		$app=D('app');
		$info=$app->where(array('pid'=>session('aid')))->select();
		if(empty($info)){
			$this->error('您名下还未有商户开通app下载认证');
		}else{
		$nav['m']=$this->getActionName();
		$nav['a']='report';
		$this->assign('nav',$nav);
		import('@.ORG.AdminPage');
		$where['pid']=$this->aid;
		$count=$app->where($where)->count();
		$page=new AdminPage($count,C('ag_page'));
        $result = $app->where($where)->limit($page->firstRow.','.$page->listRows)->order(" time desc")-> select();
        foreach($result as $k=>$v){
        	$shop=D('shop');
			 $name=$shop->where(array('id'=>$v['uid']))->getField('shopname');
			 $result[$k]['shopname']=$name;
        }
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->assign('count',$count);
     
		$this->display();
		}
		
		
		
	}

	public function index(){
		$nav['m']=$this->getActionName();
		$nav['a']='index';
		
		$db=M();
		$cxdays=D('cxdays');
		$date=date('Y-m-d');
		$yes=date('Y-m-d',strtotime('- 1 day'));
		$sql="select count(*) from(select b.id as id from wifi_agent a left join wifi_shop b on b.pid=a.id where a.id=$this->aid) c left join wifi_routemap d on d.shopid=c.id where gw_id like '%sxt%'";
		$sum=$db->query($sql);
		$timestamp=time()-200;
		$sqlon="select count(*) from(select b.id as id from wifi_agent a left join wifi_shop b on b.pid=a.id where a.id=$this->aid) c left join wifi_routemap d on d.shopid=c.id where gw_id like '%sxt%' and update_yuancheng>$timestamp";
		$a=$db->query($sqlon);
		$b=$sum[0]['count(*)']-$a[0]['count(*)'];
		$this->assign('a',$a[0]['count(*)']);
		$this->assign('b',$b);
		$sqlnum="select sum(wlan_user_num) from(select b.id as id from wifi_agent a left join wifi_shop b on b.pid=a.id where a.id=$this->aid) c left join wifi_routemap d on d.shopid=c.id where gw_id like '%sxt%' and update_yuancheng>$timestamp";
		$num=$db->query($sqlnum);
        $this->assign('num',$num[0]['sum(wlan_user_num)']);
		
		$sqlinfo="select routename,wlan_user_num from(select b.id as id from wifi_agent a left join wifi_shop b on b.pid=a.id where a.id=$this->aid) c left join wifi_routemap d on d.shopid=c.id where gw_id like '%sxt%' and update_yuancheng>$timestamp";
		$list=$db->query($sqlinfo);
		$code=json_encode($list);
		$this->assign('list',$code);
		$ways=M();
		$c=$ways->query("select sum(`00`) from wifi_ways where pid=$this->aid");
		$d=$ways->query("select sum(`01`) from wifi_ways where pid=$this->aid");
		$e=$ways->query("select sum(`02`) from wifi_ways where pid=$this->aid");
		$f=$ways->query("select sum(`03`) from wifi_ways where pid=$this->aid");
		$g=$ways->query("select sum(`04`) from wifi_ways where pid=$this->aid");
	
		$this->assign('c',$c[0]['sum(`00`)']);
		$this->assign('d',$d[0]['sum(`01`)']);
		$this->assign('e',$e[0]['sum(`02`)']);
		$this->assign('f',$f[0]['sum(`03`)']);
		$this->assign('g',$g[0]['sum(`04`)']);
		$sqltoday="select sum(`00`) as '00',sum(`01`) as '01',sum(`02`) as '00',sum(`03`) as '03',sum(`04`) as '04',sum(`05`) as '05',sum(`06`) as '06',sum(`07`) as '07',sum(`08`) as '08',sum(`09`) as '09',sum(`10`) as '10',sum(`11`) as '11',sum(`12`) as '12',sum(`13`) as '13',sum(`14`) as '14',sum(`15`) as '15',sum(`16`) as '16',sum(`17`) as '17',sum(`18`) as '18',
		sum(`19`) as '19',sum(`20`) as '20',sum(`21`) as '21',sum(`22`) as '22',sum(`23`) as '23' from wifi_cxdays where pid=$this->aid and date='$date'";
	   mysql_connect("localhost","root","xuezhehan1121");
	   mysql_select_db('wifiadv');
		$result=mysql_query($sqltoday);
		$today=mysql_fetch_row($result);
		if(empty($today)){
		$today=array(
			'00'=>0,
			'01'=>0,
			'02'=>0,
			'03'=>0,
			'04'=>0,
			'05'=>0,
			'06'=>0,
			'07'=>0,
			'08'=>0,
			'09'=>0,
			'10'=>0,
			'11'=>0,
			'12'=>0,
			'13'=>0,
			'14'=>0,
			'15'=>0,
			'16'=>0,
			'17'=>0,
			'18'=>0,
			'19'=>0,
			'20'=>0,
			'21'=>0,
			'22'=>0,
			'23'=>0
			);
	$todays=json_encode($today);
	}else{
	$todays=json_encode($today);
 	foreach($today as $k=>$v){
          	$todaynum=$todaynum+$v;
			}
		}
		$sqlyester="select sum(`00`) as '00',sum(`01`) as '01',sum(`02`) as '00',sum(`03`) as '03',sum(`04`) as '04',sum(`05`) as '05',sum(`06`) as '06',sum(`07`) as '07',sum(`08`) as '08',sum(`09`) as '09',sum(`10`) as '10',sum(`11`) as '11',sum(`12`) as '12',sum(`13`) as '13',sum(`14`) as '14',sum(`15`) as '15',sum(`16`) as '16',sum(`17`) as '17',sum(`18`) as '18',
		sum(`19`) as '19',sum(`20`) as '20',sum(`21`) as '21',sum(`22`) as '22',sum(`23`) as '23' from wifi_cxdays where pid=$this->aid and date='$yes'";	
		$results=mysql_query($sqlyester);
		$yester=mysql_fetch_row($results);
		if(empty($yester)){
		$yester=array(
			'00'=>0,
			'01'=>0,
			'02'=>0,
			'03'=>0,
			'04'=>0,
			'05'=>0,
			'06'=>0,
			'07'=>0,
			'08'=>0,
			'09'=>0,
			'10'=>0,
			'11'=>0,
			'12'=>0,
			'13'=>0,
			'14'=>0,
			'15'=>0,
			'16'=>0,
			'17'=>0,
			'18'=>0,
			'19'=>0,
			'20'=>0,
			'21'=>0,
			'22'=>0,
			'23'=>0
			);
	$yesterdays=json_encode($yester);
	}else{
	$yesterdays=json_encode($yester);
 	foreach($yester as $k=>$v){
          	$yesnum=$yesnum+$v;
		}
		}
		$months=array();
	$start=date('Y-m-01', strtotime(date("Y-m-d")));
	$end=date('Y-m-d',strtotime('-1 days'));	
	$sqlmonth="select date, sum(`00`) as '00',sum(`01`) as '01',sum(`02`) as '00',sum(`03`) as '03',sum(`04`) as '04',sum(`05`) as '05',sum(`06`) as '06',sum(`07`) as '07',sum(`08`) as '08',sum(`09`) as '09',sum(`10`) as '10',sum(`11`) as '11',sum(`12`) as '12',sum(`13`) as '13',sum(`14`) as '14',sum(`15`) as '15',sum(`16`) as '16',sum(`17`) as '17',sum(`18`) as '18',
		sum(`19`) as '19',sum(`20`) as '20',sum(`21`) as '21',sum(`22`) as '22',sum(`23`) as '23' from wifi_cxdays where pid=$this->aid and date between '$start' and '$end' group by date";		
		$resultm=mysql_query($sqlmonth);
		while($month=mysql_fetch_row($resultm)){;
		$months[]=array(array_shift($month),array_sum($month));
		$monthnum=$monthnum+array_sum($month);
		
		}
        $monthjs=json_encode($months);
		$date=array();
		  if(IS_POST){
    	  $dt_start = strtotime($_POST['sdate']);
  		  $dt_end   = strtotime($_POST['edate']);
    do { 
        //将 Timestamp 转成 ISO Date 输出
        $newdate=date('Y-m-d', $dt_start);
       $sqlnew="select sum(`00`) as '00',sum(`01`) as '01',sum(`02`) as '00',sum(`03`) as '03',sum(`04`) as '04',sum(`05`) as '05',sum(`06`) as '06',sum(`07`) as '07',sum(`08`) as '08',sum(`09`) as '09',sum(`10`) as '10',sum(`11`) as '11',sum(`12`) as '12',sum(`13`) as '13',sum(`14`) as '14',sum(`15`) as '15',sum(`16`) as '16',sum(`17`) as '17',sum(`18`) as '18',
		sum(`19`) as '19',sum(`20`) as '20',sum(`21`) as '21',sum(`22`) as '22',sum(`23`) as '23' from wifi_cxdays where pid=$this->aid and date='$newdate'";	
		$resultnew=mysql_query($sqlnew);
		$lineinfo=mysql_fetch_row($resultnew);
	if(!empty($lineinfo)){
			$date[]=array(date('Y-m-d', $dt_start),array_sum($lineinfo));
		
	}else{
		$date[]=array(date('Y-m-d', $dt_start),0);
	}
    } while (($dt_start += 86400) <= $dt_end); 
	$this->ajaxReturn($date);
    }
		$sqlshop="select shopid from wifi_routemap where shopid in (select id from wifi_shop where pid=$this->aid) and update_yuancheng>$timestamp group by shopid";
		$sonnum=$db->query($sqlshop);
	
		$shopnum=count($sonnum);
		$shop=D('shop');
		$shopid=$shop->where(array('pid'=>$this->aid))->field('id,shopname')->select();
		foreach($shopid as $k=>$v){
			 $map['date']=date("Y-m-d");
		 	 $map['shopid']=$v['id'];
		 	 $shoprz=$cxdays->where($map)->field('date,00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23')->find();
		 	if($shoprz){
		 		array_shift($shoprz);
		 		$arr[]=array($v['shopname'],array_sum($shoprz));
		 	}
		 	
		 	
		 	 }
		 	  $shopren=json_encode($arr);
	  $this->assign('shopren',$shopren);
		$this->assign('shopnum',$shopnum);
		//每月认证书
		$year=date("Y");
	 for($i=1;$i<13;$i++){
	 	if($i<10){
	 	$datas["date"]=array('like',"$year-0".$i.'%');
		$datas['pid']=$this->aid;
		}else{
		$datas["date"]=array('like',"$year-$i%");
		$datas['pid']=$this->aid;
		}
		$monthinfo=$cxdays->where($datas)->field('date,00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23')->select();
		if($monthinfo){
			foreach($monthinfo as $k=>$v){
			array_shift($v);
			$m[$i]+=array_sum($v);
		}
		}else{
		$m[$i]=0;
		}
		}
		
		$agent=D('agent');
		$agentinfo=$agent->where(array('id'=>$this->aid))->field('money,fee')->find();
		$this->assign('agent',$agentinfo);
		
	 $this->assign('mm',$m);
		$this->assign('monthjs',$monthjs);
		$this->assign('monthnum',$monthnum);
		$this->assign('yesterdays',$yesterdays);
		$this->assign('yesnum',$yesnum);
		$this->assign('todays',$todays);
		$this->assign('todaynum',$todaynum);
		$this->assign('nav',$nav);
		$this->display();
	}
	
	public function shoplist(){
		$nav['m']=$this->getActionName();
		$nav['a']='shop';
		$this->assign('nav','shop');
		$this->assign('navbar','list');
		if(IS_POST){
		$mac=I('post.mac');
		$key=M();
		$sql="select id,add_time,account,shopname,linker,phone from wifi_shop where ((account like '%$mac%') or (shopname like '%$mac%') or (linker like '%$mac%') or (phone like '%$mac%')) and (pid=$this->aid)";
    	$res=$key->query($sql);	
		 $this->assign('lists',$res);
		}else{
		import('@.ORG.AdminPage');
		$db=D('Shop');
		$where['pid']=$this->aid;
		$where['vip']=0;
		$count=$db->where($where)->count();
		$page=new AdminPage($count,C('ag_page'));
        $result = $db->where($where)->field('id,shopname,add_time,linker,phone,account,maxcount,linkflag')->order('add_time desc')->limit($page->firstRow.','.$page->listRows)-> select();
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		}
		
		
     
        $this->display();        

	}
	public function viplist(){
		$nav['m']=$this->getActionName();
		$nav['a']='vip';
		$this->assign('nav',$nav);
		
		import('@.ORG.AdminPage');
		$db=D('Shop');
		$where['pid']=$this->aid;
		$where['vip']=1;
		$count=$db->where($where)->count();
		$page=new AdminPage($count,C('ag_page'));
        $result = $db->where($where)->field('id,shopname,add_time,linker,phone,account,maxcount,linkflag,vipflag')->order('add_time desc')->limit($page->firstRow.','.$page->listRows)-> select();
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
     
        $this->display();        

	}
	public function search(){
		$mac=I('post.mac');
		$db=M();
		$sql="select id,add_time,account,shopname,linker,phone from wifi_shop where (account like '%$mac%') or (shopname like '%$mac%') or (linker like '%$mac%') or (phone like '%$mac%')";
    	 $res=$db->query($sql);
	 if($res==null){
	 	$data['error']=0;
	    $data['msg']="没有该商户信息";
  		 return $this->ajaxReturn($data);
		}
	 else{
	 	$data['error']=1;
		 $data['msg']=$res[0];
		 return $this->ajaxReturn($data);
	}
		
	}
	
	 public function yuancheng(){
  			$id=I('get.id');		
  			$shopinfo=D('shop')->where(array('id'=>$id))->select();									
  		  	session('uid',$id);
	     	session('user',$shopinfo[0]['account']);
         	session('shop_name',$shopinfo[0]['shopname']);
           	session('pid',$shopinfo[0]['pid']);	
			if($shopinfo[0]['jifei']==1){
					session('jifei',1);
			}else{
				session('jifei',0);
			}		
									
  			$this->success('正在跳转中',U('index/login/index'));		
			
									
  }

		public function account(){
		$nav['m']=$this->getActionName();
		$nav['a']='account';
		$this->assign('nav',$nav);
		$db=D('Agent');
		$sql="select a.id,a.name,a.fee,a.money,a.wifiphone,a.linker,a.phone,a.level,a.province,a.city,a.area, b.openpay from ".C('DB_PREFIX')."agent a left join ".C('DB_PREFIX')."agentlevel b on a.level=b.id ";
		$sql.=" where a.id=".$this->aid;
		$info=$db->query($sql);
		$this->assign('info',$info[0]);
		
		
		$this->display();
	}
	
	
	public function saveaccount(){
		$nav['m']=$this->getActionName();
		$nav['a']='account';
		$this->assign('nav',$nav);
		
		$db=D('Agent');
		$where['id']=$this->aid;
		
		C('TOKEN_ON',false);
		if(!empty($_POST['password'])){
			$_POST['password']=md5($_POST['password']);
		}
else{
	unset($_POST['password']);
	
	
}
		if($db->create($_POST,2)){
			if($db->where($where)->save()){
				$data['error']=0;
	    		$data['msg']="更新成功";
	    		$this->success('保存成功','index',3);
			}else{
				$data['error']=1;
	    		$data['msg']=$db->getError();
	    		$this->error($db->getError());
			}
		}else{
				$data['error']=1;
	    		$data['msg']=$db->getError();
	    		$this->error($db->getError());
		}
		
	}
 public function adsedit()
    {
        import('@.ORG.UserPage');
        
       
        $where['uid']=$_GET['id'];
        $ad = D('Ad');
        $count = $ad->where($where)->count();
		$page = new UserPage($count,C('ad_page'));
        $result = $ad->where($where)->field('id,ad_pos,ad_thumb,ad_sort,mode')->order('ad_sort desc,id asc')->select();
       
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
        $this->display();        
    }
	public function editad()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
     
        $where['id']=$id;
       
        if($id)
        {
            $result = D('Ad')->where($where)->find();
            $this->assign('info',$result);
			print_r($result);
            $this->display();
        }else{
            $this->error('无此广告信息');
        }

    }
	
	public function shopedit(){
			$db=D('Agent');
			$info2=$db->where(array('id'=>$this->aid))->field('id,dltourl')->find();
			//exit(print($info2['dltourl']));
			$this->assign('dltourlok',$info2['dltourl']);

		$nav['m']=$this->getActionName();
		$nav['a']='shop';
		$this->assign('nav',$nav);
		$id=I('get.id','0','int');
		$where['pid']=$this->aid;
		$where['id']=$id;
		$db=D('Shop');
		$info=$db->where($where)->find();
		if(!$info){
			$this->error("参数不正确");
		}
		$this->assign("shop",$info);
	

		$nav['m']=$this->getActionName();
		$nav['a']='shoplist';
		$this->assign('nav',$nav);
		
		include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('enumdata',$enumdata);
        $this->display();
    }

    /**
     * 添加商户
     */
	public function shopadd(){
				$db=D('Agent');
			$info2=$db->where(array('id'=>$this->aid))->field('id,dltourl')->find();
			//exit(print($info2['dltourl']));
			$this->assign('dltourlok',$info2['dltourl']);
		$nav['m'] = $this->getActionName();
		$nav['a'] = 'shop';
		$this->assign('nav','shop');
		$this->assign('navbar','add');

		include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('enumdata',$enumdata);


        $this->display();
	}
	//删除商户回款
	public function shopdel(){
		 	$now  = time();
			$id=I('get.id');
			$db=D('routemap');
			$agent=D('agent');
			$info=$db->where(array('shopid'=>$id))->select();
			$account=D('shop')->where(array('id'=>$id))->getField('account');
			if($info!==null){
				$this->error('该商户名下还有未解除绑定的路由器,请到该商户名下删除路由器','',3);
			}
			else{
				if(D('shop')->where(array('id'=>$id))->delete()){
					$paydata['aid']=$this->aid;
                    $paydata['paymoney']=20;
                    $paydata['oldmoney']=$agent->where(array('id'=>$this->aid))->getField('money');
                    $paydata['nowmoney']=$paydata['oldmoney']+20;
                    $paydata['do']=1;
                    $paydata['desc']='删除账户回款';
                    $paydata['add_time']=$now;
                    $paydata['update_time']=$now;
					$paydata['account']=$account;
                    D('Agentpay')->add($paydata);
					$agent->where(array('id'=>$this->aid))->setInc('money',20);
					$this->success("{$account}删除成功,并返还相应款项",'',3);	
				}else{
					
					
					$this->error('服务器繁忙');
					
				}
			
				
			}
		
	}
    /**
     * @return mixed开户 ajax处理
     */
    public function openshop(){
    	
        if(IS_POST){
            $db=D('Agent');
            $sql="select a.id,a.money,a.level,b.openpay from ".C('DB_PREFIX')."agent a left join ".C('DB_PREFIX')."agentlevel b on a.level=b.id ";
            $sql.=" where a.id=".$this->aid;
            $where['id']=$this->aid;
            $info=$db->query($sql);
            if(!$info){
                $data['error']=1;
                $data['msg']="服务器忙，请稍候再试";
               $this->error('服务器忙，请稍候再试');
            }
            $money = $info[0]['money']==null?0:$info[0]['money'];
            $pay   = $info[0]['openpay']==null?0:$info[0]['openpay'];
            if($money<$pay){
                $data['error']=1;
                $data['msg']="当前帐号余额不足，无法添加商户";
               $this->error('当前帐号余额不足，无法添加商户');
            }
            $user = D('Shop');
            $now  = time();
            $_POST['pid']   = $this->aid;
            $_POST['authmode'] = '#1#';
            $_POST['maxcount'] = C('OpenMaxCount');
			$_POST['authaction'] = 1;
			$_POST['mode'] = 1;
			if($_POST['jumpurl']==''){
				unset($_POST['jumpurl']);
				$_POST['jumpurl']='http://www.baidu.com';
			}
			$_POST['vipflag'] = 0;
            if($user->create($_POST,1)){
                $aid=$user->add();
                if($aid>0){
                    $rs['shopid'] = $aid;
                    $rs['sortid'] = 0;
                    $rs['routename'] = $_POST['shopname'];
                    $rs['gw_id']     = $_POST['account'];

//                  M("Routemap")->data($rs)->add();
           
                    //扣款
                    $db->where($where)->setDec('money',$pay);
                    //添加消费记录
                    $paydata['aid']=$this->aid;
                    $paydata['paymoney']=$pay;
                    $paydata['oldmoney']=$money;
                    $paydata['nowmoney']=$money-$pay;
                    $paydata['do']=0;
//					if($_POST['vip']==1){
//                  $paydata['desc']='VIP商户开户扣款';
//					}else{
//					$paydata['desc']='普通商户开户扣款';
//					}
					$paydata['desc']='普通商户开户扣款';
                    $paydata['add_time']=$now;
                    $paydata['update_time']=$now;
					$paydata['account']=$_POST['account'];
                    D('Agentpay')->add($paydata);

                    $data['error'] = 0;
                    $data['url']   = U('shoplist');
                    $this->success('开户成功','shoplist',3);
                }else{
                    $data['error']=1;
                    $msg=$user->getError();
                    $this->error("$msg");
                }
            }else{
                $data['error'] = 1;
               $msg = $user->getError();
               $this->error("$msg");
            }
        }else{
            $data['error']=1;
            $data['msg']="服务器忙，请稍候再试";
            $this->error("服务器忙，请稍候再试");
        }
    }
	
	public function pwd(){
		$this->display();
	}
	public function shtongji(){
		$db=M();
		$cxdays=D('cxdays');
		$timestamp=time()-1000;
		$sqlshop="select shopid from wifi_routemap where shopid in (select id from wifi_shop where pid=$this->aid) and update_yuancheng>$timestamp group by shopid";
		$sonnum=$db->query($sqlshop);
		foreach($sonnum as $k=>$v){
			$id[]=$v['shopid'];
		}
	
		$a=count($sonnum);
		
		$shop=D('shop');
		$sum=$shop->where(array('pid'=>$this->aid))->count();
		$b=$sum-$a;
		$lock['id']=array('in',$id);
		$shopid=$shop->where($lock)->field('id')->select();
		
//		foreach($shopid as $k=>$v){
//			 $map['date']=date("Y-m-d");
//		 	 $map['shopid']=$v['id'];
//		 	 $shoprz=$cxdays->where($map)->field('date,00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23')->find();
//		 	if($shoprz){
//		 		array_shift($shoprz);
//				
//		 		$arr[$v['id']]=array_sum($shoprz);
//		 	}
//		 	
//		 	
//		 	 }
//	arsort($arr);
	foreach($id as $k=>$v){
		$shopinfo=$shop->where(array('id'=>$v))->field('shopname,linker,phone,add_time,id,account')->find();
		
		$info[]=$shopinfo;
	}
		import('@.ORG.AdminPage');
		
		$where['pid']=$this->aid;
        $where['id']=array('not in',$id);
		$count=$b;
		$page=new AdminPage($count,C('ag_page'));
        $result = $shop->where($where)->field('id,shopname,add_time,linker,phone,account,linkflag')->order('add_time desc')->limit($page->firstRow.','.$page->listRows)-> select();
        $this->assign('page',$page->show());
        $this->assign('listsoff',$result);
	
	
	
	
	$this->assign('b',$b);
	$this->assign('a',$a);
	$this->assign('lists',$info);
		$this->display();
	}
public function sbtongji(){
	$db=M();
		$shop=D('shop');
		$timestamp=time()-1000;
		$sqlshop="select shopid,routename,id,product_sw_ver,gw_id,ontime,shengji,reboot,manage from wifi_routemap where shopid in (select id from wifi_shop where pid=$this->aid) and update_yuancheng>$timestamp";
		$sonnum=$db->query($sqlshop);
		foreach($sonnum as $k=>$v){
			$shopname=$shop->where(array('id'=>$v['shopid']))->getField('shopname');
			$sonnum[$k]['shopname']=$shopname;
			$idon[]=$v['id'];
		
		}
		$a=count($sonnum);
		$shopid=$shop->where(array('pid'=>$this->aid))->field('id')->select();
		foreach($shopid as $k=>$v){
			$idall[]=$v['id'];
		}
		
		$routemap=D('routemap');
		import('@.ORG.AdminPage');
	    $where['id']=array('not in',$idon);
		$where['shopid']=array('in',$idall);
		$count=$routemap->where($where)->count();
		
		$page=new AdminPage($count,C('ag_page'));
        $result = $routemap->where($where)->field('shopid,routename,id,product_sw_ver,gw_id')->order('id desc')->limit($page->firstRow.','.$page->listRows)-> select();
		foreach($result as $k=>$v){
			$shopname=$shop->where(array('id'=>$v['shopid']))->getField('shopname');
			$result[$k]['shopname']=$shopname;
		}
        $this->assign('page',$page->show());
        $this->assign('a',$a);
        $this->assign('b',$count);
        $this->assign('listsoff',$result);
       
		$this->assign('lists',$sonnum);
		$this->display();
}


//升级为vip商户操作方法
public function vipup(){
	$id=I('get.id');
	$shop=D('shop');
	$data['vip']=1;
	$data['vipflag']=0;
	$result=$shop->where(array('id'=>$id))->save($data);
	if($result){
		$this->success("后台正在审核中！");
	}else{
		$this->success("参数不对！");
	}
	
	
	
}




	public function dopwd(){
		if(IS_POST){
			$pwd=I('post.password');
			if($pwd==""){
				$data['error']=1;
		    	$data['msg']="新密码不能为空";
		    	return $this->ajaxReturn($data);
			}
			if(!validate_pwd($pwd)){
				$data['error']=1;
		    		$data['msg']="密码由4-20个字符 ，数字，字母或下划线组成";
		    		return $this->ajaxReturn($data);
			}
			$db=D('Agent');
			$info=$db->where(array('id'=>$this->aid))->field('id,account,password')->find();
			if(md5($_POST['oldpwd'])!=$info['password']){
					$data['error']=1;
		    		$data['msg']="旧密码不正确";
		    		return $this->ajaxReturn($data);
			}
		}
		
		$_POST['update_time']=time();
		$_POST['password']=md5($_POST['password']);
		$where['id']=$this->aid;
			if($db->where($where)->save($_POST)){
				$data['error']=0;
	    		$data['msg']="更新成功";
	    		return $this->ajaxReturn($data);
			}else{
				$data['error']=1;
	    		$data['msg']=$db->getError();
	    		return $this->ajaxReturn($data);
			}
		
	}
	
		public function saveshop(){
		if(IS_POST){
			$user = D('Shop');
			$id=I('post.id');
			
	        $where['id']=$id;
	        $where['pid']=$this->aid;
	        $info=$user->where($where)->find();
	        if(!$info){
	        	//无此用户信息
	        	$this->error("没有此商户信息");
	        }
	        /*
	        $lv="";
	        foreach($_POST['shoplevel'] as $K=>$v )
	        {
	        	$lv.="#".$v."#";
	        }
	        $_POST['shoplevel']=$lv;
	        $trade="";
	        foreach($_POST['trade'] as $K=>$v )
	        {
	        	$trade.="#".$v."#";
	        }
	        $_POST['trade']=$trade;
	        */
	        $_POST['linkflag']=1;//不限制
	        if($user->create($_POST,2)){
	        	if($user->where($where)->save()){
	        		$data['error']=0;
		    		$data['url']=U('shoplist');
		    		$this->success("保存成功",U("Index/shoplist"));
	        	}else{
	        		$data['error']=1;
		    		$data['msg']=$user->getError();
		    		$this->error($user->getError());
	        	}
	        }else{
	        		$data['error']=1;
		    		$this->error($user->getError());
		    		
	        }
		}else{
			$data['error']=1;
    		$data['msg']="服务器忙，请稍候再试";
    		return $this->ajaxReturn($data);
		}
	}
	
	public function report(){
		$nav['m']=$this->getActionName();
		$nav['a']='report';
		$this->assign('nav',$nav);
		
		import('@.ORG.AdminPage');
		$db=D('Agentpay');
		$where['aid']=$this->aid;
		$count=$db->where($where)->count();
		$page=new AdminPage($count,C('ag_page'));
        $result = $db->where($where)->limit($page->firstRow.','.$page->listRows)->order(" add_time desc")-> select();
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
     
		$this->display();
	}
	   public function sbreport(){
		$nav['m']=$this->getActionName();
		$nav['a']='sbreport';
		$this->assign('nav',$nav);
		
		import('@.ORG.AdminPage');
		$db=D('Routeopt');
		$where['aid']=$this->aid;
		$count=$db->where($where)->count();
		$page=new AdminPage($count,C('ag_page'));
        $result = $db->where($where)->limit($page->firstRow.','.$page->listRows)->order("opttime desc")->select();
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
     
		$this->display();
	}
	  public function pushrpt(){
	  	if(IS_POST){
	    $stime=strtotime($_POST['sdate']);
		$etime=strtotime($_POST['edate']);
//		$etime=$etime1+(60*60*24);
        $nav['m']=$this->getActionName();
		$nav['a']='pushrpt';
		$this->assign('nav',$nav);
		import('@.ORG.AdminPage');
		$db=D('Pushadvlink');
		$where['aid']=$this->aid;
		$count=$db->where($where)->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
        $result=$db->where($where)->limit($page->firstRow.','.$page->listRows)->order('add_time desc') -> select();
		$results=array();
         foreach($result as $k=>$v){
			$adcount=M();
			$sql="select count(*) from wifi_adcountlink where aid={$v['id']} and add_time between $stime and $etime";
			$today=$adcount->query($sql);
////			
			$v['today']=$today[0]['count(*)'];
            $results[]=$v;
		
//			
		   }
         $this->assign('stime',$stime);
		 $this->assign('etime',$etime);
         $this->assign('lists',$results);
		 $this->assign('page',$page->show());

        include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('trade',$enumdata['trades']);
//		
		$this->display();
   }
	  	
	  	else{
	  	$date=getNowDate();
		$nav['m']=$this->getActionName();
		$nav['a']='pushrpt';
		$this->assign('nav',$nav);
		import('@.ORG.AdminPage');
		$db=D('Pushadvlink');
		$where['aid']=$this->aid;
		$count=$db->where($where)->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
       
		$result=$db->where($where)->limit($page->firstRow.','.$page->listRows)->order('add_time desc') -> select();
		
//      $this->assign('lists',$result);
             $results=array();
         foreach($result as $k=>$v){
			$adcount=D('adcountlink');
			$today=$adcount->where(array('aid'=>$v['id'],'add_date'=>"$date"))->count();
			$v['today']=$today;
            $results[]=$v;
	}
	
		 $this->assign('lists',$results);
		 $this->assign('page',$page->show());

        include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('trade',$enumdata['trades']);
//		
		$this->display();
    }
	}
private  function getadrpt(){
    	
    	$way=I('get.mode');
		$id=$this->aid;
    	
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcountlink";

				$sql.=" where  aid=$id and add_date='".date("Y-m-d")."' and mode=50 and agent='".$this->aid."'";
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		case "yestoday":
    			
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcountlink";

				$sql.=" where aid=$id and add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and mode=50 and agent='".$this->aid."'";
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		case "week":
    			$sql="  select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit ,COALESCE(hit/showup*100,0) as rt from ";
    			$sql.=" ( select CURDATE() as td ";
    			for($i=1;$i<7;$i++){
    				$sql.="  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
    			}
    			$sql.=" ORDER BY td ) a left join ";
    			$sql.="( select add_date,sum(showup) as showup ,sum(hit) as hit from ".C('DB_PREFIX')."adcountlink";
				$sql.=" where  aid=$id and add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE() and mode=50 and agent='".$this->aid."' GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,sum(showup) as showup ,sum(hit) as hit  from ".C('DB_PREFIX')."adcountlink  ";
				$sql.=" where  aid=$id and add_date >= '".date("Y-m-01")."' and mode=50 and agent='".$this->aid."' GROUP BY  add_date";
				$sql.=" ) b on a.tname=b.td ";
				$sql.=" where a.id between 1 and  $t";
		
    			break;
    		case "query":
    			$sdate=I('get.sdate');
    			$edate=I('get.edate');
    			import("ORG.Util.Date");
    			//$sdt=Date("Y-M-d",$sdate);
    			//$edt=Date("Y-M-d",$edate);
    			$dt=new Date($sdate);
    			$leftday=$dt->dateDiff($edate,'d');
    			$sql=" select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ";
    			$sql.=" ( select '$sdate' as td ";
    			for($i=0;$i<=$leftday;$i++){
    				$sql.="  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
    			}
    			$sql.=" ) a left join ";
    			
    
				$sql.="( select add_date,sum(showup) as showup ,sum(hit) as hit  from ".C('DB_PREFIX')."adcountlink ";
				$sql.=" where aid=$id and add_date between '$sdate' and '$edate'  and mode=50 and agent=".$this->aid." GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
    			break;
    	}
    
    	$db=D('Adcountlink');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }
	 public function online(){
		$nav['m']=$this->getActionName();
		$nav['a']='online';
		$this->assign('nav',$nav);
		$time=time();
		
		import('@.ORG.AdminPage');
		$db=M();
		$sql="select month1.gw_id,month1.routename,month1.shopname,now1.now,today1.today,seven1.seven,month1.month from((((select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,b.id,count(mac) as now from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$this->aid}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60) group by b.id) as now1 right join (select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,b.id,count(mac) as today from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$this->aid}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24) group by b.id) as today1 on now1.gw_id=today1.gw_id)right join (select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,b.id,count(mac) as seven from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$this->aid}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24*7) group by b.id) as seven1 on seven1.gw_id = today1.gw_id ) right join(select b.gw_id as gw_id,a.shopname as shopname,b.routename as routename,b.id ,count(mac) as month from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$this->aid}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24*7*30) group by b.id) as month1 on seven1.gw_id = month1.gw_id  ) group by month1.gw_id";
		$count=$db->query($sql);
        $a=count($count);
		$page=new AdminPage($a,C('ag_page'));
		$sqlex=$sql="select month1.gw_id,month1.routename,month1.shopname,now1.now,today1.today,seven1.seven,month1.month from((((select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,b.id,count(mac) as now from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$this->aid}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60) group by b.id) as now1 right join (select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,b.id,count(mac) as today from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$this->aid}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24) group by b.id) as today1 on now1.gw_id=today1.gw_id)right join (select b.gw_id as gw_id,b.routename as routename,a.shopname as shopname,b.id,count(mac) as seven from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$this->aid}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24*7) group by b.id) as seven1 on seven1.gw_id = today1.gw_id ) right join(select b.gw_id as gw_id,a.shopname as shopname,b.routename as routename,b.id ,count(mac) as month from (wifi_shop as a join wifi_routemap as b on b.shopid=a.id and a.pid='{$this->aid}') join wifi_authlist as c on c.routeid=b.id where mac is not null and c.update_time > ($time-60*60*24*7*30) group by b.id) as month1 on seven1.gw_id = month1.gw_id  ) group by month1.gw_id %LIMIT%";
        $db->limit($page->firstRow.','.$page->listRows);
        $result=$db->query($sql,true);
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
     
		$this->display();
	}
	 public function routem(){
		$nav['m']=$this->getActionName();
		$nav['a']='route';
		$this->assign('nav',$nav);
		$time=time()-300;
		import('@.ORG.AdminPage');
		$db=M();
		$sql="select a.shopname,a.account,c.routename,c.gw_id,c.product_sw_ver,c.update_yuancheng,c.id from wifi_shop a,wifi_routemap c where c.shopid=a.id and a.pid=$this->aid order by a.id";
		$count=$db->query($sql);
        $a=count($count);
		$page=new AdminPage($a,C('ag_page'));
		$sql="select a.shopname,a.account,c.routename,c.gw_id,c.product_sw_ver,c.update_yuancheng,c.id from wifi_shop a,wifi_routemap c where c.shopid=a.id and a.pid=$this->aid order by a.id %LIMIT%";
        $db->limit($page->firstRow.','.$page->listRows);
        $result=$db->query($sql,true);
        $this->assign('time',$time);
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
     
		$this->display();
	}

    public function routelist(){
			
			$id=I('get.id','0','int');
			
			if(empty($id)){
				$this->error("参数不正确");
			}
			import('@.ORG.AdminPage');
			$db=D('Routemap');
			$where['shopid']=$id;
			$count=$db->where($where)->count();
			$page=new AdminPage($count,100);
		
			$sql=" select a.* ,b.shopname from ". C('DB_PREFIX')."routemap a left join ". C('DB_PREFIX')."shop b on a.shopid=b.id left join ".C('DB_PREFIX')."agent c on b.pid=c.id where a.shopid=".$id." and b.pid=".$this->aid." order by a.id desc limit ".$page->firstRow.','.$page->listRows." ";
			
			
	        
			$result = $db->query($sql);

	         $this->assign('time',time()-300);
	        $this->assign('page',$page->show());
	        $this->assign('lists',$result);
			$this->display();        

	}
public function caozuo(){
	
	$db= D('Routemap');
	$id=I('get.id');
	$caozuo=I('get.caozuo');
	$where['id']=$id;

         	$result =$db
                    ->where($where)
                    ->field('id')
                    ->find();
             if($result==false){
	         	$this->error('没有此路由信息');
	         	exit;
	         }
	if($caozuo=='shengji'){
		$data['shengji']=1;
	}else{
		$data['reboot']=1;
	}   
	
  if($db->where($where)->save($data)){
	    $this->success('操作成功');
	  }else{
	      $this->error("正在重启或升级中");
	  }		
        	
	
	
	
}
	
	public function editroute(){
		if(IS_POST){
			$db= D('Routemap');
        	
        	$id = I('post.id','0','int');
	        $where['id']=$id;

         	$result =$db
                    ->where($where)
                    ->field('id')
                    ->find();
                   
                    
	         if($result==false){
	         	$this->error('没有此路由信息');
	         	exit;
	         }
	        
        	if($db->create()){
        			if($db->where($where)->save()){
	        		   $this->success('更新成功',U('shoplist'));
	        		}else{
	        			
	        			$this->error("操作失败");
	        		}
        	}else{
        		$this->error($db->getError());
        	}
		}else{
			$id=I('get.id','0','int');
			$shopid=I('get.shopid','0','int');
			$where['id']=$id;
			$where['shopid']=$shopid;
			$db=D('Routemap');
			$info=$db->where($where)->find();
			if(!$info){
				$this->error("参数不正确");
			}
			$this->assign("info",$info);
			
	

	        
	     
	        
	        
	        $this->display();    
		}
	}
 public function rebot()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $uid = isset($_GET['shopid']) ? intval($_GET['shopid']) : 0;
        $where['id']=$id;
        $where['shopid']=$uid;
        
        $r = D('Routemap')->where($where)->find();
        
        if($r==false){
        	$this->error('没有此路由信息');
        }else{
        	
          if(D('Routemap')->where($where)->setField('reboot','1')){
          	$this->success('操作保存成功,设备将在2分钟内重启！','',3);
          }else{
          	$this->error('操作失败');
          }
        	
        }
    }
	public function shengji(){
	
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	        $uid = isset($_GET['shopid']) ? intval($_GET['shopid']) : 0;
	        $uid = session('uid');
	        $where['id']=$id;
	        $where['shopid']=$uid;
			$r= D('Routemap')->where($where)->setField('shengji',1);
			if($r){
				$this->success('路由器将在5分钟内升级完成！');
			}else{
				$this->error('未知错误');
			}
	}
	  public function routemanage(){
	  	     $route=D('Routemap');
		if(IS_POST){
			
    		if($_POST['sub']=='保存'){
    			$_POST['manage']=1;
				unset($_POST['sub']);
			}
		$wheres['id']=$_POST['routeid'];
		if(isset($_POST['authEnable'])){
			$_POST['authEnable']=1;
		}else{
			$_POST['authEnable']=0;
		}
		if(isset($_POST['TimeRebootEnable'])){
			$_POST['TimeRebootEnable']=1;
		}else{
			$_POST['TimeRebootEnable']=0;
		}
		if(isset($_POST['vap_enable'])){
			$_POST['vap_enable']=1;
		}else{
			$_POST['vap_enable']=0;
		}
		if(isset($_POST['vap_hide_ssid'])){
			$_POST['vap_hide_ssid']=1;
		}else{
			$_POST['vap_hide_ssid']=0;
		}
		$a=$_POST['wlan_txpower'];
		if($a>0&&$a<10){
			$_POST['wlan_txpower']=5;
		}else{
			if($a>11&&$a<20){
			$_POST['wlan_txpower']=15;
		}else{
			if($a>21&&$a<40){
			$_POST['wlan_txpower']=25;
		}else{
			if($a>41&&$a<80){
			$_POST['wlan_txpower']=60;
		}else{
			$_POST['wlan_txpower']=100;
		
		}
		}
		}
		}

		
		$data=$_POST;
	
		$flag=$route->where($wheres)->save($data);
		
		if($flag){
			$this->success('操作保存成功,参数将在2分钟之内设置完成！','routemap',3);
	
			}	
		 else{
          	$this->error('操作失败');
          }
		}
		else{
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
       		$uid = isset($_GET['shopid']) ? intval($_GET['shopid']) : 0;
        	$where['id']=$id;
        	$where['shopid']=$uid; 
		$info=$route->where($where)->select();
		
		$info1=$route->where($where)->find();
		
	   if($info==false){
        $this->error('没有此路由信息');}
		else{
		$isAC=preg_match('/SXTGAC/i', $info[0]['gw_id']);
			
	  	 $this->assign('info',$info[0]);
		 $this->assign('ac',$isAC);
		$this->display();
			}
					}
		
		
		
	}

	public function delroute(){
	 	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
         $shopid=I('get.shopid','0','int');
       
        $where['id']=$id;
        $where['shopid']=$shopid;
        
        $r = D('Routemap')->where($where)->find();
        
        if($r==null){	
        	$this->error('没有此路由信息');
        }else{
        	$gw_id=strtolower($r['gw_id']);
        	$routefee=D('Routeopt');
			$fee=$routefee->where(array('aid'=>$this->aid,'gw_id'=>$gw_id))->getField('fee');
			$agent=D('Agent');
			$agentname=$agent->where(array('id'=>$this->aid))->getField('name');
			$fee1=$agent->where(array('id'=>$this->aid))->getField('fee');
			$data['fee']=$fee+$fee1;
			$agent->where(array('id'=>$this->aid))->data($data)->save();
			preg_match('/((SXTG|SXTGAC|SXTCPE)\d{3})\-\([0-9a-zA-Z]{12}\)/i', $gw_id,$arr);
			$routenum=D('Routenum');
			$routenum->where(array('aid'=>$this->aid,'gw_id'=>$arr[1]))->setInc("$arr[1]");
		    $route_num=$routenum->where(array('aid'=>$this->aid,'gw_id'=>$arr[1]))->getField("$arr[1]");
			        $num['gw_id']=$r['gw_id'];
					$num['opt']=$agentname;
					$num['opttime']=time();
					$num['fee']=$fee;
					$num['state']='删除设备返还相应设备积分';
					$num['feesy']=$data['fee'];
					$num['aid']=$this->aid;
					$routefee->data($num)->add();
			
				
          if(D('Routemap')->where($where)->delete()){
          	$this->success("删除成功<br>返还该型号设备{$fee}积分,剩余{$data['fee']}积分<br>该型号剩余绑定设备数量{$route_num}.",'',5);
          }else{
          	$this->error('删除失败');
          }
        	
        }
	}

public function addroute(){
		if(IS_POST){
			  $db=D('Routemap');
			  $agent=D('Agent');
			  $routenum=D('Routenum');
			  $fee=$agent->where(array('id'=>$this->aid))->getField('fee');
			  $gw_id=strtolower($_POST['gw_id']);
		      $pattern='/(sxtg|sxtgac|sxtcpe)\d{3}/';
			  if(preg_match($pattern,$gw_id,$arr)){
			        $a=$arr[0];
				    $route_num=$routenum->where(array('aid'=>$this->aid))->getField("$a");
					if(isset($route_num)){
					if($route_num==0){
						$this->error("添加失败，该型号设备绑定余额不足");
					 }
					}
				    $routefee=D('Route');	
					$routefee1=$routefee->where(array('aid'=>$this->aid))->getField("$a");
			   if($routefee1==''){
				$routefee1=intval(C('Defaultfee'));
					  }
			    }else{
			  	$routefee1=intval(C('Defaultfee'));
			    }
			  if($fee<$routefee1){
			  	$this->error("添加失败，剩余积分{$fee}所需积分不足请及时充值");
			  	}else{
			
			 if($db->create()){
				if($db->add()){
					$agent=D('Agent');
			     $fee=$agent->where(array('id'=>$this->aid))->getField('fee');
				 $agentname=$agent->where(array('id'=>$this->aid))->getField('name');
	             $gw_id=strtolower($_POST['gw_id']);
		         $pattern='/(sxtg|sxtgac|sxtcpe)\d{3}/';
                  if(preg_match($pattern,$gw_id,$arr)){
			        $a=$arr[0];
					$routefee=D('Route');	
					$routefee1=$routefee->where(array('aid'=>$this->aid))->getField("$a");
					if($routefee1==''){
				    $routefee1=intval(C('Defaultfee'));
					$data['fee']=$fee-$routefee1;
					$agent->where(array('id'=>$this->aid))->data($data)->save();
                     }
					else{
					$data['fee']=$fee-$routefee1;
					$agent->where(array('id'=>$this->aid))->data($data)->save();
					}
					}else {
					$routefee1=intval(C('Defaultfee'));
					$data['fee']=$fee-$routefee1;
					$agent->where(array('id'=>$this->aid))->data($data)->save();
				    }
					$routeopt=D('Routeopt');
					$num['gw_id']=$_POST['gw_id'];
					$num['opt']=$agentname;
					$num['opttime']=time();
					$num['fee']=$routefee1;
					$num['state']='添加设备扣除设备积分';
					$num['feesy']=$data['fee'];
					$num['aid']=$this->aid;
					$routeopt->data($num)->add();
					$routenum->where(array('aid'=>$this->aid))->setDec("$a");
					$route_num=$routenum->where(array('aid'=>$this->aid))->getField("$a");
					$this->success("添加成功<br>扣除绑定该设备积分{$routefee1},<br>剩余{$data['fee']}积分<br>该型号剩余绑定设备数量{$route_num}.",'',5);
				}else{
					$this->error("操作失败");
				}
			}else{
				$this->error($db->getError());
			
			}
			}
		}else{
			$id=I('get.id','0','int');
			
			if(empty($id)){
				$this->error("参数不正确");
			}
			$where['id']=$id;
			$db=D('Shop');
			$info=$db->where($where)->field('id,shopname')->find();
			if(!$info){
				$this->error("参数不正确");
			}
			$this->assign("shop",$info);
			$this->display();        
		}
	}
}