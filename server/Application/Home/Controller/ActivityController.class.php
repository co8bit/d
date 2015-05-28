<?php
namespace Home\Controller;
use Think\Controller;

require_once(APP_PATH."/Home/Conf/MyConfigINI.php");

class ActivityController extends Controller
{
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



	protected $uid = null;



	protected function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->uid		=		session("uid");
        empty($this->uid) && exit("need");
    }

    

    /**
     * 整理二维数组中的json格式，以使得ajaxReturn可以进行正常的含json嵌套的输出
     * @param  array[][] $data 要被整理的数据
     * @return array[][] 整理完成的数据
     */
    protected function trimForAjax($data = null)
    {
        foreach ($data as $key1=>$value1)
        {
            foreach ($data[$key1] as $key2=>$value2)
            {
                if ( ($key2 == "tag") || ($key2 == "check") || ($key2 == "participant") || ($key2 == "comment") )
                {
                    $data[$key1][$key2]     =   json_decode($value2,true);
                }
            }
        }

        return $data;
    }

    /**
    * 查询某个活动
    * @param int aid
    * @return error 错误
    * @return ReturnJson 返回数据
    */
    public function queryOne()
    {
        $dbActivity = D("Activity");

        $dbActivity->field("aid")->create(I('param.'));

        $result    =   $dbActivity->where(array("aid"=>$dbActivity->aid))->find();
        $result["tag"] = json_decode($result["tag"],true);
        $result["participant"] = json_decode($result["participant"],true);
        $this->ajaxReturn($result);
    }


    /**
     * 查询所有未完成的活动;
     * @param int page 当前的页数
     * @param int class 活动的类别;不传或传class=9999,返回所有类别的活动
     * @return error "" page过大或没有此类活动
     * @return json 活动内容，形如：
     *         {
     *             "pageTotalNum":2,//总页数
     *             "content":[ //具体的内容
     *                 {},//一个活动
     *                 {}
     *             ]
     *         }
     */
    public function queryAll()
    {
        $dbActivity     =   D("Activity");

        $page   =   I("param.page",1);
        $class  =   I("param.class",9999);
        if ($class == 9999)
        {
            $condition  =   "state=0 and (class=2 or class=3 or class=4)";
        }
        else
        {
            $condition  =   array("class"=>$class,"state"=>0);
        }

        $scheduleCount = $dbActivity->where($condition)->count();
        $scheduleTotalPageNum   =   ceil($scheduleCount / _ACTIVITY_PAGE_NUM);

        if ($page > $scheduleTotalPageNum)
            exit("error");

        $result     =   $dbActivity->where($condition)->order("startTime")->limit(($page-1) * _ACTIVITY_PAGE_NUM,_ACTIVITY_PAGE_NUM)->select();

        $tmp    =   null;
        $tmp["pageTotalNum"] = $scheduleTotalPageNum;
        $tmp["content"]    =   $this->trimForAjax($result);
        $this->ajaxReturn($tmp);
    }



    /**
     * 查询所有自己的活动;
     * @param int page 当前的页数
     * @param int class 活动的类别;不传此参数或传class=9999,返回所有类别的活动
     * @param int state 选择要查询的状态，不传此参数或传state=9999，返回所有状态
     * @return error "" page过大或没有此类活动
     * @return jsonArray 活动内容，形如：
     *         [{
     *             "pageTotalNum":2,//总页数
     *             "content":[ //具体的内容
     *                 {},//一个活动
     *                 {}
     *             ]
     *         },
     *         {...},
     *         ...
     *         ]
     */
    public function querySelf()
    {
        $dbActivity     =   D("Activity");

        $page   =   I("param.page",1);
        $class  =   I("param.class",9999);
        $state  =   I("param.state",9999);
        $state  =   (int)$state;

        if ($state != 9999)
        {
            $stateSQL   =   array("state"=>$state);
        }

        if ($class == 9999)
        {
            $condition  =   "uid=".$this->uid." and (class=2 or class=3 or class=4)";
        }
        else
        {
            $condition  =   array("uid"=>$this->uid,"class"=>$class);
        }

        $scheduleCount = $dbActivity->where($stateSQL)->where($condition)->count();
        $scheduleTotalPageNum   =   ceil($scheduleCount / _ACTIVITY_PAGE_NUM);

        // if ($page > $scheduleTotalPageNum)
        //     exit("error");
        // dump($condition);
        $result     =   $dbActivity->where($stateSQL)->where($condition)->order("startTime")->limit(($page-1) * _ACTIVITY_PAGE_NUM,_ACTIVITY_PAGE_NUM)->select();

        $tmp    =   null;
        $tmp["pageTotalNum"] = $scheduleTotalPageNum;
        $tmp["content"]    =   $this->trimForAjax($result);
        // dump($tmp);
        $this->ajaxReturn($tmp);
    }


    /**
     * 查询最近活动。（范围：所有类别的未完成活动）
     * @param [GET]:int num 代表返回多少条，默认为4
     * @return null 没有该类活动
     * @return jsonArray 活动内容，形如：
     *         [
     *           {},//一个活动，一行数据表中的内容
     *           {}
     *         ]
     */
    public function queryRecently($num = 4)
    {
        $dbActivity     =   D("Activity");

        $map["startTime"]   =   array("egt",date("Y-m-d H:i:00"));
        $result     =   $dbActivity->where($map)->where(array("state"=>0))->order("startTime")->limit(0,$num)->select();
        $this->ajaxReturn($this->trimForAjax($result));
    }



    /**
     * 查询热门活动。（范围：所有类别的未完成活动）
     * @param [GET]:int num 代表返回多少条，默认为4
     * @return null 没有该类活动
     * @return jsonArray 活动内容，形如：
     *         [
     *           {},//一个活动，一行数据表中的内容
     *           {}
     *         ]
     */
    public function queryHot($num = 4)
    {
        $dbActivity     =   D("Activity");

        $map["startTime"]   =   array("egt",date("Y-m-d H:i:00"));
        $map["zan"]         =   array("egt",_HOT_ACTIVITY_ZAN_THRESHOLD);
        $result     =   $dbActivity->where($map)->where(array("state"=>0))->order("startTime")->limit(0,$num)->select();
        $this->ajaxReturn($this->trimForAjax($result));
    }



    /**
     * 创建一个活动
     * @param 多个参数，名称参考returnJson,但是形式是post
     * @return true "" 成功
     *         其他任何东西 "" 失败
     *         error "" 非法操作
     */
    public function create()
    {
        $dbActivity     =   D("Activity");
        $data   =   null;

        $dbActivity->field("title,location,startTime,endTime,content,brief,templateNo,class")->create(I('param.'));
        $dbActivity->uid =  $this->uid;
        $dbActivity->tag = I('param.tag',"null",false);
        $dbActivity->participant = I('param.participant',"null",false);
        $dbActivity->state = 0;

        //TODO:自动补全和验证
        if (!$dbActivity->tagValidateRules($dbActivity->tag))
            exit("error");
        if (!$dbActivity->participantValidateRules($dbActivity->participant))
            exit("error");

        $upload = new \Think\Upload($this->UPLOADCONFIG);// 实例化上传类
        $info   =   $upload->upload();
        if(!$info)
        {// 上传错误提示错误信息
            exit($upload->getError());
        }
        else
        {// 上传成功获取上传文件信息    
            // dump($info);
            $dbActivity->logoPic = $info["logoPic"]['savepath'].$info["logoPic"]['savename'];
        }


        $tmp    =   $dbActivity->add();
        if(empty($tmp))//添加失败
        {
            echo "false";
        }
        else
        {
            echo "true";
        }
    }



    /**
     * 修改一个活动
     * @param 多个参数，名称参考returnJson,但是形式是post; 
     * @param int mode 模式，为1修改活动（不改logo），为2是修改活动（改logo）
     * @return true "" 成功
     *         其他任何东西 "" 失败
     *         error "" 非法操作
     */
    public function edit()
    {
        $dbActivity     =   D("Activity");
        $data   =   null;

        $dbActivity->field("aid,title,location,startTime,endTime,content,state,brief,templateNo,class")->create(I('param.'));
        $dbActivity->uid =  $this->uid;
        $dbActivity->tag = I('param.tag',"null",false);
        $dbActivity->participant = I('param.participant',"null",false);
        
        $mode   =   I("param.mode",0);
        if ((int)$mode == 2)
        {
            $upload = new \Think\Upload($this->UPLOADCONFIG);// 实例化上传类
            $info   =   $upload->upload();
            if(!$info)
            {// 上传错误提示错误信息
                exit($upload->getError());
            }
            else
            {// 上传成功获取上传文件信息    
                // dump($info);
                $dbActivity->logoPic = $info["logoPic"]['savepath'].$info["logoPic"]['savename'];
            }
        }

        if (!$dbActivity->tagValidateRules($dbActivity->tag))
            exit("error");
        if (!$dbActivity->participantValidateRules($dbActivity->participant))
            exit("error");

        $tmp    =   null;
        $tmp = $dbActivity->save();
        if( ($tmp === null) || ($tmp === false) )
        {
            echo "false";
        }
        else
        {
            echo "true";
        }
    }


    /**
     * 删除一个活动
     * @param int aid
     * @return bool "" 是否成功
     */
    public function delete()
    {
        $dbActivity     =   D("Activity");

        $dbActivity->field("aid")->create(I('param.'));
        if ($dbActivity->where(array("uid"=>$this->uid,"aid"=>$dbActivity->aid))->delete())
            exit("true");
        else
            exit("false");
    }


    /**
     * 更改活动的状态
     * @param int aid
     * @param int state 状态
     * @return bool "" 是否完成
     */
    public function editState()
    {
        $dbActivity     =   D("Activity");

        $dbActivity->field("aid,state")->create(I('param.'));
        $dbActivity->uid    =   $this->uid;

        $tmp    =   $dbActivity->save();
        if ( ($tmp === false) || ($tmp === null) )
            exit("false");
        else
            exit("true");
    }


    /**
     * 给当前用户的当前活动添加一个参与者
     * @param int mode 改字段不用传入，是服务器代码自己用，不对外开放的字段
     *     当外部调用时（post）,mode=0：作为组织者，即调用者的uid要为活动创建人的uid
     *     当内部调用时（服务器端自己）,mode=1：任意uid
     * @param int aid
     * @param int uid 被添加的用户
     * @return bool "" 是否成功
     */
    public function addParticipant($mode = 0,$aid = 0,$uid = 0)
    {
        $dbActivity     =   D("Activity");

        if ($mode == 0)//post
        {
            $dbActivity->field("aid,uid")->create(I('param.'));

            $tmp    =   null;
            $tmp    =   M("Activity")->where(array("aid"=>$dbActivity->aid))->find();
            if ((int)$this->uid != $tmp["uid"])
                exit("error");
        }
        elseif ($mode == 1)//php
        {
            $dbActivity->aid    =   $aid;
            $dbActivity->uid    =   $uid;
        }
        $newUid     =   (int)$dbActivity->uid;//TODO:去除重复uid的检查
        $tmp    =   null;
        $tmp    =   M("Activity")->where(array("aid"=>$dbActivity->aid))->find();
        $tmp["participant"]   =   json_decode($tmp["participant"],true);
        if ($tmp["participant"] === null)
            $tmp["participant"] = array($newUid);
        else
            array_push($tmp["participant"],$newUid);
        $tmp2   =   null;
        $tmp2   =   json_encode($tmp["participant"]);

        $tmp3   =   $dbActivity->save(array("aid"=>$dbActivity->aid,"participant"=>$tmp2));
        if ( ($tmp3 === null) || ($tmp3 === false) )
            exit("false");
        else
            exit("true");
    }



    /**
     * 当前用户的当前活动更新参与者列表（作为组织者进行）
     * @param int aid
     * @param ReturnJson_participant participant 全部的参与者列表
     * @return bool "" 是否成功
     * @return error "" 出错
     */
    public function editParticipant()
    {
        $dbActivity     =   D("Activity");

        $dbActivity->field("aid")->create(I('param.'));
        $dbActivity->participant = I('param.participant',"null",false);
        if (!$dbActivity->participantValidateRules($dbActivity->participant))
            exit("error");

        $tmp    =   $dbActivity->save(array("uid"=>$this->uid,"aid"=>$dbActivity->aid,"participant"=>$dbActivity->participant));
        if ( ($tmp === null) || ($tmp === false) )
            exit("false");
        else
            exit("true");
    }



    /**
     * 给当前用户的当前活动添加一个标签（作为组织者进行）
     * @param int aid
     * @param string tag 新增的标签
     * @return bool "" 是否成功
     */
    public function addTag()
    {
        $dbActivity     =   D("Activity");

        $dbActivity->field("aid")->create(I('param.'));
        $data["tag"]      =   I("param.tag",null);

        if (!$dbActivity->tagValidateRules($data))//TODO:去除重复tag的检查
            exit("error");

        
        $tmp    =   M("Activity")->where(array("uid"=>$this->uid,"aid"=>$dbActivity->aid))->find();
        $tmp["tag"]   =   json_decode($tmp["tag"],true);
        if ($tmp["tag"] === null)
            $tmp["tag"] = array($data["tag"]);
        else
            array_push($tmp["tag"],$data["tag"]);
        $tmp2   =   null;
        $tmp2   =   json_encode($tmp["tag"]);

        $tmp3   =   $dbActivity->save(array("uid"=>$this->uid,"aid"=>$dbActivity->aid,"tag"=>$tmp2));
        if ( ($tmp3 === null) || ($tmp3 === false) )
            exit("false");
        else
            exit("true");
    }


    /**
     * 当前用户的当前活动修改标签（作为组织者进行）
     * @param int aid
     * @param ReturnJson_tag tag 全部的标签列表
     * @return bool "" 是否成功
     * @return error "" 出错
     */
    public function editTag()
    {
        $dbActivity     =   D("Activity");

        $dbActivity->field("aid")->create(I('param.'));
        $dbActivity->tag = I('param.tag',"null",false);
        if (!$dbActivity->tagValidateRules($dbActivity->tag))
            exit("error");

        $tmp    =   $dbActivity->save(array("uid"=>$this->uid,"aid"=>$dbActivity->aid,"tag"=>$dbActivity->tag));
        if ( ($tmp === null) || ($tmp === false) )
            exit("false");
        else
            exit("true");
    }



    /**
     * 给当前用户的当前活动添加一个评论
     * @param int aid
     * @param int uid 谁发表的评论
     * @param string content 内容
     * @return bool "" 是否成功
     */
    public function addComment()
    {
        $dbActivity     =   D("Activity");

        $dbActivity->field("aid,content,uid")->create(I('param.'));
        $data["date"]      =   date("Y-m-d H:i:s");

        $tmp    =   M("Activity")->where(array("aid"=>$dbActivity->aid))->find();
        $tmp["comment"]   =   json_decode($tmp["comment"],true);
        if ($tmp["comment"] === null)
            $tmp["comment"] = array(array("content"=>$dbActivity->content,"date"=>$data["date"],"uid"=>$dbActivity->uid));
        else
            array_push($tmp["comment"],array("content"=>$dbActivity->content,"date"=>$data["date"],"uid"=>$dbActivity->uid));
        $tmp2   =   null;
        $tmp2   =   json_encode($tmp["comment"]);

        $tmp3   =   $dbActivity->save(array("aid"=>$dbActivity->aid,"comment"=>$tmp2));
        if ( ($tmp3 === null) || ($tmp3 === false) )
            exit("false");
        else
            exit("true");
    }


    /**
     * 修改全部评论，注意是全部，因为区分不出来修改第几个评论
     * @param int aid
     * @param ReturJson_comment comment 全部评论的json
     * @return bool "" 是否成功
     * @return error "" 错误
     */
    public function editComment()
    {
        $dbActivity     =   D("Activity");

        $dbActivity->field("aid")->create(I('param.'));
        $comment = I('param.comment',"null",false);
        if (!$dbActivity->commentValidateRules($check))
            exit("error");

        $tmp    =   $dbActivity->save(array("aid"=>$dbActivity->aid,"comment"=>$comment));
        if ( ($tmp === null) || ($tmp === false) )
            exit("false");
        else
            exit("true");
    }



    /**
     * 添加活动到日程中去，完成两件事情：
     *     1.添加aid活动的参与者
     *     2.添加活动到uid日程中去
     * @param int uid 将活动添加到uid那个人的日程中
     * @param int aid 哪个活动
     * @return bool "" 是否成功
     */
    public function addActivityToSchedule()
    {
        $dbActivity     =   D("Activity");
        $dbSchedule     =   D("Schedule");

        $dbActivity->field("uid,aid")->create(I("param."));//TODO:是否uid已有aid的检查
        
        $tmp    =   M("Activity")->where(array("aid"=>$dbActivity->aid))->find();

        $data   =   $tmp;
        unset($data["uid"]);
        unset($data["content"]);
        unset($data["participant"]);
        unset($data["comment"]);
        unset($data["templateNo"]);
        unset($data["brief"]);
        unset($data["heat"]);
        $data["uid"]    =   $this->uid;
        $data["content"]  =   $tmp["brief"];

        if (!$dbSchedule->add($data))
            exit("false");

        //TODO:一致性？
        $this->addParticipant(1,$dbActivity->aid,$dbActivity->uid);
        $ScheduleAction  =   A("Schedule");
        $ScheduleAction->create();
    }


    /**
     * 将活动从日程中删除，完成两件事情：
     *     1.删除aid活动的参与者
     *     2.从uid日程中删除
     * @param int uid 将活动从uid那个人的日程中删除
     * @param int aid 哪个活动
     * @return bool "" 是否成功
     */
    public function deleteActivityToSchedule()
    {
        $dbActivity     =   D("Activity");
        $dbSchedule     =   D("Schedule");

        $dbActivity->field("uid,aid")->create(I("param."));//TODO:是否uid已有aid的检查
        
        if ( !$dbSchedule->where(array("uid"=>$dbActivity->uid,"aid"=>$dbActivity->aid))->delete() )
            exit("false");

        //TODO:一致性，需要锁数据表
        $tmp    =   M("Activity")->where(array("aid"=>$dbActivity->aid))->find();
        $tmp2   =   json_decode($tmp["participant"]);
        $tmp3   =   "[";
        foreach($tmp2 as $key=>$value)
        {
            if ($value == $dbActivity->uid)
            {
                unset($tmp2[$key]);
            }
            else
                $tmp3 .= $value.",";
        }
        if (empty($tmp3))
            $tmp3 = "";
        else
            $tmp3[strlen($tmp3) - 1] = "]";
        if (!$dbActivity->save(array("aid"=>$dbActivity->aid,"participant"=>$tmp3)))
            exit("false");
        else
            exit("true");
    }



    /**
     * 得到一个活动的参与者详细信息数组；注：只能是创建者本人才有这个权限，服务器会进行验证
     * @param int aid
     * @param int mode 模式，为1返回头像logoPic字段，为0不反回
     * @return array[i][数据库字段] 详细信息数组，每一个array[i]是数据库中的一行
     */
    protected function getActivityUserArray($aid,$mode = 0)
    {
        $dbActivity     =   D("Activity");
        $dbUser         =   D("User");

        $tmp    =   $dbActivity->where(array("aid"=>$aid))->find();
        if ($tmp["uid"] != $this->uid)
            exit("error");

        $uidList    =   json_decode($tmp["participant"],true);

        $map["uid"] = arraY("in",$uidList);
        $result     =   $dbUser->where($map)->select();

        //删选出现的字段
        foreach ($result as $key1=>$value1)
        {
            foreach ($result[$key1] as $key2=>$value2)
            {
                if ( ($key2 == "uid") || ($key2 == "name") || ($key2 == "realName") || ($key2 == "phone") || ($key2 == "address") || ( ($mode == 1) && ($key2 == "logoPic") ) )
                {
                    ;
                }
                else
                {
                    unset($result[$key1][$key2]);
                }
            }
        }

        return $result;
    }

    /**
     * 查询一个活动的参与者名单；注：只能是创建者本人才有这个权限，服务器会进行验证
     * @param int aid
     * @return jsonArray 参与用户的详细信息数组，如：
     *         [
     *             {"uid":"1","name":"wbx@wbx.com","realName":"","logoPic":"","phone":"","address":""},
     *             {"uid":"2","name":"neirong1@goOneDay.com","realName":"","logoPic":"","phone":"","address":""},
     *             {"uid":"3","name":"neirong2@goOneDay.com","realName":"","logoPic":"","phone":"","address":""}
     *         ]
     * @return error "" 没有权限
     */
    public function queryActivityUser()
    {
        $dbActivity     =   D("Activity");
        $dbActivity->field("aid")->create(I("param."));

        $this->ajaxReturn($this->getActivityUserArray($dbActivity->aid,1));
    }



    /**
     * 创建一个活动的参与者名单的excel表格；注：只能是创建者本人才有这个权限，服务器会进行验证
     * @param int aid
     * @return excel文件
     * @return error "" 没有权限
     */
    public function createActivityUserExcel()
    {
        $dbActivity     =   D("Activity");
        $dbActivity->field("aid")->create(I("param."));

        $tmp    =   $dbActivity->find();
        $data   =   $this->getActivityUserArray($dbActivity->aid);
        //============================EXCEL====================================

        /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');
        
        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');
        
        /** Include PHPExcel */
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel = new \PHPExcel();
       
        // Set document properties
        $objPHPExcel->getProperties()->setCreator(_COMPANY_NAME)
        ->setLastModifiedBy(_COMPANY_NAME)
        ->setTitle($tmp["title"]."参与者名单")
        ->setSubject($tmp["title"]."参与者名单")
        ->setDescription(_COMPANY_NAME.$tmp["title"]."参与者名单")
        ->setKeywords("参与者名单")
        ->setCategory("参与者名单");
        
        $objPHPExcel->getActiveSheet()->setTitle($tmp["title"]."参与者名单");
        $objPHPExcel->setActiveSheetIndex(0);

        /**
         * 添加数据
         */
        //制作表头
        $objActSheet = $objPHPExcel->setActiveSheetIndex(0);
        
        foreach(range('A', 'Z') as $value)
        {
            $objActSheet->getColumnDimension($value)->setWidth(10);
        
            $objActSheet->getStyle($value)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActSheet->getStyle($value)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        }
         
        $objActSheet->getColumnDimension("B")->setWidth(30);
        $objActSheet->getColumnDimension("C")->setWidth(30);
        $objActSheet->getColumnDimension("D")->setWidth(30);
        $objActSheet->getColumnDimension("E")->setWidth(50);
        
        $objActSheet
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '用户名')
            ->setCellValue('C1', '真实姓名')
            ->setCellValue('D1', '电话')
            ->setCellValue('E1', '地址');        
        
        
        $objActSheet->getStyle('A1')->getFont()->setSize(12)->setBold(true);
        $objActSheet->getStyle('B1')->getFont()->setSize(12)->setBold(true);
        $objActSheet->getStyle('C1')->getFont()->setSize(12)->setBold(true);
        $objActSheet->getStyle('D1')->getFont()->setSize(12)->setBold(true);
        $objActSheet->getStyle('E1')->getFont()->setSize(12)->setBold(true);



        //写数据
        $baseOffset = 2;//基准偏移量。内容第一行真正开始的位置
        for ($i = 0; $i < count($data); $i++)//处理一个订单
        {
            $nowRow = $baseOffset + $i;//当前订单开始的第一行的行编号（excel的）。当前订单有可能是多行，但是nowRow一定是开始的第一行
            $objActSheet
            ->setCellValue('A'.$nowRow, $i + 1)
            ->setCellValue('B'.$nowRow,$data[$i]["name"])
            ->setCellValue('C'.$nowRow,$data[$i]["realName"])
            ->setCellValue('D'.$nowRow,$data[$i]["phone"])
            ->setCellValue('E'.$nowRow,$data[$i]["address"]);
        }



        /**
         * 浏览器进行输出
         */
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$tmp["title"].'参与者名单.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }


    /**
     * 赞一下
     * 做了两件事：
     *     1.给activity表的aid行的zan+1
     *     2.给user表的uid行的zanTable添加一个aid
     * @param int aid
     * @return bool "" 是否成功
     */
    public function zan()
    {
        $dbActivity     =   D("Activity");
        $dbUser     =   D("User");
        $dbActivity->field("aid")->create(I("param."));

        //TODO:检查一个用户一次
        //TODO:一致性
        if (!$dbActivity->where(array("aid"=>$dbActivity->aid))->setInc("zan"))
            exit("false");

        $tmp    =   $dbUser->where(array("uid"=>$this->uid))->find();
        $tmp["zanTable"]   =   json_decode($tmp["zanTable"]);
        if ($tmp["zanTable"] === null)
            $tmp["zanTable"] = array((int)$dbActivity->aid);
        else
            array_push($tmp["zanTable"],(int)$dbActivity->aid);
        $tmp2   =   null;
        $tmp2   =   json_encode($tmp["zanTable"]);

        $tmp3   =   $dbUser->save(array("uid"=>$this->uid,"zanTable"=>$tmp2));
        if ( ($tmp3 === null) || ($tmp3 === false) )
            exit("false");
        else
            exit("true");
    }



    /**
     * 查询用户都赞过哪些活动
     * @return json 一个json的数组，类似：[1,2,3]代表aid=1、2、3的活动都被这个用户赞过了
     * @return error "" 出错
     * @return null "" 数据为空
     */
    public function queryZan()
    {
        $dbUser     =   D("User");

        $tmp    =   $dbUser->where(array("uid"=>$this->uid))->find();

        if (empty($tmp))
            exit("error");
        elseif (empty($tmp["zanTable"]))
            exit("null");
        else
            exit($tmp["zanTable"]);
    }

}