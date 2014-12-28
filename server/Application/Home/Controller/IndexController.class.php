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

    	echo '<br>测试schedule/create：
    		<form action="'.U("Schedule/create").'" enctype="multipart/form-data" method="post" >
    			mode:<input type="text" name="mode" value="1"/>
	    		file:<input type="file" name="logoPic" />
	    		<input type="submit" value="提交" >
    		</form><br>
    	';
    	echo '<br>测试schedule/edit:
    		<form action="'.U("Schedule/edit").'" enctype="multipart/form-data" method="post" >
    			mode:<input type="text" name="mode" value="2"/>
    			sid:<input type="text" name="sid" value="2"/>
	    		file:<input type="file" name="logoPic" />
	    		<input type="submit" value="提交" >
    		</form><br>
    	';
    }
}