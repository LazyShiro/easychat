<?php

namespace app\data\service;

use app\data\dao\FriendDao;
use app\data\dao\MemberDao;
use app\data\dao\RecordDao;
use app\data\dao\RoomDao;

class RecordService
{
	protected $recordModel;

	protected $roomModel;

	protected $friendModel;

	protected $memberModel;

	public function __construct()
	{
		$this->recordModel = new RecordDao;
		$this->roomModel   = new RoomDao;
		$this->friendModel = new FriendDao;
		$this->memberModel = new MemberDao;
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

	/**
	 * 获取所有未读消息
	 *
	 * @return array
	 */
	public function getAllNoReadChatHistory()
	{
		$uid = getUid();

		$friendList = $this->friendModel->getListByUid($uid, 'friendid');
		if (empty($friendList)) {
			return [];
		}
		$friendList = array_column($friendList, 'friendid');
		$roomNumber = [];
		foreach ($friendList as $value) {
			array_push($roomNumber, getRoomNumber($uid, $value));
		}
		$roomId = [];
		foreach ($roomNumber as $value) {
			$roomInfo = $this->roomModel->getInfoByNumber($value, 'id');
			if (empty($roomInfo)) {
				continue;
			}
			array_push($roomId, $roomInfo['id']);
		}
		if (empty($roomId)) {
			return [];
		}
		$recordList = $this->recordModel->getAllNoReadChatHistory($roomId, 'id,uid,content,createtime');
		if (empty($recordList)) {
			return [];
		}
		$list = [];
		foreach ($recordList as $value) {
			if ($value['uid'] == $uid) {
				continue;
			}
			$userInfo = $this->memberModel->getInfoById($value['uid'], 'avatar,username');
			$data     = [
				'username'  => $userInfo['username'],
				'avatar'    => getAvatar($userInfo['avatar']),
				'id'        => $value['uid'],
				'type'      => 'friend',
				'content'   => $value['content'],
				'cid'       => $value['id'],
				'fromid'    => $value['uid'],
				'mine'      => FALSE,
				'timestamp' => $value['createtime'] * 1000,
			];
			array_push($list, $data);
		}

		return $list;
	}

	/**
	 * 将好友未读消息置为已读
	 *
	 * @param $friendId
	 */
	public function readHisRecord($friendId)
	{
		$uid        = getUid();
		$roomNumber = getRoomNumber($uid, $friendId);
		$roomInfo   = $this->roomModel->getInfoByNumber($roomNumber, 'id');
		$this->recordModel->readHisRecord($roomInfo['id'], $friendId);
	}

}
