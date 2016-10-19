<?php
class PushadvAction extends BaseagentAction{

	private function doLoadID($id){
		$nav['m']=$this->getActionName();
		$nav['a']='pushadv';
		$this->assign('nav',$nav);
	}

	protected function _initialize(){
		parent::_initialize();
		$this->doLoadID();
	}
	
	public  function index(){
		
		import('@.ORG.AdminPage');
		$db=D('Pushadv');
		$where['aid']=$this->aid;
		$count=$db->where($where)->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
       
		$result=$db->where($where)->limit($page->firstRow.','.$page->listRows)->order('add_time desc') -> select();
		$this->assign('page',$page->show());
        $this->assign('lists',$result);

        include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('trade',$enumdata['trades']);
		$this->display();
	}
    /**
     * 添加推送广告
     */
	public function add(){
		if(IS_POST){
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
	            $info           =  $upload->getUploadFileInfo();
	            $ad             = D('Pushadv');
	           	$_POST['aid'] =$this->aid;
	     
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
            $info['startdate'] = time();
            $info['enddate']   = time() + (30 * 24 * 60 * 60);
            $this->assign("info", $info);
            include CONF_PATH.'enum/enum.php';//$enumdata
            $this->assign('enumdata',$enumdata);
			$this->display();
		}
	}
    /**
     * 编辑广告信息
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
                $_POST['aid'] =$this->aid;
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
            $where['aid']=$this->aid;
	        $result = D('Pushadv')->where($where)->find();
            if($id)
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
     * 删除推送广告信息
     */
	public function  del(){
		$id = isset($_GET['id']) ? intval($_GET[id]) : 0;       
        
        if($id)
        { 
        	$where['id']=$id;
	        	        $where['aid']=$this->aid;
            $thumb = D('Pushadv')->where($where)->field("id,pic")->select();
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
     * 代理商 广告推送设置
     * $this->aid 代理商用户ID
     * 添加开关  通过wifiadmin管理 代理商能否推送广告
     * pushflag 是否启用广告推送
     */
    public function set(){
        if(IS_POST){

            //			$wt=$_POST['showtime'];
            //
            //			if(!isNumber($wt)){
            //				$this->error("广告展示时间以秒为单位,请输入展示的时间");
            //			}
            //			if($wt<3){
            //				$this->error("最低展示时间不能小于3秒");
            //			}

            //			$db=D('Agent');
            //			$where['aid']=$this->aid;
            //			$info=$db->where($where)->field(array('id,state,push'))->find();

            $push = $_POST['push'];
            if($push == "0"){
                $this->success('广告推送功能已关闭');
            }else{
                $this->success('请找运营帮你开通广告推送功能');
            }
            //			if($info){
            //				if($db->create()){
            //                    $db->where($where)->save();
            //                    $this->success("操作成功");
            //				}else{
            //					$this->error('请找运营帮你开通广告推送功能');
            //				}
            //			}else{
            //				if($db->create()){
            //						$id=$db->add();
            //						$this->success("操作成功");
            //				}else{
            //					$this->error($db->getError());
            //				}
            //			}
        }else{
            $db=D('Agent');
            $agent = array('id' => $this->aid);

            $info=$db->where($agent)->field(array('id,state,push'))->find();
            $this->assign('info',$info);
            $this->display();
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
    /**
     * 处理广告推送统计
     */
	private  function getadrpt(){
    	
    	$way=I('get.mode');
    	
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcount";

				$sql.=" where add_date='".date("Y-m-d")."' and mode=50 and agent='".$this->aid."'";
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		case "yestoday":
    			
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcount";

				$sql.=" where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and mode=50 and agent='".$this->aid."'";
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
				$sql.=" where   add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE() and mode=50 and agent='".$this->aid."' GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,sum(showup) as showup ,sum(hit) as hit  from ".C('DB_PREFIX')."adcount  ";
				$sql.=" where   add_date >= '".date("Y-m-01")."' and mode=50 and agent='".$this->aid."' GROUP BY  add_date";
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
				$sql.=" where  add_date between '$sdate' and '$edate'  and mode=50 and agent=".$this->aid." GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
    			break;
    	}
    
    	$db=D('Adcount');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }
}