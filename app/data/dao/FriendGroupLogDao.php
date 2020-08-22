<?php

namespace app\data\dao;

use app\data\model\FriendGroupLogModel;

class FriendGroupLogDao
{
	protected $friendGroupLogModel;

	public function __construct()
	{
		$this->friendGroupLogModel = new FriendGroupLogModel;
	}

	/**
	 * 添加创建日志
	 *
	 * @param int $groupId
	 *
	 * @return bool
	 */
	public function addCreateLog(int $groupId)
	{
		return $this->friendGroupLogModel->save(['groupid' => $groupId, 'type' => 1, 'data' => '', 'createtime' => time(),]);
	}

	/**
	 * 添加编辑日志
	 *
	 * @param int    $groupId
	 * @param string $title
	 *
	 * @return bool
	 */
	public function addEditLog(int $groupId, string $title)
	{
		return $this->friendGroupLogModel->save(['groupid' => $groupId, 'type' => 2, 'data' => $title, 'createtime' => time(),]);
	}

	/**
	 * 添加删除日志
	 *
	 * @param int $groupId
	 *
	 * @return bool
	 */
	public function addDeleteLog(int $groupId)
	{
		return $this->friendGroupLogModel->save(['groupid' => $groupId, 'type' => 0, 'data' => '', 'createtime' => time(),]);
	}

}