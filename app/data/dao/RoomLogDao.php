<?php

namespace app\data\dao;

use app\data\model\RoomLogModel;

class RoomLogDao
{
	protected $roomLogModel;

	public function __construct()
	{
		$this->roomLogModel = new RoomLogModel;
	}

	/**
	 * 保存创建房间日志
	 *
	 * @param int $roomId
	 *
	 * @return bool
	 */
	public function addCreateLog(int $roomId)
	{
		return $this->roomLogModel->save(['roomid' => $roomId, 'type' => 1, 'createtime' => time()]);
	}

	/**
	 * 保存删除房间日志
	 *
	 * @param int $roomId
	 *
	 * @return bool
	 */
	public function addDeleteLog(int $roomId)
	{
		return $this->roomLogModel->save(['roomid' => $roomId, 'type' => 0, 'createtime' => time()]);
	}

	/**
	 * 保存异常房间日志
	 *
	 * @param int $roomId
	 *
	 * @return bool
	 */
	public function addAbnormalLog(int $roomId)
	{
		return $this->roomLogModel->save(['roomid' => $roomId, 'type' => 2, 'createtime' => time()]);
	}

}