<?php

namespace app\api\controller;

use app\BaseController;
use app\data\dao\FriendDao;
use app\data\dao\MemberDao;
use app\data\dao\RecordDao;
use app\data\enum\MemberEnum;
use app\data\service\FriendService;
use app\data\service\MemberService;
use app\data\service\RecordService;
use app\data\service\RoomService;
use think\App;

class ChatApi extends BaseController
{
	protected $request;

	public function __construct(App $app)
	{
		parent::__construct($app);
		(new MemberService)->authentication();
		$this->request = json_decode(Request()->getInput(), TRUE);
		if (Request()->action() !== 'getMainData') {
			validateV2(!empty($this->request), 900006);
		}
	}

	/**
	 * 获取主体数据
	 *
	 * @param MemberDao     $memberDao
	 * @param FriendService $friendService
	 */
	public function getMainData(MemberDao $memberDao, FriendService $friendService)
	{
		$uid = getUid();
		//我的基本信息
		$myInfo = $memberDao->getInfoById($uid, 'id,username,fettle,signature,avatar');
		//好友列表
		$friendList = $friendService->getFriendList($uid);

		$data = ["mine" => ["id" => $myInfo['id'], "username" => $myInfo['username'], "status" => MemberEnum::getEnumName($myInfo['fettle']), "sign" => $myInfo['signature'], "avatar" => getAvatar($myInfo['avatar']),], "friend" => $friendList, "group" => [["groupname" => "前端群", "id" => "101", "avatar" => "//tva1.sinaimg.cn/crop.0.0.200.200.50/006q8Q6bjw8f20zsdem2mj305k05kdfw.jpg",], ["groupname" => "Fly社区官方群", "id" => "102", "avatar" => "//tva2.sinaimg.cn/crop.0.0.199.199.180/005Zseqhjw1eplix1brxxj305k05kjrf.jpg",],],];

		returnData($data);
	}

	/**
	 * 获取聊天记录
	 */
	public function getChatHistory()
	{
		$friendId = de($this->request['friendId']);
		$keyword  = removeXSS($this->request['keyword']);
		$page     = $this->request['page'];
		$limit    = $this->request['limit'];
		$uid      = getUid();

		validateV2(is_numeric($friendId), 100002);
		validateV2(is_numeric($page), 900001);
		validateV2(is_numeric($limit), 900002);

		$recordService = new RecordService;

		$list       = $recordService->getChatHistory($uid, $friendId, $keyword, $page, $limit);
		$totalCount = $recordService->getCountByRoomId($uid, $friendId, $keyword);

		returnData(['list' => $list, 'totalCount' => $totalCount]);
	}

}