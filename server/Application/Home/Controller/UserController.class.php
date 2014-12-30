<?php
namespace Home\Controller;
use Think\Controller;

class UserController extends Controller
{
    /**
     * 上传文件的配置数组
     * @var array
     */
    protected $UPLOADCONFIG = array(    
        'maxSize'    =>    3145728,
        'rootPath'   =>    './Public/',
        'savePath'   =>    '/Uploads/',    
        'saveName'   =>    array('uniqid',''),    
        'exts'       =>    array('jpg', 'png', 'jpeg'),    
        'autoSub'    =>    true,    
        'subName'    =>    array('date','Ymd'),
    );

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
     * @param string $userName 用户名
     * @param string  密码
     * @return bool"" 是否成功
     *         error"" 用户名或密码为空
     */
    public function login() 
    {
        //if (IS_POST)
       // {
            $userName           =       I('param.userName',"");
            $userPassword       =       I('param.userPassword',"");
            empty($userName) && exit("error");
            empty($userPassword) && exit("error");
            
            if ( $result = D("User")->login($userName,$userPassword) )
            {
                //设置session
                $this->setSession($result);
                echo "true";
            }
            else
            {
                echo "false";
            }
       // }
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
            exit("error");
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

    /**
     * 注册函数
     * @param string $userName 用户名
     * @param string $userPassword 密码
     * @return bool "" 是否成功
     *         "error" 用户名已存在或用户名密码为空
     */
    public function sign()
    {
        $dbUser = D("User");
        $data["name"]      =       I('param.userName',"");
        $data["pwd"]       =       I('param.userPassword',"");
        empty($data["name"]) && exit("error");
        empty($data["pwd"]) && exit("error");

        //判断用户名是否重复 TODO:这么写好么！？添加数据库的唯一标识
        $tmpResult  =   $dbUser->where(array("name"=>$data["name"]))->find();
        if ( !empty($tmpResult) )
            exit("error");

        $userId = $dbUser->add($data);
        if(empty($userId))//添加失败
        {
            echo "false";
        }
        else
        {
            $data["uid"]    =   $userId;
            $this->setSession($data);
            echo "true";
        }
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
     * 得到用户的真实姓名，如果不存在真实姓名，返回用户名
     * @return string 用户的真实姓名
     */
    public function getUserRealName()
    {
        $dbUser     =   D("User");

        $tmp    =   $dbUser->getUserInfo(session("uid"));
        if (!$tmp)
            exit("error");

        if (empty($tmp["realName"]))
            exit($tmp["name"]);
        else
            exit($tmp["realName"]);
    }


    /**
     * 得到用户的用户名
     * @return string 用户的用户名
     */
    public function getUserName()
    {
        $dbUser     =   D("User");

        $tmp    =   $dbUser->getUserInfo(session("uid"));
        if (!$tmp)
            exit("error");

        exit($tmp["name"]);
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
}