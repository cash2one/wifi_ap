<?php

/**
 * Class 广告推送
 */

class PushadvlinkAction extends AdminAction{

    protected function _initialize(){
		parent::_initialize();
		$this->doLoadID(800);
	}

    /**
     * 广告推送列表
     */
	public  function index(){
		import('@.ORG.AdminPage');
		$db=D('Pushadvlink');
		
		$count=$db->count();
		$page=new AdminPage($count,C('ADMINPAGE'));

        $sql="select a.*,b.name as agentname from ".C('DB_PREFIX').'pushadvlink a left join '.C('DB_PREFIX').'agent b on a.aid=b.id order by add_time desc limit '.$page->firstRow.','.$page->listRows;
		$result=$db->query($sql);

        include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('trade',$enumdata['trades']);

		$this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
    /**
     * 添加广告
     */
	public function add(){
		if(IS_POST){
//            p($_POST);die;
//			import('ORG.Net.UploadFile');
//	        $upload             = new UploadFile();
//	        $upload->maxSize    = C('AD_SIZE') ;
//	        $upload->allowExts  = C('AD_IMGEXT');
//	        $upload->savePath   =  C('AD_PUSHSAVE');
//	        if($_POST['startdate']==""||$_POST['enddate']==""){
//	        	$this->error('请选择广告投放时间段');
//	        }
//	        if(!$upload->upload()) {
//	            $this->error($upload->getErrorMsg());
//	        }else{
//	            $info    =  $upload->getUploadFileInfo();
	            $ad      = D('Pushadvlink');
	     
//	            $_POST['pic'] = trim( $info[0]['savepath'],'.').$info[0]['savename'];
	            $_POST['sort'] = isset($_POST['sort']) ? $_POST['sort'] : 0;
	            $_POST['startdate']=strtotime($_POST['startdate']);
	            $_POST['enddate']=strtotime($_POST['enddate']);
				$_POST['add_time']=time();
	            if($ad->create()){
		            if($ad->add())
		            {
		                $this->success('添加链接成功',U('pushadvlink/index'));
		            }else{
		                $this->error('添加失败，请重新添加');
		            }
	            }else{
	            	 $this->error($ad->getError());
	            }
	       // }
		}else{
            include CONF_PATH.'enum/enum.php';//$enumdata
            $this->assign('enumdata',$enumdata);
			$this->display();
		}
	}
    /**
     * 编辑广告
     */
	public function edit(){
		if(IS_POST){
		  	if($_POST['startdate']==""||$_POST['enddate']==""){
	        	$this->error('请选择广告投放时间段');
	        }
	        $id = I('post.id','0','int');
	        $where['id']=$id;
	      
	        $db=D('Pushadvlink');
	        $result =$db->where($where)->field('id,pic')->find();
	         if($result==false){
	         	$this->error('无此广告信息');
	         	exit;
	         }
//	        import('ORG.Net.UploadFile');      
//	       
//	        $upload             = new UploadFile();
//	        $upload->maxSize    = C('AD_SIZE');
//	        $upload->allowExts  = C('AD_IMGEXT');
//	        $upload->savePath   =  C('AD_PUSHSAVE');
//	    
//	      	if (!is_null($_FILES['img']['name'])&& $_FILES['img']['name']!="") {
//				
//	        	if(!$upload->upload()) {
//	            	$this->error($upload->getErrorMsg());
//		        }else{
//		            $info =  $upload->getUploadFileInfo();
//		            $_POST['pic'] = trim( $info[0]['savepath'],'.').$info[0]['savename'];
//		        }
//	    	}else{
//	    		$_POST['pic']=$result['pic'];
//	    	}
         
            if($result)
            {
                $_POST['startdate']=strtotime($_POST['startdate']);
	            $_POST['enddate']=strtotime($_POST['enddate']);
	           
                if($db->create()){
                	 if($db->where($where)->save()){
                	     $this->success('修改成功',U('index'));
                	 }else{
                		 $this->error('操作出错');
                	 }
                }else{
                	 $this->error($db->getError());
                }
               
            }
		}else{
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	      
	        $where['id']=$id;
            $result = D('Pushadvlink')->where($where)->find();
	        if($result){
	            $this->assign('info',$result);

                include CONF_PATH.'enum/enum.php';//$enumdata
                $this->assign('trade',$enumdata['trades']);

                $this->display();
	        }else{
	        	$this->error('无此广告信息');
	        }

		}
	}
    /**
     * 删除广告
     */
	public function  del(){
	$id = isset($_GET['id']) ? intval($_GET[id]) : 0;       
        
        if($id)
        {
            $thumb = D('Pushadvlink')->where("id={$id}")->field("id,pic")->select();
            if(D('Pushadvlink')->delete($id))
            {
                if(file_exists( ".{$thumb[0]['pic']}"))
                {
                    unlink(".{$thumb[0]['pic']}");
                }
                
                $this->success('删除成功',U('index'));
            }else{
                $this->error('操作出错');
            }
        }
	}
    /**
     * 广告推送设置 配置
     */
	public function set(){
		if(IS_POST){
			$wt=$_POST['WAITSECONDS'];
			$this->configsave();
		}else{
			$this->display();
		}
	}
	 public function pushlink(){
	  	if(IS_POST){
	    $stime=strtotime($_POST['sdate']);
		$etime=strtotime($_POST['edate']);
        import('@.ORG.AdminPage');
		$db=D('Pushadvlink');
	    $count=$db->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
        $result=$db->limit($page->firstRow.','.$page->listRows)->order('aid desc,add_time desc')->select();
		$results=array();
         foreach($result as $k=>$v){
			$adcount=M();
			$sql="select count(*) from wifi_adcountlink where aid={$v['id']} and add_time between $stime and $etime";
			$today=$adcount->query($sql);
            $v['today']=$today[0]['count(*)'];
           	if($v['aid']==0){
		    $v['agentaccount']='admin';
			$v['agentname']='总平台';			
			}
			else{
		    $agent=D('agent');
			$agentinfo=$agent->field('account,name')->where(array('id'=>$v['aid']))->select();
			$v['agentaccount']=$agentinfo[0]['account'];
			$v['agentname']=$agentinfo[0]['name'];
			}
            $results[]=$v;
		 }
         $this->assign('stime',$stime);
		 $this->assign('etime',$etime);
         $this->assign('lists',$results);
		 $this->assign('page',$page->show());

        include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('trade',$enumdata['trades']);
     	$this->display();
   }
	  	
	  	else{
	  	$date=getNowDate();
		import('@.ORG.AdminPage');
		$db=D('Pushadvlink');
		$count=$db->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
        $result=$db->limit($page->firstRow.','.$page->listRows)->order('aid,add_time desc')->select();
		
         $results=array();
         foreach($result as $k=>$v){
			$adcount=D('adcountlink');
			$today=$adcount->where(array('aid'=>$v['id'],'add_date'=>"$date"))->count();
		    $v['today']=$today;
			if($v['aid']==0){
		    $v['agentaccount']='admin';
			$v['agentname']='总平台';			
			}
			else{
		    $agent=D('agent');
			$agentinfo=$agent->field('account,name')->where(array('id'=>$v['aid']))->select();
			$v['agentaccount']=$agentinfo[0]['account'];
			$v['agentname']=$agentinfo[0]['name'];
			}
            $results[]=$v;

	}
//	
		 $this->assign('lists',$results);
		 $this->assign('page',$page->show());

        include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('trade',$enumdata['trades']);
//		
		$this->display();
    }
	}
	
	
	
	
	
	
    /**
     * 广告推送设置 更新
     */
	private  function configsave(){
		$act=$this->_post('action');
		unset($_POST['files']);
		unset($_POST['action']);
		unset($_POST[C('TOKEN_NAME')]);
		//exit(print(C('OPENPUSHLINK')));
		//exit(echo(update_config($_POST,CONF_PATH."advlink.php")));
		if(update_config($_POST,CONF_PATH."advlink.php")){
			$this->success('操作成功',U('Pushadvlink/'.$act));
		}else{
			$this->success('操作失败',U('Pushadvlink/'.$act));
		}
	}
    /**
     * 广告推送统计
     */
	public function rpt(){
		$way=I('get.mode');
			if(!empty($way)){
				$this->getadrpt();
				exit;
			}
			$id=$_GET['id'];
			$this->assign('id',$id);
			$this->display();
	}

public function app(){
	if(IS_POST){
			$sdate=strtotime(I('post.sdate'));
			$edate=strtotime(I('post.edate'));
			$shopname=I('post.shopname');
			$sql="select uid,count(*) from wifi_app";
			if($edate==$sdate){
				$sql.=" where time>$edate";
			}else{
				$sql.=" where time>$sdate and time<$edate";
			}
			if($shopname!=''){
				$shop=D('shop');
				$arr['shopname']=array('like','%'.$shopname.'%');
				$id=$shop->where($arr)->getField('id');
				$sql.=" and uid=$id";
			}else{
				$sql.=" group by uid";
			}
			$this->assign('sdate',I('post.sdate'));
			$this->assign('edate',I('post.edate'));
		
		$app=M();
		$result=$app->query($sql);
	foreach($result as $k=>$v){
		$shop=D('shop');
	$name=$shop->where(array('id'=>$v['uid']))->getField('shopname');
		$account=$shop->where(array('id'=>$v['uid']))->getField('account');
		$result[$k]['shopname']=$name;
		$result[$k]['account']=$account;
		}
$this->assign('post',1);
	}else{
	$t = time();
	$start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
	$app=M();

	$sql="select uid,count(*) from wifi_app group by uid";
	$result=$app->query($sql);
	foreach($result as $k=>$v){
		$shop=D('shop');
	
		$name=$shop->where(array('id'=>$v['uid']))->getField('shopname');
		$account=$shop->where(array('id'=>$v['uid']))->getField('account');
		$result[$k]['shopname']=$name;
		$result[$k]['account']=$account;
		$sql="select count(*) from wifi_app where time>$start and uid=".$v['uid']."";

		$cishu=$app->query($sql);
		$result[$k]['cishu']=$cishu[0]['count(*)'];
	}
}
		$this->assign('lists',$result);
			$this->display();
	}
public function toady(){
	$t = time();
	$start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
	$uid=I('get.uid');
	$sdate=I('get.sdate');
	$edate=I('get.edate');
	$app=M();
	if($sdate!=''&&$edate!=''){
		$s=strtotime($sdate);
		$d=strtotime($edate);
		
		$sql="select * from wifi_app where time>$s and uid=$uid and time<$d";
	}else{
		$sql="select * from wifi_app where time>$start and uid=$uid";
	}

	
	$result=$app->query($sql);
	
	
		$this->assign('lists',$result);
			$this->display();
	}
public function his(){
	
	$uid=I('get.uid');
	$app=M();
	$sql="select * from wifi_app where  uid=$uid";
	$result=$app->query($sql);
	$this->assign('lists',$result);
			$this->display();
	}
	private  function getadrpt(){
    	$way=I('get.mode');
		$id=I('get.id');
    	$where=" where shopid=".session('uid');
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit,  ";
				$sql.="sum(case when mode=99 then showup else 0 end) as pshowup, ";
				$sql.="sum(case when mode=50 then showup else 0 end) as ashowup, ";
				$sql.="sum(case when mode=99 then hit else 0 end) as phit, ";
				$sql.="sum(case when mode=99 then showup else 0 end) as ahit from";
				$sql.="  (select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit,mode from ".C('DB_PREFIX')."adcountlink";

				$sql.=" where add_date='".date("Y-m-d")."' and (mode=99 or mode=50) and aid=$id";
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		case "yestoday":
    			
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit,  ";
				$sql.="sum(case when mode=99 then showup else 0 end) as pshowup, ";
				$sql.="sum(case when mode=50 then showup else 0 end) as ashowup, ";
				$sql.="sum(case when mode=99 then hit else 0 end) as phit, ";
				$sql.="sum(case when mode=99 then showup else 0 end) as ahit from";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcountlink";

				$sql.=" where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and (mode=99 or mode=50) ";
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
				$sql.=" where  aid=$id and add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE() and (mode=99 or mode=50) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,sum(showup) as showup ,sum(hit) as hit  from ".C('DB_PREFIX')."adcountlink  ";
				$sql.=" where  aid=$id and add_date >= '".date("Y-m-01")."' and (mode=99 or mode=50) GROUP BY  add_date";
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
				$sql.=" where aid=$id and add_date between '$sdate' and '$edate'  and (mode=99 or mode=50) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
    			break;
    	}
		//exit(echo $sql);
    	$db=D('Adcountlink');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }
}