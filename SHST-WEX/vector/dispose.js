"use strict";
const md5 = require('@/utils/md5.js');
const eventBus = require('@/utils/eventBus.js');
module.exports = {
	ajax: ajax,
	toast: toast,
	extend: extend,
	onLunch: onLunch,
	addIconfont: addIconfont
}

/**
 * 颜色方案
 */
// module.exports.colorList = ["#EAA78C", "#F9CD82", "#9ADEAD", "#9CB6E9", "#E49D9B", "#97D7D7", "#ABA0CA", "#9F8BEC",
//     "#ACA4D5", "#6495ED", "#7BCDA5", "#76B4EF","#E1C38F","#F6C46A","#B19ED1","#F09B98","#87CECB","#D1A495","#89D196"
// ];
module.exports.colorList = ["#FE9E9F","#FFCA62","#93BAFF","#FFA477","#D999F9","#75E1A5"];

/**
 * 拓展对象
 * 浅拷贝与深拷贝
 */
function extend() {
	var aLength = arguments.length;
	var options = arguments[0];
	var target = {};
	var copy;
	var i = 1;
	if (typeof options === "boolean" && options === true) {
		//深拷贝 (仅递归处理对象)
		for (; i < aLength; i++) {
			if ((options = arguments[i]) != null) {
				if (typeof options !== 'object') {
					return options;
				}
				for (var name in options) {
					copy = options[name];
					if (target === copy) {
						continue;
					}
					target[name] = this.extend(true, options[name]);
				}
			}
		}
	} else {
		//浅拷贝
		target = options;
		if (aLength === i) {
			target = this;
			i--;
		} //如果是只有一个参数，拓展功能 如果两个以上参数，将后续对象加入到第一个对象
		for (; i < aLength; i++) {
			options = arguments[i];
			for (var name in options) {
				target[name] = options[name];
			}
		}
	}
	return target;
}

/**
 * startLoading
 */
function startLoading(option) {
	if (!option.load) return 0;
	console.log("LOADING")
	uni.showLoading({
		title: '请求中',
		mask: true
	})
}

/**
 * endLoading
 */
function endLoading(option) {
	if (!option.load) return 0;
	uni.hideLoading();
	console.log("END LOADING")
}



/**
 * SetCookie
 */
function setCookie(res, app = getApp()) {
	if (app.globalData.header.Cookie === "") {
		if (res && res.header && res.header['Set-Cookie']) {
			var cookies = res.header['Set-Cookie'].split(";")[0] + ";";
			console.log("SetCookie:" + cookies);
			app.globalData.header.Cookie = cookies;
			uni.setStorage({
				key: "cookies",
				data: app.globalData.header.Cookie
			});
		} else {
			console.log("Get Cookie From Cache");
			app.globalData.header.Cookie = uni.getStorageSync("cookies") || "";
		}
	}
}

/**
 * 弹窗提示
 */
function toast(e, time = 2000, icon = 'none') {
	uni.showToast({
		title: e,
		icon: icon,
		duration: time
	})
}

/**
 * 延时执行
 */
function delay(e,args){
	setTimeout((args) => e.apply(this),100);
}

/**
 * Resize
 */
function resize(dom,that) {
	const result = dom.getComponentRect(that.$refs.box, option => {
		if (uni.getSystemInfoSync().windowHeight > option.size.height) that.signalPage = true;
		else that.signalPage = false;
		console.log(that.signalPage ? "SIGNAL PAGE" : "FULL PAGE")
	})
}

/**
 * NextTick
 */
function nextTick(dom,that){
	that.$nextTick(() => { resize(dom,that) })
}

/**
 * 添加字体
 */
function addIconfont(dom){
	dom.addRule('fontFace', {
		'fontFamily': 'iconfont',
		'src': "url('https://at.alicdn.com/t/font_1582902_a1btjrevzq.ttf')"
	})
}

/**
 * HTTP请求
 */
function ajax(requestInfo, app = getApp()) {
	var option = {
		load: 1,
		autoCookie: true,
		url: "",
		method: "GET",
		data: {},
		fun: () => {},
		success: () => {},
		fail: function() {
			this.completeLoad = () => {
				toast("网络错误");
			}
		},
		complete: () => {},
		completeLoad: () => {}
	};
	extend(option, requestInfo);
	startLoading(option);
	uni.request({
		url: option.url,
		data: option.data,
		method: option.method,
		header: app.globalData.header,
		success: function(res) {
			if (option.autoCookie) setCookie(res);
			try {
				option.fun(res);
				option.success(res);
			} catch (e) {
				option.completeLoad = () => {
					toast("PARSE ERROR");
				}
				console.warn(e);
			}
		},
		fail: function(res) {
			option.fail(res);
		},
		complete: function(res) {
			endLoading(option);
			try {
				option.complete(res);
			} catch (e) {
				console.warn(e);
			}
			option.completeLoad(res);
		}
	})
}

/**
 * APP启动事件
 */
function onLunch() {
	var app = this;
	app.eventBus = eventBus.getEventBus;
}
