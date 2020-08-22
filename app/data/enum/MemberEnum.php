<?php

namespace app\data\enum;

class MemberEnum
{
	const OFFLINE = 0;
	const ONLINE  = 1;
	const STEALTH = 2;
	const BUSY    = 3;
	const LEAVE   = 4;

	/**
	 * 获取名称
	 *
	 * @param int $type
	 *
	 * @return string
	 */
	public static function getEnumName(int $type)
	{
		$name = '';
		switch ($type) {
			case self::OFFLINE:
				$name = 'offline';
				break;
			case self::ONLINE:
				$name = 'online';
				break;
			case self::STEALTH:
				$name = 'stealth';
				break;
			case self::BUSY:
				$name = 'busy';
				break;
			case self::LEAVE:
				$name = 'leave';
				break;
		}

		return $name;
	}

}