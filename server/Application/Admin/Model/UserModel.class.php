<?php
namespace Admin\Model;
use Think\Model;

class UserModel extends Model
{

	public function init()
	{
	}
	
	
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
	 * @param	int $uid
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