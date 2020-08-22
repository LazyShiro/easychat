<?php

namespace app\data\dao;

use app\data\model\MemberModel;

class MemberDao
{
	protected $memberModel;

	public function __construct()
	{
		$this->memberModel = new MemberModel;
	}

	/**
	 * 获取数据（通过id）
	 *
	 * @param int    $id
	 * @param string $field
	 *
	 * @return array
	 */
	public function getInfoById(int $id, string $field = '*')
	{
		$where = ['id' => $id];

		$info = $this->memberModel->where($where)->field($field)->find();
		if ($info != NULL) {
			return $info->toArray();
		} else {
			return [];
		}
	}

	/**
	 * 获取数据（通过账号）
	 *
	 * @param string $account
	 * @param string $field
	 *
	 * @return array
	 */
	public function getInfoByAccount(string $account, string $field = '*')
	{
		$where = ['account' => $account];

		$info = $this->memberModel->where($where)->field($field)->find();
		if ($info != NULL) {
			return $info->toArray();
		} else {
			return [];
		}
	}

	/**
	 * 用户注册
	 *
	 * @param string $account
	 * @param string $password
	 * @param string $salt
	 *
	 * @return int|string
	 */
	public function register(string $account, string $password, string $salt)
	{
		return $this->memberModel->insertGetId(['username' => $account, 'account' => $account, 'password' => $password, 'salt' => $salt]);
	}

	/**
	 * 更改昵称
	 *
	 * @param int    $uid
	 * @param string $username
	 *
	 * @return bool
	 */
	public function editUsername(int $uid, string $username)
	{
		return $this->memberModel->where(['id' => $uid])->save(['username' => $username]);
	}

	/**
	 * 更改密码
	 *
	 * @param int    $uid
	 * @param string $password
	 *
	 * @return bool
	 */
	public function editPassword(int $uid, string $password)
	{
		return $this->memberModel->where(['id' => $uid])->save(['password' => $password]);
	}

	/**
	 * 更改头像
	 *
	 * @param int $uid
	 * @param int $avatar
	 *
	 * @return bool
	 */
	public function editAvatar(int $uid, int $avatar)
	{
		return $this->memberModel->where(['id' => $uid])->save(['avatar' => $avatar]);
	}

	/**
	 * 更改个签
	 *
	 * @param int    $uid
	 * @param string $signature
	 *
	 * @return bool
	 */
	public function editSignature(int $uid, string $signature)
	{
		return $this->memberModel->where(['id' => $uid])->save(['signature' => $signature]);
	}

	/**
	 * 更改性别
	 *
	 * @param int $uid
	 * @param int $gender
	 *
	 * @return bool
	 */
	public function editGender(int $uid, int $gender)
	{
		return $this->memberModel->where(['id' => $uid])->save(['gender' => $gender]);
	}

	/**
	 * 更改在线状态
	 *
	 * @param int $uid
	 * @param int $fettle
	 *
	 * @return bool
	 */
	public function editFettle(int $uid, int $fettle)
	{
		return $this->memberModel->where(['id' => $uid])->save(['fettle' => $fettle]);
	}

}