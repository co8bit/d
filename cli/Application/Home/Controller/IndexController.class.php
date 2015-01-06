<?php
namespace Home\Controller;
use Think\Controller;

require_once(COMMON_PATH."/function.php");

class IndexController extends Controller 
{
	protected function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
    }

    public function index()
    {
    	echo "<a href=".U('Index/scanForSendSMS').">";
    	exit("你知道的非常多啊");
    }

    public function go()
    {
    	echo "ASd";
    }

    /**
     * 为了发定时短信而扫描数据库
     * @return [type] [description]
     */
    public function scanForSendSMS()
    {
    	//TODO:对数据排序
    	$dbSchedule		=	M("Schedule");

    	$BATCH_NUM	=	100;//一次处理多少条
    	
    	while (true)
    	{
    		$count	=	ceil( $dbSchedule->count() / $BATCH_NUM );//要处理多少轮
    	

	    	for ($i = 0; $i < $count; $i++)
	    	{
	    		$tmp	=	null;
	    		$map["startTime"]	=	array( "between",array( date("Y-m-d H:i:00",(strtotime("+28 minutes"))) , date("Y-m-d H:i:59",(strtotime("+32 minutes"))) ) );//时间间隔
	    		$tmp	=	$dbSchedule->where(array("schedule.isSMS"=>false))->where($map)->limit($i * $BATCH_NUM, $BATCH_NUM)->join("user ON schedule.uid = user.uid")->select();

	    		// dump($tmp);

	    		$condition	=	null;
	    		foreach ($tmp as $key=>$value)
	    		{
	    			if (!empty($value["phone"]))
	    			{
	    				sendSMS($value["title"],$value["phone"]);
	    			}

	    			if (empty($condition))
	    				$condition	=	"sid=".$tmp[$key]["sid"];
	    			else
	    				$condition	.=	" or sid=".$tmp[$key]["sid"];
	    		}
	    		if (!empty($condition))
	    			echo $condition." ";
	    		$dbSchedule->where($condition)->save(array("isSMS"=>true));
	    	}
    	}
    }
}