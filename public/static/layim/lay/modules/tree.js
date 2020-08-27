/** layui-v2.5.4 MIT License By https://www.layui.com */
;layui.define("form", function (e) {
    "use strict";
    var i = layui.$, a = layui.form, n = "tree", r = {
            config: {}, index: layui[n] ? layui[n].index + 1e4 : 0, set: function (e) {
                var a = this;
                return a.config = i.extend({}, a.config, e), a
            }, on: function (e, i) {
                return layui.onevent.call(this, n, e, i)
            }
        }, l = function () {
            var e = this, i = e.config, a = i.id || e.index;
            return l.that[a] = e, l.config[a] = i, {
                config: i, reload: function (i) {
                    e.reload.call(e, i)
                }, getChecked: function () {
                    return e.getChecked.call(e)
                }, setChecked: function (i) {
                    return e.setChecked.call(e, i)
                }
            }
        }, t = "layui-hide", d = "layui-disabled", s = "layui-tree-set", c = "layui-tree-iconClick",
        o = "layui-icon-addition", h = "layui-icon-subtraction", u = "layui-tree-entry", f = "layui-tree-main",
        p = "layui-tree-txt", y = "layui-tree-pack", v = "layui-tree-spread", C = "layui-tree-setLineShort",
        m = "layui-tree-showLine", k = "layui-tree-lineExtend", g = function (e) {
            var a = this;
            a.index = ++r.index, a.config = i.extend({}, a.config, r.config, e), a.render()
        };
    g.prototype.config = {
        data: [],
        showCheckbox: !1,
        showLine: !0,
        accordion: !1,
        onlyIconControl: !1,
        isJump: !1,
        edit: !1,
        text: {defaultNodeName: "未命名", none: "无数据"}
    }, g.prototype.reload = function (e) {
        var a = this;
        layui.each(e, function (e, i) {
            i.constructor === Array && delete a.config[e]
        }), a.config = i.extend(!0, {}, a.config, e), a.render()
    }, g.prototype.render = function () {
        var e = this, a = e.config,
            n = i('<div class="layui-tree' + (a.showCheckbox ? " layui-form" : "") + (a.showLine ? " layui-tree-line" : "") + '" lay-filter="LAY-tree-' + e.index + '"></div>');
        e.tree(n);
        var r = a.elem = i(a.elem);
        if (r[0]) {
            if (a.showSearch && n.prepend('<input type="text" class="layui-input layui-tree-search" placeholder="请输入关键字进行过滤">'), e.key = a.id || e.index, e.elem = n, e.elemNone = i('<div class="layui-tree-emptyText">' + a.text.none + "</div>"), r.html(e.elem), 0 == e.elem.find(".layui-tree-set").length) return e.elem.append(e.elemNone);
            a.drag && e.drag(), a.showCheckbox && e.renderForm("checkbox"), e.elem.find(".layui-tree-set").each(function () {
                var e = i(this);
                e.parent(".layui-tree-pack")[0] || e.addClass("layui-tree-setHide"), !e.next()[0] && e.parents(".layui-tree-pack").eq(1).hasClass("layui-tree-lineExtend") && e.addClass(C), e.next()[0] || e.parents(".layui-tree-set").eq(0).next()[0] || e.addClass(C)
            }), e.events()
        }
    }, g.prototype.renderForm = function (e) {
        a.render(e, "LAY-tree-" + this.index)
    }, g.prototype.tree = function (e, a) {
        var n = this, r = n.config, l = a || r.data;
        layui.each(l, function (a, l) {
            var c = l.children && l.children.length > 0,
                o = i('<div class="layui-tree-pack" ' + (l.spread ? 'style="display: block;"' : "") + '"></div>'),
                h = i(['<div data-id="' + l.id + '" class="layui-tree-set' + (l.spread ? " layui-tree-spread" : "") + (l.checked ? " layui-tree-checkedFirst" : "") + '">', "<div " + (r.drag && !l.fixed ? 'draggable="true"' : "") + ' class="layui-tree-entry">', '<div class="layui-tree-main">', function () {
                    return r.showLine ? c ? '<span class="layui-tree-iconClick layui-tree-icon"><i class="layui-icon ' + (l.spread ? "layui-icon-subtraction" : "layui-icon-addition") + '"></i></span>' : '<span class="layui-tree-iconClick"><i class="layui-icon layui-icon-file"></i></span>' : '<span class="layui-tree-iconClick"><i class="layui-tree-iconArrow ' + (c ? "" : t) + '"></i></span>'
                }(), function () {
                    return r.showCheckbox ? '<input type="checkbox" name="layuiTreeCheck" lay-skin="primary" ' + (l.disabled ? "disabled" : "") + '  value="' + l.id + '">' : ""
                }(), function () {
                    return r.isJump && l.href ? '<a href="' + l.href + '" target="_blank" class="' + p + '">' + (l.title || l.label || r.text.defaultNodeName) + "</a>" : '<span class="' + p + (l.disabled ? " " + d : "") + '">' + (l.title || l.label || r.text.defaultNodeName) + "</span>"
                }(), "</div>", function () {
                    if (!r.edit) return "";
                    var e = {
                        add: '<i class="layui-icon layui-icon-add-1"  data-type="add"></i>',
                        update: '<i class="layui-icon layui-icon-edit" data-type="update"></i>',
                        del: '<i class="layui-icon layui-icon-delete" data-type="del"></i>'
                    }, i = ['<div class="layui-btn-group layui-tree-btnGroup">'];
                    return r.edit === !0 && (r.edit = ["update", "del"]), "object" == typeof r.edit ? (layui.each(r.edit, function (a, n) {
                        i.push(e[n] || "")
                    }), i.join("") + "</div>") : void 0
                }(), "</div></div>"].join(""));
            c && (h.append(o), n.tree(o, l.children)), e.append(h), h.prev("." + s)[0] && h.prev().children(".layui-tree-pack").addClass("layui-tree-showLine"), c || h.parent(".layui-tree-pack").addClass("layui-tree-lineExtend"), n.spread(h, l), r.showCheckbox && n.checkClick(h, l), r.edit && n.operate(h, l)
        })
    }, g.prototype.spread = function (e, a) {
        var n = this, r = n.config, l = e.children("." + u), t = l.children("." + f), C = l.find("." + c),
            m = l.find("." + p), k = r.onlyIconControl ? C : t, g = "";
        k.on("click", function (i) {
            var a = e.children("." + y),
                n = k.children(".layui-icon")[0] ? k.children(".layui-icon") : k.find(".layui-tree-icon").children(".layui-icon");
            if (a[0]) {
                if (e.hasClass(v)) e.removeClass(v), a.slideUp(200), n.removeClass(h).addClass(o); else if (e.addClass(v), a.slideDown(200), n.addClass(h).removeClass(o), r.accordion) {
                    var l = e.siblings("." + s);
                    l.removeClass(v), l.children("." + y).slideUp(200), l.find(".layui-tree-icon").children(".layui-icon").removeClass(h).addClass(o)
                }
            } else g = "normal"
        }), m.on("click", function () {
            var n = i(this);
            n.hasClass(d) || (g = e.hasClass(v) ? r.onlyIconControl ? "open" : "close" : r.onlyIconControl ? "close" : "open", r.click && r.click({
                elem: e,
                state: g,
                data: a
            }))
        })
    }, g.prototype.setCheckbox = function (e, i, a) {
        var n = this, r = (n.config, a.prop("checked"));
        if ("object" == typeof i.children || e.find("." + y)[0]) {
            var l = e.find("." + y).find('input[name="layuiTreeCheck"]');
            l.each(function () {
                this.disabled || (this.checked = r)
            })
        }
        var t = function (e) {
            if (e.parents("." + s)[0]) {
                var i, a = e.parent("." + y), n = a.parent(), l = a.prev().find('input[name="layuiTreeCheck"]');
                r ? l.prop("checked", r) : (a.find('input[name="layuiTreeCheck"]').each(function () {
                    this.checked && (i = !0)
                }), i || l.prop("checked", !1)), t(n)
            }
        };
        t(e), n.renderForm("checkbox")
    }, g.prototype.checkClick = function (e, a) {
        var n = this, r = n.config, l = e.children("." + u), t = l.children("." + f);
        t.on("click", 'input[name="layuiTreeCheck"]+', function (l) {
            layui.stope(l);
            var t = i(this).prev(), d = t.prop("checked");
            t.prop("disabled") || (n.setCheckbox(e, a, t), r.oncheck && r.oncheck({elem: e, checked: d, data: a}))
        })
    }, g.prototype.operate = function (e, a) {
        var n = this, r = n.config, l = e.children("." + u), d = l.children("." + f);
        l.children(".layui-tree-btnGroup").on("click", ".layui-icon", function (l) {
            layui.stope(l);
            var f = i(this).data("type"), g = e.children("." + y), x = {data: a, type: f, elem: e};
            if ("add" == f) {
                g[0] || (r.showLine ? (d.find("." + c).addClass("layui-tree-icon"), d.find("." + c).children(".layui-icon").addClass(o).removeClass("layui-icon-file")) : d.find(".layui-tree-iconArrow").removeClass(t), e.append('<div class="layui-tree-pack"></div>'));
                var b = r.operate && r.operate(x), w = {};
                if (w.title = r.text.defaultNodeName, w.id = b, n.tree(e.children("." + y), [w]), r.showLine) if (g[0]) g.hasClass(k) || g.addClass(k), e.find("." + y).each(function () {
                    i(this).children("." + s).last().addClass(C)
                }), g.children("." + s).last().prev().hasClass(C) ? g.children("." + s).last().prev().removeClass(C) : g.children("." + s).last().removeClass(C), !e.parent("." + y)[0] && e.next()[0] && g.children("." + s).last().removeClass(C); else {
                    var T = e.siblings("." + s), L = 1, N = e.parent("." + y);
                    layui.each(T, function (e, a) {
                        i(a).children("." + y)[0] || (L = 0)
                    }), 1 == L ? (T.children("." + y).addClass(m), T.children("." + y).children("." + s).removeClass(C), e.children("." + y).addClass(m), N.removeClass(k), N.children("." + s).last().children("." + y).children("." + s).last().addClass(C)) : e.children("." + y).children("." + s).addClass(C)
                }
                if (!r.showCheckbox) return;
                if (d.find('input[name="layuiTreeCheck"]')[0].checked) {
                    var A = e.children("." + y).children("." + s).last();
                    A.find('input[name="layuiTreeCheck"]')[0].checked = !0
                }
                n.renderForm("checkbox")
            } else if ("update" == f) {
                var q = d.children("." + p).html();
                d.children("." + p).html(""), d.append('<input type="text" class="layui-tree-editInput">'), d.children(".layui-tree-editInput").val(q).focus();
                var F = function (e) {
                    var i = e.val().trim();
                    i = i ? i : r.text.defaultNodeName, e.remove(), d.children("." + p).html(i), x.data.title = i, r.operate && r.operate(x)
                };
                d.children(".layui-tree-editInput").blur(function () {
                    F(i(this))
                }), d.children(".layui-tree-editInput").on("keydown", function (e) {
                    13 === e.keyCode && (e.preventDefault(), F(i(this)))
                })
            } else {
                if (r.operate && r.operate(x), x.status = "remove", !e.prev("." + s)[0] && !e.next("." + s)[0] && !e.parent("." + y)[0]) return e.remove(), void n.elem.append(n.elemNone);
                if (e.siblings("." + s).children("." + u)[0]) {
                    if (r.showCheckbox) {
                        var I = function (e) {
                            if (e.parents("." + s)[0]) {
                                var a = e.siblings("." + s).children("." + u), r = e.parent("." + y).prev(),
                                    l = r.find('input[name="layuiTreeCheck"]')[0], t = 1, d = 0;
                                0 == l.checked && (a.each(function (e, a) {
                                    var n = i(a).find('input[name="layuiTreeCheck"]')[0];
                                    0 != n.checked || n.disabled || (t = 0), n.disabled || (d = 1)
                                }), 1 == t && 1 == d && (l.checked = !0, n.renderForm("checkbox"), I(r.parent("." + s))))
                            }
                        };
                        I(e)
                    }
                    if (r.showLine) {
                        var T = e.siblings("." + s), L = 1, N = e.parent("." + y);
                        layui.each(T, function (e, a) {
                            i(a).children("." + y)[0] || (L = 0)
                        }), 1 == L ? (g[0] || (N.removeClass(k), T.children("." + y).addClass(m), T.children("." + y).children("." + s).removeClass(C)), e.next()[0] ? N.children("." + s).last().children("." + y).children("." + s).last().addClass(C) : e.prev().children("." + y).children("." + s).last().addClass(C), e.next()[0] || e.parents("." + s)[1] || e.parents("." + s).eq(0).next()[0] || e.prev("." + s).addClass(C)) : !e.next()[0] && e.hasClass(C) && e.prev().addClass(C)
                    }
                } else {
                    var H = e.parent("." + y).prev();
                    if (r.showLine) {
                        H.find("." + c).removeClass("layui-tree-icon"), H.find("." + c).children(".layui-icon").removeClass(h).addClass("layui-icon-file");
                        var S = H.parents("." + y).eq(0);
                        S.addClass(k), S.children("." + s).each(function () {
                            i(this).children("." + y).children("." + s).last().addClass(C)
                        })
                    } else H.find(".layui-tree-iconArrow").addClass(t);
                    e.parents("." + s).eq(0).removeClass(v), e.parent("." + y).remove()
                }
                e.remove()
            }
        })
    }, g.prototype.drag = function () {
        var e = this, a = e.config;
        e.elem.on("dragstart", "." + u, function () {
            var e = i(this).parent("." + s), n = e.parents("." + s)[0] ? e.parents("." + s).eq(0) : "未找到父节点";
            a.dragstart && a.dragstart(e, n)
        }), e.elem.on("dragend", "." + u, function (n) {
            var n = n || event, r = n.clientY, l = i(this), d = l.parent("." + s), f = d.height(), p = d.offset().top,
                g = e.elem.find("." + s), x = e.elem.height(), b = e.elem.offset().top, w = x + b - 13,
                T = d.parents("." + s)[0], L = d.next()[0];
            if (T) var N = d.parent("." + y), A = d.parents("." + s).eq(0), q = A.parent("." + y), F = A.offset().top,
                I = d.siblings(), H = A.children("." + y).children("." + s).length;
            var S = function (n) {
                if (T || L || e.elem.children("." + s).last().children("." + y).children("." + s).last().addClass(C), !T) return void d.removeClass("layui-tree-setHide");
                if (1 == H) a.showLine ? (n.find("." + c).removeClass("layui-tree-icon"), n.find("." + c).children(".layui-icon").removeClass(h).addClass("layui-icon-file"), q.addClass(k), q.children("." + s).children("." + y).each(function () {
                    i(this).children("." + s).last().addClass(C)
                })) : n.find(".layui-tree-iconArrow").addClass(t), n.children("." + y).remove(), n.removeClass(v); else {
                    if (a.showLine) {
                        var r = 1;
                        layui.each(I, function (e, a) {
                            i(a).children("." + y)[0] || (r = 0)
                        }), 1 == r ? (d.children("." + y)[0] || (N.removeClass(k), I.children("." + y).addClass(m), I.children("." + y).children("." + s).removeClass(C)), N.children("." + s).last().children("." + y).children("." + s).last().addClass(C), L || n.parents("." + s)[0] || n.next()[0] || N.children("." + s).last().addClass(C)) : !L && d.hasClass(C) && N.children("." + s).last().addClass(C)
                    }
                    if (a.showCheckbox) {
                        var l = function (a) {
                            if (a) {
                                if (!a.parents("." + s)[0]) return
                            } else if (!n[0]) return;
                            var r = a ? a.siblings().children("." + u) : I.children("." + u),
                                t = a ? a.parent("." + y).prev() : N.prev(),
                                d = t.find('input[name="layuiTreeCheck"]')[0], c = 1, o = 0;
                            0 == d.checked && (r.each(function (e, a) {
                                var n = i(a).find('input[name="layuiTreeCheck"]')[0];
                                0 != n.checked || n.disabled || (c = 0), n.disabled || (o = 1)
                            }), 1 == c && 1 == o && (d.checked = !0, e.renderForm("checkbox"), l(t.parent("." + s) || n)))
                        };
                        l()
                    }
                }
            };
            g.each(function () {
                if (0 != i(this).height()) {
                    if (r > p && r < p + f) return void (a.dragend && a.dragend("drag error"));
                    if (1 == H && r > F && r < p + f) return void (a.dragend && a.dragend("drag error"));
                    var n = i(this).offset().top;
                    if (r > n && r < n + 15) {
                        if (i(this).children("." + y)[0] || (a.showLine ? (i(this).find("." + c).eq(0).addClass("layui-tree-icon"), i(this).find("." + c).eq(0).children(".layui-icon").addClass(o).removeClass("layui-icon-file")) : i(this).find(".layui-tree-iconArrow").removeClass(t), i(this).append('<div class="layui-tree-pack"></div>')), i(this).children("." + y).append(d), S(A), a.showLine) {
                            var l = i(this).children("." + y).children("." + s);
                            if (d.children("." + y).children("." + s).last().addClass(C), 1 == l.length) {
                                var h = i(this).siblings("." + s), v = 1, g = i(this).parent("." + y);
                                layui.each(h, function (e, a) {
                                    i(a).children("." + y)[0] || (v = 0)
                                }), 1 == v ? (h.children("." + y).addClass(m), h.children("." + y).children("." + s).removeClass(C), i(this).children("." + y).addClass(m), g.removeClass(k), g.children("." + s).last().children("." + y).children("." + s).last().addClass(C).removeClass("layui-tree-setHide")) : i(this).children("." + y).children("." + s).addClass(C).removeClass("layui-tree-setHide")
                            } else d.prev("." + s).hasClass(C) ? (d.prev("." + s).removeClass(C), d.addClass(C)) : (d.removeClass("layui-tree-setLineShort layui-tree-setHide"), d.children("." + y)[0] ? d.prev("." + s).children("." + y).children("." + s).last().removeClass(C) : d.siblings("." + s).find("." + y).each(function () {
                                i(this).children("." + s).last().addClass(C)
                            })), i(this).next()[0] || d.addClass(C)
                        }
                        if (a.showCheckbox && i(this).children("." + u).find('input[name="layuiTreeCheck"]')[0].checked) {
                            var x = d.children("." + u);
                            x.find('input[name="layuiTreeCheck"]+').click()
                        }
                        return a.dragend && a.dragend("drag success", d, i(this)), !1
                    }
                    if (r < n) {
                        if (i(this).before(d), S(A), a.showLine) {
                            var b = d.children("." + y), T = i(this).parents("." + s).eq(0),
                                L = T.children("." + y).children("." + s).last();
                            if (b[0]) {
                                d.removeClass(C), b.children("." + s).last().removeClass(C);
                                var h = d.siblings("." + s), v = 1;
                                layui.each(h, function (e, a) {
                                    i(a).children("." + y)[0] || (v = 0)
                                }), 1 == v ? T[0] && (h.children("." + y).addClass(m), h.children("." + y).children("." + s).removeClass(C), L.children("." + y).children("." + s).last().addClass(C).removeClass(m)) : d.children("." + y).children("." + s).last().addClass(C), !T.parent("." + y)[0] && T.next()[0] && L.removeClass(C)
                            } else T.hasClass(k) || T.addClass(k), T.find("." + y).each(function () {
                                i(this).children("." + s).last().addClass(C)
                            });
                            T[0] || (d.addClass("layui-tree-setHide"), d.children("." + y).children("." + s).last().removeClass(C))
                        }
                        if (T[0] && a.showCheckbox && T.children("." + u).find('input[name="layuiTreeCheck"]')[0].checked) {
                            var x = d.children("." + u);
                            x.find('input[name="layuiTreeCheck"]+').click()
                        }
                        return a.dragend && a.dragend("拖拽成功，插入目标节点上方", d, i(this)), !1
                    }
                    if (r > w) return e.elem.children("." + s).last().children("." + y).addClass(m), e.elem.append(d), S(A), d.prev().children("." + y).children("." + s).last().removeClass(C), d.addClass("layui-tree-setHide"), d.children("." + y).children("." + s).last().addClass(C), a.dragend && a.dragend("拖拽成功，插入最外层节点", d, e.elem), !1
                }
            })
        })
    }, g.prototype.events = function () {
        var e = this, a = e.config, n = e.elem.find(".layui-tree-checkedFirst");
        layui.each(n, function (e, a) {
            i(a).children("." + u).find('input[name="layuiTreeCheck"]+').trigger("click")
        }), e.elem.find(".layui-tree-search").on("keyup", function () {
            var n = i(this), r = n.val(), l = n.nextAll(), d = [];
            l.find("." + p).each(function () {
                var e = i(this).parents("." + u);
                if (i(this).html().indexOf(r) != -1) {
                    d.push(i(this).parent());
                    var a = function (e) {
                        e.addClass("layui-tree-searchShow"), e.parent("." + y)[0] && a(e.parent("." + y).parent("." + s))
                    };
                    a(e.parent("." + s))
                }
            }), l.find("." + u).each(function () {
                var e = i(this).parent("." + s);
                e.hasClass("layui-tree-searchShow") || e.addClass(t)
            }), 0 == l.find(".layui-tree-searchShow").length && e.elem.append(e.elemNone), a.onsearch && a.onsearch({elem: d})
        }), e.elem.find(".layui-tree-search").on("keydown", function () {
            i(this).nextAll().find("." + u).each(function () {
                var e = i(this).parent("." + s);
                e.removeClass("layui-tree-searchShow " + t)
            }), i(".layui-tree-emptyText")[0] && i(".layui-tree-emptyText").remove()
        })
    }, g.prototype.getChecked = function () {
        var e = this, a = e.config, n = [], r = [];
        e.elem.find(".layui-form-checked").each(function () {
            n.push(i(this).prev()[0].value)
        });
        var l = function (e, a) {
            layui.each(e, function (e, r) {
                layui.each(n, function (e, n) {
                    if (r.id == n) {
                        var t = i.extend({}, r);
                        return delete t.children, a.push(t), r.children && (t.children = [], l(r.children, t.children)), !0
                    }
                })
            })
        };
        return l(i.extend({}, a.data), r), r
    }, g.prototype.setChecked = function (e) {
        var a = this;
        a.config;
        a.elem.find("." + s).each(function (a, n) {
            var r = i(this).data("id"), l = i(n).children("." + u).find('input[name="layuiTreeCheck"]'), t = l.next();
            if ("number" == typeof e) {
                if (r == e) return l[0].checked || t.click(), !1
            } else i.inArray(r, e) != -1 && (l[0].checked || t.click())
        })
    }, l.that = {}, l.config = {}, r.reload = function (e, i) {
        var a = l.that[e];
        return a.reload(i), l.call(a)
    }, r.getChecked = function (e) {
        var i = l.that[e];
        return i.getChecked()
    }, r.setChecked = function (e, i) {
        var a = l.that[e];
        return a.setChecked(i)
    }, r.render = function (e) {
        var i = new g(e);
        return l.call(i)
    }, e(n, r)
});
