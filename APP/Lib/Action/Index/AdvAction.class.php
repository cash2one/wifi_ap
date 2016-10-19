<?php
/*
 * 高级收费功能 3G
 */
class AdvAction extends HomeAction{

 	private function isLogin()
    {
        if(!session('uid'))
        {
            $this->redirect('index/index/log');
        }    
        $this->assign('a','advfun');
    }

	public function index(){}
	
	public function set(){
		$this->isLogin();
		$db=D('Smscfg');
		$uid = session('uid');
		$where['uid']=$uid;
		$info=$db->where($where)->find();
		$this->assign('info',$info);
		$this->display();
	}
	public function del(){
		if(IS_POST){
			$where['name']=I('post.name');
			$where['uid']=session('uid');
			$fenzu=D("fenzuphone");
			$result=$fenzu->where($where)->delete();
			if($result){
				$data['error']=0;
			$data['num']="成功删除".$result;
				
			}else{
				$data['error']=1;
			$data['num']="您没有权限删除该组";
			}
			
			$this->ajaxReturn($data);
		}
	}
		public function orderlist(){
		import('@.ORG.UserPage');
        $this->assign('a','advfun');
        $uid = session('uid');
      	$ad = D('morderlist');
		$where['uid']=$uid;
		$where['trade_status']='TRADE_SUCCESS';
		$where['_logic']='and';
        $count=$ad->where($where)->count();
      	$page=new UserPage($count,C('ad_page'));
        $result = $ad->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
 		$this->assign('page',$page->show());
        $this->assign('lists',$result);
		
		$this->display();
		
	}
		
	public function addfenzu(){
			$this->isLogin();
			$data['name']=I('post.name');
			$data['uid']=session('uid');
			$data['add_time']=date("Y-m-d");
			$data['num']=0;
			$db=D('fenzu');
			$res=$db->where(array('name'=>$data['name'],'uid'=>session('uid')))->select();
		if($res){
			$datas['error']=1;
			$datas['msg']="分组名重复！";
			$this->ajaxReturn($datas);
		}else{
			if($db->add($data)){
			$datas['error']=0;
			$datas['msg']="添加成功！";
			$this->ajaxReturn($datas);
			}
			
		}
	}
	public function delfenzu(){
			$this->isLogin();
			$name=I('post.name');
			$db=D('fenzu');
			$phone=D('fenzuphone');
			$res=$db->where(array('name'=>$name,'uid'=>session('uid')))->delete();
			$phone->where(array('name'=>$name,'uid'=>session('uid')))->delete();
		if($res){
			$datas['error']=0;
			$datas['msg']="删除成功！";
			$this->ajaxReturn($datas);
			
		}else{
			
			$datas['error']=1;
			$datas['msg']="没有该组信息";
			$this->ajaxReturn($datas);
			
			
		}
	}
	public function qkfenzu(){
			$this->isLogin();
			$name=I('post.name');
			$db=D('fenzu');
			$phone=D('fenzuphone');
			$res=$phone->where(array('name'=>$name,'uid'=>session('uid')))->delete();
			$db->where(array('name'=>$name,'uid'=>session('uid')))->setField('num',0);
		if($res){
			$datas['error']=0;
			$datas['msg']="清空成功！";
			$this->ajaxReturn($datas);
			
		}else{
			
			$datas['error']=1;
			$datas['msg']="没有该组信息";
			$this->ajaxReturn($datas);
			
			
		}
	}
		public function cxfenzu(){
			$this->isLogin();
			$db=D('fenzu');
			$res=$db->where(array('uid'=>session('uid')))->select();
			$data['error']=0;
			$data['msg']=$res;
			$this->ajaxReturn($data);
		
	}
		public function cxphone(){
			$this->isLogin();
			$name=I('post.name');
			$db=D('fenzuphone');
			$res=$db->where(array('name'=>$name,'uid'=>session('uid')))->select();
			if($res){
			$data['error']=0;
			$data['msg']=$res;
			$this->ajaxReturn($data);
			}else{
			$data['error']=1;
			$data['msg']='该组号码为空！';
			$this->ajaxReturn($data);
			}
		
	}
		
		
		
