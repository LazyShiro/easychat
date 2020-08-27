<?php

namespace app\data\dao;

use app\data\model\RoomModel;

class RoomDao
{
	protected $roomModel;

	public function __construct()
	{
		$this->roomModel = new RoomModel;
	}

	/**
	 * 获取信息（通过房间号）
	 *
	 * @param string $number
	 * @param string $field
	 *
	 * @return array
	 */
	public function getInfoByNumber(string $number, $field = '*')
	{
		$where = ['number' => $number];
		$info  = $this->roomModel->where($where)->field($field)->find();
		if ($info != NULL) {
			return $info->toArray();
		} else {
			return [];
		}
	}

	/**
	 * 获取信息（通过id）
	 *
	 * @param int    $id
	 * @param string $field
	 *
	 * @return array
	 */
	public function getInfoById(int $id, $field = '*')
	{
		$where = ['id' => $id];
		$info  = $this->roomModel->where($where)->field($field)->find();
		if ($info != NULL) {
			return $info->toArray();
		} else {
			return [];
		}
	}

	/**
	 * 获取列表
	 *
	 * @param string $field
	 * @param int    $page
	 * @param int    $limit
	 *
	 * @return array
	 */
	public function getList(string $field = '*', int $page = 0, int $limit = 20)
	{
		$where = ['status' => 1,];

		if ($page > 0) {
			return $this->roomModel->where($where)->page($page)->limit($limit)->column($field);
		} else {
			return $this->roomModel->where($where)->column($field);
		}
	}

	/**
	 * 获取列表（通过uid查询）
	 *
	 * @param int    $uid
	 * @param string $field
	 * @param int    $page
	 * @param int    $limit
	 *
	 * @return array
	 */
	public function getListByUid(int $uid, string $field = '*', int $page = 0, int $limit = 20)
	{
		$where = ['uid' => $uid, 'status' => 1,];

		if ($page > 0) {
			return $this->roomModel->where($where)->page($page)->limit($limit)->column($field);
		} else {
			return $this->roomModel->where($where)->column($field);
		}
	}

	/**
	 * 获取列表（通过房间名模糊查询）
	 *
	 * @param string $name
	 * @param string $field
	 * @param int    $page
	 * @param int    $limit
	 *
	 * @return array
	 */
	public function getListByName(string $name, string $field = '*', int $page = 0, int $limit = 20)
	{
		$where = ['name' => ['LIKE', '%' . $name . '%'], 'status' => 1,];

		if ($page > 0) {
			return $this->roomModel->where($where)->page($page)->limit($limit)->column($field);
		} else {
			return $this->roomModel->where($where)->column($field);
		}
	}

	/**
	 * 创建房间
	 *
	 * @param string $number
	 * @param string $name
	 * @param string $description
	 * @param int    $memberMax
	 *
	 * @return bool
	 */
	public function createRoom(string $number, string $name = '', string $description = '', int $memberMax = 1)
	{
		return $this->roomModel->insertGetId(['number' => $number, 'uid' => getUid(), 'name' => $name, 'description' => $description, 'membermax' => $memberMax,]);
	}

}
