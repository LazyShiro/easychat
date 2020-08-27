<?php

namespace app\data\service;

use app\data\dao\RoomDao;
use app\data\dao\RoomLogDao;

class RoomService
{
	protected $roomModel;

	public function __construct()
	{
		$this->roomModel = new RoomDao;
	}

	public function createRoom(string $name, string $description = '此房间暂无简介', int $memberMax = 1)
	{

		//暂时废弃
		exit;
		$roomModel    = new RoomDao;
		$roomLogModel = new RoomLogDao;

		//房间名称长度>0并且<=50
		validateV2((strlen($name) > 0 && strlen($name) <= 50), '200001');
		//房间简介长度>0并且<=50
		validateV2((strlen($description) > 0 && strlen($name) <= 50), '200002');
		//房间最大容纳人数>0并且<=1000
		validateV2(($memberMax > 0 && $memberMax <= 1000), '200003');
	}

	/**
	 * 获取房间详情
	 *
	 * @param int    $uid
	 * @param int    $friendId
	 * @param string $field
	 *
	 * @return array
	 */
	public function getRoomInfo(int $uid, int $friendId, string $field = '*')
	{
		$roomNumber = getRoomNumber($uid, $friendId);
		$roomInfo   = $this->roomModel->getInfoByNumber($roomNumber, $field);
		if (empty($roomInfo)) {
			$roomId   = $this->roomModel->createRoom($roomNumber);
			$roomInfo = $this->roomModel->getInfoById($roomId, $field);
		}

		return $roomInfo;
	}

}
