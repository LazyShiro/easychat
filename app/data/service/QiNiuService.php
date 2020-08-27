<?php

namespace app\data\service;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class QiNiuService
{
	protected $accessKey;

	protected $secretKey;

	protected $bucket;

	protected $avatarPath = 'path';

	public function __construct()
	{
		$this->accessKey = 'AK';
		$this->secretKey = 'SK';
		$this->bucket    = 'bucket';
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
