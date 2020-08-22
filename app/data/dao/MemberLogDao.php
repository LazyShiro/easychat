<?php

namespace app\data\dao;

use app\data\model\MemberLogModel;

class MemberLogDao
{
	protected $memberLogModel;

	public function __construct()
	{
		$this->memberLogModel = new MemberLogModel;
	}

	/**
	 * 注册记录
	 *
	 * @param int $uid
	 *
	 * @return bool
	 */
	public function addRegisterLog(int $uid)
	{
		return $this->memberLogModel->save(['uid' => $uid, 'type' => 1, 'data' => '', 'createtime' => time()]);
	}

	/**
	 * 昵称记录
	 *
	 * @param int    $uid
	 * @param string $username
	 *
	 * @return bool
	 */
	public function addUsernameLog(int $uid, string $username)
	{
		return $this->memberLogModel->save(['uid' => $uid, 'type' => 2, 'data' => $username, 'createtime' => time()]);
	}

	/**
	 * 密码记录
	 *
	 * @param int    $uid
	 * @param string $password
	 *
	 * @return bool
	 */
	public function addPasswordLog(int $uid, string $password)
	{
		return $this->memberLogModel->save(['uid' => $uid, 'type' => 3, 'data' => $password, 'createtime' => time()]);
	}

	/**
	 * 头像记录
	 *
	 * @param int $uid
	 * @param int $avatar
	 *
	 * @return bool
	 */
	public function addAvatarLog(int $uid, int $avatar)
	{
		return $this->memberLogModel->save(['uid' => $uid, 'type' => 4, 'data' => $avatar, 'createtime' => time()]);
	}

	/**
	 * 个签记录
	 *
	 * @param int    $uid
	 * @param string $signature
	 *
	 * @return bool
	 */
	public function addSignatureLog(int $uid, string $signature)
	{
		return $this->memberLogModel->save(['uid' => $uid, 'type' => 5, 'data' => $signature, 'createtime' => time()]);
	}

	/**
	 * 性别记录
	 *
	 * @param int $uid
	 * @param int $gender
	 *
	 * @return bool
	 */
	public function addGenderLog(int $uid, int $gender)
	{
		return $this->memberLogModel->save(['uid' => $uid, 'type' => 6, 'data' => $gender, 'createtime' => time()]);
	}

	/**
	 * 在线状态记录
	 *
	 * @param int $uid
	 * @param int $fettle
	 *
	 * @return bool
	 */
	public function addFettleLog(int $uid, int $fettle)
	{
		return $this->memberLogModel->save(['uid' => $uid, 'type' => 7, 'data' => $fettle, 'createtime' => time()]);
	}

}