<?php

namespace app\data\dao;

use app\data\enum\FriendEnum;
use app\data\model\FriendLogModel;

class FriendLogDao
{
	protected $friendLogModel;

	public function __construct()
	{
		$this->friendLogModel = new FriendLogModel;
	}

	/**
	 * 获取最近一条申请信息
	 *
	 * @param int    $uid
	 * @param int    $friendId
	 * @param string $field
	 *
	 * @return array
	 */
	public function getRecentApplyData(int $uid, int $friendId, string $field = '*')
	{
		$where = ['uid' => $friendId, 'friendid' => $uid, 'type' => 0];

		$info = $this->friendLogModel->where($where)->field($field)->find();
		if ($info != NULL) {
			return $info->toArray();
		} else {
			return [];
		}
	}

	/**
	 * 申请日志
	 *
	 * @param int    $uid
	 * @param int    $friendId
	 * @param int    $groupId
	 * @param string $remark
	 *
	 * @return bool
	 */
	public function addApplyLog(int $uid, int $friendId, int $groupId, string $remark)
	{
		return $this->friendLogModel->save(['uid' => $uid, 'friendid' => $friendId, 'groupid' => $groupId, 'type' => FriendEnum::APPLY, 'remark' => $remark, 'createtime' => time()]);
	}

	/**
	 * 接受日志
	 *
	 * @param int $uid
	 * @param int $friendId
	 *
	 * @return bool
	 */
	public function addAcceptLog(int $uid, int $friendId)
	{
		return $this->friendLogModel->save(['uid' => $uid, 'friendid' => $friendId, 'type' => FriendEnum::ACCEPT, 'createtime' => time()]);
	}

	/**
	 * 编辑好友请求数据
	 *
	 * @param int $id
	 * @param int $type
	 *
	 * @return bool
	 */
	public function editFriendApply(int $id, int $type)
	{
		$where = ['id' => $id];
		$data  = ['type' => $type];

		return $this->friendLogModel->where($where)->save($data);
	}

	/**
	 * 拒绝日志
	 *
	 * @param int $uid
	 * @param int $friendId
	 *
	 * @return bool
	 */
	public function addRefuseLog(int $uid, int $friendId)
	{
		return $this->friendLogModel->save(['uid' => $uid, 'friendid' => $friendId, 'type' => FriendEnum::REFUSE, 'createtime' => time()]);
	}

	/**
	 * 删除日志
	 *
	 * @param int $uid
	 * @param int $friendId
	 *
	 * @return bool
	 */
	public function addDeleteLog(int $uid, int $friendId)
	{
		return $this->friendLogModel->save(['uid' => $uid, 'friendid' => $friendId, 'type' => FriendEnum::DELETE, 'createtime' => time()]);
	}

	/**
	 * 拉黑日志
	 *
	 * @param int $uid
	 * @param int $friendId
	 *
	 * @return bool
	 */
	public function addBlackLog(int $uid, int $friendId)
	{
		return $this->friendLogModel->save(['uid' => $uid, 'friendid' => $friendId, 'type' => FriendEnum::BLACK, 'createtime' => time()]);
	}

	/**
	 * 获取列表（所有关于自己未处理过的）
	 *
	 * @param int    $uid
	 * @param int    $page
	 * @param int    $limit
	 * @param string $field
	 *
	 * @return array
	 */
	public function getListByAll(int $uid, int $page, int $limit, string $field = '*')
	{
		$list = $this->friendLogModel->where([['uid|friendid', '=', $uid], ['type', '<=', FriendEnum::REFUSE]])->order(['id' => 'desc'])->page($page, $limit)->field($field)->select();
		if ($list != NULL) {
			return $list->toArray();
		} else {
			return [];
		}
	}

	/**
	 * 获取数量（所有关于自己未处理过的）
	 *
	 * @param int $uid
	 *
	 * @return int
	 */
	public function getCountByAll(int $uid)
	{
		return $this->friendLogModel->where([['uid|friendid', '=', $uid], ['type', '<=', FriendEnum::REFUSE]])->count();
	}

}