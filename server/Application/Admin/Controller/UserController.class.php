<?php
namespace Admin\Controller;
use Think\Controller;

class UserController extends Controller
{
    protected function _initialize()
    {
        //parent::_initialize();
        header("Content-Type:text/html; charset=utf-8");
    }
    

    /**
     * 登录好后设置session
     * @param array $data 要设置session所需要的信息数组
     */
    protected function setSession($data)
    {
        session('userName',$data['name']);
        session("uid",$data["uid"]);
    }

    /**
     * 用户登录函数
     */
    public function login()
    {
        if (IS_POST)
        {
            $userName           =       I('param.username',"");
            $userPassword       =       I('param.password',"");
            empty($userName) && $this->error("用户名为空");
            empty($userPassword) && $this->error("密码为空");
            
            if ( $result = D("User")->login($userName,$userPassword) )
            {
                //设置session
                $this->setSession($result);
                $this->success("登录成功",U("User/createActivity"));
            }
            else
            {
                $this->error("登录失败");
            }
        }
        else
            $this->display();
    }
    


    /**
     * 用户退出函数
     * @return "true" 成功
     */
    public function logout()//安全退出
    {
        //判断session是否存在
        if (!session('?uid'))
        {
            $this->error("退出失败");
        }
    
        //删除session
        session('userName',null);
        session('uid',null);
    
        //再次判断session是否存在
        if ( session('?uid') )
            $this->error("退出失败");
        else
            $this->success("退出成功",U("User/login"));
    }

    /**
     * 注册函数
     * @param string $userName 用户名
     * @param string $userPassword 密码
     */
    public function sign()
    {
        if (IS_POST)
        {
            $dbUser = D("User");
            $data["name"]      =       I('param.username',"");
            $data["pwd"]       =       I('param.password',"");
            $data["leibie"]    =       I("param.leibie",3);
            empty($data["name"]) && exit("error");
            empty($data["pwd"]) && exit("error");

            //判断用户名是否重复 TODO:这么写好么！？添加数据库的唯一标识
            $tmpResult  =   $dbUser->where(array("name"=>$data["name"]))->find();
            if ( !empty($tmpResult) )
                $this->error("注册失败");

            $userId = $dbUser->add($data);
            if(empty($userId))//添加失败
            {
                $this->error("注册失败");
            }
            else
            {
                $data["uid"]    =   $userId;
                $this->setSession($data);
                $this->success("注册成功",U("User/createActivity"));
            }  
        }
        else
            $this->display();
        
    }

    /**
     * 获得用户uid
     * @return int uid
     */
    public function getUid()
    {
        exit(session("uid"));
    }

    /**
     * 创建活动信息的web页面
     */
    public function createActivity()
    {
        $this->uid      =       session("uid");
        empty($this->uid) && exit("非法登陆");
        
        if (IS_POST)
        {

        }
        else
        {

        }

        $this->display();
    }























    /**
     * 得到用户的信息
     * @param int uid
     * @return json 用户信息，为数据库中的一行
     *         如：{"uid":"1","name":"wbx@wbx.com","pwd":"wbx","realName":"","logoPic":"","phone":"","address":""}
     * @return false "" 失败
     * @return error "" uid非法
     */
    public function getUserInfo()
    {
        $dbUser     =   D("User");

        $uid    =   I("param.uid");
        if (!$dbUser->uidValidateRules($uid))
            exit("error");

        $this->ajaxReturn($dbUser->getUserInfo($uid));
    }


    /**
     * 修改用户信息（没有修改头像）
     * @param json 用户信息，为数据库中的一行；不能修改name
     *         如：{"uid":"1","pwd":"wbx","realName":"","logoPic":"","phone":"","address":""}
     * @return bool "" 是否成功
     * @return error "" uid非法
     */
    public function editUserInfo()
    {
        $dbUser     =   D("User");

        $dbUser->field("uid,pwd,realName,logoPic,phone,address")->create(I('param.'));
        if ($dbUser->save())
            exit("true");
        else
            exit("false");
    }


    /**
     * 设置用户信息（必须同时上传头像）
     * @param 表单 用户信息，为数据库中的一行；不能修改name
     *         如：{"uid":"1","pwd":"wbx","realName":"","logoPic":"","phone":"","address":""}
     * @return bool "" 是否成功
     * @return error "" uid非法
     */
    public function setUserInfo()
    {
        $dbUser     =   D("User");

        $dbUser->field("uid,pwd,realName,phone,address")->create(I('param.'));

        $upload = new \Think\Upload($this->UPLOADCONFIG);// 实例化上传类
        $info   =   $upload->upload();
        if(!$info)
        {// 上传错误提示错误信息
            exit($upload->getError());
        }
        else
        {// 上传成功获取上传文件信息    
            $dbUser->logoPic = $info["logoPic"]['savepath'].$info["logoPic"]['savename'];
        }

        if ($dbUser->save())
            exit("true");
        else
            exit("false");
    }


    /**
     * 设置用户头像（必须上传头像）
     * @param int uid
     * @param file 头像文件
     * @return bool "" 是否成功
     * @return error "" uid非法
     */
    public function setUserInfoLogo()
    {
        $dbUser     =   D("User");

        $dbUser->field("uid")->create(I('param.'));

        $upload = new \Think\Upload($this->UPLOADCONFIG);// 实例化上传类
        $info   =   $upload->upload();
        if(!$info)
        {// 上传错误提示错误信息
            exit($upload->getError());
        }
        else
        {// 上传成功获取上传文件信息    
            $dbUser->logoPic = $info["logoPic"]['savepath'].$info["logoPic"]['savename'];
        }

        if ($dbUser->save())
            exit("true");
        else
            exit("false");
    }



    /**
     * 发送手机绑定验证码
     * @param string phone 要绑定的手机号
     * @return void
     */
    public function sendSMSForBindPhone()
    {
        require_once(COMMON_PATH."/function.php");

        $dbUser     =   D("User");

        $dbUser->field("phone")->create(I(_INPUT_METHOD));

        $Verify = new \Think\Verify(array('length'=>4));
        $code   =   $Verify->entry(1,false);

        sendSMS("$code,30",$dbUser->phone,2650);
    }



    /**
     * 判断手机验证码是否正确
     * @param string code 用户输入的验证码
     * @return bool "" 是否正确
     */
    public function bindPhoneVerify()
    {
        $verify = new \Think\Verify();
        if ($verify->check(I(_INPUT_METHOD."code"), 1))
            exit("true");
        else
            exit("false");
    }

}