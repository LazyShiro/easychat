<?php

namespace app\data\dao;

use app\data\enum\FriendEnum;
use app\data\model\FriendModel;

class FriendDao
{
	protected $friendModel;

	public function __construct()
	{
		$this->friendModel = new FriendModel;
	}

	/**
	 * 获取列表（通过uid）
	 *
	 * @param int    $uid
	 * @param string $field
	 *
	 * @return array
	 */
	public function getListByUid(int $uid, string $field = '*')
	{
		$where = ['uid' => $uid, 'status' => FriendEnum::ACCEPT];
		$list  = $this->friendModel->where($where)->field($field)->select();
		if ($list != NULL) {
			return $list->toArray();
		} else {
			return [];
		}
	}

	/**
	 * 获取列表（通过好友分组id）
	 *
	 * @param int    $groupId
	 * @param string $field
	 *
	 * @return array
	 */
	public function getListByGroupId(int $groupId, string $field = '*')
	{
		$where = ['groupid' => $groupId, 'status' => FriendEnum::ACCEPT];
		$list  = $this->friendModel->where($where)->field($field)->select();
		if ($list != NULL) {
			return $list->toArray();
		} else {
			return [];
		}
	}

	/**
	 * 获取数据（通过用户id好友id）
	 *
	 * @param int    $uid
	 * @param int    $friendId
	 * @param string $field
	 *
	 * @return array
	 */
	public function getInfoByUidFriend(int $uid, int $friendId, string $field = '*')
	{
		$where = ['uid' => $uid, 'friendid' => $friendId];
		$info  = $this->friendModel->where($where)->field($field)->find();
		if ($info != NULL) {
			return $info->toArray();
		} else {
			return [];
		}
	}

	/**
	 * 添加好友（申请）
	 *
	 * @param int $uid
	 * @param int $friendId
	 * @param int $groupId
	 *
	 * @return bool
	 */
	public function addFriend(int $uid, int $friendId, int $groupId)
	{
		return $this->friendModel->save(['uid' => $uid, 'groupid' => $groupId, 'friendid' => $friendId, 'status' => FriendEnum::APPLY]);
	}

	/**
	 * 添加好友（通过）
	 *
	 * @param int $uid
	 * @param int $friendId
	 * @param int $groupId
	 *
	 * @return bool
	 */
	public function acceptFriend(int $uid, int $friendId, int $groupId)
	{
		$this->friendModel->where(['uid' => $friendId, 'friendid' => $uid])->save(['status' => FriendEnum::ACCEPT]);
		$this->friendModel->save(['friendid' => $uid, 'uid' => $friendId, 'groupid' => $groupId, 'status' => FriendEnum::ACCEPT]);

		return TRUE;
	}

	/**
	 * 拒绝好友
	 *
	 * @param int $uid
	 * @param int $friendId
	 *
	 * @return bool
	 */
	public function refuseFriend(int $uid, int $friendId)
	{
		return $this->friendModel->where(['uid' => $friendId, 'friendid' => $uid])->save(['status' => FriendEnum::REFUSE]);
	}

	/**
	 * 删除好友
	 *
	 * @param int $uid
	 * @param int $friendId
	 *
	 * @return bool
	 */
	public function deleteFriend(int $uid, int $friendId)
	{
		return $this->friendModel->where(['uid' => $friendId, 'friendid' => $uid])->save(['status' => FriendEnum::DELETE]);
	}

	/**
	 * 拉黑好友
	 *
	 * @param int $uid
	 * @param int $friendId
	 *
	 * @return bool
	 */
	public function blackFriend(int $uid, int $friendId)
	{
		return $this->friendModel->where(['uid' => $friendId, 'friendid' => $uid])->save(['status' => FriendEnum::BLACK]);
	}

}