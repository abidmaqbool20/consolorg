String.prototype.replaceAll = function(b, a) {
    var c = this;
    return c.replace(new RegExp(b, "g"), a)
};
String.prototype.splice = function(a, b, c) {
    return this.slice(0, a) + c + this.slice(a + Math.abs(b))
};
if (typeof Object.assign != "function") {
    Object.defineProperty(Object, "assign", {
        value: function assign(d, f) {
            if (d == null) {
                throw new TypeError("Cannot convert undefined or null to object")
            }
            var e = Object(d);
            for (var a = 1; a < arguments.length; a++) {
                var c = arguments[a];
                if (c != null) {
                    for (var b in c) {
                        if (Object.prototype.hasOwnProperty.call(c, b)) {
                            e[b] = c[b]
                        }
                    }
                }
            }
            return e
        },
        writable: true,
        configurable: true
    })
}
if (typeof String.prototype.endsWith !== "function") {
    String.prototype.endsWith = function(a) {
        return this.indexOf(a, this.length - a.length) !== -1
    }
};
var OrgChart = function(b, a) 
{
    this.ver = "3.3.0";
    var f = this;
    this.element = b;
    this.config = {
        lazyLoading: true,
        enableDragDrop: false,
        enableSearch: true,
        nodeMenu: null,
        menu: null,
        nodeMouseClickBehaviour: BALKANGraph.action.details,
        mouseScroolBehaviour: BALKANGraph.action.none,
        showXScroll: BALKANGraph.none,
        showYScroll: BALKANGraph.none,
        template: "ana",
        tags: {},
        linkTags: {},
        nodeBinding: {},
        linkBinding: {},
        nodes: [],
        links: [],
        levelSeparation: 60,
        siblingSeparation: 20,
        subtreeSeparation: 40,
        mixedHierarchyNodesSeparation: 20,
        padding: 30,
        orientation: BALKANGraph.orientation.top,
        layout: BALKANGraph.normal,
        scaleInitial: 1,
        scaleMin: 0.4,
        scaleMax: 5,
        expandToLevel: 3,
        editUI: null,
        searchUI: null,
        xScrollUI: null,
        yScrollUI: null,
        nodeMenuUI: null,
        menuUI: null,
        UI: null,
        onUpdate: "",
        onRemove: "",
        onAdd: "",
        onRedraw: "",
        onImageUploaded: ""
    };
    if (a) {
        for (var e in this.config) {
            if (typeof a[e] === "object" && !Array.isArray(a[e])) {
                this.config[e] = BALKANGraph._I(this.config[e], a[e])
            } else {
                if (typeof a[e] !== "undefined") {
                    this.config[e] = a[e]
                }
            }
        }
    }
    if (this.config.links && this.config.links.length > 0) {
        for (var c = 0; c < this.config.links.length; c++) {
            for (var d = 0; d < this.config.nodes.length; d++) {
                if (this.config.links[c].from == this.config.nodes[d].id) {
                    this.config.nodes[d].pid = this.config.links[c].to
                }
            }
        }
    }
    if (!BALKANGraph._ah(this.config)) {
        return
    }
    this.server = null;
    this._an = {};
    if (!this.config.ui) {
        this.ui = OrgChart.ui
    }
    if (!this.config.editUI) {
        this.editUI = new OrgChart.editUI()
    } else {
        this.editUI = this.config.editUI
    }
    this.editUI.init(this);
    if (this.server === null) {
        this.server = new OrgChart.server(this.config)
    }
    if (!this.config.searchUI) {
        this.searchUI = new OrgChart.searchUI()
    } else {
        this.searchUI = this.config.searchUI
    }
    this.searchUI.init(this);
    if (!this.config.searchUI) {
        this.nodeMenuUI = new BALKANGraph.menuUI()
    } else {
        this.nodeMenuUI = this.config.nodeMenuUI
    }
    this.nodeMenuUI.init(this, this.config.nodeMenu);
    if (!this.config.menuUI) {
        this.menuUI = new BALKANGraph.menuUI()
    } else {
        this.menuUI = this.config.menuUI
    }
    this.menuUI.init(this, this.config.menu);
    if (!this.config.xScrollUI) {
        this.xScrollUI = new OrgChart.xScrollUI(this.element, this.config, function() {
            return {
                boundary: f.response.boundary,
                scale: f.getScale(),
                viewBox: f.getViewBox(),
                padding: f.config.padding
            }
        }, function(g) {
            f.setViewBox(g)
        }, function() {
            f._y(true, BALKANGraph.action.xScroll)
        })
    }
    if (!this.config.yScrollUI) {
        this.yScrollUI = new OrgChart.yScrollUI(this.element, this.config, function() {
            return {
                boundary: f.response.boundary,
                scale: f.getScale(),
                viewBox: f.getViewBox(),
                padding: f.config.padding
            }
        }, function(g) {
            f.setViewBox(g)
        }, function() {
            f._y(true, BALKANGraph.action.xScroll)
        })
    }
    this.lastHoveredNodeId = null;
    this._at = null;
    this._T = false;
    this.response = null;
    this.nodes = null;
    this._ae();
    this._y(false, BALKANGraph.action.init)
};

OrgChart.prototype.draw = function(a) {
    if (a == undefined) {
        a = BALKANGraph.action.update
    }
    this._y(false, a, null, null)
};
OrgChart.prototype._y = function(n, a, b, e) {
    var p = this;
    var q = (a == BALKANGraph.action.init) ? null : this.getViewBox();
    this.response = this.server.read(n, this.width(), this.height(), q, a, b);
    this.nodes = this.response.visibleNodes;
    this.editUI.fields = this.response.allFields;
    var g = this.ui.defs();
    g += this.ui.pointer(this.config);
    for (var i in this.response.visibleNodes) {
        var j = this.response.visibleNodes[i];
        g += this.ui.node(j, this.response.animations, this.config);
        g += this.ui.link(j, this.config);
        g += this.ui.expandCollapse(j, this.config)
    }
    g += this.ui.lonely(this.config);
    var m = this.getViewBox();
    var r = this.response.viewBox;
    if (a === BALKANGraph.action.centerNode) {
        r = m
    }
    var h = this.ui.svg(this.width(), this.height(), r, this.config, g);
    if (!this._T) {
        this.element.innerHTML = BALKANGraph._a(this.config.padding) + this.ui.css() + this.ui.exportMenuButton(this.config) + h;
        this._c();
        this._d();
        if (this.config.showXScroll === BALKANGraph.scroll.visible) {
            this.xScrollUI.create(this.width(), this.config.padding);
            this.xScrollUI.setPosition();
            this.xScrollUI.addListener(this.getSvg())
        }
        if (this.config.showYScroll === BALKANGraph.scroll.visible) {
            this.yScrollUI.create(this.height(), this.config.padding);
            this.yScrollUI.setPosition();
            this.yScrollUI.addListener(this.getSvg())
        }
        if (this.config.enableSearch) {
            this.searchUI.addSearchControl()
        }
    } else {
        var o = this.getSvg();
        var l = o.parentNode;
        l.removeChild(o);
        l.insertAdjacentHTML("afterbegin", h);
        this._d();
        this.xScrollUI.addListener(this.getSvg());
        this.yScrollUI.addListener(this.getSvg());
        this.xScrollUI.setPosition();
        this.yScrollUI.setPosition()
    }
    var f = false;
    var d = this.response.animations;
    if (Object.keys(d).length !== 0) {
        for (i in d) {
            var k = this.getNodeElement(i);
            var c = d[i];
            BALKANGraph.animate(k, c.from, c.to, c.duration, BALKANGraph.animate[c.func], function(s) {
                var t = Object.keys(d)[Object.keys(d).length - 1];
                var u = s[0].getAttribute("node-id");
                if (u == t) {
                    if (!f) {
                        if (e) {
                            e()
                        }
                        if (p.config.onRedraw) {
                            p.config.onRedraw()
                        }
                        f = true
                    }
                }
            })
        }
    }
    if (a === BALKANGraph.action.centerNode) {
        BALKANGraph.animate(this.getSvg(), {
            viewbox: m
        }, {
            viewbox: this.response.viewBox
        }, 500, BALKANGraph.animate.inOutPow, function() {
            var s = p.response.visibleNodes[b.id];
            p.ripple(b.id, s.templateName)
        }, function() {
            p.xScrollUI.setPosition();
            p.yScrollUI.setPosition();
            if (!f) {
                if (e) {
                    e()
                }
                if (p.config.onRedraw) {
                    p.config.onRedraw()
                }
                f = true
            }
        })
    } else {
        if (!f) {
            if (e) {
                e()
            }
            if (p.config.onRedraw) {
                p.config.onRedraw()
            }
            f = true
        }
    }
    this._T = true
};
OrgChart.prototype._ae = function() {
    this.element.style.overflow = "hidden";
    this.element.style.position = "relative";
    if (this.element.offsetHeight == 0) {
        this.element.style.height = "100%";
        if (this.element.offsetHeight == 0) {
            this.element.style.height = "700px"
        }
    }
    if (this.element.offsetWidth == 0) {
        this.element.style.width = "100%";
        if (this.element.offsetWidth == 0) {
            this.element.style.width = "700px"
        }
    }
};
OrgChart.prototype.getViewBox = function() {
    var a = this.getSvg();
    var b = null;
    if (a) {
        b = a.getAttribute("viewBox");
        b = "[" + b + "]";
        b = b.replace(/\ /g, ",");
        b = JSON.parse(b);
        return b
    } else {
        return null
    }
};
OrgChart.prototype.setViewBox = function(a) {
    this.getSvg().setAttribute("viewBox", a.toString())
};
OrgChart.prototype.width = function() {
    return this.element.offsetWidth
};
OrgChart.prototype.height = function() {
    return this.element.offsetHeight
};
OrgChart.prototype.getScale = function(a) {
    if (!a) {
        a = this.getViewBox()
    }
    return BALKANGraph.getScale(a, this.width(), this.height(), this.config.scaleInitial)
};
OrgChart.prototype.canUpdateLink = function(a, c) {
    var b = this.response.visibleNodes[a];
    var d = this.response.visibleNodes[c];
    if (d.isAssistant) {
        return false
    }
    return !d._B(b)
};
OrgChart.prototype._ab = function(a, b) {
    if (this.canUpdateLink(a, b)) {
        this.response.visibleNodes[a].data.pid = b;
        this._ay(this.response.visibleNodes[a].data)
    } else {
        console.warn("Cannot add node under assistance nodes. id=" + b)
    }
};
OrgChart.prototype._ay = function(a) {
    if (this.config.onUpdate) {
        var b = this.config.onUpdate(this, a);
        if (b === false) {
            return
        }
    }
    this.updateNode(a)
};
OrgChart.prototype.updateNode = function(a) {
    var b = this;
    this.update(a);
    this._y(false, BALKANGraph.action.update, null, function() {
        if (b.response.visibleNodes[a.id]) {
            var c = b.response.visibleNodes[a.id];
            b.ripple(c.id, c.templateName)
        }
    })
};

OrgChart.prototype.getNodes = function() {
	if(this.config.nodes.length > 0)
	{
		return this.config.nodes;
	}
	else
	{
		return false;
	}  
};


