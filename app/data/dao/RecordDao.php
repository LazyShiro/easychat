<?php

namespace app\data\dao;

use app\data\model\RecordModel;

class RecordDao
{
	protected $recordModel;

	public function __construct()
	{
		$this->recordModel = new RecordModel;
	}

	/**
	 * 获取列表（通过房间id）
	 *
	 * @param int    $roomId
	 * @param int    $page
	 * @param int    $limit
	 * @param string $keyword
	 * @param string $field
	 * @param string $order
	 *
	 * @return array
	 */
	public function getListByRoomId(int $roomId, int $page, int $limit, string $keyword = '', string $field = '*', string $order = 'desc')
	{
		$where = [['roomid', '=', $roomId], ['status', '=', 1],];

		if (!empty($keyword)) {
			$where[] = ['content', 'like', "%{$keyword}%"];
		}
		if ($page > 0) {
			$list = $this->recordModel->where($where)->page($page)->limit($limit)->field($field)->order(['id' => $order])->select();
		} else {
			$list = $this->recordModel->where($where)->field($field)->order(['id' => $order])->select();
		}

		if ($list != NULL) {
			return $list->toArray();
		} else {
			return [];
		}
	}

	/**
	 * 获取数量（通过房间id）
	 *
	 * @param int    $roomId
	 * @param string $keyword
	 *
	 * @return int
	 */
	public function getCountByRoomId(int $roomId, string $keyword = '')
	{
		$where = [['roomid', '=', $roomId], ['status', '=', 1],];

		if (!empty($keyword)) {
			$where[] = ['content', 'like', "%{$keyword}%"];
		}

		return $this->recordModel->where($where)->count();
	}

}