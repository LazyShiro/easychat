<?php

namespace app\data\service;

use app\data\dao\FriendGroupDao;
use app\data\dao\FriendGroupLogDao;

class FriendGroupService
{
	/**
	 * 初始化系统好友分组
	 *
	 * @param int $uid
	 */
	public function initSystemFriendGroup(int $uid)
	{
		$friendGroupModel    = new FriendGroupDao;
		$friendGroupLogModel = new FriendGroupLogDao;

		//初始化系统好友分组
		$friendGroupId = $friendGroupModel->initSystemGroup($uid);
		//创建好友分组日志
		$friendGroupLogModel->addCreateLog($friendGroupId);
	}

}