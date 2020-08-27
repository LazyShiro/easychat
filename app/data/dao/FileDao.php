<?php

namespace app\data\dao;

use app\data\model\FileModel;

class FileDao
{
	protected $fileModel;

	public function __construct()
	{
		$this->fileModel = new FileModel;
	}

	/**
	 * 新增文件
	 *
	 * @param string $key
	 * @param int    $uid
	 *
	 * @return int|string
	 */
	public function addFile(string $key, int $uid)
	{
		return $this->fileModel->insertGetId(['key' => $key, 'uid' => $uid, 'createtime' => time()]);
	}

	/**
	 * 获取数据
	 *
	 * @param int    $id
	 * @param string $field
	 *
	 * @return array
	 */
	public function getInfo(int $id, $field = '*')
	{
		$info = $this->fileModel->where(['id' => $id])->field($field)->find();
		if ($info != NULL) {
			return $info->toArray();
		} else {
			return [];
		}
	}

}