OrgChart.prototype.update = function(a) {
    for (var b = 0; b < this.config.nodes.length; b++) {
        if (this.config.nodes[b].id === a.id) {
            this.config.nodes[b] = a
        }
    }
};
OrgChart.prototype._az = function(a) {
    if (this.config.onRemove) {
        var b = this.config.onRemove(this, a);
        if (b === false) {
            return
        }
    }
    this.removeNode(a)
};
OrgChart.prototype.removeNode = function(a) {
    var b = this;
    this.remove(a);
    this._y(false, BALKANGraph.action.update, null, function() {
        BALKANGraph._1(b.getSvg(), b.getViewBox(), b.response.boundary)
    })
};
OrgChart.prototype.remove = function(b) {
    var c = null;
    for (var a = this.config.nodes.length - 1; a >= 0; a--) {
        if (this.config.nodes[a].id == b) {
            c = this.config.nodes[a].pid;
            this.config.nodes.splice(a, 1)
        }
    }
    for (var a = this.config.nodes.length - 1; a >= 0; a--) {
        if (this.config.nodes[a].pid == b) {
            this.config.nodes[a].pid = c
        }
    }
};
OrgChart.prototype._w = function(a) {
    if (this.config.onAdd) {
        var b = this.config.onAdd(this, a);
        if (b === false) {
            return
        }
    }
    this.addNode(a)
};
OrgChart.prototype.addNode = function(b) {
    var c = this;
    this.add(b);
    var a = BALKANGraph.action.insert;
    if (b.pid == undefined || b.pid == null) {
        a = BALKANGraph.action.update
    }
    c._y(false, a, {
        id: b.pid,
        insertedNodeId: b.id
    }, function() {
        BALKANGraph._1(c.getSvg(), c.getViewBox(), c.response.boundary)
    })
};
OrgChart.prototype.add = function(a) {
    if (a.id == undefined) {
        console.error("Call addNode without id")
    }
    if (a.pid != undefined && this.response && this.response.visibleNodes && this.response.visibleNodes[a.pid] && this.response.visibleNodes[a.pid].isAssistant) {
        console.warn("Cannot add node under assistance nodes. id=" + node.pid);
        return
    }
    if (!this.config.nodes) {
        this.config.nodes = []
    }
    this.config.nodes.push(a)
};
OrgChart.prototype.ripple = function(f, r, a, b) {
    var p = this.getScale();
    var q = OrgChart.templates[r];
    var g = this.getNodeElement(f);
    var t = q.size[0] / 2;
    var u = q.size[1] / 2;
    if (a !== undefined && b !== undefined) {
        var l = g.getBoundingClientRect();
        t = a / p - (l.left) / p;
        u = b / p - (l.top) / p
    }
    var s = q.size[0];
    var e = q.size[1];
    var c = (s - t) > t ? (s - t) : t;
    var d = (e - u) > u ? (e - u) : u;
    c = c;
    d = d;
    var i = c > d ? c : d;
    var m = document.createElementNS("http://www.w3.org/2000/svg", "g");
    var k = document.createElementNS("http://www.w3.org/2000/svg", "clipPath");
    var o = document.createElementNS("http://www.w3.org/2000/svg", "rect");
    var j = document.createElementNS("http://www.w3.org/2000/svg", "circle");
    var n = BALKANGraph._0();
    k.setAttribute("id", n);
    o.setAttribute("x", 0);
    o.setAttribute("y", 0);
    o.setAttribute("width", q.size[0]);
    o.setAttribute("height", q.size[1]);
    o.setAttribute("rx", q.rippleRadius);
    o.setAttribute("ry", q.rippleRadius);
    j.setAttribute("clip-path", "url(#" + n + ")");
    j.setAttribute("cx", t);
    j.setAttribute("cy", u);
    j.setAttribute("r", 0);
    j.setAttribute("fill", q.rippleColor);
    k.appendChild(o);
    m.appendChild(k);
    m.appendChild(j);
    g.appendChild(m);
    BALKANGraph.animate(j, {
        r: 0,
        opacity: 1
    }, {
        r: i,
        opacity: 0
    }, 500, BALKANGraph.animate.outPow, function() {
        g.removeChild(m)
    })
};
OrgChart.prototype.center = function(a) {
    this._y(false, BALKANGraph.action.centerNode, {
        id: a
    })
};
BALKANGraph = {};
BALKANGraph._s = {};
BALKANGraph._s._au = function() {
    var a;
    if (window.XMLHttpRequest) {
        return new XMLHttpRequest()
    } else {
        return new ActiveXObject("Microsoft.XMLHTTP")
    }
};
BALKANGraph._s._ax = function(f, a, d, c, b, e) {
    var g = BALKANGraph._s._au();
    g.open(d, f, e);
    g.onreadystatechange = function() {
        if (!BALKANGraph._r().msie && c === "xml" && g.readyState === 4) {
            a(g.responseXML)
        } else {
            if (BALKANGraph._r().msie && c === "xml" && g.readyState === 4) {
                try {
                    var i = new DOMParser();
                    var j = i.parseFromString(g.responseText, "text/xml");
                    a(j)
                } catch (h) {
                    j = new ActiveXObject("Microsoft.XMLDOM");
                    j.loadXML(g.responseText);
                    a(j)
                }
            } else {
                if (g.readyState === 4) {
                    a(g.responseText)
                }
            }
        }
    };
    if (d === "POST") {
        g.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
    }
    g.send(b)
};
BALKANGraph._s._m = function(g, b, a, c, f) {
    var e = [];
    for (var d in b) {
        e.push(encodeURIComponent(d) + "=" + encodeURIComponent(b[d]))
    }
    BALKANGraph._s._ax(g + "?" + e.join("&"), a, "GET", c, null, f)
};
BALKANGraph._s._7 = function(g, b, a, c, f) {
    var e = [];
    for (var d in b) {
        e.push(encodeURIComponent(d) + "=" + encodeURIComponent(b[d]))
    }
    BALKANGraph._s._ax(g, a, "POST", c, e.join("&"), f)
};
OrgChart.prototype._P = function(b, a, c) {
    c[0] = a;
    c[1] = b;
    this.setViewBox(c);
    this.xScrollUI.setPosition();
    this.yScrollUI.setPosition()
};
BALKANGraph.orientation = {};
BALKANGraph.orientation.top = 0;
BALKANGraph.orientation.bottom = 1;
BALKANGraph.orientation.right = 2;
BALKANGraph.orientation.left = 3;
BALKANGraph.orientation.top_left = 4;
BALKANGraph.orientation.bottom_left = 5;
BALKANGraph.orientation.right_top = 6;
BALKANGraph.orientation.left_top = 7;
BALKANGraph.ID = "id";
BALKANGraph.PID = "pid";
BALKANGraph.TAGS = "tags";
BALKANGraph.MAX_DEPTH = 100;
BALKANGraph.SCALE_FACTOR = 1.44;
BALKANGraph.EXPAND = 0;
BALKANGraph.COLLAPSE = 1;
BALKANGraph.LAZY_LOADING_FACTOR = 5000;
BALKANGraph.action = {};
BALKANGraph.action.expandCollapse = 0;
BALKANGraph.action.edit = 1;
BALKANGraph.action.zoom = 2;
BALKANGraph.action.xScroll = 3;
BALKANGraph.action.yScroll = 4;
BALKANGraph.action.none = 5;
BALKANGraph.action.init = 6;
BALKANGraph.action.update = 7;
BALKANGraph.action.pan = 8;
BALKANGraph.action.centerNode = 9;
BALKANGraph.action.resize = 10;
BALKANGraph.action.insert = 11;
BALKANGraph.action.insertfirst = 12;
BALKANGraph.action.details = 13;
BALKANGraph.none = 400001;
BALKANGraph.scroll = {};
BALKANGraph.scroll.visible = 1;
BALKANGraph.match = {};
BALKANGraph.match.height = 100001;
BALKANGraph.match.width = 100002;
BALKANGraph.match.boundary = 100003;
BALKANGraph.normal = 0;
BALKANGraph.mixed = 1;
BALKANGraph.nodeOpenTag = '<g node-id="{id}" style="opacity: {opacity}" transform="matrix(1,0,0,1,{x},{y})" class="{class}" level="{level}">';
BALKANGraph.linkOpenTag = '<g link-id="[{id}][{child-id}]" class="{class}" level="{level}">';
BALKANGraph.expcollOpenTag = '<g control-expcoll-id="{id}" transform="matrix(1,0,0,1,{x},{y})"  style="cursor:pointer;">';
BALKANGraph.linkFieldsOpenTag = '<g transform="matrix(1,0,0,1,{x},{y}) rotate({rotate})">';
BALKANGraph.grCloseTag = "</g>";
BALKANGraph.IT_IS_LONELY_HERE = '<g transform="translate(-100, 0)" style="cursor:pointer;"  control-add="control-add"><text fill="#039be5">{link}</text></g>';
BALKANGraph.RES = {};
BALKANGraph.RES.IT_IS_LONELY_HERE_LINK = "It's lonely here, add your first node";
BALKANGraph.node = function(a, c) {
    var b = OrgChart.templates[c];
    this.templateName = c;
    this.id = a.id;
    this.tags = BALKANGraph._C(a);
    this.pid = a.pid;
    this.x = null;
    this.y = null;
    this.w = b.size[0];
    this.h = b.size[1];
    this.level = null;
    this.leftNeighbor = null;
    this.rightNeighbor = null;
    this._8 = 0;
    this._K = 0;
    this.children = [];
    this.parent = null;
    this.data = a;
    this.state = BALKANGraph.EXPAND;
    this.isLastChild = true;
    this.assistants = [];
    this.isAssistant = BALKANGraph._e(this.tags, "assistant")
};
BALKANGraph.node.prototype.getTopCollapsedParent = function() {
    var a = this.parent;
    var b = null;
    while (a != null) {
        if (a.state == BALKANGraph.COLLAPSE) {
            if (!this.isAssistant) {
                b = a
            } else {
                if (this.isAssistant && a.id != this.pid) {
                    b = a
                }
            }
        }
        a = a.parent
    }
    return b
};
BALKANGraph.node.prototype._B = function(a) {
    if (this.isAssistant && this.pid == a.id) {
        return false
    }
    var b = this.parent;
    while (b != null) {
        if (b == a) {
            return true
        }
        b = b.parent
    }
    return false
};
BALKANGraph.node.prototype._k = function(a) {
    return this.children[a]
};
BALKANGraph.node.prototype._o = function(c) {
    var a = this._p();
    var b = this._A();
    return a._8 + ((b._8 - a._8) + c) / 2
};
BALKANGraph.node.prototype._p = function() {
    return this._k(0)
};
BALKANGraph.node.prototype._A = function() {
    return this._k(this.children.length - 1)
};
BALKANGraph.node.prototype._l = function() {
    if (this.state == BALKANGraph.COLLAPSE) {
        return 0
    }
    return this.children.length
};
BALKANGraph.node.prototype._W = function() {
    if ((this.leftNeighbor != null && this.leftNeighbor.parent == this.parent)) {
        return this.leftNeighbor
    } else {
        return null
    }
};
BALKANGraph.node.prototype._D = function() {
    if (this.rightNeighbor != null && this.rightNeighbor.parent == this.parent) {
        return this.rightNeighbor
    } else {
        return null
    }
};
BALKANGraph.icon = {};
BALKANGraph.icon.excel = function(d, b, a) {};
BALKANGraph.icon.png = function(d, b, a) {
    return '<svg width="' + d + '" height="' + b + '" viewBox="0 0 360 360"><path fill="' + a + '" d="M344.71,126.996h-49.42V77.834L212.267,0H15.29v360h280v-50.277h49.42V126.996z M314.71,279.723h-210 V156.996h210V279.723z" /><path fill="' + a + '" d="M137.171,224.809h14.448c2.808,0,5.388-0.573,7.74-1.72c2.35-1.146,4.356-2.666,6.02-4.558 c1.662-1.892,2.952-4.055,3.87-6.493c0.916-2.437,1.376-4.945,1.376-7.525c0-2.464-0.488-4.915-1.462-7.353 c-0.976-2.436-2.322-4.614-4.042-6.536c-1.72-1.92-3.77-3.468-6.149-4.644c-2.38-1.175-4.975-1.763-7.783-1.763h-25.886v61.06 h11.868V224.809z M137.171,194.623h13.244c1.032,0,2.049,0.215,3.053,0.645c1.002,0.43,1.877,1.075,2.623,1.935 c0.745,0.86,1.347,1.907,1.806,3.139c0.458,1.233,0.688,2.623,0.688,4.171c0,3.04-0.731,5.448-2.193,7.224 c-1.462,1.778-3.283,2.666-5.461,2.666h-13.76V194.623z" /><polygon fill="' + a + '" points="190.748,206.147 222.138,245.277 231.769,245.277 231.769,184.303 219.902,184.303 219.902,224.293 188.081,184.217 178.879,184.217 178.879,245.277 190.748,245.277" /><path fill="' + a + '" d="M249.355,236.677c2.608,2.81,5.676,5.018,9.202,6.622c3.526,1.606,7.295,2.408,11.309,2.408 c6.478,0,12.154-2.437,17.028-7.31v6.88h9.804v-31.39h-22.102v8.686h12.298v4.3c-4.76,5.562-10.092,8.342-15.996,8.342 c-2.58,0-4.975-0.529-7.181-1.591c-2.208-1.06-4.115-2.508-5.719-4.343c-1.606-1.834-2.868-3.999-3.784-6.493 c-0.918-2.494-1.376-5.174-1.376-8.041c0-2.753,0.415-5.36,1.247-7.826c0.83-2.464,2.006-4.644,3.526-6.536 c1.519-1.892,3.354-3.382,5.504-4.472c2.15-1.089,4.515-1.634,7.095-1.634c3.268,0,6.292,0.804,9.073,2.408 c2.78,1.605,4.945,3.928,6.493,6.966l8.858-6.536c-2.064-4.07-5.147-7.31-9.245-9.718c-4.1-2.408-9.017-3.612-14.749-3.612 c-4.243,0-8.17,0.817-11.782,2.451c-3.612,1.634-6.751,3.842-9.417,6.622c-2.666,2.782-4.76,6.02-6.278,9.718 c-1.52,3.698-2.279,7.641-2.279,11.825c0,4.416,0.759,8.529,2.279,12.341C244.682,230.558,246.746,233.869,249.355,236.677z" /></svg>'
};
BALKANGraph.icon.svg = function(d, b, a) {
    return '<svg width="' + d + '" height="' + b + '" viewBox="0 0 550.801 550.801"><path fill="' + a + '" d="M488.426,197.019H475.2v-63.816c0-0.398-0.063-0.799-0.116-1.202c-0.021-2.534-0.827-5.023-2.562-6.995L366.325,3.694 c-0.032-0.031-0.063-0.042-0.085-0.076c-0.633-0.707-1.371-1.295-2.151-1.804c-0.231-0.155-0.464-0.285-0.706-0.419 c-0.676-0.369-1.393-0.675-2.131-0.896c-0.2-0.056-0.38-0.138-0.58-0.19C359.87,0.119,359.037,0,358.193,0H97.2 c-11.918,0-21.6,9.693-21.6,21.601v175.413H62.377c-17.049,0-30.873,13.818-30.873,30.873v160.545 c0,17.043,13.824,30.87,30.873,30.87h13.224V529.2c0,11.907,9.682,21.601,21.6,21.601h356.4c11.907,0,21.6-9.693,21.6-21.601 V419.302h13.226c17.044,0,30.871-13.827,30.871-30.87v-160.54C519.297,210.838,505.47,197.019,488.426,197.019z M97.2,21.605 h250.193v110.513c0,5.967,4.841,10.8,10.8,10.8h95.407v54.108H97.2V21.605z M338.871,225.672L284.545,386.96h-42.591 l-51.69-161.288h39.967l19.617,68.196c5.508,19.143,10.531,37.567,14.36,57.67h0.717c4.061-19.385,9.089-38.527,14.592-56.953 l20.585-68.918h38.77V225.672z M68.458,379.54l7.415-30.153c9.811,5.021,24.888,10.051,40.439,10.051 c16.751,0,25.607-6.935,25.607-17.465c0-10.052-7.662-15.795-27.05-22.734c-26.8-9.328-44.263-24.168-44.263-47.611 c0-27.524,22.971-48.579,61.014-48.579c18.188,0,31.591,3.823,41.159,8.131l-8.126,29.437c-6.465-3.116-17.945-7.657-33.745-7.657 c-15.791,0-23.454,7.183-23.454,15.552c0,10.296,9.089,14.842,29.917,22.731c28.468,10.536,41.871,25.365,41.871,48.094 c0,27.042-20.812,50.013-65.09,50.013C95.731,389.349,77.538,384.571,68.458,379.54z M453.601,523.353H97.2V419.302h356.4V523.353z M488.911,379.54c-11.243,3.823-32.537,9.103-53.831,9.103c-29.437,0-50.73-7.426-65.57-21.779 c-14.839-13.875-22.971-34.942-22.738-58.625c0.253-53.604,39.255-84.235,92.137-84.235c20.81,0,36.852,4.073,44.74,7.896 l-7.657,29.202c-8.859-3.829-19.849-6.95-37.567-6.95c-30.396,0-53.357,17.233-53.357,52.173c0,33.265,20.81,52.882,50.73,52.882 c8.375,0,15.072-0.96,17.94-2.395v-33.745h-24.875v-28.471h60.049V379.54L488.911,379.54z" /></svg>'
};
BALKANGraph.icon.csv = function(d, b, a) {
    return '<svg width="' + d + '" height="' + b + '" viewBox="0 0 548.29 548.291" ><path fill="' + a + '" d="M486.2,196.121h-13.164V132.59c0-0.399-0.064-0.795-0.116-1.2c-0.021-2.52-0.824-5-2.551-6.96L364.656,3.677 c-0.031-0.034-0.064-0.044-0.085-0.075c-0.629-0.707-1.364-1.292-2.141-1.796c-0.231-0.157-0.462-0.286-0.704-0.419 c-0.672-0.365-1.386-0.672-2.121-0.893c-0.199-0.052-0.377-0.134-0.576-0.188C358.229,0.118,357.4,0,356.562,0H96.757 C84.893,0,75.256,9.649,75.256,21.502v174.613H62.093c-16.972,0-30.733,13.756-30.733,30.73v159.81 c0,16.966,13.761,30.736,30.733,30.736h13.163V526.79c0,11.854,9.637,21.501,21.501,21.501h354.777 c11.853,0,21.502-9.647,21.502-21.501V417.392H486.2c16.966,0,30.729-13.764,30.729-30.731v-159.81 C516.93,209.872,503.166,196.121,486.2,196.121z M96.757,21.502h249.053v110.006c0,5.94,4.818,10.751,10.751,10.751h94.973v53.861 H96.757V21.502z M258.618,313.18c-26.68-9.291-44.063-24.053-44.063-47.389c0-27.404,22.861-48.368,60.733-48.368 c18.107,0,31.447,3.811,40.968,8.107l-8.09,29.3c-6.43-3.107-17.862-7.632-33.59-7.632c-15.717,0-23.339,7.149-23.339,15.485 c0,10.247,9.047,14.769,29.78,22.632c28.341,10.479,41.681,25.239,41.681,47.874c0,26.909-20.721,49.786-64.792,49.786 c-18.338,0-36.449-4.776-45.497-9.77l7.38-30.016c9.772,5.014,24.775,10.006,40.264,10.006c16.671,0,25.488-6.908,25.488-17.396 C285.536,325.789,277.909,320.078,258.618,313.18z M69.474,302.692c0-54.781,39.074-85.269,87.654-85.269 c18.822,0,33.113,3.811,39.549,7.149l-7.392,28.816c-7.38-3.084-17.632-5.939-30.491-5.939c-28.822,0-51.206,17.375-51.206,53.099 c0,32.158,19.051,52.4,51.456,52.4c10.947,0,23.097-2.378,30.241-5.238l5.483,28.346c-6.672,3.34-21.674,6.919-41.208,6.919 C98.06,382.976,69.474,348.424,69.474,302.692z M451.534,520.962H96.757v-103.57h354.777V520.962z M427.518,380.583h-42.399 l-51.45-160.536h39.787l19.526,67.894c5.479,19.046,10.479,37.386,14.299,57.397h0.709c4.048-19.298,9.045-38.352,14.526-56.693 l20.487-68.598h38.599L427.518,380.583z" /></svg>'
};
BALKANGraph.icon.excel = function(d, b, a) {
    return '<svg width="' + d + '" height="' + b + '" viewBox="0 0 512 512"><path fill="#ECEFF1" d="M496,432.011H272c-8.832,0-16-7.168-16-16s0-311.168,0-320s7.168-16,16-16h224 c8.832,0,16,7.168,16,16v320C512,424.843,504.832,432.011,496,432.011z" /><path fill="' + a + '" d="M336,176.011h-64c-8.832,0-16-7.168-16-16s7.168-16,16-16h64c8.832,0,16,7.168,16,16 S344.832,176.011,336,176.011z" /><path fill="' + a + '" d="M336,240.011h-64c-8.832,0-16-7.168-16-16s7.168-16,16-16h64c8.832,0,16,7.168,16,16 S344.832,240.011,336,240.011z" /><path fill="' + a + '" d="M336,304.011h-64c-8.832,0-16-7.168-16-16s7.168-16,16-16h64c8.832,0,16,7.168,16,16 S344.832,304.011,336,304.011z" /><path fill="' + a + '" d="M336,368.011h-64c-8.832,0-16-7.168-16-16s7.168-16,16-16h64c8.832,0,16,7.168,16,16 S344.832,368.011,336,368.011z" /><path fill="' + a + '" d="M432,176.011h-32c-8.832,0-16-7.168-16-16s7.168-16,16-16h32c8.832,0,16,7.168,16,16 S440.832,176.011,432,176.011z" /><path fill="' + a + '" d="M432,240.011h-32c-8.832,0-16-7.168-16-16s7.168-16,16-16h32c8.832,0,16,7.168,16,16 S440.832,240.011,432,240.011z" /><path fill="' + a + '" d="M432,304.011h-32c-8.832,0-16-7.168-16-16s7.168-16,16-16h32c8.832,0,16,7.168,16,16 S440.832,304.011,432,304.011z" /><path fill="' + a + '" d="M432,368.011h-32c-8.832,0-16-7.168-16-16s7.168-16,16-16h32c8.832,0,16,7.168,16,16 S440.832,368.011,432,368.011z" /><path fill="' + a + '"  d="M282.208,19.691c-3.648-3.04-8.544-4.352-13.152-3.392l-256,48C5.472,65.707,0,72.299,0,80.011v352 c0,7.68,5.472,14.304,13.056,15.712l256,48c0.96,0.192,1.952,0.288,2.944,0.288c3.712,0,7.328-1.28,10.208-3.68 c3.68-3.04,5.792-7.584,5.792-12.32v-448C288,27.243,285.888,22.731,282.208,19.691z" /><path fill="#FAFAFA" d="M220.032,309.483l-50.592-57.824l51.168-65.792c5.44-6.976,4.16-17.024-2.784-22.464 c-6.944-5.44-16.992-4.16-22.464,2.784l-47.392,60.928l-39.936-45.632c-5.856-6.72-15.968-7.328-22.56-1.504 c-6.656,5.824-7.328,15.936-1.504,22.56l44,50.304L83.36,310.187c-5.44,6.976-4.16,17.024,2.784,22.464 c2.944,2.272,6.432,3.36,9.856,3.36c4.768,0,9.472-2.112,12.64-6.176l40.8-52.48l46.528,53.152 c3.168,3.648,7.584,5.504,12.032,5.504c3.744,0,7.488-1.312,10.528-3.968C225.184,326.219,225.856,316.107,220.032,309.483z" /></svg>'
};
BALKANGraph.icon.edit = function(d, b, a) {
    return '<svg width="' + d + '" height="' + b + '" viewBox="0 0 528.899 528.899"><path fill="' + a + '" d="M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z M518.113,63.177l-47.981-47.981 c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611 C532.495,100.753,532.495,77.559,518.113,63.177z M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069 L27.473,390.597L0.3,512.69z" /></svg>'
};
BALKANGraph.icon.details = function(d, b, a) {
    return '<svg width="' + d + '" height="' + b + '" viewBox="0 0 512 512"><path fill="' + a + '" d="M447.933,103.629c-0.034-3.076-1.224-6.09-3.485-8.352L352.683,3.511c-0.004-0.004-0.007-0.005-0.011-0.008 C350.505,1.338,347.511,0,344.206,0H89.278C75.361,0,64.04,11.32,64.04,25.237v461.525c0,13.916,11.32,25.237,25.237,25.237 h333.444c13.916,0,25.237-11.32,25.237-25.237V103.753C447.96,103.709,447.937,103.672,447.933,103.629z M356.194,40.931 l50.834,50.834h-49.572c-0.695,0-1.262-0.567-1.262-1.262V40.931z M423.983,486.763c0,0.695-0.566,1.261-1.261,1.261H89.278 c-0.695,0-1.261-0.566-1.261-1.261V25.237c0-0.695,0.566-1.261,1.261-1.261h242.94v66.527c0,13.916,11.322,25.239,25.239,25.239 h66.527V486.763z"/><path fill="' + a + '" d="M362.088,164.014H149.912c-6.62,0-11.988,5.367-11.988,11.988c0,6.62,5.368,11.988,11.988,11.988h212.175 c6.62,0,11.988-5.368,11.988-11.988C374.076,169.381,368.707,164.014,362.088,164.014z"/><path fill="' + a + '" d="M362.088,236.353H149.912c-6.62,0-11.988,5.368-11.988,11.988c0,6.62,5.368,11.988,11.988,11.988h212.175 c6.62,0,11.988-5.368,11.988-11.988C374.076,241.721,368.707,236.353,362.088,236.353z"/><path fill="' + a + '" d="M362.088,308.691H149.912c-6.62,0-11.988,5.368-11.988,11.988c0,6.621,5.368,11.988,11.988,11.988h212.175 c6.62,0,11.988-5.367,11.988-11.988C374.076,314.06,368.707,308.691,362.088,308.691z"/><path fill="' + a + '" d="M256,381.031H149.912c-6.62,0-11.988,5.368-11.988,11.988c0,6.621,5.368,11.988,11.988,11.988H256 c6.62,0,11.988-5.367,11.988-11.988C267.988,386.398,262.62,381.031,256,381.031z"/></svg>'
};
BALKANGraph.icon.remove = function(d, b, a) {
    return '<svg width="' + d + '" height="' + b + '"  viewBox="0 0 900.5 900.5"><path fill="' + a + '" d="M176.415,880.5c0,11.046,8.954,20,20,20h507.67c11.046,0,20-8.954,20-20V232.487h-547.67V880.5L176.415,880.5z M562.75,342.766h75v436.029h-75V342.766z M412.75,342.766h75v436.029h-75V342.766z M262.75,342.766h75v436.029h-75V342.766z"/><path fill="' + a + '" d="M618.825,91.911V20c0-11.046-8.954-20-20-20h-297.15c-11.046,0-20,8.954-20,20v71.911v12.5v12.5H141.874 c-11.046,0-20,8.954-20,20v50.576c0,11.045,8.954,20,20,20h34.541h547.67h34.541c11.046,0,20-8.955,20-20v-50.576 c0-11.046-8.954-20-20-20H618.825v-12.5V91.911z M543.825,112.799h-187.15v-8.389v-12.5V75h187.15v16.911v12.5V112.799z"/></svg>'
};
BALKANGraph.icon.add = function(d, b, a) {
    return '<svg width="' + d + '" height="' + b + '"   viewBox="0 0 922 922"><path fill="' + a + '" d="M922,453V81c0-11.046-8.954-20-20-20H410c-11.045,0-20,8.954-20,20v149h318c24.812,0,45,20.187,45,45v198h149 C913.046,473.001,922,464.046,922,453z" /><path fill="' + a + '" d="M557,667.001h151c11.046,0,20-8.954,20-20v-174v-198c0-11.046-8.954-20-20-20H390H216c-11.045,0-20,8.954-20,20v149h194 h122c24.812,0,45,20.187,45,45v4V667.001z" /><path fill="' + a + '" d="M0,469v372c0,11.046,8.955,20,20,20h492c11.046,0,20-8.954,20-20V692v-12.501V667V473v-4c0-11.046-8.954-20-20-20H390H196 h-12.5H171H20C8.955,449,0,457.955,0,469z" /></svg>'
};
BALKANGraph.icon.search = function(b, a) {
    return '<svg width="' + b + '" height="' + a + '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 485.213 485.213"><path fill="#757575" d="M471.882,407.567L360.567,296.243c-16.586,25.795-38.536,47.734-64.331,64.321l111.324,111.324 c17.772,17.768,46.587,17.768,64.321,0C489.654,454.149,489.654,425.334,471.882,407.567z" /><path fill="#757575" d="M363.909,181.955C363.909,81.473,282.44,0,181.956,0C81.474,0,0.001,81.473,0.001,181.955s81.473,181.951,181.955,181.951 C282.44,363.906,363.909,282.437,363.909,181.955z M181.956,318.416c-75.252,0-136.465-61.208-136.465-136.46 c0-75.252,61.213-136.465,136.465-136.465c75.25,0,136.468,61.213,136.468,136.465 C318.424,257.208,257.206,318.416,181.956,318.416z" /><path fill="#757575" d="M75.817,181.955h30.322c0-41.803,34.014-75.814,75.816-75.814V75.816C123.438,75.816,75.817,123.437,75.817,181.955z" /></svg>'
};
OrgChart.prototype._O = function(g, b) {
    var h = this;
    document.body.style.mozUserSelect = document.body.style.webkitUserSelect = document.body.style.userSelect = "none";
    this.editUI.hide();
    this.searchUI.hide();
    this.nodeMenuUI.hide();
    this.menuUI.hide();
    var i = this.getViewBox();
    var f = this.getScale();
    var j = b[0].offsetX / f + i[0] - 16 / f;
    var k = b[0].offsetY / f + i[1] - 16 / f;
    var e = this.getPointerElement();
    e.style.display = "inherit";
    e.setAttribute("transform", "matrix(0,0,0,0," + j + "," + k + ")");
    BALKANGraph.animate(e, {
        transform: [0, 0, 0, 0, j, k],
        opacity: 0
    }, {
        transform: [1 / f, 0, 0, 1 / f, j, k],
        opacity: 1
    }, 300, BALKANGraph.animate.outBack);
    var a = {
        diffX: 0,
        diffY: 0,
        x: b[0].clientX,
        y: b[0].clientY,
        viewBoxLeft: i[0],
        viewBoxTop: i[1]
    };
    var d = function(l) {
        if (a) {
            a.diffX = l.clientX - a.x;
            a.diffY = l.clientY - a.y;
            var n = -(a.diffY / f) + a.viewBoxTop;
            var m = -(a.diffX / f) + a.viewBoxLeft;
            h._P(n, m, i)
        }
    };
    var c = function() {
        if (a.diffX = !0 || a.diffY != 0) {
            BALKANGraph._1(g, h.getViewBox(), h.response.boundary, function() {
                h._y(true, BALKANGraph.action.pan)
            })
        }
        a = null;
        e.style.display = "none";
        g.removeEventListener("mousemove", d);
        g.removeEventListener("mouseup", c);
        g.removeEventListener("mouseleave", c)
    };
    g.addEventListener("mousemove", d);
    g.addEventListener("mouseup", c);
    g.addEventListener("mouseleave", c)
};
BALKANGraph.input = function(f, k, h, i, e, c) {
    if (i == undefined) {
        i = false
    }
    var l = document.createElement("div");
    var g = document.createElement("div");
    var d = document.createElement("input");
    var b = document.createElement("hr");
    var j = document.createElement("button");
    j.innerHTML = "Upload";
    j.style.position = "absolute";
    j.style.right = 0;
    l.style.margin = "14px 14px 7px 14px";
    l.style.textAlign = "left";
    l.style.position = "relative";
    b.style.border = "1px solid #d7d7d7";
    b.style.backgroundColor = "#d7d7d7";
    b.style.display = "block";
    b.style.width = "100%";
    g.style.floa = "left";
    g.style.color = "#bcbcbc";
    d.style.border = "none";
    d.style.outline = "none";
    d.style.width = "100%";
    if (e) {
        d.style.width = "80%"
    }
    d.style.fontSize = "16px";
    d.readOnly = i;
    if (k != undefined && k != null) {
        d.value = k
    }
    if (h != undefined && h != null) {
        d.placeholder = h
    }
    if (f != undefined && f != null) {
        g.innerHTML = f
    }
    g.setAttribute("label", f);
    d.style.color = "#7a7a7a";
    if (!i) {
        d.addEventListener("focus", function() {
            var m = this.parentElement.getElementsByTagName("hr")[0];
            m.style.border = "1px solid #039BE5";
            BALKANGraph.animate(m, {
                width: 10
            }, {
                width: l.clientWidth
            }, 250, BALKANGraph.animate.inOutSin)
        })
    }
    j.addEventListener("click", function() {
        var n = this;
        var m = document.createElement("INPUT");
        m.setAttribute("type", "file");
        m.style.display = "none";
        m.onchange = function() {
            if (c) {
                var o = this.files[0];
                c(o, n.parentElement.querySelector("input"))
            }
        };
        document.body.appendChild(m);
        m.click()
    });
    d.addEventListener("blur", function() {
        var m = this.parentElement.getElementsByTagName("hr")[0];
        m.style.border = "1px solid #d7d7d7"
    });
    l.appendChild(g);
    l.appendChild(d);
    if (e) {
        l.appendChild(j)
    }
    l.appendChild(b);
    if (BALKANGraph.addValidation) {
        var a = {
            wrapper: l,
            label: g,
            input: d,
            hr: b
        };
        BALKANGraph.addValidation(f, k, a)
    }
    return l
};
OrgChart.editUI = function() {};
OrgChart.editUI.prototype.init = function(a) {
    this.obj = a;
    this.fields = null;
    this.node = null
};
OrgChart.editUI.prototype.show = function(d, e) {
    this.hide();
    var f = this;
    this.node = d;
    this.wrapperElement = document.getElementById("bgEditForm");
    if (this.wrapperElement) {
        this.obj.element.removeChild(this.wrapperElement)
    }
    this.wrapperElement = document.createElement("div");
    var b = document.createElement("div");
    var a = document.createElement("div");
    var c = window.matchMedia("(max-width: 1150px)").matches;
    var g = "400px";
    if (c) {
        g = "100%"
    }
    Object.assign(this.wrapperElement.style, {
        width: g,
        position: "absolute",
        top: 0,
        right: "-150px",
        opacity: 0,
        "border-left": "1px solid #d7d7d7",
        "text-align": "left",
        height: "100%",
        "background-color": "#ffffff"
    });
    if (e) {
        this._v(d, a)
    } else {
        this._t(d, b)
    }
};
OrgChart.editUI.prototype._v = function(f, a) {
    var l = this;
    var g = document.createElement("div");
    var c = document.createElement("div");
    var b = document.createElement("div");
    var k = document.createElement("div");
    c.innerHTML = '<svg style="width: 34px; height: 34px;"><path style="fill:#ffffff;" d="M21.205,5.007c-0.429-0.444-1.143-0.444-1.587,0c-0.429,0.429-0.429,1.143,0,1.571l8.047,8.047H1.111 C0.492,14.626,0,15.118,0,15.737c0,0.619,0.492,1.127,1.111,1.127h26.554l-8.047,8.032c-0.429,0.444-0.429,1.159,0,1.587 c0.444,0.444,1.159,0.444,1.587,0l9.952-9.952c0.444-0.429,0.444-1.143,0-1.571L21.205,5.007z"></path></svg>';
    Object.assign(c.style, {
        cursor: "pointer",
        width: "34px",
        height: "34px",
        position: "absolute",
        top: "7px",
        right: "7px"
    });
    Object.assign(b.style, {
        "overflow-x": "hidden",
        "overflow-y": "auto"
    });
    Object.assign(g.style, {
        "background-color": "#039BE5",
        "min-height": "50px",
        textAlign: "center",
        position: "relative"
    });
    Object.assign(k.style, {
        margin: "12px"
    });
    b.style.height = (this.obj.element.offsetHeight - g.offsetHeight) + "px";
    this.wrapperElement.appendChild(a);
    a.appendChild(g);
    a.appendChild(b);
    b.appendChild(k);
    g.appendChild(c);
    BALKANGraph.htmlRipple(g);
    for (var d = 0; d < this.fields.length; d++) {
        var m = f.data[this.fields[d]];
        if (BALKANGraph._Y(this.obj.config, this.fields[d])) {
            var e = document.createElement("img");
            e.src = m;
            e.style.width = "100px";
            e.style.margin = "10px";
            e.style.borderRadius = "50px";
            g.appendChild(e)
        } else {
            if (this.fields[d] == "tags") {
                if (m) {
                    for (var h = 0; h < m.length; h++) {
                        var j = document.createElement("span");
                        Object.assign(j.style, {
                            "background-color": "#F57C00",
                            color: "#ffffff",
                            margin: "2px",
                            padding: "2px 12px",
                            "border-radius": "10px",
                            display: "inline-block",
                            border: "1px solid #FFCA28",
                            "user-select": "none"
                        });
                        j.innerHTML = m[h];
                        k.appendChild(j)
                    }
                }
            } else {
                b.appendChild(BALKANGraph.input(this.fields[d], m, null, true))
            }
        }
    }
    this.obj.element.appendChild(this.wrapperElement);
    g.addEventListener("click", function() {
        l.hide(false)
    });
    BALKANGraph.animate(this.wrapperElement, {
        right: -150,
        opacity: 0
    }, {
        right: 0,
        opacity: 0.9
    }, 300, BALKANGraph.animate.inOutSin)
};
OrgChart.editUI.prototype._t = function(j, d) {
    var l = this;
    var k = document.createElement("div");
    var f = document.createElement("div");
    var a = document.createElement("div");
    var e = document.createElement("div");
    var b = document.createElement("div");
    f.innerHTML = '<svg style="width: 34px; height: 34px;"><path style="fill:#ffffff;" d="M21.205,5.007c-0.429-0.444-1.143-0.444-1.587,0c-0.429,0.429-0.429,1.143,0,1.571l8.047,8.047H1.111 C0.492,14.626,0,15.118,0,15.737c0,0.619,0.492,1.127,1.111,1.127h26.554l-8.047,8.032c-0.429,0.444-0.429,1.159,0,1.587 c0.444,0.444,1.159,0.444,1.587,0l9.952-9.952c0.444-0.429,0.444-1.143,0-1.571L21.205,5.007z"></path></svg>';
    this.wrapperElement.id = "bgEditForm";
    Object.assign(f.style, {
        cursor: "pointer",
        width: "34px",
        height: "34px",
        position: "absolute",
        top: "7px",
        right: "7px"
    });
    Object.assign(e.style, {
        "overflow-x": "hidden",
        "overflow-y": "auto"
    });
    Object.assign(k.style, {
        "background-color": "#039BE5",
        "min-height": "50px",
        textAlign: "center",
        position: "relative"
    });
    Object.assign(a.style, {
        margin: "14px 14px 7px",
        color: "#4285F4",
        cursor: "pointer"
    });
    Object.assign(b.style, {
        margin: "14px 14px 7px",
        color: "rgb(188, 188, 188)"
    });
    e.style.height = (this.obj.element.offsetHeight - k.offsetHeight) + "px";
    a.innerHTML = "Add new field";
    var c = BALKANGraph._e(this.node.data.tags, "assistant") ? "checked" : "";
    b.innerHTML = '<div label="isAssistant" style="margin-top: 10px;display:inline-block;">Assistant</div><label class="bg-switch"><input type="checkbox" ' + c + '><span class="bg-slider round"></span></label>';
    this.wrapperElement.appendChild(d);
    d.appendChild(k);
    d.appendChild(e);
    k.appendChild(f);
    BALKANGraph.htmlRipple(k);
    for (var g = 0; g < this.fields.length; g++) {
        var m = j.data[this.fields[g]];
        if (this.fields[g] != "tags") {
            if (BALKANGraph._Y(this.obj.config, this.fields[g])) {
                if (m) {
                    var h = document.createElement("img");
                    h.src = m;
                    h.style.width = "100px";
                    h.style.margin = "10px";
                    h.style.borderRadius = "50px";
                    k.appendChild(h)
                }
                e.appendChild(BALKANGraph.input(this.fields[g], m, null, false, true, this.obj.config.onImageUploaded))
            } else {
                e.appendChild(BALKANGraph.input(this.fields[g], m, null, false))
            }
        }
    }
    if (j.children.length == 0) {
        e.appendChild(b)
    }
    e.appendChild(a);
    this.obj.element.appendChild(this.wrapperElement);
    k.addEventListener("click", function() {
        l.hide(true)
    });
    a.addEventListener("click", function() 
    {
        if (a.innerHTML == "Save") 
        {
            BALKANGraph.animate(a, {
                opacity: 1
            }, {
                opacity: 0
            }, 200, BALKANGraph.animate.inSin, function() {
                a.innerHTML = "Add new field";
                a.style.textAlign = "left";
                var n = document.getElementById("bgNewField");
                var o = n.getElementsByTagName("input")[0].value;
                e.removeChild(n);
                if (o && !BALKANGraph._e(l.fields, o)) {
                    var p = BALKANGraph.input(o);
                    p.style.opacity = 0;
                    e.insertBefore(p, a);
                    BALKANGraph.animate(p, {
                        opacity: 0
                    }, {
                        opacity: 1
                    }, 200, BALKANGraph.animate.inSin, function() {
                        p.getElementsByTagName("input")[0].focus()
                    })
                }
                BALKANGraph.animate(a, {
                    opacity: 0
                }, {
                    opacity: 1
                }, 200, BALKANGraph.animate.inSin)
            })
        } 
        else
        {
            BALKANGraph.animate(a, {
                opacity: 1
            }, {
                opacity: 0
            }, 200, BALKANGraph.animate.inSin, function() {
                a.innerHTML = "Save";
                a.style.textAlign = "right";
                BALKANGraph.animate(a, {
                    opacity: 0
                }, {
                    opacity: 1
                }, 200, BALKANGraph.animate.inSin)
            });

            var i = BALKANGraph.input(null, null, "Field name");
            i.style.opacity = 0;
            i.id = "bgNewField";
            e.appendChild(i);
            BALKANGraph.animate(i,
            {  
            	opacity: 0

            }, {
                opacity: 1
            }, 200, BALKANGraph.animate.inSin, function() {
                i.getElementsByTagName("input")[0].focus()
            });
        }
    });
    BALKANGraph.animate(this.wrapperElement, {
        right: -150,
        opacity: 0
    }, {
        right: 0,
        opacity: 0.9
    }, 300, BALKANGraph.animate.inOutSin, function() {
        if (l.wrapperElement.getElementsByTagName("input").length > 1) {
            l.wrapperElement.getElementsByTagName("input")[0].focus()
        }
    })
};
OrgChart.editUI.prototype.hide = function(e) {
    if (!this.wrapperElement) {
        return
    }
    if (e) {
        var d = this.wrapperElement.querySelectorAll("[label]");
        for (var b = 0; b < d.length; b++) {
            var c = d[b].getAttribute("label");
            if (c != null) {
                var g = d[b].parentElement.getElementsByTagName("input")[0].value;
                if (c === BALKANGraph.TAGS) {
                    this.node.data.tags = g.split(",")
                } else {
                    if (c === "isAssistant") {
                        var a = d[b].parentElement.getElementsByTagName("input")[0].checked;
                        if (a && this.node.data.tags) {
                            if (!BALKANGraph._e(this.node.data.tags, "assistant")) {
                                this.node.data.tags.push("assistant")
                            }
                        } else {
                            if (a && !this.node.data.tags) {
                                this.node.data.tags = ["assistant"]
                            } else {
                                if (!a && this.node.data.tags) {
                                    if (this.node.data.tags.indexOf("assistant") != -1) {
                                        this.node.data.tags.splice(this.node.data.tags.indexOf("assistant"), 1)
                                    }
                                }
                            }
                        }
                    } else {
                        if (this.node.data[c] != undefined) {
                            this.node.data[c] = g
                        } else {
                            if (g != "") {
                                this.node.data[c] = g
                            }
                        }
                    }
                }
            }
        }
        var f = this;
        this.obj._ay(this.node.data);
        BALKANGraph.animate(f.wrapperElement, {
            right: 0,
            opacity: 1
        }, {
            right: -150,
            opacity: 0
        }, 300, BALKANGraph.animate.inOutSin, function() {
            f.obj.element.removeChild(f.wrapperElement);
            f.wrapperElement = null
        })
    } else {
        this.obj.element.removeChild(this.wrapperElement);
        this.wrapperElement = null
    }
};
OrgChart.ui = {
    _g: {},
    defs: function() {
        var a = "";
        for (var c in OrgChart.templates) {
            var b = OrgChart.templates[c];
            if (b.defs) {
                OrgChart.ui._g[c] = BALKANGraph._0();
                a += b.defs.replace("{randId}", OrgChart.ui._g[c])
            } else {
                a += b.defs
            }
        }
        return "<defs>" + a + "</defs>"
    },
    css: function() {
        var a = '.bg-ripple-container {position: absolute; top: 0; right: 0; bottom: 0; left: 0; } .bg-ripple-container span {transform: scale(0);border-radius:100%;position:absolute;opacity:0.75;background-color:#fff;animation: bg-ripple 1000ms; }@-moz-keyframes bg-ripple {to {opacity: 0;transform: scale(2);}}@-webkit-keyframes bg-ripple {to {opacity: 0;transform: scale(2);}}@-o-keyframes bg-ripple {to {opacity: 0;transform: scale(2);}}@keyframes bg-ripple {to {opacity: 0;transform: scale(2);}}.bg-switch {position:relative;display:inline-block;width:60px;height:24px;float:right;}.bg-switch input {opacity:0;width:0;height:0;}.bg-slider {position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color: #ccc;-webkit-transition:.4s;transition: .4s;}.bg-slider:before {position:absolute;content:"";height:16px;width:16px;left:4px;bottom:4px;background-color:white;-webkit-transition:.4s;transition:.4s;}input:checked + .bg-slider {background-color:#2196F3;}input:focus + .bg-slider {box-shadow:0 0 1px #2196F3;}input:checked + .bg-slider:before {-webkit-transform:translateX(34px);-ms-transform:translateX(34px);transform:translateX(34px);}.bg-slider.round {border-radius:24px;}.bg-slider.round:before {border-radius:50%;}';
        return "<style>" + a + "</style>"
    },
    lonely: function(a) {
        if (!a.nodes || !a.nodes.length) {
            return BALKANGraph.IT_IS_LONELY_HERE.replace("{link}", BALKANGraph.RES.IT_IS_LONELY_HERE_LINK)
        } else {
            return ""
        }
    },
    pointer: function(a) {
        var b = OrgChart.templates[a.template];
        return b.pointer
    },
    node: function(g, b, c, n, p, h) {
        var l = OrgChart.templates[g.templateName];
        var i = l.node;
        if (l.defs) {
            i = i.replace("{randId}", OrgChart.ui._g[g.templateName])
        }
        if (h == undefined) {
            h = c.nodeBinding
        }
        for (var d in h) {
            var m = h[d];
            var k = g.data[m];
            if (k != undefined && k != null && l[d] != undefined) {
                var e = l[d].replace("{val}", k);
                e = e.replaceAll("{randId}", BALKANGraph._0()).replaceAll("{randId2}", BALKANGraph._0());
                i += e
            }
        }
        var o = n;
        if (o == undefined) {
            if (b[g.id] != undefined && b[g.id].from.transform) {
                o = b[g.id].from.transform[4]
            }
        }
        if (o == undefined) {
            o = g.x
        }
        var q = n;
        if (q == undefined) {
            if (b[g.id] != undefined && b[g.id].from.transform) {
                q = b[g.id].from.transform[5]
            }
        }
        if (q == undefined) {
            q = g.y
        }
        var j = BALKANGraph.nodeOpenTag.replace("{id}", g.id).replace("{class}", "node " + g.tags.join(" ")).replace("{level}", g.level).replace("{x}", o).replace("{y}", q);
        if (b[g.id]) {
            var a = b[g.id];
            if (a.from.opacity != undefined) {
                j = j.replace("{opacity}", a.from.opacity)
            }
        } else {
            j = j.replace("{opacity}", 1)
        }
        if (c.nodeMenu != null) {
            i += l.nodeMenuButton.replace("{id}", g.id)
        }
        i = j + i + BALKANGraph.grCloseTag;
        return i
    },
    expandCollapse: function(c, a) {
        if (!a.expandToLevel) {
            return ""
        }
        if (c.children.length == 0) {
            return ""
        }
        if (a.layout == BALKANGraph.mixed && c.isLastChild) {
            return ""
        }
        var b = "";
        var e = 0;
        var f = 0;
        var d = OrgChart.templates[c.templateName];
        switch (a.orientation) {
            case BALKANGraph.orientation.top:
            case BALKANGraph.orientation.top_left:
                e = c.x + (c.w / 2);
                f = c.y + c.h;
                break;
            case BALKANGraph.orientation.bottom:
            case BALKANGraph.orientation.bottom_left:
                e = c.x + (c.w / 2);
                f = c.y;
                break;
            case BALKANGraph.orientation.right:
            case BALKANGraph.orientation.right_top:
                e = c.x;
                f = c.y + (c.h / 2);
                break;
            case BALKANGraph.orientation.left:
            case BALKANGraph.orientation.left_top:
                e = c.x + c.w;
                f = c.y + (c.h / 2);
                break
        }
        e = e - d.expandCollapseSize / 2;
        f = f - d.expandCollapseSize / 2;
        if (c.state == BALKANGraph.COLLAPSE) {
            b += BALKANGraph.expcollOpenTag.replace("{id}", c.id).replace("{x}", e).replace("{y}", f);
            b += d.plus;
            b += BALKANGraph.grCloseTag
        }
        if (c.state == BALKANGraph.EXPAND) {
            b += BALKANGraph.expcollOpenTag.replace("{id}", c.id).replace("{x}", e).replace("{y}", f);
            b += d.minus;
            b += BALKANGraph.grCloseTag
        }
        return b
    },
    link: function(k, b) {
        var p = OrgChart.templates[k.templateName];
        var g = [];
        var s = 0,
            A = 0,
            t = 0,
            B = 0,
            u = 0,
            C = 0,
            v = 0,
            D = 0,
            w = 0,
            E = 0,
            r = 0,
            z = 0,
            o = 0;
        for (var d = 0; d < k.assistants.length; d++) {
            var l = k.assistants[d];
            var a = p.assistanseLink;
            switch (b.orientation) {
                case BALKANGraph.orientation.top:
                case BALKANGraph.orientation.top_left:
                    s = t = u = v = k.x + k.w;
                    A = B = C = D = k.y + k.h / 2;
                    if (k.assistants.length == 1) {
                        w = l.x;
                        E = l.y + l.h / 2
                    } else {
                        t = s + b.siblingSeparation / 2;
                        B = A;
                        u = t;
                        C = k.y - b.levelSeparation / 4;
                        v = l.x + l.w / 2;
                        D = C;
                        w = v;
                        E = l.y
                    }
                    break;
                case BALKANGraph.orientation.bottom:
                case BALKANGraph.orientation.bottom_left:
                    s = t = u = v = k.x + k.w;
                    A = B = C = D = k.y + k.h / 2;
                    if (k.assistants.length == 1) {
                        w = l.x;
                        E = l.y + l.h / 2
                    } else {
                        t = s + b.siblingSeparation / 2;
                        B = A;
                        u = t;
                        C = k.y + k.h + b.levelSeparation / 4;
                        v = l.x + l.w / 2;
                        D = C;
                        w = v;
                        E = l.y
                    }
                    break;
                case BALKANGraph.orientation.right:
                case BALKANGraph.orientation.right_top:
                    s = t = u = v = k.x + k.w / 2;
                    A = B = C = D = k.y + k.h;
                    if (k.assistants.length == 1) {
                        w = l.x + l.w / 2;
                        E = l.y
                    } else {
                        t = s;
                        B = A + b.siblingSeparation / 2;
                        u = k.x + k.w + b.levelSeparation / 4;
                        C = B;
                        v = u;
                        D = l.y + l.h / 2;
                        w = l.x + l.w;
                        E = D
                    }
                    break;
                case BALKANGraph.orientation.left:
                case BALKANGraph.orientation.left_top:
                    s = t = u = v = k.x + k.w / 2;
                    A = B = C = D = k.y + k.h;
                    if (k.assistants.length == 1) {
                        w = l.x + l.w / 2;
                        E = l.y
                    } else {
                        t = s;
                        B = A + b.siblingSeparation / 2;
                        u = k.x - b.levelSeparation / 4;
                        C = B;
                        v = u;
                        D = l.y + l.h / 2;
                        w = l.x + l.w;
                        E = D
                    }
                    break
            }
            a = a.replace("{xa}", s).replace("{ya}", A).replace("{xb}", t).replace("{yb}", B).replace("{xc}", u).replace("{yc}", C).replace("{xd}", v).replace("{yd}", D).replace("{xe}", w).replace("{ye}", E);
            g.push(BALKANGraph.linkOpenTag.replace("{id}", k.id).replace("{class}", "link").replace("{level}", k.level).replace("{child-id}", a.id));
            g.push(a)
        }
        if (k.state == BALKANGraph.COLLAPSE || k._l() == 0) {
            return g.join("")
        }
        var e = b.levelSeparation / 2;
        if (b.layout == BALKANGraph.mixed && k.isLastChild) {
            e = b.mixedHierarchyNodesSeparation / 2
        }
        for (var d = 0; d < k.children.length; d++) {
            var m = k.children[d];
            p = OrgChart.templates[m.templateName];
            var j = p.link;
            switch (b.orientation) {
                case BALKANGraph.orientation.top:
                case BALKANGraph.orientation.top_left:
                    s = k.x + (k.w / 2) + p.linkAdjuster.toX;
                    A = k.y + k.h + p.linkAdjuster.toY;
                    v = u = m.x + (m.w / 2) + p.linkAdjuster.fromX;
                    D = m.y + p.linkAdjuster.fromY;
                    t = s;
                    B = C = D - e;
                    r = u;
                    z = C + 16;
                    break;
                case BALKANGraph.orientation.bottom:
                case BALKANGraph.orientation.bottom_left:
                    s = k.x + (k.w / 2) + p.linkAdjuster.toX;
                    A = k.y + p.linkAdjuster.toY;
                    v = u = m.x + (m.w / 2) + p.linkAdjuster.fromX;
                    D = m.y + m.h + p.linkAdjuster.fromY;
                    t = s;
                    B = C = D + b.levelSeparation / 2;
                    r = u;
                    z = C - 14;
                    break;
                case BALKANGraph.orientation.right:
                case BALKANGraph.orientation.right_top:
                    s = k.x + p.linkAdjuster.toX;
                    A = k.y + (k.h / 2) + p.linkAdjuster.toY;
                    v = m.x + m.w + p.linkAdjuster.fromX;
                    D = C = m.y + (m.h / 2) + p.linkAdjuster.fromY;
                    B = A;
                    t = u = v + b.levelSeparation / 2;
                    r = u - 16;
                    z = C;
                    o = 90;
                    break;
                case BALKANGraph.orientation.left:
                case BALKANGraph.orientation.left_top:
                    s = k.x + k.w + p.linkAdjuster.toX;
                    A = k.y + (k.h / 2) + p.linkAdjuster.toY;
                    v = m.x + p.linkAdjuster.fromX;
                    D = C = m.y + (m.h / 2) + p.linkAdjuster.fromY;
                    B = A;
                    t = u = v - b.levelSeparation / 2;
                    r = u + 14;
                    z = C;
                    o = 270;
                    break
            }
            j = j.replace("{xa}", s).replace("{ya}", A).replace("{xb}", t).replace("{yb}", B).replace("{xc}", u).replace("{yc}", C).replace("{xd}", v).replace("{yd}", D);
            g.push(BALKANGraph.linkOpenTag.replace("{id}", k.id).replace("{class}", "link " + m.tags.join(" ")).replace("{level}", k.level).replace("{child-id}", m.id));
            g.push(j);
            var h = "";
            for (var c in b.linkBinding) {
                var q = b.linkBinding[c];
                var n = m.data[q];
                if (n != undefined && n != null && p[c] != undefined) {
                    h += p[c].replace("{val}", n)
                }
            }
            if (h != "") {
                h = BALKANGraph.linkFieldsOpenTag.replace("{x}", r).replace("{y}", z).replace("{rotate}", o) + h + BALKANGraph.grCloseTag;
                g.push(h)
            }
            g.push(BALKANGraph.grCloseTag)
        }
        return g.join("")
    },
    svg: function(f, c, e, a, b) {
        var d = OrgChart.templates[a.template].svg.replace("{w}", f).replace("{h}", c).replace("{viewBox}", e).replace("{content}", b);
        return d
    },
    wrapper: function(f, c, e, a, b) {
        var d = OrgChart.templates[a.template].svg.replace("{w}", f).replace("{h}", c).replace("{viewBox}", e).replace("{content}", b);
        return d
    },
    exportMenuButton: function(a) {
        if (a.menu == null) {
            return ""
        }
        var b = OrgChart.templates[a.template];
        return b.exportMenuButton.replaceAll("{p}", a.padding)
    }
};
BALKANGraph.menuUI = function() {};
BALKANGraph.menuUI.prototype.init = function(b, a) {
    this.obj = b;
    this.wrapper = null;
    this.menu = a
};
BALKANGraph.menuUI.prototype.show = function(l, m, g) {
    var k = this;
    this.hide();
    var b = "";
    for (var e in this.menu) {
        var d = this.menu[e].icon;
        var j = this.menu[e].text;
        if (d === undefined) {
            d = BALKANGraph.icon[e](24, 24, "#7A7A7A")
        }
        b += '<div data-item="' + e + '" style="border-bottom: 1px solid #D7D7D7; padding: 7px 10px;color: #7A7A7A;">' + d + "<span>&nbsp;&nbsp;" + j + "</span></div>"
    }
    if (b != "") {
        this.wrapper = document.createElement("div");
        Object.assign(this.wrapper.style, {
            opacity: 0,
            "background-color": "#FFFEFF",
            "box-shadow": "#DCDCDC 0px 1px 2px 0px",
            display: "inline-block",
            border: "1px solid #D7D7D7;border-radius:5px",
            "z-index": 1000,
            position: "absolute",
            "text-align": "left",
            "user-select": "none"
        });
        var h = l - 45;
        this.wrapper.style.left = h + "px";
        this.wrapper.style.top = m + "px";
        this.wrapper.innerHTML = b;
        this.obj.element.appendChild(this.wrapper);
        this.wrapper.style.left = h - this.wrapper.offsetWidth + "px";
        var a = l - this.wrapper.offsetWidth;
        BALKANGraph.animate(this.wrapper, {
            opacity: 0,
            left: h - this.wrapper.offsetWidth
        }, {
            opacity: 1,
            left: a
        }, 300, BALKANGraph.animate.inOutPow);
        var f = this.wrapper.getElementsByTagName("div");
        for (var c = 0; c < f.length; c++) {
            var e = f[c];
            e.addEventListener("mouseover", function() {
                this.style.backgroundColor = "#F0F0F0"
            });
            e.addEventListener("mouseleave", function() {
                this.style.backgroundColor = "#FFFFFF"
            });
            e.addEventListener("click", function() {
                var n = this.getAttribute("data-item");
                var p = k.menu[n].onClick;
                if (p === undefined) {
                    if (n === "add") {
                        var i = {
                            id: BALKANGraph._0(),
                            pid: g
                        };
                        k.obj._w(i)
                    } else {
                        if (n === "edit") {
                            var o = k.obj.response.visibleNodes[g];
                            k.obj.editUI.show(o)
                        } else {
                            if (n === "details") {
                                var o = k.obj.response.visibleNodes[g];
                                k.obj.editUI.show(o, true)
                            } else {
                                if (n === "remove") {
                                    k.obj._az(g)
                                } else {
                                    if (n === "svg") {
                                        k.obj.exportSVG()
                                    } else {
                                        if (n === "csv") {
                                            k.obj.exportCSV()
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    k.menu[n].onClick.call(k.obj, g)
                }
                k.hide()
            })
        }
    }
};
BALKANGraph.menuUI.prototype.hide = function() {
    if (this.wrapper != null) {
        this.obj.element.removeChild(this.wrapper);
        this.wrapper = null
    }
};
OrgChart.server = function(a) {
    this.config = a;
    this.visibleNodes = null;
    this.viewBox = null;
    this.action = null;
    this.actionParams = null
};
OrgChart.server.prototype.read = function(d, f, c, e, a, b) {
    this.viewBox = e;
    this.action = a;
    this.actionParams = b;
    if (!d) {
        this._V();
        this._aq(f, c)
    }
    return this._E(f, c)
};
OrgChart.server.prototype._V = function() {
    if (this.nodes) {
        this.oldNodes = this.nodes
    } else {
        this.oldNodes = null
    }
    this.nodes = null;
    this.roots = null;
    this.minX = null;
    this.minY = null;
    this.maxX = null;
    this.maxY = null;
    this._U = [];
    this._J = [];
    this._9 = []
};
OrgChart.server.prototype._E = function(u, i) {
    var s = {};
    var q = this.visibleNodes;
    var d = {
        top: null,
        left: null,
        bottom: null,
        right: null,
        minX: null,
        maxX: null,
        minY: null,
        maxY: null
    };
    var c = {};
    var l = this.maxX + this.config.padding * 2;
    var m = this.maxY + this.config.padding * 2;
    switch (this.config.orientation) {
        case BALKANGraph.orientation.bottom:
        case BALKANGraph.orientation.bottom_left:
            m = Math.abs(this.minY) + this.config.padding * 2;
            break;
        case BALKANGraph.orientation.right:
        case BALKANGraph.orientation.right_top:
            l = Math.abs(this.minX) + this.config.padding * 2;
            break
    }
    var r = BALKANGraph.getScale(this.viewBox, u, i, this.config.scaleInitial, l, m);
    d.top = this.minY - this.config.padding;
    d.left = this.minX - this.config.padding;
    d.bottom = this.maxY + this.config.padding - i / r;
    d.right = this.maxX + this.config.padding - u / r;
    d.maxX = this.maxX;
    d.minX = this.minX;
    d.maxY = this.maxY;
    d.minY = this.minY;
    switch (this.config.orientation) {
        case BALKANGraph.orientation.top:
        case BALKANGraph.orientation.top_left:
        case BALKANGraph.orientation.left:
        case BALKANGraph.orientation.left_top:
            if (this.maxY < i / r) {
                d.bottom = this.minY - this.config.padding;
                d.top = this.maxY + this.config.padding - i / r
            }
            if (this.maxX < u / r) {
                d.right = this.minX - this.config.padding;
                d.left = (this.maxX + this.config.padding - u / r)
            }
            break;
        case BALKANGraph.orientation.bottom:
        case BALKANGraph.orientation.bottom_left:
            if (Math.abs(this.minY) < i / r) {
                d.bottom = this.minY - this.config.padding;
                d.top = Math.abs(this.maxY) + this.config.padding - i / r
            }
            if (this.maxX < u / r) {
                d.right = this.minX - this.config.padding;
                d.left = this.maxX + this.config.padding - u / r
            }
            break;
        case BALKANGraph.orientation.right:
        case BALKANGraph.orientation.right_top:
            if (this.maxY < i / r) {
                d.bottom = this.minY - this.config.padding;
                d.top = this.maxY + this.config.padding - i / r
            }
            if (Math.abs(this.minX) < u / r) {
                d.right = this.minX - this.config.padding;
                d.left = Math.abs(this.maxX) + this.config.padding - u / r
            }
            break
    }
    if (this.viewBox == null) {
        var t = u / r;
        var g = i / r;
        var v = 0;
        var z = 0;
        if (t - this.config.padding * 2 >= this.maxX - this.minX) {
            v = (this.maxX - this.minX) / 2 - t / 2;
            switch (this.config.orientation) {
                case BALKANGraph.orientation.right:
                case BALKANGraph.orientation.right_top:
                    v = (this.minX - this.maxX) / 2 - t / 2;
                    break
            }
        } else {
            v = this.roots[0].x - t / 2 + OrgChart.server._X(this.roots[0], this.config) / 2;
            switch (this.config.orientation) {
                case BALKANGraph.orientation.right:
                case BALKANGraph.orientation.right_top:
                    v = -((t / 2) - (this.minX - this.maxX) / 2);
                    if (v < this.config.padding - t) {
                        v = this.config.padding - t
                    }
                    break;
                case BALKANGraph.orientation.left:
                case BALKANGraph.orientation.bottom_left:
                case BALKANGraph.orientation.top_left:
                case BALKANGraph.orientation.left_top:
                    v = -((t / 2) - (this.maxX - this.minX) / 2);
                    if (v > -this.config.padding) {
                        v = -this.config.padding
                    }
                    break
            }
        }
        if (g - this.config.padding * 2 >= this.maxY - this.minY) {
            z = (this.maxY - this.minY) / 2 - g / 2;
            switch (this.config.orientation) {
                case BALKANGraph.orientation.bottom:
                case BALKANGraph.orientation.bottom_left:
                    z = (this.minY - this.maxY) / 2 - g / 2;
                    break
            }
        } else {
            z = -((g / 2) - (this.maxY - this.minY) / 2);
            if (z > -this.config.padding) {
                z = -this.config.padding
            }
            switch (this.config.orientation) {
                case BALKANGraph.orientation.bottom:
                case BALKANGraph.orientation.bottom_left:
                    z = -((g / 2) - (this.minY - this.maxY) / 2);
                    if (z < this.config.padding - g) {
                        z = this.config.padding - g
                    }
                    break;
                case BALKANGraph.orientation.left:
                case BALKANGraph.orientation.right:
                    z = this.roots[0].y - g / 2 + OrgChart.server._X(this.roots[0], this.config) / 2;
                    break
            }
        }
        this.viewBox = [v, z, t, g]
    }
    var f = null;
    if (this.action == BALKANGraph.action.expandCollapse) {
        f = this.nodes[this.actionParams.id]
    }
    if (this.action == BALKANGraph.action.centerNode) {
        var e = this.nodes[this.actionParams.id];
        this.viewBox[0] = (e.x + e.w / 2 - this.viewBox[2] / 2);
        this.viewBox[1] = (e.y + e.h / 2 - this.viewBox[3] / 2);
        if (this.viewBox[0] < d.left && this.viewBox[0] < d.right) {
            this.viewBox[0] = d.left
        }
        if (this.viewBox[0] > d.right && this.viewBox[0] > d.left) {
            this.viewBox[0] = d.right
        }
        if (this.viewBox[1] < d.top && this.viewBox[1] < d.bottom) {
            this.viewBox[1] = d.top
        }
        if (this.viewBox[1] > d.bottom && this.viewBox[1] > d.top) {
            this.viewBox[1] = d.bottom
        }
    }
    for (var k in this.nodes) {
        var o = this.nodes[k];
        if (this._H(o)) {
            s[o.id] = o;
            var b = null;
            if (this.action == BALKANGraph.action.expandCollapse && this.oldNodes && this.oldNodes[o.id]) {
                var p = this.oldNodes[o.id];
                b = {
                    x: p.x,
                    y: p.y,
                };
                var f = this.nodes[this.actionParams.id];
                if (f && o._B(f)) {
                    b = {
                        x: f.x,
                        y: f.y,
                    }
                }
            } else {
                if (this.action == BALKANGraph.action.insert && this.actionParams && this.actionParams.insertedNodeId == o.id) {
                    b = {
                        x: o.parent.x,
                        y: o.parent.y,
                    }
                } else {
                    if ((this.action == BALKANGraph.action.update || this.action == BALKANGraph.action.insert) && this.oldNodes && this.oldNodes[o.id]) {
                        var p = this.oldNodes[o.id];
                        b = {
                            x: p.x,
                            y: p.y,
                        }
                    } else {
                        if (q && !q[o.id]) {
                            c[o.id] = {
                                from: {
                                    opacity: 0
                                },
                                to: {
                                    opacity: 1
                                },
                                duration: 300,
                                func: "outPow"
                            }
                        }
                    }
                }
            }
            if (b != null) {
                c[o.id] = {
                    from: {
                        transform: [1, 0, 0, 1, b.x, b.y]
                    },
                    to: {
                        transform: [1, 0, 0, 1, o.x, o.y]
                    },
                    duration: 200,
                    func: "outBack"
                }
            }
            for (var n = 0; n < this.config.nodes.length; n++) {
                if (this.config.nodes[n][BALKANGraph.ID] == o.id) {
                    o.data = this.config.nodes[n];
                    break
                }
            }
        }
    }
    this.visibleNodes = s;
    return {
        animations: c,
        boundary: d,
        viewBox: this.viewBox,
        visibleNodes: s,
        allFields: OrgChart.server.getAllFields(this.config)
    }
};
OrgChart.server.getAllFields = function(a) {
    var b = [BALKANGraph.TAGS];
    for (var c in a.nodeParams) {
        b.push(a.nodeBinding[c])
    }
    for (var c = 0; c < a.nodes.length; c++) {
        for (var d in a.nodes[c]) {
            if (d === BALKANGraph.ID) {
                continue
            }
            if (d === BALKANGraph.TAGS) {
                continue
            }
            if (d === BALKANGraph.PID) {
                continue
            }
            if (a.nodeBinding[d]) {
                continue
            }
            if (!BALKANGraph._e(b, d)) {
                b.push(d)
            }
        }
    }
    return b
};
OrgChart.server.prototype._aq = function(r, c) {
    var k = {};
    var n = [];
    for (var d = 0; d < this.config.nodes.length; d++) {
        var o = this.config.nodes[d];
        var q = this.config.template;
        if (o.tags) {
            for (var f = 0; f < o.tags.length; f++) {
                var p = o.tags[f];
                if (this.config.tags[p] && this.config.tags[p].template) {
                    q = this.config.tags[p].template;
                    if (q == undefined) {
                        console.log(p)
                    }
                }
            }
        }
        k[o.id] = new BALKANGraph.node(o, q)
    }
    for (var e in k) {
        var g = k[e];
        if (this.oldNodes && this.oldNodes[e]) {
            g.state = this.oldNodes[e].state
        } else {
            g.state = BALKANGraph.EXPAND
        }
        if (g.pid && !g.isAssistant) {
            var l = k[g.pid];
            g.parent = l;
            if (l != null) {
                var b = l.children.length;
                l.children[b] = g
            }
        } else {
            if (g.pid && g.isAssistant) {
                var l = k[g.pid];
                g.parent = l;
                if (l != null) {
                    var b = l.assistants.length;
                    l.assistants[b] = g
                }
            } else {
                n.push(g)
            }
        }
    }
    this._G(k);
    var m = {};
    for (var e in k) {
        var g = k[e];
        if (this.config.layout != BALKANGraph.mixed) {
            g.isLastChild = false
        } else {
            if (k[e].children.length) {
                g.isLastChild = false
            } else {
                if (g.parent == null) {
                    g.isLastChild = false
                } else {
                    for (var f = 0; f < g.parent.children.length; f++) {
                        if (g.parent.children[f].children.length) {
                            g.isLastChild = false
                        }
                    }
                }
            }
        }
        if (g.isLastChild) {
            m[g.pid] = g.parent
        }
    }
    if (this.config.layout == BALKANGraph.mixed) {
        for (var e in m) {
            var l = m[e];
            for (var d = l.children.length - 1; d >= 1; d--) {
                var a = l.children[d];
                a.pid = l.children[d - 1].id;
                a.parent = l.children[d - 1];
                l.children[d - 1].children.push(a);
                l.children.splice(d, 1)
            }
        }
    }
    for (var d = 0; d < n.length; d++) {
        this._j(n[d], 0);
        this._as(n[d], 0, 0, 0)
    }
    this._b(k);
    for (var e in k) {
        this._av(k[e]);
        this._ar(k[e])
    }
    this.nodes = k;
    this.roots = n
};
OrgChart.server.prototype._av = function(d) {
    if (d.assistants.length > 0) {
        switch (this.config.orientation) {
            case BALKANGraph.orientation.top:
            case BALKANGraph.orientation.top_left:
            case BALKANGraph.orientation.bottom:
            case BALKANGraph.orientation.bottom_left:
                var b = d.x + OrgChart.server._X(d, this.config) + this.config.siblingSeparation;
                for (var a = 0; a < d.assistants.length; a++) {
                    d.assistants[a].y = d.y + d.h / 2 - d.assistants[a].h / 2;
                    d.assistants[a].x = b;
                    b += OrgChart.server._X(d.assistants[a], this.config) + this.config.siblingSeparation / 2
                }
                break;
            case BALKANGraph.orientation.right:
            case BALKANGraph.orientation.right_top:
            case BALKANGraph.orientation.left:
            case BALKANGraph.orientation.left_top:
                var c = d.y + OrgChart.server._X(d, this.config) + this.config.siblingSeparation;
                for (var a = 0; a < d.assistants.length; a++) {
                    d.assistants[a].y = c;
                    d.assistants[a].x = d.x;
                    c += OrgChart.server._X(d.assistants[a], this.config) + this.config.siblingSeparation / 2
                }
                break
        }
    }
};
OrgChart.server.prototype._b = function(c) {
    if (this.action != BALKANGraph.action.expandCollapse && this.action != BALKANGraph.action.insert) {
        return
    }
    var a = {
        x: this.oldNodes[this.actionParams.id].x - c[this.actionParams.id].x,
        y: this.oldNodes[this.actionParams.id].y - c[this.actionParams.id].y
    };
    for (var b in c) {
        switch (this.config.orientation) {
            case BALKANGraph.orientation.top:
            case BALKANGraph.orientation.top_left:
            case BALKANGraph.orientation.bottom:
            case BALKANGraph.orientation.bottom_left:
                c[b].x += a.x;
                break;
            case BALKANGraph.orientation.right:
            case BALKANGraph.orientation.right_top:
            case BALKANGraph.orientation.left:
            case BALKANGraph.orientation.left_top:
                c[b].y += a.y;
                break
        }
    }
};
OrgChart.server.prototype._ar = function(a) {
    if (a.getTopCollapsedParent()) {
        return
    }
    if (this.minX == null || ((a.x != null) && (a.x < this.minX))) {
        this.minX = a.x
    }
    if (this.minY == null || ((a.y != null) && (a.y < this.minY))) {
        this.minY = a.y
    }
    if (this.maxX == null || ((a.x != null) && (a.x + a.w > this.maxX))) {
        this.maxX = a.x + a.w
    }
    if (this.maxY == null || ((a.y != null) && (a.y + a.h > this.maxY))) {
        this.maxY = a.y + a.h
    }
};
OrgChart.server.prototype._G = function(e) {
    if (this.action == BALKANGraph.action.init && e) {
        for (var d in e) {
            if (e[d].tags) {
                for (var c = 0; c < e[d].tags.length; c++) {
                    var f = e[d].tags[c];
                    if (this.config.tags && this.config.tags[f] && this.config.tags[f].state != undefined) {
                        e[d].state = this.config.tags[f].state
                    }
                }
            }
        }
    }
    if (this.action != BALKANGraph.action.expandCollapse && this.action != BALKANGraph.action.centerNode) {
        return
    }
    var b = function(i, h, k) {
        e[i].state = BALKANGraph.EXPAND;
        if (h == true && i != k) {
            if (e[i].children.length) {
                for (var j = 0; j < e[i].children.length; j++) {
                    b(e[i].children[j].id, h, k)
                }
            }
        }
    };
    var a = function(i, h) {
        e[i].state = BALKANGraph.COLLAPSE;
        if (h == true) {
            if (e[i].children.length) {
                for (var j = 0; j < e[i].children.length; j++) {
                    a(e[i].children[j].id, h)
                }
            }
        }
    };
    if (this.action == BALKANGraph.action.expandCollapse) {
        if (this.actionParams.state == BALKANGraph.EXPAND) {
            b(this.actionParams.id, this.actionParams.deep)
        } else {
            if (this.actionParams.state == BALKANGraph.COLLAPSE) {
                a(this.actionParams.id, this.actionParams.deep)
            }
        }
    } else {
        if (this.action == BALKANGraph.action.centerNode) {
            var g = e[this.actionParams.id].getTopCollapsedParent();
            if (g) {
                b(g.id, this.actionParams.deep, e[this.actionParams.id].pid)
            }
        }
    }
};
OrgChart.server.prototype._j = function(g, d) {
    var c = null;
    g.x = 0;
    g.y = 0;
    g._8 = 0;
    g._K = 0;
    g.level = d;
    g.leftNeighbor = null;
    g.rightNeighbor = null;
    this._ad(g, d);
    this._ac(g, d);
    this._af(g, d);
    if (g._l() == 0 || d == BALKANGraph.MAX_DEPTH) {
        c = g._W();
        if (c != null) {
            g._8 = c._8 + OrgChart.server._Q(c, this.config) + this.config.siblingSeparation
        } else {
            g._8 = 0
        }
    } else {
        var f = g._l();
        for (var a = 0; a < f; a++) {
            var b = g._k(a);
            this._j(b, d + 1)
        }
        var h = OrgChart.server._i(g, this.config);
        var e = g._o(h);
        e -= OrgChart.server._X(g, this.config) / 2;
        c = g._W();
        if (c != null) {
            g._8 = c._8 + OrgChart.server._Q(c, this.config) + this.config.siblingSeparation;
            g._K = g._8 - e;
            this._au(g, d)
        } else {
            if (this.config.orientation <= 3) {
                g._8 = e
            } else {
                g._8 = 0
            }
        }
    }
};
OrgChart.server.prototype._au = function(m, g) {
    var a = m._p();
    var b = a.leftNeighbor;
    var c = 1;
    for (var d = BALKANGraph.MAX_DEPTH - g; a != null && b != null && c <= d;) {
        var i = 0;
        var h = 0;
        var o = a;
        var f = b;
        for (var e = 0; e < c; e++) {
            o = o.parent;
            f = f.parent;
            i += o._K;
            h += f._K
        }
        var s = (b._8 + h + OrgChart.server._Q(b, this.config) + this.config.subtreeSeparation) - (a._8 + i);
        if (s > 0) {
            var q = m;
            var n = 0;
            for (; q != null && q != f; q = q._W()) {
                n++
            }
            if (q != null) {
                var r = m;
                var p = s / n;
                for (; r != f; r = r._W()) {
                    r._8 += s;
                    r._K += s;
                    s -= p
                }
            }
        }
        c++;
        if (a._l() == 0) {
            a = OrgChart.server._Z(m, 0, c)
        } else {
            a = a._p()
        }
        if (a != null) {
            b = a.leftNeighbor
        }
    }
};
OrgChart.server.prototype._as = function(e, b, i, k) {
    if (b <= BALKANGraph.MAX_DEPTH) {
        var j = e._8 + i;
        var l = k;
        var d = 0;
        var f = 0;
        var a = false;
        switch (this.config.orientation) {
            case BALKANGraph.orientation.top:
            case BALKANGraph.orientation.top_left:
            case BALKANGraph.orientation.bottom:
            case BALKANGraph.orientation.bottom_left:
                d = this._U[b];
                f = e.h;
                break;
            case BALKANGraph.orientation.right:
            case BALKANGraph.orientation.right_top:
            case BALKANGraph.orientation.left:
            case BALKANGraph.orientation.left_top:
                d = this._J[b];
                a = true;
                f = e.w;
                break
        }
        e.x = j;
        e.y = l;
        if (a) {
            var h = e.x;
            e.x = e.y;
            e.y = h
        }
        switch (this.config.orientation) {
            case BALKANGraph.orientation.bottom:
            case BALKANGraph.orientation.bottom_left:
                e.y = -e.y - f;
                break;
            case BALKANGraph.orientation.right:
            case BALKANGraph.orientation.right_top:
                e.x = -e.x - f;
                break
        }
        if (e._l() != 0) {
            var c = this.config.levelSeparation;
            if (this.config.layout == BALKANGraph.mixed && e.isLastChild) {
                c = this.config.mixedHierarchyNodesSeparation
            }
            this._as(e._p(), b + 1, i + e._K, k + d + c)
        }
        var g = e._D();
        if (g != null) {
            this._as(g, b, i, k)
        }
    }
};
OrgChart.server.prototype._H = function(c) {
    if (c.getTopCollapsedParent()) {
        return false
    }
    if (this.config.lazyLoading) {
        function b(e, f) {
            var o = e.x;
            var p = e.y;
            var m = e.w;
            var d = e.h;
            var g = f[0] - BALKANGraph.LAZY_LOADING_FACTOR;
            var i = f[2] + BALKANGraph.LAZY_LOADING_FACTOR + f[0];
            var j = f[1] - BALKANGraph.LAZY_LOADING_FACTOR;
            var k = f[3] + BALKANGraph.LAZY_LOADING_FACTOR + f[1];
            var l = (o + m > g && i > o);
            if (l) {
                l = (p + d > j && k > p)
            }
            return l
        }
        if (b(c, this.viewBox)) {
            return true
        } else {
            for (var a = 0; a < c.children.length; a++) {
                if (b(c.children[a], this.viewBox)) {
                    return true
                }
            }
        }
        return false
    }
    return true
};
OrgChart.server.prototype._ad = function(c, b) {
    if (this._U[b] == null) {
        this._U[b] = 0
    }
    if (this._U[b] < c.h) {
        this._U[b] = c.h
    }
    if (c.assistants.length) {
        for (var a = 0; a < c.assistants.length; a++) {
            this._ad(c.assistants[a], b)
        }
    }
};
OrgChart.server.prototype._ac = function(c, b) {
    if (this._J[b] == null) {
        this._J[b] = 0
    }
    if (this._J[b] < c.w) {
        this._J[b] = c.w
    }
    if (c.assistants.length) {
        for (var a = 0; a < c.assistants.length; a++) {
            this._ac(c.assistants[a], b)
        }
    }
};
OrgChart.server.prototype._af = function(b, a) {
    if (this._9[a] && this._9[a].id === b.id) {
        return
    }
    b.leftNeighbor = this._9[a];
    if (b.leftNeighbor != null) {
        b.leftNeighbor.rightNeighbor = b
    }
    this._9[a] = b
};
OrgChart.server._Q = function(d, b) {
    switch (b.orientation) {
        case BALKANGraph.orientation.top:
        case BALKANGraph.orientation.top_left:
        case BALKANGraph.orientation.bottom:
        case BALKANGraph.orientation.bottom_left:
            var a = 0;
            if (d.assistants.length > 0) {
                for (var c = 0; c < d.assistants.length; c++) {
                    a += d.assistants[c].w + b.siblingSeparation
                }
            }
            return d.w + a;
        case BALKANGraph.orientation.right:
        case BALKANGraph.orientation.right_top:
        case BALKANGraph.orientation.left:
        case BALKANGraph.orientation.left_top:
            var a = 0;
            if (d.assistants.length > 0) {
                for (var c = 0; c < d.assistants.length; c++) {
                    a += d.assistants[c].h + b.siblingSeparation
                }
            }
            return d.h + a
    }
    return 0
};
OrgChart.server._X = function(b, a) {
    switch (a.orientation) {
        case BALKANGraph.orientation.top:
        case BALKANGraph.orientation.top_left:
        case BALKANGraph.orientation.bottom:
        case BALKANGraph.orientation.bottom_left:
            return b.w;
        case BALKANGraph.orientation.right:
        case BALKANGraph.orientation.right_top:
        case BALKANGraph.orientation.left:
        case BALKANGraph.orientation.left_top:
            return b.h
    }
    return 0
};
OrgChart.server._i = function(c, a) {
    switch (a.orientation) {
        case BALKANGraph.orientation.top:
        case BALKANGraph.orientation.top_left:
        case BALKANGraph.orientation.bottom:
        case BALKANGraph.orientation.bottom_left:
            var e = 0;
            for (var b = 0; b < c.children.length; b++) {
                var d = c.children[b];
                e += d.w
            }
            return e / c.children.length;
        case BALKANGraph.orientation.right:
        case BALKANGraph.orientation.right_top:
        case BALKANGraph.orientation.left:
        case BALKANGraph.orientation.left_top:
            return c.h
    }
    return 0
};
OrgChart.server._S = function(b, a) {
    switch (a.orientation) {
        case BALKANGraph.orientation.top:
        case BALKANGraph.orientation.top_left:
        case BALKANGraph.orientation.bottom:
        case BALKANGraph.orientation.bottom_left:
            return b.h;
        case BALKANGraph.orientation.right:
        case BALKANGraph.orientation.right_top:
        case BALKANGraph.orientation.left:
        case BALKANGraph.orientation.left_top:
            return b.w
    }
    return 0
};
OrgChart.server._Z = function(g, d, e) {
    if (d >= e) {
        return g
    }
    if (g._l() == 0) {
        return null
    }
    var f = g._l();
    for (var a = 0; a < f; a++) {
        var b = g._k(a);
        var c = OrgChart.server._Z(b, d + 1, e);
        if (c != null) {
            return c
        }
    }
    return null
};
OrgChart.server.prototype.convertToCSVdata = function() {
    var c = [];
    var f = this;
    var d = function(k) {
        var g = "";
        for (var i = 0; i < c.length; i++) {
            var h;
            if (c[i] == "reportsTo") {
                h = null
            } else {
                if (k[c[i]] == undefined) {
                    h = ""
                } else {
                    h = k[c[i]]
                }
            }
            if (h instanceof Date) {
                h = h.toLocaleString()
            }
            h = h === null ? "" : h.toString();
            var l = h.replace(/"/g, '""');
            if (l.search(/("|,|\n)/g) >= 0) {
                l = '"' + l + '"'
            }
            if (i > 0) {
                g += ","
            }
            g += l
        }
        return g + "\n"
    };
    var a = "";
    for (var b = 0; b < this.config.nodes.length; b++) {
        for (var e in this.config.nodes[b]) {
            if (!BALKANGraph._e(c, e)) {
                c.push(e);
                a += e + ","
            }
        }
    }
    a += "\n";
    for (var b = 0; b < this.config.nodes.length; b++) {
        a += d(this.config.nodes[b])
    }
    return a
};
OrgChart.server.prototype.search = function(n) {
    var t = this;
    if (n == null || n == undefined || n == "") {
        return []
    }
    n = n.toLowerCase();
    var b = function(i, j) {
        var u = i.toString().toLowerCase().indexOf(j);
        var v = i.toString().splice(u + j.length, 0, "</mark>");
        return v.splice(u, 0, "<mark>")
    };
    var f = function(j) {
        for (var i in t.config.nodeBinding) {
            if (t.config.nodeBinding[i] == j) {
                return true
            }
        }
        return false
    };
    var d = [];
    var m = [];
    for (var e = 0; e < this.config.nodes.length; e++) {
        var h = this.config.nodes[e];
        for (var k in h) {
            var l = h[k];
            if (l && l.toString().toLowerCase().indexOf(n) != -1 && !BALKANGraph._Y(this.config, k)) {
                d.push({
                    id: h.id,
                    propertyName: k,
                    isId: (k == BALKANGraph.ID),
                    isNodeParam: f(k),
                    weight: l.toString().indexOf(n),
                    data: h
                })
            }
        }
    }

    function c(i, j) {
        if (i.isId == true && j.isId == false) {
            return -1
        }
        if (j.isId == true && i.isId == false) {
            return 1
        }
        if (i.isNodeParam == true && j.isNodeParam == false) {
            return -1
        }
        if (j.isNodeParam == true && i.isNodeParam == false) {
            return 1
        }
        if (i.weight < j.weight) {
            return -1
        }
        if (i.weight > j.weight) {
            return 1
        }
        return 0
    }
    d.sort(c);
    for (var e = 0; e < d.length; e++) {
        if (e == 10) {
            break
        }
        var a = false;
        for (var g = 0; g < m.length; g++) {
            if (m[g].id == d[e].id) {
                a = true;
                break
            }
        }
        if (a) {
            continue
        }
        var s = "";
        var r = "";
        var q = "";
        for (var k in d[e].data) {
            var l = d[e].data[k];
            if (k === d[e].propertyName) {
                l = b(l, n)
            }
            if (k == BALKANGraph.TAGS) {
                continue
            } else {
                if (k == BALKANGraph.ID) {
                    r = r + l + ", "
                } else {
                    if (BALKANGraph._Y(this.config, k)) {
                        continue
                    } else {
                        if (f(k) && !BALKANGraph._Y(this.config, k)) {
                            s = s + l + ", "
                        } else {
                            if (!BALKANGraph._Y(this.config, k)) {
                                q = q + l + ", "
                            }
                        }
                    }
                }
            }
        }
        r = r.slice(0, r.length - 2);
        s = s.slice(0, s.length - 2);
        q = q.slice(0, q.length - 2);
        var p = this.config.template;
        if (d[e].data.tags) {
            for (var g = 0; g < d[e].data.tags.length; g++) {
                var o = d[e].data.tags[g];
                if (this.config.tags[o] && this.config.tags[o].template) {
                    p = this.config.tags[o].template
                }
            }
        }
        m.push({
            id: d[e].id,
            node: new BALKANGraph.node(d[e], p),
            textId: r,
            textInNode: s,
            text: q
        })
    }
    return m
};
OrgChart.server.prototype.getCsv = function() {
    for (var a in this.nodes) {
        a.data
    }
};
OrgChart.prototype.exportSVG = function(b) {
    var c = (function() {
        var d = document.createElement("a");
        document.body.appendChild(d);
        d.style = "display: none";
        return function(e, f) {
            var g = new XMLSerializer();
            var h = new Blob([g.serializeToString(e.svg)], {
                type: "image/svg+xml"
            });
            var i = URL.createObjectURL(h);
            d.href = i;
            d.download = f;
            d.click();
            window.URL.revokeObjectURL(i)
        }
    }());
    var a = {
        svg: this.getSvg()
    };
    if (!b) {
        b = "BALKANGraph.svg"
    }
    c(a, b)
};
OrgChart.prototype.exportCSV = function(c) {
    if (!c) {
        c = "BALKANGraph.csv"
    }
    var b = this.server.convertToCSVdata();
    var a = new Blob([b], {
        type: "text/csv;charset=utf-8;"
    });
    if (navigator.msSaveBlob) {
        navigator.msSaveBlob(a, c)
    } else {
        var d = document.createElement("a");
        if (d.download !== undefined) {
            var e = URL.createObjectURL(a);
            d.setAttribute("href", e);
            d.setAttribute("download", c);
            d.style.visibility = "hidden";
            document.body.appendChild(d);
            d.click();
            document.body.removeChild(d)
        }
    }
};
OrgChart.prototype.expand = function(d, c, b) {
    var a = {
        id: d,
        deep: c,
        state: BALKANGraph.EXPAND
    };
    this._y(false, BALKANGraph.action.expandCollapse, a, b)
};
OrgChart.prototype.collapse = function(d, c, b) {
    var a = {
        id: d,
        deep: c,
        state: BALKANGraph.COLLAPSE
    };
    this._y(false, BALKANGraph.action.expandCollapse, a, b)
};
OrgChart.prototype._h = function(d, a) {
    this.nodeMenuUI.hide();
    this.menuUI.hide();
    var b = d.getAttribute("control-expcoll-id");
    var c = this.response.visibleNodes[b];
    if (c.state == BALKANGraph.EXPAND) {
        this.collapse(b, false)
    } else {
        if (c.state == BALKANGraph.COLLAPSE) {
            this.expand(b, false)
        }
    }
};
OrgChart.prototype._n = function(b, a) {
    a[0].stopPropagation();
    a[0].preventDefault()
};
BALKANGraph._ah = function(a) {
    if (!a) {
        console.error("config is not defined")
    } else {
        return true
    }
    return false
};
BALKANGraph._e = function(a, c) {
    if (a && Array.isArray(a)) {
        var b = a.length;
        while (b--) {
            if (a[b] === c) {
                return true
            }
        }
    }
    return false
};
BALKANGraph._C = function(a) {
    if (a.tags && !Array.isArray(a.tags)) {
        return a.tags.split(",")
    } else {
        if (a.tags && Array.isArray(a.tags)) {
            return a.tags
        }
    }
    return []
};
BALKANGraph._f = function(a, b, c) {
    var d = a.getBoundingClientRect();
    var g = b - d.left;
    var h = c - d.top;
    var e = (g) / (d.width / 100);
    var f = (h) / (d.height / 100);
    return [e, f]
};
BALKANGraph._ag = function(a) {
    return a.replace(/^\s+|\s+$/g, "")
};
BALKANGraph._R = function(a) {
    var b = a.getAttribute("transform");
    b = b.replace("matrix", "").replace("(", "").replace(")", "");
    if (BALKANGraph._r().msie) {
        b = b.replace(/ /g, ",")
    }
    b = BALKANGraph._ag(b);
    b = "[" + b + "]";
    b = JSON.parse(b);
    return b
};
BALKANGraph.getScale = function(e, f, a, b, g, i) {
    if (!e && b === BALKANGraph.match.boundary) {
        var c = (f) / g;
        var d = (a) / i;
        return c > d ? d : c
    }
    if (!e && b === BALKANGraph.match.width) {
        return (f) / g
    }
    if (!e && b === BALKANGraph.match.height) {
        return (a) / i
    } else {
        if (!e) {
            return b
        } else {
            c = f / e[2];
            d = a / e[3];
            return c > d ? d : c
        }
    }
};
BALKANGraph._I = function(b, c) {
    var d = {};
    for (var a in b) {
        d[a] = b[a]
    }
    for (a in c) {
        d[a] = c[a]
    }
    return d
};
BALKANGraph._Y = function(c, d) {
    if (c.nodeBinding) {
        for (var a in c.nodeBinding) {
            if (c.nodeBinding[a] == d && a.indexOf("img") != -1) {
                return true
            }
        }
    }
    return false
};
BALKANGraph._F = function() {
    function a() {
        return Math.floor((1 + Math.random()) * 65536).toString(16).substring(1)
    }
    return a() + a() + "-" + a() + "-" + a() + "-" + a() + "-" + a() + a() + a()
};
BALKANGraph.htmlRipple = function(d) {
    var b = document.createElement("style");
    b.type = "text/css";
    b.innerHTML = " .bg-ripple-container {position: absolute; top: 0; right: 0; bottom: 0; left: 0; } .bg-ripple-container span {transform: scale(0);border-radius:100%;position:absolute;opacity:0.75;background-color:#fff;animation: bg-ripple 1000ms; }@-moz-keyframes bg-ripple {to {opacity: 0;transform: scale(2);}}@-webkit-keyframes bg-ripple {to {opacity: 0;transform: scale(2);}}@-o-keyframes bg-ripple {to {opacity: 0;transform: scale(2);}}@keyframes bg-ripple {to {opacity: 0;transform: scale(2);}}";
    document.head.appendChild(b);
    var c = function(h, g) {
        var i;
        i = undefined;
        return function() {
            var j, k;
            k = this;
            j = arguments;
            clearTimeout(i);
            return i = setTimeout(function() {
                return h.apply(k, j)
            }, g)
        }
    };
    var f = function(g) {
        var h, i, j, k, l, m, n;
        i = this;
        j = document.createElement("span");
        k = i.offsetWidth;
        h = i.getBoundingClientRect();
        m = g.pageX - h.left - (k / 2);
        n = g.pageY - h.top - (k / 2);
        l = "top:" + n + "px; left: " + m + "px; height: " + k + "px; width: " + k + "px;";
        d.rippleContainer.appendChild(j);
        return j.setAttribute("style", l)
    };
    var a = function() {
        while (this.rippleContainer.firstChild) {
            this.rippleContainer.removeChild(this.rippleContainer.firstChild)
        }
    };
    var e = document.createElement("div");
    e.className = "bg-ripple-container";
    d.addEventListener("mousedown", f);
    d.addEventListener("mouseup", c(a, 2000));
    d.rippleContainer = e;
    d.appendChild(e)
};
BALKANGraph._1 = function(e, d, a, b) {
    var c = d.slice(0);
    if (d[0] < a.left && d[0] < a.right) {
        c[0] = a.left
    }
    if (d[0] > a.right && d[0] > a.left) {
        c[0] = a.right
    }
    if (d[1] < a.top && d[1] < a.bottom) {
        c[1] = a.top
    }
    if (d[1] > a.bottom && d[1] > a.top) {
        c[1] = a.bottom
    }
    if (d[0] !== c[0] || d[1] !== c[1]) {
        BALKANGraph.animate(e, {
            viewBox: d
        }, {
            viewBox: c
        }, 300, BALKANGraph.animate.outPow, function() {
            if (b) {
                b()
            }
        })
    } else {
        if (b) {
            b()
        }
    }
};
BALKANGraph._a = function(a) {
   // return '<a style="text-decoration: none;position:absolute;bottom:' + a + "px;left:" + a + 'px;" title="OrgChart | BALKANGraph" href="https://BALKANGraph.com"><span style="color:#039BE5;font-family:Roboto,Roboto-Regular,Helvetica;font-weight:bold;">BALKAN</span><span style="color:#F57C00;font-family:Roboto,Roboto-Regular,Helvetica;">Graph</span></a>'
};
BALKANGraph._0 = function() {
    return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15)
};
BALKANGraph._r = function() {
    var g = navigator.userAgent;
    g = g.toLowerCase();
    var f = /(webkit)[ \/]([\w.]+)/;
    var e = /(opera)(?:.*version)?[ \/]([\w.]+)/;
    var d = /(msie) ([\w.]+)/;
    var c = /(mozilla)(?:.*? rv:([\w.]+))?/;
    var b = f.exec(g) || e.exec(g) || d.exec(g) || g.indexOf("compatible") < 0 && c.exec(g) || [];
    var a = {
        browser: b[1] || "",
        version: b[2] || "0"
    };
    return {
        msie: (navigator.userAgent.indexOf("Trident") != -1),
        webkit: a.browser == "webkit",
        mozilla: a.browser == "mozilla",
        opera: a.browser == "opera"
    }
};
OrgChart.templates = {};
OrgChart.templates.base = {
    defs: "",
    size: [250, 120],
    expandCollapseSize: 30,
    linkAdjuster: {
        fromX: 0,
        fromY: 0,
        toX: 0,
        toY: 0
    },
    rippleRadius: 0,
    rippleColor: "#e6e6e6",
    svg: '<svg style="display:block;" width="{w}" height="{h}" viewBox="{viewBox}">{content}</svg>',
    link: '<path stroke-linejoin="round" stroke="#aeaeae" stroke-width="1px" fill="none" d="M{xa},{ya} {xb},{yb} {xc},{yc} L{xd},{yd}"/>',
    pointer: '<g data-pointer="pointer" transform="matrix(0,0,0,0,100,100)"><radialGradient id="pointerGradient"><stop stop-color="#ffffff" offset="0" /><stop stop-color="#C1C1C1" offset="1" /></radialGradient><circle cx="16" cy="16" r="16" stroke-width="1" stroke="#acacac" fill="url(#pointerGradient)"></circle></g>',
    node: '<rect x="0" y="0" height="120" width="250" fill="none" stroke-width="1" stroke="#aeaeae"></rect>',
    plus: '<circle cx="15" cy="15" r="15" fill="#ffffff" stroke="#aeaeae" stroke-width="1"></circle><line x1="4" y1="15" x2="26" y2="15" stroke-width="1" stroke="#aeaeae"></line><line x1="15" y1="4" x2="15" y2="26" stroke-width="1" stroke="#aeaeae"></line>',
    minus: '<circle cx="15" cy="15" r="15" fill="#ffffff" stroke="#aeaeae" stroke-width="1"></circle><line x1="4" y1="15" x2="26" y2="15" stroke-width="1" stroke="#aeaeae"></line>',
    nodeMenuButton: '<g style="cursor:pointer;" transform="matrix(1,0,0,1,225,105)" control-node-menu-id="{id}"><rect x="-4" y="-10" fill="#000000" fill-opacity="0" width="22" height="22"></rect><circle cx="0" cy="0" r="2" fill="#ffffff"></circle><circle cx="7" cy="0" r="2" fill="#ffffff"></circle><circle cx="14" cy="0" r="2" fill="#ffffff"></circle></g>',
    exportMenuButton: '<div style="position:absolute;right:{p}px;top:{p}px; width:40px;height:50px;cursor:pointer;" control-export-menu=""><hr style="background-color: #7A7A7A; height: 3px; border: none;"><hr style="background-color: #7A7A7A; height: 3px; border: none;"><hr style="background-color: #7A7A7A; height: 3px; border: none;"></div>',
    img_0: '<clipPath id="{randId}"><circle cx="60" cy="60" r="40"></circle></clipPath><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="20" y="20"  width="80" height="80"></image>',
    link_field_0: '<text class="field_0" text-anchor="middle" fill="#aeaeae" width="290" x="0" y="0" style="font-size:10px;">{val}</text>'
};
OrgChart.templates.ana = {
    defs: "",
    size: [250, 120],
    linkAdjuster: {
        fromX: 0,
        fromY: 0,
        toX: 0,
        toY: 0
    },
    rippleRadius: 0,
    rippleColor: "#e6e6e6",
    expandCollapseSize: 30,
    svg: '<svg style="display:block;" width="{w}" height="{h}" viewBox="{viewBox}">{content}</svg>',
    link: '<path stroke-linejoin="round" stroke="#aeaeae" stroke-width="1px" fill="none" d="M{xa},{ya} {xb},{yb} {xc},{yc} L{xd},{yd}" />',
    assistanseLink: '<path stroke-linejoin="round" stroke="#aeaeae" stroke-width="2px" fill="none" d="M{xa},{ya} {xb},{yb} {xc},{yc} {xd},{yd} L{xe},{ye}"/>',
    pointer: '<g data-pointer="pointer" transform="matrix(0,0,0,0,100,100)"><radialGradient id="pointerGradient"><stop stop-color="#ffffff" offset="0" /><stop stop-color="#C1C1C1" offset="1" /></radialGradient><circle cx="16" cy="16" r="16" stroke-width="1" stroke="#acacac" fill="url(#pointerGradient)"></circle></g>',
    node: '<rect x="0" y="0" height="120" width="250" fill="#039BE5" stroke-width="1" stroke="#aeaeae" rx="5" ry="5"></rect>',
    plus: '<circle cx="15" cy="15" r="15" fill="#ffffff" stroke="#aeaeae" stroke-width="1"></circle><line x1="4" y1="15" x2="26" y2="15" stroke-width="1" stroke="#aeaeae"></line><line x1="15" y1="4" x2="15" y2="26" stroke-width="1" stroke="#aeaeae"></line>',
    minus: '<circle cx="15" cy="15" r="15" fill="#ffffff" stroke="#aeaeae" stroke-width="1"></circle><line x1="4" y1="15" x2="26" y2="15" stroke-width="1" stroke="#aeaeae"></line>',
    nodeMenuButton: '<g style="cursor:pointer;" transform="matrix(1,0,0,1,225,105)" control-node-menu-id="{id}"><rect x="-4" y="-10" fill="#000000" fill-opacity="0" width="22" height="22"></rect><circle cx="0" cy="0" r="2" fill="#ffffff"></circle><circle cx="7" cy="0" r="2" fill="#ffffff"></circle><circle cx="14" cy="0" r="2" fill="#ffffff"></circle></g>',
    exportMenuButton: '<div style="position:absolute;right:{p}px;top:{p}px; width:40px;height:50px;cursor:pointer;" control-export-menu=""><hr style="background-color: #7A7A7A; height: 3px; border: none;"><hr style="background-color: #7A7A7A; height: 3px; border: none;"><hr style="background-color: #7A7A7A; height: 3px; border: none;"></div>',
    img_0: '<clipPath id="{randId}"><circle cx="60" cy="30" r="40"></circle></clipPath><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="20" y="-10"  width="80" height="80"></image>',
    link_field_0: '<text text-anchor="middle" fill="#aeaeae" width="290" x="0" y="0" style="font-size:10px;">{val}</text>',
    field_0: '<text class="field_0"  style="font-size: 24px;" fill="#ffffff" x="125" y="90" text-anchor="middle">{val}</text>',
    field_1: '<text class="field_1"  style="font-size: 16px;" fill="#ffffff" x="230" y="30" text-anchor="end">{val}</text>'
};
OrgChart.templates.ula = Object.assign({}, OrgChart.templates.ana);
OrgChart.templates.ula.field_0 = '<text class="field_0" style="font-size: 18px;" fill="#039BE5" x="105" y="55">{val}</text>';
OrgChart.templates.ula.field_1 = '<text class="field_1" style="font-size: 16px;" fill="#afafaf" x="105" y="76">{val}</text>';
OrgChart.templates.ula.node = '<rect x="0" y="0" height="120" width="250" fill="#ffffff" stroke-width="1" stroke="#aeaeae"></rect><line x1="0" y1="0" x2="250" y2="0" stroke-width="2" stroke="#039BE5"></line>';
OrgChart.templates.ula.img_0 = '<clipPath id="{randId}"><circle cx="60" cy="60" r="40"></circle></clipPath><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="20" y="20" width="80" height="80" ></image>';
OrgChart.templates.ula.menu = '<g style="cursor:pointer;" transform="matrix(1,0,0,1,225,12)" control-node-menu-id="{id}"><rect x="-4" y="-10" fill="#ffffff" width="22" height="22"></rect><circle cx="0" cy="0" r="2" fill="#039BE5"></circle><circle cx="7" cy="0" r="2" fill="#039BE5"></circle><circle cx="14" cy="0" r="2" fill="#039BE5"></circle></g>';
OrgChart.templates.ula.nodeMenuButton = '<g style="cursor:pointer;" transform="matrix(1,0,0,1,225,105)" control-node-menu-id="{id}"><rect x="-4" y="-10" fill="#000000" fill-opacity="0" width="22" height="22"></rect><circle cx="0" cy="0" r="2" fill="#AEAEAE"></circle><circle cx="7" cy="0" r="2" fill="#AEAEAE"></circle><circle cx="14" cy="0" r="2" fill="#AEAEAE"></circle></g>';
OrgChart.templates.belinda = Object.assign({}, OrgChart.templates.ana);
OrgChart.templates.belinda.size = [180, 180];
OrgChart.templates.belinda.rippleRadius = 90;
OrgChart.templates.belinda.rippleColor = "#e6e6e6";
OrgChart.templates.belinda.node = '<circle cx="90" cy="90" r="90" fill="#039BE5" stroke-width="1" stroke="#aeaeae"></circle>';
OrgChart.templates.belinda.img_0 = '<clipPath id="{randId}"><rect x="0" y="0" width="180" height="110"></rect></clipPath><clipPath clip-path="url(#{randId})" id="{randId2}"><circle cx="90" cy="90" r="90"></circle></clipPath><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId2})" xlink:href="{val}" x="0" y="0" width="180" height="180" ></image>';
OrgChart.templates.belinda.field_0 = '<text class="field_0" style="font-size: 18px;" text-anchor="middle" fill="#ffffff"  x="90" y="130">{val}</text>';
OrgChart.templates.belinda.field_1 = '<text class="field_1" style="font-size: 14px;" text-anchor="middle" fill="#ffffff"  x="90" y="150">{val}</text>';
OrgChart.templates.belinda.link = '<path stroke="#aeaeae" stroke-width="1px" fill="none" d="M{xa},{ya} C{xb},{yb} {xc},{yc} {xd},{yd}"/>';
OrgChart.templates.belinda.nodeMenuButton = '<g style="cursor:pointer;" transform="matrix(1,0,0,1,79,5)" control-node-menu-id="{id}"><rect x="0" y="0" fill="#000000" fill-opacity="0" width="22" height="22"></rect><line stroke-width="2" stroke="#000" x1="0" y1="3" x2="22" y2="3"></line><line stroke-width="2" stroke="#000" x1="0" y1="9" x2="22" y2="9"></line><line stroke-width="2" stroke="#000" x1="0" y1="15" x2="22" y2="15"></line></g>';
OrgChart.templates.rony = Object.assign({}, OrgChart.templates.ana);
OrgChart.templates.rony.svg = '<svg  style="background-color:#E8E8E8;display:block;" width="{w}" height="{h}" viewBox="{viewBox}">{content}</svg>';
OrgChart.templates.rony.defs = '<filter id="{randId}" x="0" y="0" width="200%" height="200%"><feOffset result="offOut" in="SourceAlpha" dx="5" dy="5"></feOffset><feGaussianBlur result="blurOut" in="offOut" stdDeviation="5"></feGaussianBlur><feBlend in="SourceGraphic" in2="blurOut" mode="normal"></feBlend></filter>';
OrgChart.templates.rony.size = [180, 250];
OrgChart.templates.rony.rippleRadius = 5;
OrgChart.templates.rony.rippleColor = "#F57C00";
OrgChart.templates.rony.img_0 = '<clipPath id="{randId}"><circle cx="90" cy="160" r="60"></circle></clipPath><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="30" y="100"  width="120" height="120"></image>';
OrgChart.templates.rony.node = '<rect filter="url(#{randId})" x="0" y="0" height="250" width="180" fill="#ffffff" stroke-width="0" rx="5" ry="5"></rect>';
OrgChart.templates.rony.field_0 = '<text class="field_0" style="font-size: 22px;" fill="#039BE5" x="90" y="40" text-anchor="middle">{val}</text>';
OrgChart.templates.rony.field_1 = '<text class="field_1" style="font-size: 16px;" fill="#F57C00" x="90" y="60" text-anchor="middle">{val}</text>';
OrgChart.templates.rony.field_2 = '<text style="font-size: 16px;" fill="#FFCA28" x="90" y="80" text-anchor="middle">{val}</text>';
OrgChart.templates.rony.link = '<path stroke="#039BE5" stroke-width="1px" fill="none" d="M{xa},{ya} {xb},{yb} {xc},{yc} L{xd},{yd}"/>';
OrgChart.templates.rony.plus = '<circle cx="15" cy="15" r="15" fill="#ffffff" stroke="#039BE5" stroke-width="1"></circle><line x1="4" y1="15" x2="26" y2="15" stroke-width="1" stroke="#039BE5"></line><line x1="15" y1="4" x2="15" y2="26" stroke-width="1" stroke="#039BE5"></line>';
OrgChart.templates.rony.minus = '<circle cx="15" cy="15" r="15" fill="#ffffff" stroke="#039BE5" stroke-width="1"></circle><line x1="4" y1="15" x2="26" y2="15" stroke-width="1" stroke="#039BE5"></line>';
OrgChart.templates.rony.nodeMenuButton = '<g style="cursor:pointer;" transform="matrix(1,0,0,1,155,235)" control-node-menu-id="{id}"><rect x="-4" y="-10" fill="#000000" fill-opacity="0" width="22" height="22"></rect><circle cx="0" cy="0" r="2" fill="#F57C00"></circle><circle cx="7" cy="0" r="2" fill="#F57C00"></circle><circle cx="14" cy="0" r="2" fill="#F57C00"></circle></g>';
OrgChart.templates.mery = Object.assign({}, OrgChart.templates.ana);
OrgChart.templates.mery.rippleRadius = 50;
OrgChart.templates.mery.rippleColor = "#e6e6e6";
OrgChart.templates.mery.node = '<rect x="0" y="0" height="120" width="250" fill="#ffffff" stroke-width="1" stroke="#686868" rx="50" ry="50"></rect><rect x="0" y="45" height="30" width="250" fill="#039BE5" stroke-width="1"></rect>';
OrgChart.templates.mery.link = '<path stroke="#aeaeae" stroke-width="1px" fill="none" d="M{xa},{ya} C{xb},{yb} {xc},{yc} {xd},{yd}" />';
OrgChart.templates.mery.img_0 = '<clipPath id="{randId}"><circle cx="125" cy="60" r="24"></circle></clipPath><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="95" y="33"  width="60" height="60"></image>';
OrgChart.templates.mery.field_0 = '<text class="field_0" style="font-size: 24px;" fill="#039BE5" x="125" y="30" text-anchor="middle">{val}</text>';
OrgChart.templates.mery.field_1 = '<text class="field_1" style="font-size: 16px;" fill="#039BE5" x="125" y="100" text-anchor="middle">{val}</text>';
OrgChart.templates.mery.nodeMenuButton = '<g style="cursor:pointer;" transform="matrix(1,0,0,1,225,60)" control-node-menu-id="{id}"><rect x="-4" y="-10" fill="#000000" fill-opacity="0" width="22" height="22"></rect><circle cx="0" cy="0" r="2" fill="#ffffff"></circle><circle cx="7" cy="0" r="2" fill="#ffffff"></circle><circle cx="14" cy="0" r="2" fill="#ffffff"></circle></g>';
OrgChart.templates.polina = Object.assign({}, OrgChart.templates.ana);
OrgChart.templates.polina.size = [300, 80];
OrgChart.templates.polina.rippleRadius = 40;
OrgChart.templates.polina.rippleColor = "#e6e6e6";
OrgChart.templates.polina.node = '<rect x="0" y="0" height="80" width="300" fill="#039BE5" stroke-width="1" stroke="#686868" rx="40" ry="40"></rect>';
OrgChart.templates.polina.img_0 = '<clipPath id="{randId}"><circle cx="40" cy="40" r="40"></circle></clipPath><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="0" y="0"  width="80" height="80"></image>';
OrgChart.templates.polina.field_0 = '<text class="field_0" style="font-size: 24px;" fill="#ffffff" x="90" y="30" text-anchor="start">{val}</text>';
OrgChart.templates.polina.field_1 = '<text class="field_1" style="font-size: 16px;" fill="#ffffff" x="90" y="55" text-anchor="start">{val}</text>';
OrgChart.templates.polina.link = '<path stroke="#686868" stroke-width="1px" fill="none" d="M{xa},{ya} C{xb},{yb} {xc},{yc} {xd},{yd}" />';
OrgChart.templates.polina.nodeMenuButton = '<g style="cursor:pointer;" transform="matrix(1,0,0,1,285,33)" control-node-menu-id="{id}"><rect x="-4" y="-10" fill="#000000" fill-opacity="0" width="22" height="22"></rect><circle cx="0" cy="0" r="2" fill="#ffffff"></circle><circle cx="0" cy="7" r="2" fill="#ffffff"></circle><circle cx="0" cy="14" r="2" fill="#ffffff"></circle></g>';
OrgChart.templates.mila = Object.assign({}, OrgChart.templates.ana);
OrgChart.templates.mila.node = '<rect x="0" y="0" height="120" width="250" fill="#039BE5" stroke-width="1" stroke="#aeaeae"></rect><rect x="-5" y="70" height="30" width="260" fill="#ffffff" stroke-width="1" stroke="#039BE5"></rect><line x1="-5" x2="0" y1="100" y2="105" stroke-width="1" stroke="#039BE5"/><line x1="255" x2="250" y1="100" y2="105" stroke-width="1" stroke="#039BE5"/>';
OrgChart.templates.mila.img_0 = '<image preserveAspectRatio="xMidYMid slice" xlink:href="{val}" x="20" y="5"  width="64" height="64"></image>';
OrgChart.templates.mila.field_0 = '<text class="field_0" style="font-size: 24px;" fill="#039BE5" x="125" y="94" text-anchor="middle">{val}</text>';
OrgChart.templates.mila.nodeMenuButton = '<g style="cursor:pointer;" transform="matrix(1,0,0,1,225,110)" control-node-menu-id="{id}"><rect x="-4" y="-10" fill="#000000" fill-opacity="0" width="22" height="22"></rect><circle cx="0" cy="0" r="2" fill="#ffffff"></circle><circle cx="7" cy="0" r="2" fill="#ffffff"></circle><circle cx="14" cy="0" r="2" fill="#ffffff"></circle></g>';
OrgChart.templates.diva = Object.assign({}, OrgChart.templates.ana);
OrgChart.templates.diva.size = [200, 170];
OrgChart.templates.diva.rippleRadius = 0;
OrgChart.templates.diva.rippleColor = "#e6e6e6";
OrgChart.templates.diva.node = '<rect x="0" y="80" height="90" width="200" fill="#039BE5"></rect><circle cx="100" cy="50" fill="#ffffff" r="50" stroke="#039BE5" stroke-width="2"></circle>';
OrgChart.templates.diva.img_0 = '<clipPath id="{randId}"><circle cx="100" cy="50" r="45"></circle></clipPath><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="50" y="0"  width="100" height="100"></image>';
OrgChart.templates.diva.field_0 = '<text class="field_0" style="font-size: 24px;" fill="#ffffff" x="100" y="125" text-anchor="middle">{val}</text>';
OrgChart.templates.diva.field_1 = '<text class="field_1" style="font-size: 16px;" fill="#ffffff" x="100" y="145" text-anchor="middle">{val}</text>';
OrgChart.templates.diva.pointer = '<g data-pointer="pointer" transform="matrix(0,0,0,0,100,100)"><radialGradient id="pointerGradient"><stop stop-color="#ffffff" offset="0" /><stop stop-color="#039BE5" offset="1" /></radialGradient><circle cx="16" cy="16" r="16" stroke-width="1" stroke="#acacac" fill="url(#pointerGradient)"></circle></g>';
OrgChart.templates.diva.nodeMenuButton = '<g style="cursor:pointer;" transform="matrix(1,0,0,1,175,155)" control-node-menu-id="{id}"><rect x="-4" y="-10" fill="#000000" fill-opacity="0" width="22" height="22"></rect><circle cx="0" cy="0" r="2" fill="#ffffff"></circle><circle cx="7" cy="0" r="2" fill="#ffffff"></circle><circle cx="14" cy="0" r="2" fill="#ffffff"></circle></g>';
OrgChart.templates.luba = Object.assign({}, OrgChart.templates.ana);
OrgChart.templates.luba.svg = '<svg style="display:block;background-color: #2E2E2E;" width="{w}" height="{h}" viewBox="{viewBox}">{content}</svg>';
OrgChart.templates.luba.defs = '<linearGradient id="{randId}" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" style="stop-color:#646464;stop-opacity:1" /><stop offset="100%" style="stop-color:#363636;stop-opacity:1" /></linearGradient>';
OrgChart.templates.luba.node = '<rect fill="url(#{randId})" x="0" y="0" height="120" width="250" fill="#039BE5" stroke-width="1" stroke="#aeaeae" rx="5" ry="5"></rect>';
OrgChart.templates.luba.img_0 = '<clipPath id="{randId}"><circle cx="60" cy="25" r="40"></circle></clipPath><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="20" y="-15"  width="80" height="80"></image>';
OrgChart.templates.luba.nodeMenuButton = '<g style="cursor:pointer;" transform="matrix(1,0,0,1,225,105)" control-node-menu-id="{id}"><rect x="-4" y="-10" fill="#000000" fill-opacity="0" width="22" height="22"></rect><circle cx="0" cy="0" r="2" fill="#aeaeae"></circle><circle cx="7" cy="0" r="2" fill="#aeaeae"></circle><circle cx="14" cy="0" r="2" fill="#aeaeae"></circle></g>';
OrgChart.templates.luba.field_0 = '<text class="field_0" style="font-size: 24px;" fill="#aeaeae" x="125" y="90" text-anchor="middle">{val}</text>';
OrgChart.templates.luba.field_1 = '<text class="field_1" style="font-size: 16px;" fill="#aeaeae" x="230" y="30" text-anchor="end">{val}</text>';
OrgChart.templates.luba.plus = '<rect x="0" y="0" width="36" height="36" rx="12" ry="12" fill="#2E2E2E" stroke="#aeaeae" stroke-width="1"></rect><line x1="4" y1="18" x2="32" y2="18" stroke-width="1" stroke="#aeaeae"></line><line x1="18" y1="4" x2="18" y2="32" stroke-width="1" stroke="#aeaeae"></line>';
OrgChart.templates.luba.minus = '<rect x="0" y="0" width="36" height="36" rx="12" ry="12" fill="#2E2E2E" stroke="#aeaeae" stroke-width="1"></rect><line x1="4" y1="18" x2="32" y2="18" stroke-width="1" stroke="#aeaeae"></line>';
OrgChart.templates.luba.expandCollapseSize = 36;
OrgChart.templates.derek = Object.assign({}, OrgChart.templates.ana);
OrgChart.templates.derek.link = '<path stroke="#aeaeae" stroke-width="1px" fill="none" d="M{xa},{ya} C{xb},{yb} {xc},{yc} {xd},{yd}"/>';
OrgChart.templates.derek.field_0 = '<text class="field_0"  style="font-size: 24px;" fill="#aeaeae" x="125" y="90" text-anchor="middle">{val}</text>';
OrgChart.templates.derek.field_1 = '<text class="field_1"  style="font-size: 16px;" fill="#aeaeae" x="230" y="30" text-anchor="end">{val}</text>';
OrgChart.templates.derek.node = '<rect x="0" y="0" height="120" width="250" fill="#ffffff" stroke-width="0" stroke="none" rx="5" ry="5"></rect><path d="M1.0464172536455116 0.43190469224125483 C53.84241668202045 -0.788936709486018, 103.41786516460891 -0.7020837047025514, 252.36637077877316 2.5880308844586395 M2.9051048929845287 1.416953777798454 C94.33574460557007 1.0012759229446266, 189.39715875173388 0.6456731199982935, 252.78978918302985 2.4201778360648074 M253.62699063660838 2.9193391477217157 C249.73034548542307 47.55931231380586, 252.87525930998083 91.26715478378368, 253.10179184315842 121.7440626272514 M251.37132919216776 1.8733470844542213 C252.2809675089866 32.73212950193807, 251.34402714677282 62.11470833534073, 251.87050951184997 121.58550827952904 M253.33945599552268 122.05611967964798 C171.36076589155192 123.47737863766969, 88.83808249906167 125.27259840604118, 3.1999393565128846 121.26868651191393 M252.26165120810887 122.5938901158243 C192.76996487394138 123.44664377223289, 131.37122563794998 122.94880221756583, 1.2373006891045732 121.88999197324904 M2.223863211672736 121.04511533445009 C1.4438124377596486 86.38398979211773, 2.8540468486809294 55.805566409478374, 3.8890451480896644 1.7483404843613926 M2.244347335490432 122.60147677858153 C2.100672267495622 92.31058899219087, 1.6261027388218166 64.160806803772, 1.6325734600065367 1.1945909353588222" stroke-width: 1; fill: none;" stroke="#aeaeae" ></path>';
OrgChart.templates.derek.defs = ' <filter id="grayscale"><feColorMatrix type="matrix" values="0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0"/></filter>';
OrgChart.templates.derek.img_0 = '<clipPath id="{randId}"><circle cx="60" cy="30" r="36"></circle></clipPath><path d="M59.27394950148486 -8.301766751084706 C67.69473471187919 -8.865443566034696, 80.29031463018414 -4.859224005049532, 87.18598909426663 0.33238763875740673 C94.08166355834912 5.523999282564345, 98.98110545022442 14.745947814116153, 100.64799628597981 22.847903111756924 C102.31488712173521 30.949858409397695, 101.71467046207992 41.576461864335656, 97.187334108799 48.944119424602036 C92.65999775551809 56.311776984868416, 82.03610997730354 64.08326918912249, 73.48397816629435 67.05384847335519 C64.93184635528516 70.02442775758789, 54.01035575000908 69.7708463163516, 45.874543242743854 66.76759512999827 C37.738730735478626 63.76434394364495, 29.04872278114092 56.18846598822666, 24.669103122702957 49.034341355235284 C20.289483464264993 41.88021672224391, 18.158053754175985 31.830144088547545, 19.596825292116065 23.84284733205002 C21.035596830056146 15.855550575552495, 25.795252700735308 6.49424361294595, 33.30173235034344 1.1105608162501355 C40.80821199995158 -4.273121980445679, 58.92971347412665 -7.0427741956040295, 64.63570318976488 -8.459249448124865 C70.34169290540312 -9.8757247006457, 67.62192977382313 -7.857597534262704, 67.53767064417285 -7.388290698874876 M62.748378255307365 -8.335850348284236 C71.26603403676657 -8.411982637093757, 83.3134559967999 -3.2332675886967737, 89.65944437868365 2.387927626929269 C96.00543276056739 8.00912284255531, 99.8018539626104 17.389209313563462, 100.82430854660979 25.39132094547202 C101.84676313060918 33.39343257738058, 100.69202080288338 43.23907526327184, 95.79417188267999 50.40059741838061 C90.8963229624766 57.56211957348938, 80.19607375493683 65.6933432424228, 71.43721502538948 68.36045387612462 C62.678356295842114 71.02756450982645, 51.31858275833087 70.03148525422704, 43.241019505395826 66.40326122059156 C35.16345625246078 62.775037186956084, 26.840434236544123 54.120081952867466, 22.971835507779204 46.59110967431175 C19.103236779014285 39.06213739575604, 17.94937086132579 28.992694575765086, 20.029427132806305 21.22942754925726 C22.10948340428682 13.466160522749433, 28.239699334668693 5.033316212766326, 35.452173136662296 0.011507515264803203 C42.6646469386559 -5.010301182236719, 59.029629541347575 -7.774813789367088, 63.30426994476793 -8.901424635751876 C67.57891034818829 -10.028035482136666, 61.20261013623477 -7.6019933587127815, 61.10001555718443 -6.748157563043929" style="stroke: #aeaeae; stroke-width: 2; fill: none;"></path><image filter="url(#grayscale)" preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="20" y="-10"  width="80" height="80"></image>';
OrgChart.templates.derek.minus = '<circle cx="15" cy="15" r="15" fill="#ffffff" stroke-width="0"></circle><path d="M17.23891044927774 1.1814294501322902 C20.29160626452551 1.030769196657948, 23.947970993006972 3.1719335544839753, 26.394853759110717 5.588671983418923 C28.84173652521446 8.005410412353871, 31.246576051221126 12.511831935158815, 31.920207045900206 15.681860023741976 C32.593838040579286 18.85188811232514, 32.286699675240925 21.948848714186923, 30.436639727185195 24.60884051491789 C28.586579779129462 27.268832315648858, 24.05246578668338 30.675892912089505, 20.819847357565806 31.64181082812777 C17.58722892844823 32.60772874416604, 13.992479948405318 31.588687222722193, 11.040929152479746 30.404348011147484 C8.089378356554175 29.220008799572774, 4.476346434761303 27.363985211380037, 3.110542582012373 24.535775558679525 C1.7447387292634435 21.707565905979013, 2.0125141957866703 16.770753327135857, 2.8461060359861694 13.435090094944405 C3.6796978761856685 10.099426862752953, 4.99838568665378 6.33816589513267, 8.112093623209367 4.521796165530812 C11.225801559764953 2.7054264359289544, 18.764666760207586 2.8505106971937155, 21.528353655319687 2.5368717173332556 C24.29204055043179 2.2232327374727956, 24.87088035867136 2.534909813412478, 24.69421499388197 2.6399622863680516 M17.281640595209783 0.19007885243722633 C20.364244435145494 -0.11577004716725742, 25.135133405402318 3.2303746945812075, 27.855952721223584 5.7353294427454955 C30.57677203704485 8.240284190909783, 33.34617538156587 11.802005102645245, 33.606556490137386 15.219807341422953 C33.8669375987089 18.637609580200664, 31.337125602828454 23.632355493641384, 29.418239372652685 26.24214287541175 C27.499353142476917 28.85193025718212, 25.044077583504755 30.13224182494988, 22.093239109082777 30.87853163204516 C19.1424006346608 31.62482143914044, 14.787723024669972 31.933646092018286, 11.713208526120809 30.719881717983426 C8.638694027571646 29.506117343948567, 5.1333408130933655 26.55826769548724, 3.6461521177877945 23.595945387835997 C2.1589634224822234 20.633623080184755, 1.9785010696309286 16.25773192692332, 2.7900763542873843 12.945947872075987 C3.60165163894384 9.634163817228652, 5.999109831161897 5.87039683716568, 8.51560382572653 3.7252410587519886 C11.032097820291161 1.5800852803382974, 16.377503419445155 0.40900388408914673, 17.88904032167518 0.0750132015938405 C19.400577223905202 -0.2589774809014657, 17.448582822593046 1.2406055078364167, 17.584825239106664 1.7212969637801514" style="stroke: rgb(174, 174, 174); stroke-width: 1; fill: none;"></path><path d="M8.571186416504453 17.64803469305822 C12.085276840999553 15.452815349785006, 19.337130848197884 16.101685575250833, 26.855223350440756 18.889299472836626 M7.857231507904164 16.840024354210055 C15.011849298914942 18.06824852479784, 22.352469730756894 17.800410681835732, 26.732355140012178 16.88515873797708" style="stroke: #aeaeae; stroke-width: 1; fill: none;"></path>';
OrgChart.templates.derek.plus = '<circle cx="15" cy="15" r="15" fill="#ffffff" stroke-width="0"></circle><path d="M12.257463787262992 2.40166003616363 C15.548960627027807 1.1768586505919105, 20.323768339136134 1.874732142276981, 23.661947633240565 3.0864861053786417 C27.000126927344997 4.298240068480302, 30.703811226452725 6.729324000523814, 32.286539551889575 9.672183814773595 C33.86926787732643 12.615043629023376, 33.788018167397944 17.557781915741554, 33.158317585861674 20.743644990877332 C32.528617004325405 23.92950806601311, 31.137543762406274 26.899980401737224, 28.508336062671955 28.787362265588257 C25.879128362937635 30.67474412943929, 21.064677192956335 31.99302804642975, 17.383071387455747 32.06793617398354 C13.701465581955157 32.14284430153733, 9.342085269075854 31.17242931287016, 6.418701229668416 29.236811030911003 C3.4953171902609785 27.301192748951845, 0.29124975331190645 23.792422306170057, -0.15723284898887968 20.454226482228595 C-0.6057154512896659 17.116030658287134, 1.5769138742615705 12.178626667602387, 3.7278056158636996 9.207636087262241 C5.878697357465828 6.236645506922095, 10.970632450860041 3.8471017540469195, 12.748117600623896 2.6282830001877198 C14.525602750387751 1.4094642463285199, 14.289563699001825 1.9470094191022314, 14.392716514446832 1.894723564107041 M22.43549828180672 1.2256088400576157 C25.69018250895055 1.7704365119039576, 29.24546322166512 4.979269460398017, 31.051492912414023 8.188373611713667 C32.85752260316293 11.397477763029316, 33.67207918890526 17.17514768034262, 33.27167642630015 20.480233747951512 C32.871273663695035 23.785319815560406, 31.41050911947538 25.966765564166938, 28.649076336783356 28.01889001736704 C25.887643554091333 30.07101447056714, 20.094058835586818 32.38500719321419, 16.70307973014802 32.79298046715211 C13.31210062470922 33.20095374109003, 10.940906263905317 32.367748192606626, 8.303201704150565 30.46672966099454 C5.665497144395813 28.565711129382457, 2.097338248187536 24.641108687248686, 0.8768523716195098 21.386869277479594 C-0.34363350494851663 18.132629867710502, -0.763694313917872 14.0433435213021, 0.980286444742406 10.941293202379995 C2.7242672034026842 7.83924288345789, 7.945090366802328 4.317959037402062, 11.340736923581177 2.774567363946959 C14.736383480360027 1.231175690491856, 19.39979547907987 1.5862021443476335, 21.354165785415507 1.6809431616493775 C23.308536091751144 1.7756841789511215, 22.887857886273373 3.132249638930103, 23.06695876159499 3.343013467757423" style="stroke: rgb(174, 174, 174); stroke-width: 1; fill: none;"></path><path d="M7.0048103933165935 18.109075284628886 C12.152504846776358 18.486044066779655, 15.926735549928488 18.85477711845977, 26.641287664541796 15.553207106118496 M6.796084928139555 16.174781745374535 C14.085050058006614 16.53898319893461, 19.579209483395115 16.725914747038104, 27.441803598385356 17.277875712554966" style="stroke: #aeaeae; stroke-width: 1; fill: none;"></path><path d="M16.293755471804 6.234638030793387 C17.448668211406996 11.453666045700338, 16.27043554943843 18.842895411477887, 16.90423703847114 28.952046969222806 M17.809777051185264 7.011866752183222 C17.599122858612276 13.07833796918755, 16.995204905243295 18.587342115968614, 17.888568853882067 26.844926419317094" style="stroke: #aeaeae; stroke-width: 1; fill: none;"></path>';
OrgChart.templates.derek.nodeMenuButton = '<g style="cursor:pointer;" transform="matrix(1,0,0,1,210,80)" control-node-menu-id="{id}"><rect x="-4" y="-4" fill="#000000" fill-opacity="0" width="30" height="30"></rect><path d="M28.28024041166867 10.015533059199505 C16.45705393905741 10.81309700412131, 9.85276157405196 9.87162723980281, 3.5441213169168515 7.075531655648353 M27.551825308513525 8.923800642512257 C18.159502224784205 9.337153563754718, 7.451197502628936 9.284728719203128, 1.8548423867425456 8.753004894810802 M27.907104120536573 17.662200828300364 C18.343063985797404 18.998694042201137, 6.69417200021006 18.568117962359516, 2.7668346274558218 17.84920936843539 M26.99365966559525 17.444217688828093 C18.288291344549645 16.258053076066645, 10.047008592341617 16.913684103209345, 2.1772395910449567 17.55258716848472 M25.754130110044443 24.79648729629354 C19.716463597004157 24.059273917380096, 12.571903015673474 24.723484329803995, 1.2709092686545498 25.961416660790483 M26.031268385778997 24.853114475295413 C16.16958752624931 25.047162545233455, 7.4039608372111765 23.9169859615211, 1.4736400026930716 24.342985647697336" style="stroke: rgb(174, 174, 174); stroke-width: 1; fill: none;"></path></g>';
OrgChart.prototype._c = function(a) {
    this._z(window, "resize", this._aw)
};
OrgChart.prototype._d = function(k) {
    var k = this.getSvg();
    this._z(k, "mousedown", this._O);
    if (this.config.mouseScroolBehaviour == BALKANGraph.action.zoom) {
        this._z(k, "DOMMouseScroll", this._L);
        this._z(k, "mousewheel", this._L)
    }
    var j = this.getNodeElements();
    for (var d = 0; d < j.length; d++) {
        var h = j[d];
        this._z(h, "mousedown", this._4);
        this._z(h, "mouseover", this._6);
        this._z(h, "mouseleave", this._5);
        this._z(h, "click", this._2)
    }
    var b = this.getExpCollButtons();
    for (var d = 0; d < b.length; d++) {
        var a = b[d];
        this._z(a, "mousedown", this._n);
        this._z(a, "click", this._h)
    }
    var g = this.getNodeMenuButtons();
    for (var d = 0; d < g.length; d++) {
        var f = g[d];
        this._z(f, "mousedown", this._M);
        this._z(f, "click", this._3)
    }
    var c = this.getExportMenuButton();
    if (c) {
        this._z(c, "click", this._u)
    }
    var e = this.getLonelyButton();
    if (e) {
        this._z(e, "mousedown", this._N)
    }
};
OrgChart.prototype._z = function(b, c, d, e) {
    if (!e) {
        e = ""
    }
    if (!b.getListenerList) {
        b.getListenerList = {}
    }
    if (b.getListenerList[c + e]) {
        return
    }

    function g(h, i) {
        return function() {
            if (i) {
                return i.apply(h, [this, arguments])
            }
        }
    }
    d = g(this, d);

    function f(h) {
        var i = d.apply(this, arguments);
        if (i === false) {
            h.stopPropagation();
            h.preventDefault()
        }
        return (i)
    }

    function a() {
        var h = d.call(b, window.event);
        if (h === false) {
            window.event.returnValue = false;
            window.event.cancelBubble = true
        }
        return (h)
    }
    if (b.addEventListener) {
        b.addEventListener(c, f, false)
    } else {
        b.attachEvent("on" + c, a)
    }
    b.getListenerList[c + e] = f
};
OrgChart.prototype._aa = function(a, b) {
    if (a.getListenerList[b]) {
        var c = a.getListenerList[b];
        a.removeEventListener(b, c, false);
        delete a.getListenerList[b]
    }
};
OrgChart.prototype._L = function(c, b) {
    var d = this;
    var a = b[0].wheelDelta ? b[0].wheelDelta / 40 : b[0].detail ? -b[0].detail : 0;
    var e = function(f) {
        var h = f > 0;
        var g = BALKANGraph._f(d.getSvg(), b[0].pageX, b[0].pageY);
        d.zoom(h, g)
    };
    if (a && this.config.mouseScroolBehaviour == BALKANGraph.action.zoom) {
        e(a)
    }
    return b[0].preventDefault() && false
};
OrgChart.prototype._6 = function(d, a) {
    var b = d.getAttribute("node-id");
    var c = this.response.visibleNodes[b];
    if (!this.config.enableDragDrop) {
        return
    }
    this.lastHoveredNodeId = d.getAttribute("node-id")
};
OrgChart.prototype._5 = function(b, a) {
    if (!this.config.enableDragDrop) {
        return
    }
    this.lastHoveredNodeId = null
};
OrgChart.prototype._4 = function(h, c) {
    c[0].stopPropagation();
    c[0].preventDefault();
    if (!this.config.enableDragDrop) {
        return
    }
    document.body.style.mozUserSelect = document.body.style.webkitUserSelect = document.body.style.userSelect = "none";
    var k = this.getViewBox();
    var j = this;
    var i = this.getSvg();
    var b = {
        x: c[0].clientX,
        y: c[0].clientY
    };
    var d = BALKANGraph._R(h);
    var l = d[4];
    var m = d[5];
    var g = j.getScale();
    var a = h.cloneNode(true);
    i.insertBefore(a, i.firstChild);
    a.style.opacity = 0.7;
    var f = function(n) {
        if (b) {
            var o = n.clientX;
            var p = n.clientY;
            var q = (o - b.x) / g;
            var r = (p - b.y) / g;
            d[4] = l + q;
            d[5] = m + r;
            a.setAttribute("transform", "matrix(" + d.toString() + ")")
        }
    };
    var e = function(n) {
        i.removeChild(a);
        i.removeEventListener("mousemove", f);
        i.removeEventListener("mouseup", e);
        i.removeEventListener("mouseleave", e);
        var o = h.getAttribute("node-id");
        if ((j.lastHoveredNodeId != null) && (j.lastHoveredNodeId != o)) {
            j._ab(o, j.lastHoveredNodeId)
        }
    };
    i.addEventListener("mousemove", f);
    i.addEventListener("mouseup", e);
    i.addEventListener("mouseleave", e)
};
OrgChart.prototype._aw = function(b, a) {
    this._y(false, BALKANGraph.action.resize)
};
OrgChart.prototype._2 = function(d, a) {
    this.searchUI.hide();
    this.nodeMenuUI.hide();
    this.menuUI.hide();
    var b = d.getAttribute("node-id");
    var c = this.response.visibleNodes[b];
    if (this.config.nodeMouseClickBehaviour == BALKANGraph.action.expandCollapse) {
        if (c.state == BALKANGraph.EXPAND) {
            this.collapse(b, false)
        } else {
            if (c.state == BALKANGraph.COLLAPSE) {
                this.expand(b, false)
            }
        }
        this.ripple(b, c.templateName, a[0].clientX, a[0].clientY)
    }
    if (this.config.nodeMouseClickBehaviour == BALKANGraph.action.edit) {
        this.editUI.show(c);
        this.ripple(b, c.templateName, a[0].clientX, a[0].clientY)
    }
    if (this.config.nodeMouseClickBehaviour == BALKANGraph.action.details) {
        this.editUI.show(c, true);
        this.ripple(b, c.templateName, a[0].clientX, a[0].clientY)
    }
};
OrgChart.prototype._M = function(b, a) {
    a[0].stopPropagation();
    a[0].preventDefault()
};
OrgChart.prototype._3 = function(c, a) {
    a[0].stopPropagation();
    a[0].preventDefault();
    var b = c.getAttribute("control-node-menu-id");
    this.menuUI.hide();
    var f = a[0].offsetX;
    var g = a[0].offsetY;
    var d = c.getBoundingClientRect();
    var e = this.getSvg().getBoundingClientRect();
    f = d.left - e.left;
    g = d.top - e.top;
    this.nodeMenuUI.show(f, g, b)
};
OrgChart.prototype._u = function(b, a) {
    a[0].stopPropagation();
    a[0].preventDefault();
    this.nodeMenuUI.hide();
    this.menuUI.show(b.offsetLeft, b.offsetTop)
};
OrgChart.prototype._N = function(c, a) {
    a[0].stopPropagation();
    a[0].preventDefault();
    var b = new BALKANGraph.node(null, this.config.template);
    this._q(b)
};
OrgChart.prototype.zoom = function(b, a) {
    var h = this.getViewBox().slice(0);
    var e = h;
    var g = h[2];
    var f = h[3];
    if (b === true) {
        h[2] = h[2] / (BALKANGraph.SCALE_FACTOR);
        h[3] = h[3] / (BALKANGraph.SCALE_FACTOR)
    } else {
        if (b === false) {
            h[2] = h[2] * (BALKANGraph.SCALE_FACTOR);
            h[3] = h[3] * (BALKANGraph.SCALE_FACTOR)
        }
    }
    if (!a) {
        a = [50, 50]
    }
    h[0] = e[0] - (h[2] - g) / (100 / a[0]);
    h[1] = e[1] - (h[3] - f) / (100 / a[1]);
    var d = this.getScale(h);
    h[2] = this.width() / d;
    h[3] = this.height() / d;
    var c = this.config.scaleMax;
    if (((b === true) && (d < c)) || ((b === false) && (d > this.config.scaleMin))) {
        this.setViewBox(h);
        this._y(true, BALKANGraph.action.zoom)
    }
};
BALKANGraph.animate = function(a, c, b, h, j, d, o) {
    var e = 10;
    var l = 1;
    var n = 1;
    var m = h / e + 1;
    var p;
    var k = document.getElementsByTagName("g");
    if (!a.length) {
        a = [a]
    }

    function f() {
        for (var t in b) {
            var u = BALKANGraph._e(["top", "left", "right", "bottom", "width"], t.toLowerCase()) ? "px" : "";
            switch (t.toLowerCase()) {
                case "d":
                    var w = j(((n * e) - e) / h) * (b[t][0] - c[t][0]) + c[t][0];
                    var x = j(((n * e) - e) / h) * (b[t][1] - c[t][1]) + c[t][1];
                    for (var y = 0; y < a.length; y++) {
                        a[y].setAttribute("d", a[y].getAttribute("d") + " L" + w + " " + x)
                    }
                    break;
                case "r":
                    var v = j(((n * e) - e) / h) * (b[t] - c[t]) + c[t];
                    for (var z = 0; z < a.length; z++) {
                        a[z].setAttribute("r", v)
                    }
                    break;
                case "transform":
                    if (b[t]) {
                        var r = c[t];
                        var q = b[t];
                        var s = [0, 0, 0, 0, 0, 0];
                        for (i in r) {
                            s[i] = j(((n * e) - e) / h) * (q[i] - r[i]) + r[i]
                        }
                        for (var A = 0; A < a.length; A++) {
                            a[A].setAttribute("transform", "matrix(" + s.toString() + ")")
                        }
                    }
                    break;
                case "viewbox":
                    if (b[t]) {
                        var r = c[t];
                        var q = b[t];
                        var s = [0, 0, 0, 0];
                        for (i in r) {
                            s[i] = j(((n * e) - e) / h) * (q[i] - r[i]) + r[i]
                        }
                        for (var B = 0; B < a.length; B++) {
                            a[B].setAttribute("viewBox", s.toString())
                        }
                    }
                    break;
                case "margin":
                    if (b[t]) {
                        var r = c[t];
                        var q = b[t];
                        var s = [0, 0, 0, 0];
                        for (i in r) {
                            s[i] = j(((n * e) - e) / h) * (q[i] - r[i]) + r[i]
                        }
                        var g = "";
                        for (i = 0; i < s.length; i++) {
                            g += parseInt(s[i]) + "px "
                        }
                        for (var C = 0; C < a.length; C++) {
                            if (a[C] && a[C].style) {
                                a[C].style[t] = v
                            }
                        }
                    }
                    break;
                default:
                    var v = j(((n * e) - e) / h) * (b[t] - c[t]) + c[t];
                    for (var D = 0; D < a.length; D++) {
                        if (a[D] && a[D].style) {
                            a[D].style[t] = v + u
                        }
                    }
                    break
            }
        }
        if (o) {
            o()
        }
        n = n + l;
        if (n > m + 1) {
            clearInterval(p);
            if (d) {
                d(a)
            }
        }
    }
    p = setInterval(f, e)
};
BALKANGraph.animate.inPow = function(b) {
    var a = 2;
    if (b < 0) {
        return 0
    }
    if (b > 1) {
        return 1
    }
    return Math.pow(b, a)
};
BALKANGraph.animate.outPow = function(c) {
    var a = 2;
    if (c < 0) {
        return 0
    }
    if (c > 1) {
        return 1
    }
    var b = a % 2 === 0 ? -1 : 1;
    return (b * (Math.pow(c - 1, a) + b))
};
BALKANGraph.animate.inOutPow = function(c) {
    var a = 2;
    if (c < 0) {
        return 0
    }
    if (c > 1) {
        return 1
    }
    c *= 2;
    if (c < 1) {
        return BALKANGraph.animate.inPow(c, a) / 2
    }
    var b = a % 2 === 0 ? -1 : 1;
    return (b / 2 * (Math.pow(c - 2, a) + b * 2))
};
BALKANGraph.animate.inSin = function(a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return -Math.cos(a * (Math.PI / 2)) + 1
};
BALKANGraph.animate.outSin = function(a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return Math.sin(a * (Math.PI / 2))
};
BALKANGraph.animate.inOutSin = function(a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return -0.5 * (Math.cos(Math.PI * a) - 1)
};
BALKANGraph.animate.inExp = function(a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return Math.pow(2, 10 * (a - 1))
};
BALKANGraph.animate.outExp = function(a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return -Math.pow(2, -10 * a) + 1
};
BALKANGraph.animate.inOutExp = function(a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return a < 0.5 ? 0.5 * Math.pow(2, 10 * (2 * a - 1)) : 0.5 * (-Math.pow(2, 10 * (-2 * a + 1)) + 2)
};
BALKANGraph.animate.inCirc = function(a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return -(Math.sqrt(1 - a * a) - 1)
};
BALKANGraph.animate.outCirc = function(a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return Math.sqrt(1 - (a - 1) * (a - 1))
};
BALKANGraph.animate.inOutCirc = function(a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return a < 1 ? -0.5 * (Math.sqrt(1 - a * a) - 1) : 0.5 * (Math.sqrt(1 - ((2 * a) - 2) * ((2 * a) - 2)) + 1)
};
BALKANGraph.animate.rebound = function(a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    if (a < (1 / 2.75)) {
        return 1 - 7.5625 * a * a
    } else {
        if (a < (2 / 2.75)) {
            return 1 - (7.5625 * (a - 1.5 / 2.75) * (a - 1.5 / 2.75) + 0.75)
        } else {
            if (a < (2.5 / 2.75)) {
                return 1 - (7.5625 * (a - 2.25 / 2.75) * (a - 2.25 / 2.75) + 0.9375)
            } else {
                return 1 - (7.5625 * (a - 2.625 / 2.75) * (a - 2.625 / 2.75) + 0.984375)
            }
        }
    }
};
BALKANGraph.animate.inBack = function(a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return a * a * ((1.70158 + 1) * a - 1.70158)
};
BALKANGraph.animate.outBack = function(a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return (a - 1) * (a - 1) * ((1.70158 + 1) * (a - 1) + 1.70158) + 1
};
BALKANGraph.animate.inOutBack = function(a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return a < 0.5 ? 0.5 * (4 * a * a * ((2.5949 + 1) * 2 * a - 2.5949)) : 0.5 * ((2 * a - 2) * (2 * a - 2) * ((2.5949 + 1) * (2 * a - 2) + 2.5949) + 2)
};
BALKANGraph.animate.impulse = function(c) {
    var b = 2;
    var a = b * c;
    return a * Math.exp(1 - a)
};
BALKANGraph.animate.expPulse = function(c) {
    var a = 2;
    var b = 2;
    return Math.exp(-a * Math.pow(c, b))
};
OrgChart.prototype.getSvg = function() {
    var a = this.element.getElementsByTagName("svg");
    if (a && a.length) {
        return a[0]
    }
    return null
};
OrgChart.prototype.getNodeElements = function() {
    return this.element.querySelectorAll("g[node-id]")
};
OrgChart.prototype.getPointerElement = function() {
    return this.element.querySelector("g[data-pointer]")
};
OrgChart.prototype.getNodeElement = function(a) {
    return this.element.querySelector("g[node-id='" + a + "']")
};
OrgChart.prototype.getExpCollButtons = function() {
    return this.element.querySelectorAll("[control-expcoll-id]")
};
OrgChart.prototype.getNodeMenuButtons = function() {
    return this.element.querySelectorAll("[control-node-menu-id]")
};
OrgChart.prototype.getExportMenuButton = function() {
    return this.element.querySelector("[control-export-menu]")
};
OrgChart.prototype.getLonelyButton = function() {
    return this.element.querySelector("[control-add]")
};
OrgChart.searchUI = function() {};
OrgChart.searchUI.prototype.init = function(a) {
    this.obj = a
};
OrgChart.searchUI.prototype.hide = function() {
    var c = this.obj.element.querySelector('[data-id="search"]');
    if (!c) {
        return
    }
    var d = c.querySelector('[data-id="cell-1"]');
    var b = this.obj.element.getElementsByTagName("input")[0];
    var a = this.obj.element.querySelector('[data-id="container"]');
    b.value = "";
    a.innerHTML = "";
    if (d.style.display != "none" && c.style.display != "none") {
        BALKANGraph.animate(d, {
            opacity: d.style.opacity
        }, {
            opacity: 0
        }, 200, BALKANGraph.animate.inOutSin, function() {
            d.style.display = "none";
            BALKANGraph.animate(c, {
                width: 300,
                opacity: 1
            }, {
                width: 50,
                opacity: 0
            }, 300, BALKANGraph.animate.inBack, function() {
                c.style.display = "none"
            })
        })
    }
};
OrgChart.searchUI.prototype.addSearchControl = function() {
    var k = this;
    var b = document.createElement("div");
    b.innerHTML = OrgChart.searchUI.createSearchIcon(this.obj.config.padding);
    b.innerHTML += OrgChart.searchUI.createInputField(this.obj.config.padding);
    this.obj.element.appendChild(b);
    var f = this.obj.element.querySelector('[data-id="search-icon"]');
    var d = this.obj.element.querySelector('[data-id="search"]');
    var e = d.querySelector('[data-id="cell-1"]');
    var c = this.obj.element.getElementsByTagName("input")[0];
    var a = this.obj.element.querySelector('[data-id="container"]');
    f.addEventListener("mouseover", function() {
        e.style.display = "none";
        d.style.width = "50px";
        d.style.display = "block";
        d.style.opacity = 0;
        BALKANGraph.animate(d, {
            width: 50,
            opacity: 0
        }, {
            width: 300,
            opacity: 1
        }, 300, BALKANGraph.animate.outBack, function() {
            e.style.display = "inherit";
            e.style.opacity = 0;
            BALKANGraph.animate(e, {
                opacity: 0
            }, {
                opacity: 1
            }, 200, BALKANGraph.animate.inOutSin)
        })
    });
    d.addEventListener("mouseleave", function() {
        if (document.activeElement == c) {
            return
        }
        k.hide()
    });
    d.addEventListener("click", function() {
        c.focus()
    });
    c.addEventListener("keyup", function(l) {
        if (l.keyCode == 40) {
            g()
        } else {
            if (l.keyCode == 38) {
                h()
            } else {
                if (l.keyCode == 13) {
                    i()
                } else {
                    if (l.keyCode == 27) {
                        k.hide()
                    } else {
                        j(this.value)
                    }
                }
            }
        }
    });
    var g = function() {
        var l = d.querySelectorAll("[data-search-item-id]");
        var m = d.querySelector('[data-selected="yes"]');
        if (m == null && l.length > 0) {
            l[0].setAttribute("data-selected", "yes");
            l[0].style.backgroundColor = "#F0F0F0"
        } else {
            if (l.length > 0) {
                if (m.nextSibling) {
                    m.setAttribute("data-selected", "no");
                    m.style.backgroundColor = "inherit";
                    m.nextSibling.setAttribute("data-selected", "yes");
                    m.nextSibling.style.backgroundColor = "#F0F0F0"
                }
            }
        }
    };
    var h = function() {
        var l = d.querySelectorAll("[data-search-item-id]");
        var m = d.querySelector('[data-selected="yes"]');
        if (m == null && l.length > 0) {
            l[l.length - 1].setAttribute("data-selected", "yes");
            l[l.length - 1].style.backgroundColor = "#F0F0F0"
        } else {
            if (l.length > 0) {
                if (m.previousSibling) {
                    m.setAttribute("data-selected", "no");
                    m.style.backgroundColor = "inherit";
                    m.previousSibling.setAttribute("data-selected", "yes");
                    m.previousSibling.style.backgroundColor = "#F0F0F0"
                }
            }
        }
    };
    var i = function() {
        var m = d.querySelector('[data-selected="yes"]');
        var l = m.getAttribute("data-search-item-id");
        k.obj.center(l)
    };
    var j = function(r) {
        var q = k.obj.server.search(r, k.obj.config);
        var l = "";
        for (var m = 0; m < q.length; m++) {
            var n = q[m];
            var p = '<svg style="padding: 2px 0px  2px 7px;" preserveAspectRatio="xMaxYMax meet" width="32" height="32"  viewBox="0,0,' + n.node.w + "," + n.node.h + '">' + k.obj.ui.node(n.node, [], k.obj.config, 0, 0, {}) + "</svg>";
            l += OrgChart.searchUI.createItem(p, n)
        }
        a.innerHTML = l;
        var o = d.querySelectorAll("[data-search-item-id]");
        for (var m = 0; m < o.length; m++) {
            o[m].addEventListener("click", function() {
                k.obj.center(this.getAttribute("data-search-item-id"))
            });
            o[m].addEventListener("mouseover", function() {
                this.setAttribute("data-selected", "yes");
                this.style.backgroundColor = "#F0F0F0"
            });
            o[m].addEventListener("mouseleave", function() {
                this.style.backgroundColor = "inherit";
                this.setAttribute("data-selected", "no")
            })
        }
    }
};
OrgChart.searchUI.createInputField = function(a) {
    return '<div data-id="search" style="display:none;border-radius: 20px 20px;padding:5px; box-shadow: #808080 0px 1px 2px 0px; font-family:Roboto-Regular, Helvetica;color:#7a7a7a;font-size:14px;border:1px solid #d7d7d7;width:300px;position:absolute;top:' + a + "px;left:" + a + 'px;background-color:#ffffff;"><div><div style="float:left;">' + BALKANGraph.icon.search(32, 32) + '</div><div data-id="cell-1" style="float:right; width:83%"><input placeholder="Search" style="font-size:14px;font-family:Roboto-Regular, Helvetica;color:#7a7a7a;width:100%;border:none;outline:none; padding-top:10px;" type="text" /></div><div style="clear:both;"></div></div><div data-id="container"></div></div>'
};
OrgChart.searchUI.createItem = function(b, a) {
    return '<div data-search-item-id="' + a.id + '" style="border-top:1px solid #d7d7d7; padding: 7px 0 7px 0;cursor:pointer;"><div style="float:left;">' + b + '</div><div style="float:right; width:83%"><div style="overflow:hidden; white-space: nowrap;text-overflow:ellipsis;text-align:left;">' + a.textId + '</div><div style="overflow:hidden; white-space: nowrap;text-overflow:ellipsis;text-align:left;">' + a.textInNode + '</div></div><div style="clear:both;"></div></div>'
};
OrgChart.searchUI.createSearchIcon = function(a) {
    return '<div data-id="search-icon" style="padding:5px; position:absolute;top:' + a + "px;left:" + a + 'px;border:1px solid transparent;"><div><div style="float:left;">' + BALKANGraph.icon.search(32, 32) + "</div></div></div>"
};
OrgChart.xScrollUI = function(b, a, e, d, c) {
    this.element = b;
    this.requestParams = e;
    this.config = a;
    this.onSetViewBoxCallback = d;
    this.onDrawCallback = c;
    this.pos = 0
};
OrgChart.xScrollUI.prototype.addListener = function(b) {
    var c = this;
    if (this.config.mouseScroolBehaviour != BALKANGraph.action.xScroll) {
        return
    }
    if (!this.bar) {
        return
    }

    function a(i, h, g) {
        var d = false;
        i.addEventListener("mousewheel", f, false);
        i.addEventListener("DOMMouseScroll", f, false);

        function f(l) {
            l.preventDefault();
            var k = l.delta || l.wheelDelta;
            if (k === undefined) {
                k = -l.detail
            }
            k = Math.max(-1, Math.min(1, k));
            c.pos += -k * h;
            var m = (parseFloat(c.innerBar.clientWidth) - parseFloat(c.bar.clientWidth));
            if (c.pos < 0) {
                c.pos = 0
            }
            if (c.pos > m) {
                c.pos = m
            }
            if (!d) {
                j()
            }
        }

        function j() {
            d = true;
            var k = (c.pos - c.bar.scrollLeft) / g;
            if (k > 0) {
                k++
            } else {
                k--
            }
            c.bar.scrollLeft += k;
            if (c.bar.scrollLeft == c.pos) {
                d = false
            } else {
                e(j)
            }
        }
        var e = function() {
            return (window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function(k) {
                setTimeout(k, 1000 / 50)
            })
        }()
    }
    a(b, 120, 12)
};
OrgChart.xScrollUI.prototype.create = function(c) {
    var b = this;
    this.bar = document.createElement("div");
    this.innerBar = document.createElement("div");
    var a = this.requestParams();
    this.innerBar.innerHTML = "&nbsp";
    Object.assign(this.bar.style, {
        position: "absolute",
        left: 0,
        bottom: 0,
        width: (c - this.config.padding) + "px",
        "overflow-x": "scroll",
        height: "20px"
    });
    this.element.appendChild(this.bar);
    this.bar.appendChild(this.innerBar);
    this.bar.addEventListener("scroll", function() {
        if (this.ignore) {
            this.ignore = false;
            return
        }
        var f = b.requestParams();
        var d = (parseFloat(b.innerBar.clientWidth) - parseFloat(b.bar.clientWidth)) / 100;
        var g = this.scrollLeft / d;
        var e = ((f.boundary.right) - (f.boundary.left)) / 100;
        f.viewBox[0] = g * e + f.boundary.left;
        b.onSetViewBoxCallback(f.viewBox);
        clearTimeout(this._at);
        this._at = setTimeout(function() {
            b.onDrawCallback()
        }, 500)
    })
};
OrgChart.xScrollUI.prototype.setPosition = function() {
    if (!this.bar) {
        return
    }
    var e = this.requestParams();
    var a = e.boundary.maxY * e.scale;
    var b = Math.abs(e.boundary.maxX - e.boundary.minX) * e.scale;
    switch (this.config.orientation) {
        case BALKANGraph.orientation.bottom:
        case BALKANGraph.orientation.bottom_left:
            a = Math.abs(e.boundary.minY * e.scale);
            break;
        case BALKANGraph.orientation.right:
        case BALKANGraph.orientation.right_top:
            b = Math.abs(e.boundary.minX * e.scale);
            break
    }
    this.innerBar.style.width = b + "px";
    var c = ((e.boundary.right) - (e.boundary.left)) / 100;
    var f = ((e.viewBox[0] - (e.boundary.left)) / Math.abs(c));
    if (f < 0) {
        f = 0
    } else {
        if (f > 100) {
            f = 100
        }
    }
    var d = (parseFloat(this.innerBar.clientWidth) - parseFloat(this.bar.clientWidth)) / 100;
    var g = f * d;
    this.bar.ignore = true;
    this.bar.scrollLeft = g;
    this.pos = this.bar.scrollLeft
};
OrgChart.yScrollUI = function(b, a, e, d, c) {
    this.element = b;
    this.requestParams = e;
    this.config = a;
    this.onSetViewBoxCallback = d;
    this.onDrawCallback = c;
    this.pos = 0
};
OrgChart.yScrollUI.prototype.addListener = function(b) {
    var c = this;
    if (this.config.mouseScroolBehaviour != BALKANGraph.action.yScroll) {
        return
    }
    if (!this.bar) {
        return
    }

    function a(i, h, g) {
        var d = false;
        i.addEventListener("mousewheel", f, false);
        i.addEventListener("DOMMouseScroll", f, false);

        function f(l) {
            l.preventDefault();
            var k = l.delta || l.wheelDelta;
            if (k === undefined) {
                k = -l.detail
            }
            k = Math.max(-1, Math.min(1, k));
            c.pos += -k * h;
            var m = (parseFloat(c.innerBar.clientHeight) - parseFloat(c.bar.clientHeight));
            if (c.pos < 0) {
                c.pos = 0
            }
            if (c.pos > m) {
                c.pos = m
            }
            if (!d) {
                j()
            }
        }

        function j() {
            d = true;
            var k = (c.pos - c.bar.scrollTop) / g;
            if (k > 0) {
                k++
            } else {
                k--
            }
            c.bar.scrollTop += k;
            if (c.bar.scrollTop == c.pos) {
                d = false
            } else {
                e(j)
            }
        }
        var e = function() {
            return (window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function(k) {
                setTimeout(k, 1000 / 50)
            })
        }()
    }
    a(b, 120, 12)
};
OrgChart.yScrollUI.prototype.create = function(a, b) {
    var c = this;
    this.bar = document.createElement("div");
    this.innerBar = document.createElement("div");
    this.innerBar.innerHTML = "&nbsp";
    Object.assign(this.bar.style, {
        position: "absolute",
        right: 0,
        bottom: 0,
        height: (a - b) + "px",
        "overflow-y": "scroll",
        width: "20px"
    });
    this.element.appendChild(this.bar);
    this.bar.appendChild(this.innerBar);
    this.bar.addEventListener("scroll", function() {
        if (this.ignore) {
            this.ignore = false;
            return
        }
        var f = c.requestParams();
        var d = (parseFloat(c.innerBar.clientHeight) - parseFloat(c.bar.clientHeight)) / 100;
        var g = this.scrollTop / d;
        var e = ((f.boundary.bottom) - (f.boundary.top)) / 100;
        f.viewBox[1] = g * e + f.boundary.top;
        c.onSetViewBoxCallback(f.viewBox);
        clearTimeout(this._at);
        this._at = setTimeout(function() {
            c.onDrawCallback()
        }, 500)
    })
};
OrgChart.yScrollUI.prototype.setPosition = function() {
    if (!this.bar) {
        return
    }
    var e = this.requestParams();
    var a = e.boundary.maxY * e.scale;
    var b = e.boundary.maxX * e.scale;
    switch (this.config.orientation) {
        case BALKANGraph.orientation.bottom:
        case BALKANGraph.orientation.bottom_left:
            a = Math.abs(e.boundary.minY * e.scale);
            break;
        case BALKANGraph.orientation.right:
        case BALKANGraph.orientation.right_top:
            b = Math.abs(e.boundary.minX * e.scale);
            break
    }
    this.innerBar.style.height = a + "px";
    var c = (e.boundary.bottom - e.boundary.top) / 100;
    var f = ((e.viewBox[1] - e.boundary.top) / Math.abs(c));
    if (f < 0) {
        f = 0
    } else {
        if (f > 100) {
            f = 100
        }
    }
    var d = (parseFloat(this.innerBar.clientHeight) - parseFloat(this.bar.clientHeight)) / 100;
    var g = f * d;
    this.bar.ignore = true;
    this.bar.scrollTop = g;
    this.pos = this.bar.scrollTop
};