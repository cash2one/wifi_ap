<?php

/**
 * Class 广告推送
 */

class PushadvAction extends AdminAction{

    protected function _initialize(){
		parent::_initialize();
		$this->doLoadID(800);
	}

    /**
     * 广告推送列表
     */
	public  function index(){
		import('@.ORG.AdminPage');
		$db=D('Pushadv');
		
		$count=$db->count();
		$page=new AdminPage($count,C('ADMINPAGE'));

        $sql="select a.*,b.name as agentname from ".C('DB_PREFIX').'pushadv a left join '.C('DB_PREFIX').'agent b on a.aid=b.id order by add_time desc limit '.$page->firstRow.','.$page->listRows;
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
			import('ORG.Net.UploadFile');
	        $upload             = new UploadFile();
	        $upload->maxSize    = C('AD_SIZE') ;
	        $upload->allowExts  = C('AD_IMGEXT');
	        $upload->savePath   =  C('AD_PUSHSAVE');
	        if($_POST['startdate']==""||$_POST['enddate']==""){
	        	$this->error('请选择广告投放时间段');
	        }
	        if(!$upload->upload()) {
	            $this->error($upload->getErrorMsg());
	        }else{
	            $info    =  $upload->getUploadFileInfo();
	            $ad      = D('Pushadv');
	     
	            $_POST['pic'] = trim( $info[0]['savepath'],'.').$info[0]['savename'];
	            $_POST['sort'] = isset($_POST['sort']) ? $_POST['sort'] : 0;
	            $_POST['startdate']=strtotime($_POST['startdate']);
	            $_POST['enddate']=strtotime($_POST['enddate']);
	            if($ad->create()){
		            if($ad->add())
		            {
		                $this->success('添加广告成功',U('pushadv/index'));
		            }else{
		                $this->error('添加失败，请重新添加');
		            }
	            }else{
	            	 $this->error($ad->getError());
	            }
	        }
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
	      
	        $db=D('Pushadv');
	        $result =$db->where($where)->field('id,pic')->find();
	         if($result==false){
	         	$this->error('无此广告信息');
	         	exit;
	         }
	        import('ORG.Net.UploadFile');      
	       
	        $upload             = new UploadFile();
	        $upload->maxSize    = C('AD_SIZE');
	        $upload->allowExts  = C('AD_IMGEXT');
	        $upload->savePath   =  C('AD_PUSHSAVE');
	    
	      	if (!is_null($_FILES['img']['name'])&& $_FILES['img']['name']!="") {
				
	        	if(!$upload->upload()) {
	            	$this->error($upload->getErrorMsg());
		        }else{
		            $info =  $upload->getUploadFileInfo();
		            $_POST['pic'] = trim( $info[0]['savepath'],'.').$info[0]['savename'];
		        }
	    	}else{
	    		$_POST['pic']=$result['pic'];
	    	}
         
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
            $result = D('Pushadv')->where($where)->find();
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
            $thumb = D('Pushadv')->where("id={$id}")->field("id,pic")->select();
            if(D('Pushadv')->delete($id))
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
			if(!isNumber($wt)){
				$this->error("广告展示时间以秒为单位,请输入展示的时间");
			}
			if($wt<1){
				$this->error("最低展示时间不能小于1秒");
			}
			$this->configsave();
		}else{
			$this->display();
		}
	}
	public function messset(){
		 import('@.ORG.AdminPage');
		$order=D('morderlist');
		if(IS_POST){
			
			$a=$_POST['qunfa'];
			$b=$_POST['messnum'];
			$data['uid']=0;
			$data['shopname']='营销总平台';
			$data['orderid']='zptcz'.time();
			$data['notify_time']=date("Y-m-d,H:i:s");
			$data['trade_status']='TRADE_SUCCESS';
			$data['message']="平台充值短信:群发充值".$_POST['qunfa']."!验证码充值".$_POST['messnum'];
			$data['qunfa']=$_POST['qunfa1'];
			$data['messnum']=$_POST['messnum1'];
			$data['qunfaczh']=$_POST['qunfa']+$_POST['qunfa1'];
			$data['messnumczh']=$_POST['messnum']+$_POST['messnum1'];
		if($order->data($data)->add()){
			if($_POST['qunfa']!=0){
			$arr['qunfa']=$_POST['qunfa']+C('qunfa');
			}
			if($_POST['messnum']!=0){
			$arr['yanzhengma']=$_POST['messnum']+C('yanzhengma');
			}
			update_config($arr,CONF_PATH."site.php");
			$this->success("操作成功平台充值短信:群发充值$a!验证码充值$b",U('Pushadv/messset'));
				}else{
			$this->error("操作失败",U('Pushadv/messset'));
			}
		
		}else{
	   
		$count=$order->where(array('trade_status'=>'TRADE_SUCCESS'))->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
		$lists=$order->where(array('trade_status'=>'TRADE_SUCCESS'))->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
	  	$this->assign('page',$page->show());
        $this->assign('lists',$lists);
	
		
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
		if(update_config($_POST,CONF_PATH."adv.php")){
			$this->success('操作成功',U('Pushadv/'.$act));
		}else{
			$this->error('操作失败',U('Pushadv/'.$act));
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
			$this->display();
	}

	private  function getadrpt(){
    	$way=I('get.mode');
    	$where=" where shopid=".session('uid');
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit,  ";
				$sql.="sum(case when mode=99 then showup else 0 end) as pshowup, ";
				$sql.="sum(case when mode=50 then showup else 0 end) as ashowup, ";
				$sql.="sum(case when mode=99 then hit else 0 end) as phit, ";
				$sql.="sum(case when mode=99 then showup else 0 end) as ahit from";
				$sql.="  (select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit,mode from ".C('DB_PREFIX')."adcount";

				$sql.=" where add_date='".date("Y-m-d")."' and (mode=99 or mode=50) ";
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
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcount";

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
    			$sql.="( select add_date,sum(showup) as showup ,sum(hit) as hit from ".C('DB_PREFIX')."adcount";
				$sql.=" where   add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE() and (mode=99 or mode=50) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,sum(showup) as showup ,sum(hit) as hit  from ".C('DB_PREFIX')."adcount  ";
				$sql.=" where   add_date >= '".date("Y-m-01")."' and (mode=99 or mode=50) GROUP BY  add_date";
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
    			
    
				$sql.="( select add_date,sum(showup) as showup ,sum(hit) as hit  from ".C('DB_PREFIX')."adcount ";
				$sql.=" where  add_date between '$sdate' and '$edate'  and (mode=99 or mode=50) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
    			break;
    	}
    	$db=D('Adcount');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }
}