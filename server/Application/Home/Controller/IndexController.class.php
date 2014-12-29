<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller
{
	protected function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
    }

    public function index()
    {
    	//$this->show("asd");
    	echo "你知道的太多了<br>";
    	echo "<a href='".U("User/sign")."'>注册</a><br>";

    	echo '<br>Activity/create：
    		<form action="'.U("Activity/create").'" enctype="multipart/form-data" method="post" >
	    		file:<input type="file" name="logoPic" />
	    		<input type="submit" value="提交" >
    		</form><br>
    	';
    	echo '<br>Activity/edit:
    		<form action="'.U("Activity/edit").'" enctype="multipart/form-data" method="post" >
    			mode:<input type="text" name="mode" value="2"/>
    			sid:<input type="text" name="aid" value="2"/>
	    		file:<input type="file" name="logoPic" />
	    		<input type="submit" value="提交" >
    		</form><br>
    	';
    }
}