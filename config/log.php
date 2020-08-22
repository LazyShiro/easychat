<?php

// +----------------------------------------------------------------------
// | 日志设置
// +----------------------------------------------------------------------
return [// 默认日志记录通道
	'default'      => env('log.channel', 'file'), // 日志记录级别
	'level'        => [], // 日志类型记录的通道 ['error'=>'email',...]
	'type_channel' => [], // 关闭全局日志写入
	'close'        => FALSE, // 全局日志处理 支持闭包
	'processor'    => NULL,

	// 日志通道列表
	'channels'     => ['file' => [// 日志记录方式
		'type'           => 'File', // 日志保存目录
		'path'           => '', // 单文件日志写入
		'single'         => FALSE, // 独立日志级别
		'apart_level'    => [], // 最大日志文件数量
		'max_files'      => 0, // 使用JSON格式记录
		'json'           => FALSE, // 日志处理
		'processor'      => NULL, // 关闭通道日志写入
		'close'          => FALSE, // 日志输出格式化
		'format'         => '[%s][%s] %s', // 是否实时写入
		'realtime_write' => FALSE,],// 其它日志通道配置
	],

];
