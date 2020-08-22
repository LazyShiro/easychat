<?php

namespace app\index\controller;

use app\BaseController;
use app\data\service\MemberService;
use think\App;
use think\facade\View;

class Index extends BaseController
{
	public function __construct(App $app)
	{
		parent::__construct($app);
		(new MemberService)->authentication();
		checkBrowser();
	}

	public function index()
	{
		View::assign(['uid' => getUid()]);

		return View::fetch();
	}

	public function messageBox()
	{
		return view();
	}

	public function roomList()
	{
		return view();
	}

	public function find()
	{
		return view();
	}

	public function chatHistory()
	{
		return view();
	}

	public function mobile()
	{
		return view();
	}

}
