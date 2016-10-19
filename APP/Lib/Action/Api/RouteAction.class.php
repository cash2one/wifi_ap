<?php
/**
 * Class 路由管理
 */
class RouteAction extends AdminAction{

	protected function _initialize(){
		parent::_initialize();
		$this->doLoadID(900);
	}

	public function index(){
		import('@.ORG.AdminPage');
		$db=D('Routemap');
		if(IS_POST){
			if(isset($_POST['sname'])&&$_POST['sname']!=""){
					$map['sname']=$_POST['sname'];
					$where.=" and b.shopname like '%%%s%%'";	
			}
			if(isset($_POST['slogin'])&&$_POST['slogin']!=""){
					$map['slogin']=$_POST['slogin'];
					$where.=" and b.account like '%%%s%%'";
			}
			if(isset($_POST['mac'])&&$_POST['mac']!=""){
					$map['mac']=$_POST['mac'];
					$where.=" and a.gw_id like '%%%s%%'";
			}
			if(isset($_POST['agent'])&&$_POST['agent']!=""){
					$map['agent']=$_POST['agent'];
					$where.=" and c.name like '%%%s%%'";
			}
			$_GET['p']=0;
		}else{
			if(isset($_GET['sname'])&&$_GET['sname']!=""){
					$map['sname']=$_GET['sname'];
					$where.=" and b.shopname like '%%%s%%'";
					
			}
			if(isset($_GET['slogin'])&&$_GET['slogin']!=""){
					$map['slogin']=$_GET['slogin'];
					$where.=" and b.account like '%%%s%%'";
			}
			if(isset($_GET['mac'])&&$$_GET['mac']!=""){
					$map['phone']=$_GET['mac'];
					$where.=" and a.gw_id like '%%%s%%'";
			}
			if(isset($_GET['agent'])&&$_GET['agent']!=""){
					$map['agent']=$_GET['agent'];
					$where.=" and c.name like '%%%s%%'";
			}
		}
		
		$sqlcount=" select count(*) as ct from ". C('DB_PREFIX')."routemap a left join ". C('DB_PREFIX')."shop b on a.shopid=b.id  left join ". C('DB_PREFIX')."agent c on b.pid=c.id ";
		if(!empty($where)){
			$sqlcount.=" where true ".$where;
		}
		$rs=$db->query($sqlcount,$map);
		
		$count=$rs[0]['ct'];

		$page=new AdminPage($count,C('ADMINPAGE'));
		foreach($map as $k=>$v){
			$page->parameter.=" $k=".urlencode($v)."&";//赋值给Page";
		}
		$sql=" select a.* ,b.shopname,b.account from ". C('DB_PREFIX')."routemap a left join ". C('DB_PREFIX')."shop b on a.shopid=b.id  left join ". C('DB_PREFIX')."agent c on b.pid=c.id ";
		if(!empty($where)){
			$sql.=" where true ".$where;
		}
		$sql.=" order by a.id desc limit ".$page->firstRow.','.$page->listRows." ";
		
		
		
        
		$result = $db->query($sql,$map);
		$this->assign('time',time()-300);
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();

	}
	
	public function gujian(){
		if(IS_POST){
		$targetFolder = '/upload/gujian';
		if (!empty($_FILES)) {
	$tempFile = $_FILES['gujian']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	// Validate the file type
	$fileTypes = array('ubin','bin'); // File extensions
	$fileParts = pathinfo($_FILES['gujian']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['gujian']['name'];
		$gujian=D('gujian');
		$path=$gujian->where(array('xinghao'=>$_POST['xinghao']))->find();
		if($path){
		if($path['path']==$targetFile){
			$this->error('固件版本号一致！');	
		}else{
			if(move_uploaded_file($tempFile,$targetFile)){
			$data['path']=$targetFile;
			$data['update_time']=time();										
		 	$gujian->where(array('xinghao'=>$_POST['xinghao']))->save($data);									
			$this->success('更新固件成功！');								
			}else{
				$this->error('上传失败');
			}							
		}
		}else{
			move_uploaded_file($tempFile,$targetFile);
			$_POST['path']=$targetFile;
			$_POST['update_time']=time();
			$gujian->create();
			$gujian->add();
			$this->success('更新固件成功！');
		}
	}
		}else{
					
		$this->error('文件类型错误');		
			
		}	
		}
		else{
		include CONF_PATH.'enum/enum.php';//$enumdata
		$this->assign('enumdata',$enumdata);
		$gujian=D('gujian');
		$info=$gujian->select();
		foreach($info as $k=>&$v){
			preg_match('/.*\/upload\/gujian\/(.*)/', $v['path'],$arr);
			$v['path']=$arr[1];
			
		}
		$this->assign('lists',$info);
		$this->display();
	}
		
	}
	
	
	
	
	
	public function edit(){
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
	        		   $this->success('更新成功',U('index'));
	        		}else{
	        			$this->error("操作失败");
	        		}
        	}else{
        		$this->error($db->getError());
        	}
		}else{
			$id=I('get.id','0','int');
			
			$where['id']=$id;
			$db=D('Routemap');
			$info=$db->where($where)->find();
			if(!$info){
				$this->error("参数不正确");
			}
			$this->assign("info",$info);
			
	

	        
	     
	        
	        
	        $this->display();    
		}
	}
public function jifei(){
	if(IS_POST){
			
			$this->configsave();
		}else{
			$this->display();
		}
	
	
	
	
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
		$_POST['shengji']=1;
	}else{
		$_POST['reboot']=1;
	}   
	if($db->create()){
        			if($db->where($where)->save()){
	        		   $this->success('操作成功',U('index'));
	        		}else{
	        			$this->error("操作失败");
	        		}
        	}else{
        		$this->error($db->getError());
        	}
	
	
	
}
	private  function configsave(){
		$act=$this->_post('action');
		unset($_POST['files']);
		unset($_POST['action']);
		unset($_POST[C('TOKEN_NAME')]);
		//exit(print(C('OPENPUSHLINK')));
		//exit(echo(update_config($_POST,CONF_PATH."advlink.php")));
		if(update_config($_POST,CONF_PATH."route.php")){
			$this->success('操作成功',U('Route/'.$act));
		}else{
			$this->success('操作失败',U('Route/'.$act));
		}
	}


	public function del(){
	 	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
         
       
        $where['id']=$id;
      
        
        $r = D('Routemap')->where($where)->find();
        
        if($r==false){
        	$this->error('没有此路由信息');
        }else{
          if(D('Routemap')->where($where)->delete()){
          	$this->success('删除成功');
          }else{
          	$this->error('删除失败');
          }
        	
        }
	}
}