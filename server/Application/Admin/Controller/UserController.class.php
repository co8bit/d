<?php
namespace Admin\Controller;
use Think\Controller;

require_once(APP_PATH."/Admin/Conf/MyConfigINI.php");

class UserController extends Controller
{
    protected $uid    =   null;

    /**
     * 上传文件的配置数组
     * @var array
     */
    protected $UPLOADCONFIG = array(    
        'maxSize'    =>    3145728,
        'rootPath'   =>    _UPLOADPATH,
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
        $this->uid      =       session("uid");
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
                $this->success("登录成功",U("User/index"));
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
                $this->success("注册成功",U("User/index"));
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
     * 信息维护页面
     */
    public function personal()
    {
        $dbUser = D("User");

        $data   =   $dbUser->where(array("uid"=>$this->uid))->find();

        if (IS_POST)
        {
            if (!$dbUser->create())
            {
                 exit($dbUser->getError());
            }
            else
            {
                $dbUser->uid  =   $this->uid;
                $dbUser->state    =   0;

                if ($dbUser->corpBook == "") $dbUser->corpBook = $data["corpBook"];
                if ($dbUser->businessCard == "") $dbUser->businessCard = $data["businessCard"];

                $upload = new \Think\Upload($this->UPLOADCONFIG);// 实例化上传类
                $info   =   $upload->upload();
                if(!$info)
                {// 上传错误提示错误信息
                    //exit($upload->getError());
                }
                else
                {// 上传成功获取上传文件信息  
                    //trace(dump($info,false),'提示','debug');  
                    foreach($info as $key=>$file)
                    {
                        $dbUser->$key = $file['savepath'].$file['savename'];
                    }
                }

                $dbUser->save();
                $edit   =   1;
            }
        }

        

        if ($data["state"] != 1)
        {
            $msg    =   "未通过审核";
        }
        else
        {
            $msg    =   $data["checkdate"]."通过审核";
        }
        
        $this->assign("data",$data);
        $this->assign("msg",$msg);
        $this->assign("edit",$edit);

        $this->general(2);
        $this->display();
    }

    public function index()
    {
        $this->general(1);
        $this->display();
    }

    /**
     * 生成本控制器页面的通用信息，目前是两个内容：上导航栏和左导航栏
     * @param  int $nav 导航条选中当前项的值
     */
    protected function general($nav)
    {
        $this->assign("nav",$nav);//导航条选中当前项的值
        $dbUser  =    D("User");
        $headerData   =   $dbUser->where(array("uid"=>$this->uid))->find();
        $this->name   =   $headerData["name"];
        $this->assign("userName",$this->name);
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


}