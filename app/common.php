<?php

use app\data\dao\MemberDao;
use app\data\dao\FileDao;
use app\data\service\MemberService;

// 应用公共文件

/**
 * 加密
 *
 * @param        $data
 * @param string $key
 * @param int    $expire
 *
 * @return string|string[]
 */
function think_encrypt($data, $key = '', $expire = 0)
{
	$key  = md5(empty ($key) ? config('app.global_auth_key') : $key);
	$data = base64_encode($data);
	$x    = 0;
	$len  = strlen($data);
	$l    = strlen($key);
	$char = '';
	for ($i = 0; $i < $len; $i ++) {
		if ($x == $l) $x = 0;
		$char .= substr($key, $x, 1);
		$x ++;
	}
	$str = sprintf('%010d', $expire ? $expire + time() : 0);
	for ($i = 0; $i < $len; $i ++) {
		$str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
	}

	return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($str));
}

/**
 * 解密
 *
 * @param        $data
 * @param string $key
 *
 * @return false|string
 */
function think_decrypt($data, $key = '')
{
	$key  = md5(empty ($key) ? config('app.global_auth_key') : $key);
	$data = str_replace(['-', '_', ''], ['+', '/', '='], $data);
	$mod4 = strlen($data) % 4;
	if ($mod4) {
		$data .= substr('====', $mod4);
	}
	$data   = base64_decode($data);
	$expire = substr($data, 0, 10);
	$data   = substr($data, 10);
	if ($expire > 0 && $expire < time()) {
		return '';
	}
	$x    = 0;
	$len  = strlen($data);
	$l    = strlen($key);
	$char = $str = '';
	for ($i = 0; $i < $len; $i ++) {
		if ($x == $l) $x = 0;
		$char .= substr($key, $x, 1);
		$x ++;
	}
	for ($i = 0; $i < $len; $i ++) {
		if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
			$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
		} else {
			$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
		}
	}

	return base64_decode($str);
}

/**
 * 简单加密
 *
 * @param        $data
 * @param string $key
 * @param int    $expire
 *
 * @return string|string[]
 */
function en($data, $key = '', $expire = 0)
{
	return think_encrypt($data, $key, $expire);
}

/**
 * 简单解密
 *
 * @param        $data
 * @param string $key
 *
 * @return false|int|string
 */
function de($data, $key = '')
{
	if (is_numeric($data)) {
		return $data;
	}
	if (empty($data)) {
		return $data;
	}

	return think_decrypt($data, $key);
}

/**
 * 异步输出
 *
 * @param $data
 */
function ajaxReturn($data)
{
	header('Content-Type:application/json; charset=utf-8');
	exit (json_encode(unserialize(str_replace(['NAN;', 'INF;'], '0;', serialize($data)))));
}

/**
 * 错误页面
 *
 * @param int  $code
 * @param bool $type
 *
 * @return string
 */
function errorPage($code = 100001, $type = FALSE)
{
	$string = '';
	$config = config('errorpage.' . $code);
	$string .= $config[0] . "[" . $code . "]";

	if ($type) {
		$string = "<script>if(confirm('{$string}')){window.location.href = '{$config[1]}'};</script>";
		//		$string = "<script>if(confirm({$string}){window.location.href = '{$config[1]}'}</script>";
	}

	return $string;
}

/**
 * 获取uid
 *
 * @return false|int|string
 */
function getUid()
{
	return de((new MemberService)->authentication()['id']);
}

/**
 * 不满足情况，提示错误信息
 *
 * @param $value
 * @param $code
 */
function validateV2($value, $code)
{
	if ($value !== TRUE) {
		returnData($code);
	}
}

/**
 * 接口公共返回
 *
 * @param array $resource
 * @param int   $code
 */
function returnData($resource = [], $code = 100000)
{
	if (is_numeric($resource)) {
		$code = $resource;
		$data = (object) [];
	} else {
		$data = is_null($resource) ? (object) [] : $resource;
	}

	$message    = config('errorcode.' . $code);
	$messageStr = !$message ? '错误码未找到，错误码为' . $code : $message;

	if ($code == 100000) {
		$status = 1;
	} else {
		$status = 0;
	}

	$returnData['status']  = $status;
	$returnData['code']    = $code;
	$returnData['message'] = $messageStr;
	$returnData['data']    = $data;

	ajaxReturn($returnData);
}

/**
 * 获取头像
 *
 * @param int $id
 *
 * @return string
 */
function getAvatar(int $id)
{
	if ($id === 0) {
		return '/static/png/chat_ex_y.png';
	}
	$fileModel = new FileDao;
	$fileInfo  = $fileModel->getInfo($id, 'key');

	return env('app.resource_url') . $fileInfo['key'];
}

/**
 * 格式化日期
 *
 * @param int $time
 *
 * @return string
 */
function dateFormat(int $time)
{
	$t = time() - $time;
	$f = [
		'31536000' => '年',
		'2592000'  => '个月',
		'604800'   => '星期',
		'86400'    => '天',
		'3600'     => '小时',
		'60'       => '分钟',
		'1'        => '秒',
	];
	foreach ($f as $k => $v) {
		if (0 != $c = floor($t / (int) $k)) {
			return $c . $v . '前';
		}
	}
}

/**
 * XSS过滤
 *
 * @param $val
 *
 * @return null|string|string[]
 */
