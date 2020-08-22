<?php

namespace app\api\controller;

use app\BaseController;
use app\data\dao\FriendLogDao;
use app\data\service\FriendService;
use app\data\service\MemberService;
use think\App;

class FriendApi extends BaseController
{
	protected $request;

	public function __construct(App $app)
	{
		parent::__construct($app);
		(new MemberService)->authentication();
		$this->request = json_decode(Request()->getInput(), TRUE);
	}

	/**
	 * 添加好友
	 */
	public function addFriend()
	{
		$friendId = de($this->request['friendId']);
		$groupId  = de($this->request['groupId']);
		$remark   = $this->request['remark'];
		$uid      = getUid();

		validateV2(is_numeric($friendId), 100002);
		validateV2(is_numeric($groupId), 400001);
		//不能添加自己
		validateV2(($uid !== $friendId), 100006);

		$friendService = new FriendService;
		$friendStatus  = $friendService->friendStatus($uid, $friendId);

		//已经是好友关系
		validateV2($friendStatus !== 1, 100003);
		//被拉黑
		validateV2($friendStatus !== 4, 100004);
		//正在申请
		validateV2($friendStatus !== 0, 100005);

		$friendService->addFriend($uid, $friendId, $groupId, $remark);

		returnData();
	}

	/**
	 * 接受好友请求
	 */
	public function acceptFriend()
	{
		$friendId = de($this->request['friendId']);
		$groupId  = de($this->request['groupId']);
		$uid      = getUid();

		validateV2(is_numeric($friendId), 100002);
		validateV2(is_numeric($groupId), 400001);

		(new FriendService)->acceptFriend($uid, $friendId, $groupId);

		returnData();
	}

	/**
	 * 拒绝好友请求
	 */
	public function refuseFriend()
	{
		$friendId = de($this->request['friendId']);
		$uid      = getUid();

		validateV2(is_numeric($friendId), 100002);

		(new FriendService)->refuseFriend($uid, $friendId);

		returnData();
	}

	/**
	 * 获取用户信息盒子列表
	 */
	public function getMessageBoxList()
	{
		$page  = $this->request['page'];
		$limit = $this->request['limit'];
		$uid   = getUid();

		validateV2((is_numeric($page) && $page >= 1), 900001);
		validateV2((is_numeric($limit) && $limit >= 1 && $limit <= 20), 900002);

		$friendService  = new FriendService;
		$friendLogModel = new FriendLogDao;

		$totalCount = $friendLogModel->getCountByAll($uid);
		$totalPage  = ceil($totalCount / $limit);
		$list       = $friendService->getMessageBoxList($uid, $page, $limit);

		returnData(['list' => $list, 'totalPage' => $totalPage]);
	}

}