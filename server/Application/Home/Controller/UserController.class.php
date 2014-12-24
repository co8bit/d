<?php
namespace Home\Controller;
use Think\Controller;

class UserController extends Controller
{
	protected function _initialize() 
    {
        parent::_initialize();
    }
    
    /**
     * 用户登录函数
     * @param string $userName 用户名
     * @param string $userPassword 密码
     * @return bool"" 是否成功
     */
    public function login() 
    {
        if (IS_POST)
        {
            $userName           =       I('param.userName');
            $userPassword       =       I('param.userPassword');
            empty($userName) && $this->error("错误：用户名不能为空");
            empty($userPassword) && $this->error("错误：密码不能为空");
            
            if ( $result = D("User")->login($userName,$userPassword) )
            {
                //设置session
                session('userName',$result['name']);
                session("uid",$result["uid"]);
                echo "true";
            }
            else
            {
                echo "false";
            }
        }
    }
    
    /**
     * 用户退出函数
     * @return "true" 成功
     * @return "error" 各种理由的操作失败
     */
    public function logout()//安全退出
    {
        //判断session是否存在
        if (!session('?uid'))
        {
            echo "error";
        }
    
        //删除session
        session('userName',null);
        session('uid',null);
    
        //再次判断session是否存在
        if ( session('?uid') )
            echo "error";
        else
            echo "true";
    }
}