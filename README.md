easychat
===============

> 运行环境要求PHP7.1+。

## 说明

* 后端框架使用ThinkPHP 6.0.*
* 前端框架使用LayIM 3.0
* websocket服务框架使用swoft 2.*

## 使用

* cp .env.example .env
* mkdir runtime
* chmod 777 runtime
* 数据库sql在/db/easychat.sql
* 要配合[websocket](https://github.com/LazyShiro/easychat_websocket)项目一起使用

## 注意

* 本项目公开但不商用，如有商用请联系[相关版权商](https://www.layui.com/layim/)
* 不要随意composer update
* 页面如有加载失败的情况，请下载[静态资源包](https://github.com/LazyShiro/easychat_resource_package)自行调试
* nginx环境要配置ThinkPHP6的[URL重写](https://www.kancloud.cn/manual/thinkphp6_0/1037488)
* 移动端还未开发完成
* 禁止吐槽代码质量

## 鸣谢

* LayIM 3 项目组全体人员
* ThinkPHP 6 项目全体人员
* Swoft 2 项目全体人员