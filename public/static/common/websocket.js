(function ($) {

    $.config = {
        url: '', //链接地址
        token: '',// 通讯key
    };

    $.init = function (config) {
        this.config = config;
        return this;
    };

    /**
     * 连接webcocket
     */
    $.connect = function () {
        var protocol = (window.location.protocol === 'http:') ? 'ws:' : 'wss:';
        this.host = protocol + this.config.url;
        this.protocols = this.config.token;

        window.WebSocket = window.WebSocket || window.MozWebSocket;
        if (!window.WebSocket) { // 检测浏览器支持
            this.error('Error: WebSocket is not supported .');
            return;
        }
        this.socket = new WebSocket(this.host, [this.protocols]); // 创建连接并注册响应函数
        this.socket.onopen = function () {
            $.onopen();
        };
        this.socket.onmessage = function (message) {
            $.onmessage(message);
        };
        this.socket.onclose = function () {
            $.onclose();
            $.socket = null; // 清理
        };
        this.socket.onerror = function (errorMsg) {
            $.onerror(errorMsg);
        }
        return this;
    }

    /**
     * 自定义异常函数
     * @param {Object} errorMsg
     */
    $.error = function (errorMsg) {
        this.onerror(errorMsg);
    }

    /**
     * 消息发送
     */
    $.send = function (message) {
        if (this.socket) {
            this.socket.send(message);
            return true;
        }
        this.error('please connect to the server first !!!');
        return false;
    }

    $.close = function () {
        if (this.socket !== undefined && this.socket !== null) {
            this.socket.close();
        } else {
            this.error("this socket is not available");
        }
    }

    /**
     * 消息回調
     * @param {Object} message
     */
    $.onmessage = function (message) {
        console.log(message)
    }

    /**
     * 链接回调函数
     */
    $.onopen = function () {
        console.log('连接成功')
    }

    /**
     * 关闭回调
     */
    $.onclose = function () {
        console.log('连接关闭');
    }

    /**
     * 异常回调
     */
    $.onerror = function (error) {
        console.log(error);
    }

})(ws = {});