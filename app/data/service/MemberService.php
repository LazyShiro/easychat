<?php

namespace app\data\service;

use app\data\dao\MemberDao;
use app\data\dao\MemberLogDao;

class MemberService
{
	/**
	 * 用户注册
	 *
	 * @param string $account
	 * @param string $password
	 *
	 * @return int|string
	 */
	public function register(string $account, string $password)
	{
		$memberModel        = new MemberDao;
		$memberLogModel     = new MemberLogDao;
		$friendGroupService = new FriendGroupService;

		//生成密码盐
		$salt = $this->generateSalt($account, $password);
		//生成数据库密码
		$dbPassword = $this->generatePassword($account, $salt);
		//注册账号
		$uid = $memberModel->register($account, $dbPassword, $salt);
		//注册日志
		$memberLogModel->addRegisterLog($uid);
		//初始化系统好友分组
		$friendGroupService->initSystemFriendGroup($uid);

		return $uid;
	}

	/**
	 * 账号密码验证
	 *
	 * @param $account
	 * @param $password
	 * @param $dbPassword
	 *
	 * @return bool
	 */
	public function verifyPassword($account, $password, $dbPassword)
	{
		//生成密码盐
		$salt = $this->generateSalt($account, $password);
		//生成密码
		$generatePassword = $this->generatePassword($account, $salt);

		if ($generatePassword == $dbPassword) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * 生成密码盐
	 *
	 * @param string $account
	 * @param string $password
	 *
	 * @return bool|false|string
	 */
	public function generateSalt(string $account, string $password)
	{
		return substr(md5($account . $password), 5, 10);
	}

	/**
	 * 生成密码
	 *
	 * @param      $account
	 * @param bool $salt
	 *
	 * @return string
	 */
	public function generatePassword($account, $salt = FALSE)
	{
		if ($salt) {
			$password = md5($account . $salt);
		} else {
			$password = md5($account . md5($account, TRUE));
		}

		return $password;
	}

	/**
	 * 鉴权
	 *
	 * @return mixed
	 */
	public function authentication()
	{
		$token = \cookie('token');
		if (!$token) {
			exit(errorPage(100001, TRUE));
		}

		$token = $this->tokenNormalization($token);
		if (!$token) {
			exit(errorPage(100001, TRUE));
		}

		$token = de($token);
		$token = json_decode($token, TRUE);
		if (!$token) {
			exit(errorPage(100001, TRUE));
		}

		if (de($token['sign']) !== $token['id'] . $token['account'] . $token['logintime']) {
			exit(errorPage(100001, TRUE));
		}

		return $token;
	}

	/**
	 * 生成token
	 *
	 * @param $id
	 * @param $account
	 * @param $salt
	 *
	 * @return string|string[]
	 */
	public function generateToken($id, $account, $salt)
	{
		$cookie = ['id' => en($id), 'account' => $account, 'salt' => $salt, 'logintime' => time(),];

		$cookie['sign'] = en($cookie['id'] . $account . $cookie['logintime']);
		$token          = en(json_encode($cookie));
		//处理token
		$token = $this->tokenMachining($token);

		return $token;
	}

	/**
	 * token匹配
	 *
	 * @param $token
	 *
	 * @return string|string[]
	 */
	private function tokenMachining($token)
	{
		//左斜杠用[2]#1[1]代替
		$token = str_replace("/", rand(10, 99) . "#1" . rand(0, 9), $token);
		//右斜杠用[1]##2[2]代替
		$token = str_replace("\\", rand(0, 9) . "##2" . rand(10, 99), $token);

		return $token;
	}

	/**
	 * token反序列化
	 *
	 * @param $token
	 *
	 * @return mixed
	 */
	private function tokenNormalization($token)
	{
		//特殊字符串1首次出现的位置
		$slash1Position = strpos($token, '#1');
		//特殊字符串2首次出现的位置
		$slash2Position = strpos($token, '##2');
		if ($slash1Position !== FALSE) {
			//截取特殊字符串1之前的字符串
			$str1 = substr($token, 0, $slash1Position - 2);
			//截取特殊字符串1之后的字符串
			$str2 = substr($token, $slash1Position + 3);
			//拼接
			$token = $str1 . '/' . $str2;
			//递归验证
			$token = $this->tokenNormalization($token);
		}
		if ($slash2Position !== FALSE) {
			//截取特殊字符串2之前的字符串
			$str1 = substr($token, 0, $slash2Position - 1);
			//截取特殊字符串2之后的字符串
			$str2 = substr($token, $slash2Position + 5);
			//拼接
			$token = $str1 . $str2;
			//递归验证
			$token = $this->tokenNormalization($token);
		}

		return $token;
	}

}