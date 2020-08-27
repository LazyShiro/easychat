<?php

namespace app\data\service;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class QiNiuService
{
	protected $accessKey;

	protected $secretKey;

	protected $bucket;

	protected $avatarPath = 'chat/avatar/';

	public function __construct()
	{
		$this->accessKey = env('qiniu.access_key');
		$this->secretKey = env('qiniu.secret_key');
		$this->bucket    = env('qiniu.bucket');
	}

	public function uploadAvatar($name, $path)
	{
		$token         = $this->getToken();
		$uploadManager = new UploadManager;
		$data          = $uploadManager->putFile($token, $this->avatarPath . $name, $path);

		return $data[0]['key'];
	}

	/**
	 * 生成上传凭证
	 *
	 * @return string
	 */
	private function getToken()
	{
		$auth = new Auth($this->accessKey, $this->secretKey);

		return $auth->uploadToken($this->bucket);
	}

}
