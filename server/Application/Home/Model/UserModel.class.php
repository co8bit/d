<?php
namespace Home\Model;
use Think\Model;

class UserModel extends Model
{

	public function init()
	{
	}
	
	// 自动验证设置
	// protected $_validate = array(
	// 		array('userName', 'require', '用户名不能为空！'),
	// 		array('userName','','用户名已经存在！',0,'unique',Model::MODEL_BOTH), //验证name字段是否唯一
	// 		array('userPassword', 'require', '密码不能为空！', 0),
	// 		array('userPassword2', 'require', '请输入第二遍密码', 0),
	// 		array('userPassword','userPassword2','两次输入的密码不一样',0,'confirm',Model::MODEL_BOTH), // 验证确认密码是否和密码一致
	// );
	//uid
	
	/**
	 * 验证uid是否合法
	 * @return bool 是否合法
	 */
	public function uidValidateRules()
	{
		return true;
	}
	
	
	/**
	 * 判断用户名和密码是否能登录
	 * @param 	string $userName;用户名
	 * 			string $userPassword 用户密码
	 * @return  array[],数据库返回的结果集
	 */
	public function login($userName,$userPassword)
	{
		$condition['name'] = $userName;
		$condition['pwd'] = $userPassword;
		$tmp = $this->where($condition)->find();
		if (!empty($tmp))
			return $tmp;
		else
			return false;
	}
	
	
	/**
	 * 得到指定用户的用户信息
	 * @param	string $uid
	 * @return	array[];
	 * 				查询成功返回用户所有字段的数组
	 * 				没查到null、false查询错误返回false
	 */
	public function getUserInfo($uid)
	{
		$tmp = $this->where(array("uid"=>$uid))->find();
		if (!empty($tmp))
			return $tmp;
		else
			return false;
	}
	
}
?>