<?php

namespace app\api\controller;

use app\BaseController;
use app\data\service\MemberService;
use think\App;

class RoomApi extends BaseController
{
	protected $request;

	public function __construct(App $app)
	{
		parent::__construct($app);
		(new MemberService)->authentication();
		$this->request = json_decode(Request()->getInput(), TRUE);
	}

	public function getList()
	{

		$page  = $this->request['page'] ? : 1;
		$limit = $this->request['limit'] ? : 10;

		echo getUid();
	}

}