function removeXSS($val)
{
	$val    = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
	$search = 'abcdefghijklmnopqrstuvwxyz';
	$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$search .= '1234567890!@#$%^&*()';
	$search .= '~`";:?+/={}[]-_|\'\\';
	for ($i = 0; $i < strlen($search); $i ++) {
		$val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
		$val = preg_replace('/(�{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val);                // with a ;
	}
	$ra1 = [
		'javascript',
		'vbscript',
		'expression',
		'applet',
		'meta',
		'xml',
		'blink',
		'link',
		'style',
		'script',
		'embed',
		'object',
		'iframe',
		'frame',
		'frameset',
		'ilayer',
		'layer',
		'bgsound',
		'title',
		'base',
	];
	$ra2 = [
		'onabort',
		'onactivate',
		'onafterprint',
		'onafterupdate',
		'onbeforeactivate',
		'onbeforecopy',
		'onbeforecut',
		'onbeforedeactivate',
		'onbeforeeditfocus',
		'onbeforepaste',
		'onbeforeprint',
		'onbeforeunload',
		'onbeforeupdate',
		'onblur',
		'onbounce',
		'oncellchange',
		'onchange',
		'onclick',
		'oncontextmenu',
		'oncontrolselect',
		'oncopy',
		'oncut',
		'ondataavailable',
		'ondatasetchanged',
		'ondatasetcomplete',
		'ondblclick',
		'ondeactivate',
		'ondrag',
		'ondragend',
		'ondragenter',
		'ondragleave',
		'ondragover',
		'ondragstart',
		'ondrop',
		'onerror',
		'onerrorupdate',
		'onfilterchange',
		'onfinish',
		'onfocus',
		'onfocusin',
		'onfocusout',
		'onhelp',
		'onkeydown',
		'onkeypress',
		'onkeyup',
		'onlayoutcomplete',
		'onload',
		'onlosecapture',
		'onmousedown',
		'onmouseenter',
		'onmouseleave',
		'onmousemove',
		'onmouseout',
		'onmouseover',
		'onmouseup',
		'onmousewheel',
		'onmove',
		'onmoveend',
		'onmovestart',
		'onpaste',
		'onpropertychange',
		'onreadystatechange',
		'onreset',
		'onresize',
		'onresizeend',
		'onresizestart',
		'onrowenter',
		'onrowexit',
		'onrowsdelete',
		'onrowsinserted',
		'onscroll',
		'onselect',
		'onselectionchange',
		'onselectstart',
		'onstart',
		'onstop',
		'onsubmit',
		'onunload',
	];
	$ra  = array_merge($ra1, $ra2);

	$found = TRUE; // keep replacing as long as the previous round replaced something
	while ($found == TRUE) {
		$val_before = $val;
		for ($i = 0; $i < sizeof($ra); $i ++) {
			$pattern = '/';
			for ($j = 0; $j < strlen($ra[$i]); $j ++) {
				if ($j > 0) {
					$pattern .= '(';
					$pattern .= '(&#[xX]0{0,8}([9ab]);)';
					$pattern .= '|';
					$pattern .= '|(�{0,8}([9|10|13]);)';
					$pattern .= ')*';
				}
				$pattern .= $ra[$i][$j];
			}
			$pattern     .= '/i';
			$replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
			$val         = preg_replace($pattern, $replacement, $val);         // filter out the hex tags
			if ($val_before == $val) {
				// no replacements were made, so exit the loop
				$found = FALSE;
			}
		}
	}

	return $val;
}

/**
 * 获取房间号
 *
 * @param int $uid
 * @param int $friendId
 *
 * @return false|string
 */
function getRoomNumber(int $uid, int $friendId)
{
	if ($uid > $friendId) {
		$str = $friendId . config('app.global_auth_key') . $uid;
	} else {
		$str = $uid . config('app.global_auth_key') . $friendId;
	}

	$roomNum = substr(md5($str), 3, 11);

	return $roomNum;
}

/**
 * 获取用户信息
 *
 * @param int    $uid
 * @param string $field
 *
 * @return array
 */
function getUserInfo(int $uid = 0, string $field = '*')
{
	if ($uid === 0) {
		$uid = getUid();
	}
	$memberModel = new MemberDao;
	$userInfo    = $memberModel->getInfoById($uid, $field);

	return $userInfo;
}

/**
 * 检查浏览器
 */
function checkBrowser()
{
	$controller = Request()->controller();

	if (isMobile() && $controller !== 'Mobile') {
		exit("<script>window.location.href='" . url('index/mobile/index') . "';</script>");
	}
	if (!isMobile() && $controller !== 'Index') {
		exit("<script>window.location.href='" . url('/') . "';</script>");
	}
}

/**
 * 是否移动端
 *
 * @return bool
 */
function isMobile()
{
	// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
		return TRUE;
	}
	// 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	if (isset($_SERVER['HTTP_VIA'])) {
		// 找不到为false,否则为true
		return stristr($_SERVER['HTTP_VIA'], "wap") ? TRUE : FALSE;
	}
	// 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = ['nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile', 'MicroMessenger'];
		// 从HTTP_USER_AGENT中查找手机浏览器的关键字
		if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return TRUE;
		}
	}
	// 协议法，因为有可能不准确，放到最后判断
	if (isset ($_SERVER['HTTP_ACCEPT'])) {
		// 如果只支持wml并且不支持html那一定是移动设备
		// 如果支持wml和html但是wml在html之前则是移动设备
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== FALSE) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === FALSE || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			return TRUE;
		}
	}

	return FALSE;
}
