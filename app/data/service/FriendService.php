<?php

namespace app\data\service;

use app\data\dao\FriendDao;
use app\data\dao\FriendGroupDao;
use app\data\dao\FriendLogDao;
use app\data\dao\MemberDao;
use app\data\enum\FriendEnum;
use app\data\enum\MemberEnum;

class FriendService
{
	/**
	 * 好友列表
	 *
	 * @param int $uid
	 *
	 * @return array
	 */
	public function getFriendList(int $uid)
	{
		$friendModel      = new FriendDao;
		$memberModel      = new MemberDao;
		$friendGroupModel = new FriendGroupDao;

		//好友分组列表
		$friendGroupList = $friendGroupModel->getListByUid($uid, 'id,title as groupname');
		foreach ($friendGroupList as &$value) {
			//组下好友列表
			$friendList = $friendModel->getListByGroupId($value['id'], 'friendid');
			foreach ($friendList as &$v) {
				//好友基本信息
				$userInfo           = $memberModel->getInfoById($v['friendid'], 'id,username,avatar,signature,fettle');
				$userInfo['avatar'] = getAvatar($userInfo['avatar']);
				$userInfo['status'] = MemberEnum::getEnumName($userInfo['fettle']);
				unset($userInfo['fettle']);
				$v = $userInfo;
			}
			$value['list'] = $friendList;
		}

		return $friendGroupList;
	}

	/**
	 * 添加好友
	 *
	 * @param int    $uid
	 * @param int    $friendId
	 * @param int    $groupId
	 * @param string $remark
	 */
	public function addFriend(int $uid, int $friendId, int $groupId, string $remark)
	{
		$friendModel    = new FriendDao;
		$friendLogModel = new FriendLogDao;

		//添加好友
		$friendModel->addFriend($uid, $friendId, $groupId);
		//添加好友日志
		$friendLogModel->addApplyLog($uid, $friendId, $groupId, $remark);
	}

	/**
	 * 接受好友请求
	 *
	 * @param int $uid
	 * @param int $friendId
	 * @param int $groupId
	 */
	public function acceptFriend(int $uid, int $friendId, int $groupId)
	{
		$friendModel    = new FriendDao;
		$friendLogModel = new FriendLogDao;

		//接受好友申请
		$friendModel->acceptFriend($uid, $friendId, $groupId);
		//对方发起的好友申请信息
		$friendApplyInfo = $friendLogModel->getRecentApplyData($uid, $friendId, 'id');
		//修改好友申请为已通过
		$friendLogModel->editFriendApply($friendApplyInfo['id'], FriendEnum::ACCEPT);
	}

	/**
	 * 拒绝好友请求
	 *
	 * @param int $uid
	 * @param int $friendId
	 */
	public function refuseFriend(int $uid, int $friendId)
	{
		$friendModel    = new FriendDao;
		$friendLogModel = new FriendLogDao;

		//拒绝好友申请
		$friendModel->refuseFriend($uid, $friendId);
		//对方发起的好友申请信息
		$friendApplyInfo = $friendLogModel->getRecentApplyData($uid, $friendId, 'id');
		//修改好友申请为已拒绝
		$friendLogModel->editFriendApply($friendApplyInfo['id'], FriendEnum::REFUSE);
	}

	/**
	 * 获取用户消息盒子列表
	 *
	 * @param int $uid
	 * @param int $page
	 * @param int $limit
	 *
	 * @return array
	 */
	public function getMessageBoxList(int $uid, int $page, int $limit)
	{
		$friendLogModel = new FriendLogDao;
		$memberModel    = new MemberDao;

		//好友关系消息列表
		$list = $friendLogModel->getListByAll($uid, $page, $limit);

		$messageList = [];
		foreach ($list as $value) {
			//如果当前数据是当前用户发起的
			if ($value['uid'] === $uid) {
				$from          = NULL;
				$friendId      = $value['friendid'];
				$friendInfo    = $memberModel->getInfoById($friendId, 'username,avatar,signature');
				$applyContent  = "你已经提交对{$friendInfo['username']}的好友申请";
				$acceptContent = "{$friendInfo['username']}已<b class='green'>同意</b>了添加你为好友";
				$refuseContent = "{$friendInfo['username']}已<b class='red'>拒绝</b>了你的好友请求";
			} else {
				if ($value['type'] != 0) {
					$from = NULL;
				} else {
					$from = $uid;
				}
				$friendId      = $value['uid'];
				$friendInfo    = $memberModel->getInfoById($friendId, 'username,avatar,signature');
				$applyContent  = "{$friendInfo['username']}申请添加你为好友";
				$acceptContent = "你已经<b class='green'>同意</b>了{$friendInfo['username']}的好友申请";
				$refuseContent = "你已经<b class='red'>拒绝</b>了{$friendInfo['username']}的好友申请";
			}
			$pushData['id'] = $value['id'];
			if ($value['type'] === 0) {
				$pushData['content'] = $applyContent;
			} elseif ($value['type'] === 1) {
				$pushData['content'] = $acceptContent;
			} elseif ($value['type'] === 2) {
				$pushData['content'] = $refuseContent;
			}
			$pushData['uid']        = $uid;
			$pushData['from']       = $from;
			$pushData['from_group'] = $value['groupid'];
			$pushData['type']       = 1;
			$pushData['remark']     = $value['remark'];
			$pushData['href']       = NULL;
			$pushData['read']       = 1;
			$pushData['time']       = dateFormat($value['createtime']);
			$pushData['user']       = ['id' => $friendId, 'avatar' => getAvatar($friendInfo['avatar']), 'username' => $friendInfo['username'], 'signature' => $friendInfo['signature'],];

			array_push($messageList, $pushData);
		}

		return $messageList;
	}

	/**
	 * 判断是否为好友关系
	 *
	 * @param int $uid
	 * @param int $friendId
	 *
	 * @return mixed
	 */
	public function friendStatus(int $uid, int $friendId)
	{
		$friendModel = new FriendDao;

		$info = $friendModel->getInfoByUidFriend($uid, $friendId, 'status');

		if (empty($info)) {
			return NULL;
		} else {
			return $info['status'];
		}
	}

}