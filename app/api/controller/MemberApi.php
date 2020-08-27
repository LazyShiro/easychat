<?php

namespace app\api\controller;

use app\BaseController;
use app\data\dao\MemberDao;
use app\data\dao\MemberLogDao;
use app\data\service\MemberService;
use think\App;

class MemberApi extends BaseController
{
	protected $request;

	protected $file;

	public function __construct(App $app)
	{
		parent::__construct($app);
		(new MemberService)->authentication();
		$this->request = json_decode(Request()->getInput(), TRUE);
		$this->file    = Request()->file();
	}

	/**
	 * 获取用户信息（通过账号）
	 */
	public function getMemberInfo()
	{
		$account = $this->request['account'];
		$info    = (new MemberDao)->getInfoByAccount($account, 'id,username,avatar');

		if ($info) {
			$info['avatar'] = getAvatar($info['avatar']);
		} else {
			$info = 100001;
		}
		returnData($info);
	}

	/**
	 * 修改用户名
	 */
	public function editUsername()
	{
		$username = $this->request['username'];
	}

	/**
	 * 修改密码
	 */
	public function editPassword()
	{
		$password = $this->request['password'];
	}

	/**
	 * 修改头像
	 */
	public function editAvatar()
	{
		$base64 = urldecode($this->request['img']);

		preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result);

		validateV2(in_array($result[2], ['jpg', 'jpeg', 'png', 'gif']), 900007);

		$base64Content = str_replace($result[1], '', $base64);
		$type          = $result[2];

		returnData((new MemberService)->uploadAvatar($base64Content, $type));
	}

	/**
	 * 修改个签
	 */
	public function editSignature()
	{
		$signature = $this->request['signature'];

		$memberModel    = new MemberDao;
		$memberLogModel = new MemberLogDao;

		$uid = getUid();

		$memberModel->editSignature($uid, $signature);
		$memberLogModel->addSignatureLog($uid, $signature);

		returnData();
	}

	/**
	 * 修改性别
	 */
	public function editGender()
	{
		$gender = $this->request['gender'];
	}

	/**
	 * 修改在线状态
	 */
	public function editFettle()
	{
		$fettle = $this->request['fettle'];
	}

}