	public function saveset(){
		$this->isLogin();
		if(IS_POST){
			$db=D('Smscfg');
			$uid = session('uid');
			$where['uid']=$uid;
			$info=$db->where($where)->find();
			if($info==false){
				//do add
				$_POST['uid']=$uid;
				if($db->create()){
					if($db->add()){
					$this->success("保存成功");
					}else{
						$this->error("保存失败");
					}
				}else{
					$this->error($db->getError());
				}
			}else{
				//do update
				if($db->create()){
					if($db->where($where)->save()){
						$this->success("保存成功");
					}else{
						$this->error("保存失败");
					}
				}else{
					$this->error($db->getError());
				}
			}
		}
		
	}
	/*
	public function  test(){
		import('@.ORG.XCSMS');
		$server='http://localhost:8088/sms.asmx?WSDL';
		$u='76c5069c8921470d9605e516e9372cb7';
		$p='PO7DJCVM';
		$client=new XCSMS($server, $u, $p);
		//$client = new SoapClient('http://localhost:8088/xcws.asmx?WSDL'); 
		//$client->soap_defencoding='utf-8';
		//$client->decode_utf8=false;
		echo $client->GetSmsAccount()."<br/>";
		echo $client->GetSmsPrice()."<br/>";
		echo $client->SendSms('13956989651', "我的短信内容");
	}
	*/
    /**
     * 手机号码显示
     */
	public function phonelist(){
		$this->isLogin();
      	import('@.ORG.UserPage');
        $this->assign('nav','yingxiao');
		   $this->assign('navbar','phone');
        $uid = session('uid');
		$shopinfo=D('shop');
		$messnum=$shopinfo->where(array('id'=>$uid))->getField('messnum');
		$this->assign('messnum',$messnum);
		$where['shopid']=$uid;
        $where['mode']  = 1;
      
        $ad = D('Member');
        $count=$ad->where($where)->count();
   
		$page=new UserPage($count,20);
        $result = $ad->where($where)->limit($page->firstRow.','.$page->listRows)->order('login_time desc')->Distinct(true)->select();
    
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
	/*
	 * 手机号码下载
	 */
	public function downphone(){
		$this->isLogin();
		$uid = session('uid');
        $where['shopid']=$uid;
        $where['mode']  = 1;
        
        $ad = D('Member');
        $data=$ad->where($where)->field('phone,add_date')->select();
        exportexcel($data,array('手机号码','注册时间'),date('Y-m-d H:i:s', time()));
	}
	
	public function sms(){
	
		$this->isLogin();
		 $uid = session('uid');
		 if(session('jifei')==1){
		 	$where['uid']=$uid;
			$vipmem=D('Vipmember');
			$data=$vipmem->where($where)->field('phonenum')->select();
			$this->assign('jifeidata',$data);
		 }else{
		 $where['shopid']=$uid;
         $where['mode']  = 1;
		 $where['messageflag']='0';
		 
	     $ad = M('Member');
        $data=$ad->where($where)->group('phone')->order('update_time')->field('phone,update_time')->select();
		$wheres['shopid']=$uid;
        $wheres['mode']  = 1;
		$wheres['messageflag']='1';
		$datas=$ad->where($wheres)->group('phone')->order('update_time')->field('phone,update_time')->select();
		$this->assign('data',$data);
		$this->assign('datas',$datas);
		 }
		 
		$shopinfo=D('shop');
		$phone=D('fenzuphone');
		$fenzu=$phone->where(array('uid'=>$uid))->group('name')->field('name')->select();
		$i=3;
	    foreach($fenzu as $k=>$v){
	    	
	    	$fenzu[$k]['num']=$phone->where(array('uid'=>$uid,'name'=>$v['name']))->field('phone')->select();
			$fenzu[$k]['nums']=$i;
			$i++;
	    }

		$this->assign('fenzu',$fenzu);
		
		$messnum=$shopinfo->where(array('id'=>$uid))->getField('messnum');
		$qunfa=$shopinfo->where(array('id'=>$uid))->getField('qunfa');
		$this->assign('messnum',$messnum);
		$this->assign('qunfa',$qunfa);
 

		
		$this->assign('navbar','message');
		$this->assign('nav','yingxiao');
		$this->display();
		
		
	}
	
	public  function ceshi(){
		 Vendor('PHPExcel.PHPExcel');
		$targetFolder = '/upload/xls'; // Relative to the root
		 $phone=array();
		 $name=I('post.namexin');
		 if(empty($name)){
		 	$name=I('post.name');
		 }
		 $db=D('fenzuphone');
if (!empty($_FILES)) {
	$tempFile = $_FILES['xls']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	// Validate the file type
	$fileTypes = array('xlsx','xls'); // File extensions
	$fileParts = pathinfo($_FILES['xls']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
	$_FILES['xls']['name']=time()."sid".session('uid').".".$fileParts['extension'];
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['xls']['name'];
		move_uploaded_file($tempFile,$targetFile);
	if($fileParts['extension']=='xls'){    
	 Vendor("PHPExcel.PHPExcel.Reader.Excel5");               
     $objReader = PHPExcel_IOFactory::createReader('Excel5');        
	       }
      elseif($fileParts['extension']=='xlsx'){
	     Vendor("PHPExcel.PHPExcel.Reader.Excel2007");           
       $objReader = PHPExcel_IOFactory::createReader('Excel2007');   
			}  
			$objPHPExcel =  $objReader->load($targetFile);
		 	$excelarray= $objPHPExcel->getSheet(0)->toArray();
			$i=0;
		
		foreach($excelarray as $k=>$v){
		foreach($v as $a=>$b){
   		if(preg_match('/^(0|86|17951)?(13[0-9]|15[012356789]|17[0-9]|18[0-9]|14[45])[0-9]{8}$/', $b)){
   			$data['phone']=$b;
			$data['name']=$name;
			$data['uid']=session('uid');
			$data['add_time']=date("Y-m-d");
		$db->add($data);
		    $i++;
   				
			}
			}
		}
//				
//	}
//	$fenzu=D('fenzu');
//	$num=$fenzu->where(array('name'=>$name,'uid'=>session('uid')))->getField("num");
//	$nums=$num+$i;
//	$fenzu->where(array('name'=>$name,'uid'=>session('uid')))->setField("num",$nums);
		
		$this->success("成功导入分组$name".$i."条记录",U('adv/sms'));
		
	} else {
		$this->error("文件格式错误");
	}
	}
	}
	
	
	
	public  function Send(){
		$fenzu=D("fenzuphone");
		$result=$fenzu->where(array('uid'=>session('uid')))->group("name")->field("name")->select();
		$this->assign('fenzu',$result);
		
		$this->display();
		
	}
	public function fasong(){
			$this->isLogin();
			$shopinfo=D('shop');
			$uid=session('uid');
			$qunfa=$shopinfo->where(array('id'=>$uid))->getField('qunfa');
			$messnum=$shopinfo->where(array('id'=>$uid))->getField('messnum');
			$qunfa=$shopinfo->where(array('id'=>$uid))->getField('qunfa');
			$this->assign('messnum',$messnum);
			$this->assign('qunfa',$qunfa);
			if($qunfa==0){
				$this->error('可用群发短信条数不足，请先进行充值！',U('user/chongzhi'));
			}
			if(IS_GET){
				$phone=I('get.phone');
				$this->assign('phone',$phone);
			}
			if(IS_POST){
				$phones=I('post.phone');
				$phone=trim($phones,',');
				$this->assign('phone',$phone);
				
			}
			
			$this->display();
	}
	public function lin($url,$data){

		$ch=curl_init();
		$timeout=5;
		$str=http_build_query($data);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $str );
		$file_contents=curl_exec($ch);
		curl_close($ch);
	return json_decode($file_contents);
}
	public  function addsms(){
		$this->isLogin();
		if(IS_POST){
			$uid = session('uid');
			$shopinfo=D('shop');
			$shopname=$shopinfo->where(array('id'=>$uid))->getField('shopname');
			$qunfa=$shopinfo->where(array('id'=>$uid))->getField('qunfa');
			$phones=rtrim(I('post.phones'),',');
		    $msg=trim(I('post.info'))."回T退订";
			if($_POST['tiaoshu']>$qunfa){
				$this->error('发送失败!,可用短信条数不足');
			}
//		$url="http://183.60.176.8:8080/http.aspx?act=sendmsg&username=shenxing&passwd=160125&msg=$msg&phone=$phones&port=&sendtime=";
		$url="http://www.qybor.com:8500/shortMessage";
		$data=array(
		'act'=>'sendmsg',
		'username'=>'shenxinton',
		'passwd'=>'462581',
		'msg'=>$msg,
		'phone'=>$phones,
		'needstatus'=>'false',
		'port'=>'',
		'sendtime'=>'',
		); 
		$result=$this->lin($url,$data);
	
      

	if($result->respcode==0){
		$countxs=$_POST['tiaoshu'];
		$shengyu=$qunfa-$_POST['tiaoshu'];
		$shopinfo->where(array('id'=>$uid))->setField('qunfa',$shengyu);
		    $msginfo=D('msginfo');
			$datas['add_time']=time();
			$datas['shopid']=$uid;
			$datas['shopname']=$shopname;
			$datas['phonenum']=$phones;
			$datas['msg']=$msg;
			$datas['taskid']=$result->batchno;
			$datas['qunfa']=$qunfa;
			$datas['afterqunfa']=$qunfa-$_POST['tiaoshu'];
			$msginfo->data($datas)->add();//数据库添加操作记录
			
		$this->success("发送成功!成功发送条数:$countxs!",'',5);
	}else{
	
		$this->error("发送失败".$result->respdesc,'',5);
	}
	       
////			$len=mb_strlen($msg,'UTF-8');//短信长度
////			$ut=ceil($len/70);//计算短信数量
//              $post_data = array();
//				$post_data['userid'] =1756;	
//				$post_data['account'] ='sxt';
//				$post_data['password'] ='123456';
//				$post_data['content'] =$msg;
//				$post_data['mobile'] ="$phones";
//				$post_data['sendtime'] = ''; 
//				$url='http://218.244.136.70:8888/sms.aspx?action=send';
//				$o='';
//				foreach ($post_data as $k=>$v)
//				{
//					 $o.="$k=".urlencode($v).'&';
//				}
//				$post_data=substr($o,0,-1);
//				$ch = curl_init();
//				curl_setopt($ch, CURLOPT_POST, 1);
//				curl_setopt($ch, CURLOPT_HEADER, 0);
//				curl_setopt($ch, CURLOPT_URL,$url);
//				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
//				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//			    $result = curl_exec($ch);
//				$results=simplexml_load_string($result);
//			    $status=$results->returnstatus;
//			    $counts=$results->successCounts;
//			    $message=$results->message;
//				$taskid=$results->taskID;    
//			if($status=='Success'){
//		    $msginfo=D('msginfo');
//			$datas['add_time']=time();
//			$datas['shopid']=$uid;
//			$datas['shopname']=$shopname;
//			$datas['phonenum']=$phones;
//			$datas['msg']=$msg;
//			$datas['taskid']=$taskid;
//			$datas['qunfa']=$qunfa;
//			$datas['afterqunfa']=$qunfa-$_POST['tiaoshu'];
//			$msginfo->data($datas)->add();//数据库添加操作记录
//			$dataq['qunfa']=$qunfa-$_POST['tiaoshu'];
//			$shopinfo->where(array('id'=>$uid))->save($dataq);
//		   $countxs=$_POST['tiaoshu'];
//		   echo $countxs;
////			    $data['error']=0;
////				$data['msg']="发送成功!成功发送条数:$counts!";
////				$data['last']=$qunfa-$counts*$_POST['tiaoshu'];
//////				$this->ajaxReturn($data);
//$this->success("发送成功!成功发送条数:$countxs!",'',5);
//			  }else{
//			  	$data['error']=1;
//				$data['msg']="发送失败!错误信息：$message!";
//$this->error("发送失败!错误信息：$message!",'',5);
//				}
			
		}
	
	}
	
	private function getsmsstate($rs){
		//1 成功 -1 失败 -2 帐号密码不正确 -3 金额不足 -4 手机号码或其他参数不正确
		switch ($rs){
			case -1:
				return "短信提交失败";
			case -2:
				return "发送短信的帐号密码不正确";
			case -3:
				return "短信帐号余额不足";
			case -4:
				return "提交的手机号码有错";
			default:
				return '短信提交失败';
				
		}
	}
	
	public function smslist(){
		import('@.ORG.UserPage');
        $this->assign('nav','yingxiao');
		$this->assign('navbar','smslist');
        $uid = session('uid');
        $where['shopid']=$uid;
      	 $ad = D('msginfo');
        $count=$ad->where($where)->count();
		$page=new UserPage($count,10);
        $result = $ad->where($where)->limit($page->firstRow.','.$page->listRows)->order('add_time desc')->select();
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
	
	
}