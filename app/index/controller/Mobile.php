<?php


namespace app\index\controller;


use app\BaseController;
use app\data\service\MemberService;
use think\App;
use think\facade\View;

class Mobile extends BaseController
{

	public function __construct(App $app)
	{
		parent::__construct($app);
		(new MemberService)->authentication();
		checkBrowser();
	}

	public function index()
	{
		return View::fetch();
	}
}