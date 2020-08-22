<?php

namespace app\api\controller;

use app\BaseController;
use app\data\dao\MemberDao;
use app\data\model\MemberModel;
use app\data\service\FriendGroupService;
use app\data\service\MemberService;
use think\App;

class LoginApi extends BaseController
{
	protected $request;

	public function __construct(App $app)
	{
		parent::__construct($app);
		$this->request = json_decode(Request()->getInput(), TRUE);
	}

	/**
	 * 登录
	 */
	public function login()
	{
		$account  = $this->request['account'];
		$password = $this->request['password'];

		validateV2(!empty($account), 900003);
		validateV2(!empty($password), 900003);

		$memberModel   = new MemberDao;
		$memberService = new MemberService;

		$info = $memberModel->getInfoByAccount($account, 'id,password,salt');

		if ($info) {
			if ($memberService->verifyPassword($account, $password, $info['password'])) {
				$token = $memberService->generateToken($info['id'], $account, $info['salt']);
				returnData(['token' => $token]);
			} else {
				returnData(900003);
			}
		}
		returnData(100001);
	}

	/**
	 * 注册
	 *
	 */
	public function register()
	{
		$account  = $this->request['account'];
		$password = $this->request['password'];

		validateV2(!empty($account), 900003);
		validateV2(!empty($password), 900003);

		$memberModel   = new MemberDao;
		$memberService = new MemberService;

		$info = $memberModel->getInfoByAccount($account, 'password');

		if (!$info) {
			$uid = $memberService->register($account, $password);
			if ($uid) {
				sleep(1);
				returnData();
			} else {
				returnData(900004);
			}
		} else {
			returnData(900005);
		}
	}

	public function editSignature()
	{

	}

}