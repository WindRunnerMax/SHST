"use strict";

module.exports = {
	ajax,
	toast,
	extend,
	request,
	onLaunch,
	checkUpdate
}

/**
 * 颜色方案
 */

// module.exports.colorList = ["#EAA78C", "#F9CD82", "#9ADEAD", "#9CB6E9", "#E49D9B", "#97D7D7", "#ABA0CA", "#9F8BEC",
//     "#ACA4D5", "#6495ED", "#7BCDA5", "#76B4EF","#E1C38F","#F6C46A","#B19ED1","#F09B98","#87CECB","#D1A495","#89D196"
// ];
module.exports.colorList = ["#FE9E9F", "#93BAFF", "#D999F9", "#81C784", "#FFCA62", "#FFA477"];

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
	switch (option.load) {
		case 1:
			uni.showNavigationBarLoading();
			break;
		case 2:
			uni.showNavigationBarLoading();
			uni.setNavigationBarTitle({
				title: '加载中...'
			})
			break;
		case 3:
			uni.showLoading({
				title: '请求中',
				mask: true
			})
			break;
	}
}

/**
 * endLoading
 */
function endLoading(option) {
	switch (option.load) {
		case 1:
			uni.hideNavigationBarLoading();
			break;
		case 2:
			uni.hideNavigationBarLoading();
			uni.setNavigationBarTitle({
				title: '山科小站'
			})
			break;
		case 3:
			uni.hideLoading();
			break;
	}
}

/**
 * 小程序更新
 */
function checkUpdate() {
	if (!uni.getUpdateManager) return false;
	uni.getUpdateManager().onCheckForUpdate((res) => {
		console.log("Update:" + res.hasUpdate);
		if (res.hasUpdate) { //如果有新版本
			uni.getUpdateManager().onUpdateReady(() => { //当新版本下载完成
				uni.showModal({
					title: '更新提示',
					content: '新版本已经准备好，单击确定重启应用',
					success: (res) => {
						if (res.confirm) uni.getUpdateManager().applyUpdate(); //applyUpdate 应用新版本并重启
					}
				})
			})
			uni.getUpdateManager().onUpdateFailed(() => { //当新版本下载失败
				uni.showModal({
					title: '提示',
					content: '检查到有新版本，但下载失败，请检查网络设置',
					showCancel: false
				})
			})
		}
	})
}

/**
 * User显示Dot
 */
function userDot(notify, app = getApp()) {
	app.globalData.tips = notify;
	if (!uni.showTabBarRedDot) return false;
	uni.getStorage({
		key: 'point',
		complete: (res) => {
			if (res.data !== app.globalData.tips) {
				uni.showTabBarRedDot({
					index: 2
				})
			}
		}
	})
}

/**
 * SetCookie
 */
function setCookie(res, app = getApp()) {
	if (!app.globalData.header.Cookie) {
		if (res && res.header && res.header['Set-Cookie']) {
			// #ifdef MP-ALIPAY
			var cookies = res.header['Set-Cookie'][0].split(";")[0] + ";";
			// #endif
			// #ifndef MP-ALIPAY
			var cookies = res.header['Set-Cookie'].split(";")[0] + ";";
			// #endif
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
 * HTTP请求
 */
function ajax(requestInfo, app = getApp()) {
	var option = {
		load: 1,
		autoCookie: true,
		url: "",
		method: "GET",
		data: {},
		success: () => {},
		resolve: () => {},
		fail: function() { this.completeLoad = () => { toast("External Error");}},
		reject: () => {},
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
			if(res.statusCode === 200){
				try {
					option.success(res);
					option.resolve(res);
				} catch (e) {
					option.completeLoad = () => { toast("External Error");}
					console.log(e);
				}
			}else{
				option.fail(res);
				option.reject(res);
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
				console.log(e);
			}
			option.completeLoad(res);
		}
	})
}

/**
 * request promise封装
 */
function request(option) {
	return new Promise((resolve,reject) => {
		option.resolve = resolve;
		option.reject = reject;
		ajax(option);
	})
}


/**
 * APP启动事件
 */
function onLaunch() {
	const util = require('@/utils/util.js');
	const pubFct = require('@/vector/pubFct.js');
	const eventBus = require('@/utils/eventBus.js');
	var app = this;
	checkUpdate(); //小程序更新
	util.extDate(); //拓展Date原型
	app.$scope.eventBus = eventBus.getEventBus;
	var userInfo = uni.getStorageSync("user") || {};
	app.globalData.colorN = app.globalData.colorList.length;
	app.globalData.curWeek = pubFct.getCurWeek(app.globalData.curTermStart);
	uni.login({
		scopes: 'auth_base'
	}).then((data) => {
		var [err,res] = data;
		if(err) return Promise.reject(err);
		return request({
			load: 3,
			// #ifdef MP-WEIXIN
			url: app.globalData.url + 'auth/wx',
			// #endif
			// #ifdef MP-QQ
			url: app.globalData.url + 'auth/QQ',
			// #endif
			method: 'POST',
			autoCookie: false,
			data: {
				"code": res.code,
				user: JSON.stringify(userInfo)
			}
		})
	}).then((res) => {
		setCookie(res, app);
		app.globalData.curTerm = res.data.initData.curTerm;
		app.globalData.curTermStart = res.data.initData.termStart;
		app.globalData.curWeek = res.data.initData.curWeek;
		app.globalData.loginStatus = res.data.Message;
		app.globalData.initData = res.data.initData;
		if(app.globalData.initData.custom){
			let custom = app.globalData.initData.custom;
			if(custom.color_list) {
				app.globalData.colorList = JSON.parse(custom.color_list);
				app.globalData.colorN = app.globalData.colorList.length;
			}
		}
		if (res.data.Message === "Ex") app.globalData.userFlag = 1;
		else app.globalData.userFlag = 0;
		console.log("Status:" + (app.globalData.userFlag === 1 ? "User Login" : "New User"));
		if (res.data.openid) {
			userDot(res.data.initData.tips, app);
			console.log("SetOpenid:" + res.data.openid);
			app.globalData.openid = res.data.openid;
			uni.setStorageSync('openid', res.data.openid);
		} else {
			console.log("Get Openid From Cache");
			app.globalData.openid = uni.getStorageSync("openid") || "";
		}
		return Promise.resolve(res);
	}).then((res) => {
		if (res.statusCode !== 200 || !res.data.initData || !res.data.initData.curTerm)  return Promise.reject("DATA INIT FAIL");
		else app.$scope.eventBus.commit('LoginEvent', res);
	}).catch((err) => {
		console.log(err);
		uni.showModal({
			title: '警告',
			content: '数据初始化失败,点击确定重新初始化数据',
			showCancel: false,
			success: (res) => {
				if (res.confirm) onLaunch.apply(app);
			}
		})
	})
}
