<?php
namespace Home\Controller;
use Think\Controller;

require_once(COMMON_PATH."/function.php");

class IndexController extends Controller 
{
	protected function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");

        if (!IS_CLI)
        	exit("biu");
    }


    public function index()
    {
    	echo "<a href=".U('Index/scanForSendSMS').">";
    	exit("你知道的非常多啊");
    }



    /**
     * 使用方法：
     * 	如果没有php环境变量，则：
     *		 	vi /etc/profile
     *		  	在文件末尾加上如下两行代码(/a/apps/php-5.4.24/bin为php所在路径,:wq为保存退出):
	 *		      	PATH=$PATH:/a/apps/php-5.4.24/bin
	 *  	      	export PATH
	 *  	    执行命令source /etc/profile
	 *
	 * 
     * 	运行程序：
	 *  	cd /a/domains/gooneday.com/public_html/cli/
	 *  	可选择两种方法：
	 *  	1. nohup php index.php /Home/Index/scanForSendSMS > sendSMS.log
	 *  		记录输出值sendSMS.log，但是如果要继续其他命令需要重登ssh
	 *  	2. nohup php index.php /Home/Index/scanForSendSMS &
     *     		会得到一个进程号如：
     *     			[root@AY14030216255480481aZ cli]# nohup php index.php /Home/Index/scanForSendSMS & > sendSMS.log
	 *           	[1] 3707
	 *            	[root@AY14030216255480481aZ cli]# nohup: ignoring input and appending output to `nohup.out'
	 *          这里是3707，这个可以用下面的kill直接杀死程序，这时需要查看nohup.out看日志
     * 
     *	杀死程序：
     *		ps
     *			找到php这个进程的PID，然后
     *		kill 3555
     *			3555为进程号
     *		出现（可能需要按多下回车）：[1]+  Terminated  nohup php index.php /Home/Index/scanForSendSMS   代表结束了
     *
     *
     * 	查看日志：
     * 		对应于上面两种运行方法，分别是sendSMS.log文件和nohup.out文件
     */
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
	    			echo date("Y-m-d/H:i:s")."/".$condition."||";
	    		$dbSchedule->where($condition)->save(array("isSMS"=>true));
	    	}
    	}
    }
}