<?php

namespace app\data\service;

use app\data\dao\RecordDao;
use app\data\dao\RoomDao;

class RecordService
{
	protected $recordModel;

	protected $roomModel;

	public function __construct()
	{
		$this->recordModel = new RecordDao;
		$this->roomModel   = new RoomDao;
	}

	/**
	 * 获取聊天记录
	 *
	 * @param $uid
	 * @param $friendId
	 * @param $keyword
	 * @param $page
	 * @param $limit
	 *
	 * @return array
	 */
	public function getChatHistory($uid, $friendId, $keyword, $page, $limit)
	{
		$roomNumber = getRoomNumber($uid, $friendId);
		$roomInfo   = $this->roomModel->getInfoByNumber($roomNumber, 'id');
		$recordList = $this->recordModel->getListByRoomId($roomInfo['id'], $page, $limit, $keyword, 'id,uid,content,createtime');

		$list = [];
		foreach ($recordList as $value) {
			$userInfo = getUserInfo($value['uid'], 'username,avatar');
			$data     = ['recordId' => $value['id'], 'uid' => $value['uid'], 'username' => $userInfo['username'], 'avatar' => getAvatar($userInfo['avatar']), 'timestamp' => $value['createtime'] * 1000, 'content' => $value['content'],];
			array_push($list, $data);
		}

		return $list;
	}

	/**
	 * 获取聊天总数
	 *
	 * @param int    $uid
	 * @param int    $friendId
	 * @param string $keyword
	 *
	 * @return int
	 */
	public function getCountByRoomId(int $uid, int $friendId, string $keyword = '')
	{
		$roomNumber = getRoomNumber($uid, $friendId);
		$roomInfo   = $this->roomModel->getInfoByNumber($roomNumber, 'id');

		return $this->recordModel->getCountByRoomId($roomInfo['id'], $keyword);
	}

}