<?php

namespace app\data\dao;

use app\data\model\FriendGroupModel;

class FriendGroupDao
{
	protected $friendGroupModel;

	public function __construct()
	{
		$this->friendGroupModel = new FriendGroupModel;
	}

	/**
	 * 初始化系统好友分组
	 *
	 * @param int $uid
	 *
	 * @return bool
	 */
	public function initSystemGroup(int $uid)
	{
		return $this->friendGroupModel->save(['uid' => $uid, 'title' => '我的好友', 'type' => 0, 'status' => 1,]);
	}

	/**
	 * 获取好友分组列表
	 *
	 * @param int    $uid
	 * @param string $field
	 *
	 * @return array
	 */
	public function getListByUid(int $uid, string $field = '*')
	{
		$where = ['uid' => $uid, 'status' => 1];
		$order = ['type' => 'asc'];
		$list  = $this->friendGroupModel->where($where)->order($order)->field($field)->select();
		if ($list != NULL) {
			return $list->toArray();
		} else {
			return [];
		}
	}

	/**
	 * 更新好友分组名称
	 *
	 * @param int    $id
	 * @param string $title
	 *
	 * @return bool
	 */
	public function editTitle(int $id, string $title)
	{
		return $this->friendGroupModel->where(['id' => $id])->save(['title' => $title]);
	}

	/**
	 * 获取数据
	 *
	 * @param int    $id
	 * @param string $field
	 *
	 * @return array
	 */
	public function getData(int $id, string $field = '*')
	{
		$where = ['id' => $id];
		$info  = $this->friendGroupModel->where($where)->field($field)->find();
		if ($info != NULL) {
			return $info->toArray();
		} else {
			return [];
		}
	}

}