/** layui-v2.5.4 MIT License By https://www.layui.com */
;layui.define(function (run) {
    "use strict";
    let operation = function (base) {
        let _this = this;
        _this.config = base || {};
        _this.config.index = ++config.index;
        _this.render(!0)
    };
    operation.prototype.type = function () {
        let renderConfig = this.config;
        if (typeof renderConfig.elem === "object") {
            return void 0 === renderConfig.elem.length ? 2 : 3
        }
    };
    operation.prototype.view = function () {
        let renderConfig = this.config,
            renderConfigContinuousPageCount = renderConfig.groups = "groups" in renderConfig ? 0 | renderConfig.groups : 5,
            renderConfigContinuousPageCountMin = renderConfigContinuousPageCount > 0 ? renderConfigContinuousPageCount : 1;
        renderConfig.layout = "object" == typeof renderConfig.layout ? renderConfig.layout : ["prev", "page", "next"],
            renderConfig.count = 0 | renderConfig.count, renderConfig.curr = 0 | renderConfig.curr || 1,
            renderConfig.limits = "object" == typeof renderConfig.limits ? renderConfig.limits : [10, 20, 30, 40, 50],
            renderConfig.limit = 0 | renderConfig.limit || 10,
            renderConfig.curr = 0 | renderConfig.curr || 1,
            renderConfig.pages = Math.ceil(renderConfig.count / renderConfig.limit) || 1,
        renderConfig.curr > renderConfig.pages && (renderConfig.curr = renderConfig.pages),
            renderConfigContinuousPageCount < 0 ? renderConfigContinuousPageCount = 1 : renderConfigContinuousPageCount > renderConfig.pages && (renderConfigContinuousPageCount = renderConfig.pages),
            renderConfig.prev = "prev" in renderConfig ? renderConfig.prev : "&#x4E0A;&#x4E00;&#x9875;",
            renderConfig.next = "next" in renderConfig ? renderConfig.next : "&#x4E0B;&#x4E00;&#x9875;";
        let pieceBlock = renderConfig.pages > renderConfigContinuousPageCount ? Math.ceil((renderConfig.curr + (renderConfigContinuousPageCount > 1 ? 1 : 0)) / renderConfigContinuousPageCountMin) : 1,
            pageConfig = {
                prev: function () {
                    return renderConfig.prev ? '<a href="javascript:;" class="layui-laypage-prev' + (1 === renderConfig.curr ? " " + "layui-disabled" : "") + '" data-page="' + (renderConfig.curr - 1) + '">' + renderConfig.prev + "</a>" : ""
                }(),
                page: function () {
                    let arrayTemp = [];
                    if (renderConfig.count < 1) return "";
                    pieceBlock > 1 && renderConfig.first !== !1 && 0 !== renderConfigContinuousPageCount && arrayTemp.push('<a href="javascript:;" class="layui-laypage-first" data-page="1"  title="&#x9996;&#x9875;">' + (renderConfig.first || 1) + "</a>");
                    let frontBackCount = Math.floor((renderConfigContinuousPageCount - 1) / 2),
                        groupFront = pieceBlock > 1 ? renderConfig.curr - frontBackCount : 1,
                        groupBack = pieceBlock > 1 ? function () {
                            let nextPageExceptFirst = renderConfig.curr + (renderConfigContinuousPageCount - frontBackCount - 1);
                            return nextPageExceptFirst > renderConfig.pages ? renderConfig.pages : nextPageExceptFirst
                        }() : renderConfigContinuousPageCount;
                    for (groupBack - groupFront < renderConfigContinuousPageCount - 1 && (groupFront = groupBack - renderConfigContinuousPageCount + 1), renderConfig.first !== !1 && groupFront > 2 && arrayTemp.push('<span class="layui-laypage-spr">&#x2026;</span>'); groupFront <= groupBack; groupFront++) groupFront === renderConfig.curr ? arrayTemp.push('<span class="layui-laypage-curr"><em class="layui-laypage-em" ' + (/^#/.test(renderConfig.theme) ? 'style="background-color:' + renderConfig.theme + ';"' : "") + "></em><em>" + groupFront + "</em></span>") : arrayTemp.push('<a href="javascript:;" data-page="' + groupFront + '">' + groupFront + "</a>");
                    return renderConfig.pages > renderConfigContinuousPageCount && renderConfig.pages > groupBack && renderConfig.last !== !1 && (groupBack + 1 < renderConfig.pages && arrayTemp.push('<span class="layui-laypage-spr">&#x2026;</span>'), 0 !== renderConfigContinuousPageCount && arrayTemp.push('<a href="javascript:;" class="layui-laypage-last" title="&#x5C3E;&#x9875;"  data-page="' + renderConfig.pages + '">' + (renderConfig.last || renderConfig.pages) + "</a>")), arrayTemp.join("")
                }(),
                next: function () {
                    return renderConfig.next ? '<a href="javascript:;" class="layui-laypage-next' + (renderConfig.curr === renderConfig.pages ? " " + "layui-disabled" : "") + '" data-page="' + (renderConfig.curr + 1) + '">' + renderConfig.next + "</a>" : ""
                }(),
                count: '<span class="layui-laypage-count">共 ' + renderConfig.count + " 条</span>",
                limit: function () {
                    let layPageSelect = ['<span class="layui-laypage-limits"><select lay-ignore>'];
                    return layui.each(renderConfig.limits, function (t, index) {
                        layPageSelect.push('<option value="' + index + '"' + (index === renderConfig.limit ? "selected" : "") + ">" + index + " 条/页</option>")
                    }), layPageSelect.join("") + "</select></span>"
                }(),
                refresh: ['<a href="javascript:;" data-page="' + renderConfig.curr + '" class="layui-laypage-refresh">', '<i class="layui-icon layui-icon-refresh"></i>', "</a>"].join(""),
                skip: function () {
                    return ['<span class="layui-laypage-skip">&#x5230;&#x7B2C;', '<input type="text" min="1" value="' + renderConfig.curr + '" class="layui-input">', '&#x9875;<button type="button" class="layui-laypage-btn">&#x786e;&#x5b9a;</button>', "</span>"].join("")
                }(),
                find: function () {
                    return ['<span class="layui-laypage-find">&#26597;&#25214;', '<input type="text" value="' + renderConfig.keyword + '" class="layui-input">', '<button type="button" class="layui-laypage-btn">&#25628;&#32034;</button>', "</span>"].join("")
                }()
            };
        return ['<div class="layui-box layui-laypage layui-laypage-' + (renderConfig.theme ? /^#/.test(renderConfig.theme) ? "molv" : renderConfig.theme : "default") + '" id="layui-laypage-' + renderConfig.index + '">', function () {
            let arrayTemp = [];
            return layui.each(renderConfig.layout, function (a, index) {
                pageConfig[index] && arrayTemp.push(pageConfig[index])
            }), arrayTemp.join("")
        }(), "</div>"].join("")
    };
    operation.prototype.jump = function (pageTpl, a) {
        if (pageTpl) {
            let _this = this,
                pageConfig = _this.config,
                childNode = pageTpl.children,
                jumpPageButtonHtml = pageTpl["getElementsByTagName"]("button")[0],
                jumpPageInputHtml = pageTpl["getElementsByTagName"]("input")[0],
                searchButtonHtml = pageTpl["getElementsByTagName"]("button")[1],
                searchInputHtml = pageTpl["getElementsByTagName"]("input")[1],
                changeLimitSelectHtml = pageTpl["getElementsByTagName"]("select")[0],
                jumpPage = function () {
                    let page = 0 | jumpPageInputHtml.value.replace(/\s|\D/g, "");
                    page && (pageConfig.curr = page, _this.render())
                };

            if (a) return jumpPage();
            for (let i = 0, childNodeLength = childNode.length; i < childNodeLength; i++) {
                childNode[i].nodeName.toLowerCase() === "a" && config.on(childNode[i], "click", function () {
                    let page = 0 | this.getAttribute("data-page");
                    page < 1 || page > pageConfig.pages || (pageConfig.curr = page, _this.render())
                });
            }
            changeLimitSelectHtml && config.on(changeLimitSelectHtml, "change", function () {
                let limit = this.value;
                console.log(Math.ceil(pageConfig.count / limit))
                pageConfig.curr * limit > pageConfig.count && (pageConfig.curr = Math.ceil(pageConfig.count / limit)), pageConfig.limit = limit, _this.render()
            });
            jumpPageButtonHtml && config.on(jumpPageButtonHtml, "click", function () {
                jumpPage()
            });
            searchButtonHtml && config.on(searchButtonHtml, "click", function () {
                let keyword = searchInputHtml.value;
                keyword !== null && (pageConfig.curr = 1), pageConfig.keyword = keyword, _this.render()
            })
        }
    };
    operation.prototype.skip = function (pageTpl) {
        if (pageTpl) {
            let _this = this,
                searchInputHtml = pageTpl["getElementsByTagName"]("input")[0];
            searchInputHtml && config.on(searchInputHtml, "keyup", function (keyboard) {
                let inputValue = this.value, keyCode = keyboard.keyCode;
                /^(37|38|39|40)$/.test(keyCode) || (/\D/.test(inputValue) && (this.value = inputValue.replace(/\D/, "")), 13 === keyCode && _this.jump(pageTpl, !0))
            })
        }
    };
    operation.prototype.render = function (status) {
        let _this = this,
            pageConfig = _this.config,
            unknownType = _this.type(),
            pageTplString = _this.view();
        if (unknownType === 2) {
            pageConfig.elem && (pageConfig.elem.innerHTML = pageTplString)
        } else if (unknownType === 3) {
            pageConfig.elem.html(pageTplString)
        } else {
            document["getElementById"](pageConfig.elem) && (document["getElementById"](pageConfig.elem).innerHTML = pageTplString)
        }
        pageConfig.jump && pageConfig.jump(pageConfig, status);
        let s = document["getElementById"]("layui-laypage-" + pageConfig.index);
        _this.jump(s), pageConfig.hash && !status && (location.hash = "!" + pageConfig.hash + "=" + pageConfig.curr), _this.skip(s)
    };
    let config = {
        render: function (base) {
            let instantiateOperation = new operation(base);
            return instantiateOperation.index
        }, index: layui.laypage ? layui.laypage.index + 1e4 : 0, on: function (pageTpl, domEvent, eventString) {
            return pageTpl.attachEvent ? pageTpl.attachEvent("on" + domEvent, function (a) {
                a.target = a.srcElement, eventString.call(pageTpl, a)
            }) : pageTpl.addEventListener(domEvent, eventString, !1), this
        }
    };
    run("laypage", config)
});