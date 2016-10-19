<?php
class RoutemapModel extends Model{
    
    protected $_validate = array(
        array('shopid','require','路由所属帐号不能为空',1),
        array('routename','require','路由名称不能为空',1),
        array('gw_id','require','MAC地址不能为空',1),
		array('gw_id','/^(SXTG|SXTGAC|SXTCPE|SXTAP|SXT|SXTAC)\d+\-\([0-9a-zA-Z]{12}\)|^(SXTG|SXTGAC|SXTCPE)\d+\-[0-9a-zA-Z]{12}|[0-9a-zA-Z]{12}/','MAC格式不正确','regex',3),
        array('gw_id','','MAC不能重复',1,'unique',1),
    );
    
    protected $_auto = array(
        array('add_time','time',self::MODEL_INSERT,'function'),
        array('update_time','time',self::MODEL_BOTH,'function'),
        array('gw_id','ToUp',self::MODEL_BOTH,'callback '),
        array('state','1',self::MODEL_INSERT),
    );
    
    protected  function ToUp($rs){
    	return strtoupper($rs);
    }
}