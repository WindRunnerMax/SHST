"use strict";
const md5 = require('md5.js');
var app = getApp();
module.exports = {
  tableDispose: tableDispose,
  checkUpdate: checkUpdate,
  userDot: userDot,
  setCookie: setCookie,
  startLoading: startLoading,
  endLoading: endLoading
}

/**
 * startLoading
 */
function startLoading(option) {
  switch (option.load) {
    case 1:
      wx.showNavigationBarLoading();
      break;
    case 2:
      wx.showNavigationBarLoading();
      wx.setNavigationBarTitle({
        title: '加载中...'
      })
      break;
    case 3:
      wx.showLoading({
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
      wx.hideNavigationBarLoading();
      break;
    case 2:
      wx.hideNavigationBarLoading();
      wx.setNavigationBarTitle({
        title: '山科小站'
      })
      break;
    case 3:
      wx.hideLoading();
      break;
  }
}

/**
 * 统一处理课表功能
 */
function tableDispose(info, flag = 0) {
  var tableArr = [];
  const week = new Date().getDay() - 1;
  info.forEach(value => {
    if (!value) return;
    var arrInner = [];
    var day = parseInt(value.kcsj[0]) - 1;
    if (flag === 1 && day !== week) return;
    var knot = parseInt(parseInt(value.kcsj.substr(1, 2)) / 2);
    var md5Str = md5.hexMD5(value.kcmc);
    var colorSignal = app.globalData.colorList[Math.abs((md5Str[0].charCodeAt() - md5Str[3].charCodeAt())) % app.globalData.colorN];
    arrInner.push(day);
    arrInner.push(knot);
    arrInner.push(value.kcmc.split("（")[0]);
    arrInner.push(value.jsxm);
    arrInner.push(value.jsmc);
    arrInner.push(colorSignal);
    if (!tableArr[day]) tableArr[day] = [];
    tableArr[day][knot] = arrInner;
  })
  if (flag === 1) return tableArr[week];
  else return tableArr;
}

/**
 * 小程序更新
 */
function checkUpdate() {
  if (!wx.getUpdateManager) return false;
  wx.getUpdateManager().onCheckForUpdate((res) => {
    console.log("Update:" + res.hasUpdate);
    if (res.hasUpdate) { //如果有新版本
      wx.getUpdateManager().onUpdateReady(() => { //当新版本下载完成
        wx.showModal({
          title: '更新提示',
          content: '新版本已经准备好，单击确定重启应用',
          success: (res) => {
            if (res.confirm) wx.getUpdateManager().applyUpdate(); //applyUpdate 应用新版本并重启
          }
        })
      })
      wx.getUpdateManager().onUpdateFailed(() => { //当新版本下载失败
        wx.showModal({
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
function userDot() {
  if (!wx.showTabBarRedDot) return false;
  wx.getStorage({
    key: 'point',
    complete: (res) => {
      if (res.data !== app.globalData.tips) {
        wx.showTabBarRedDot({
          index: 2
        })
      }
    }
  })
}

/**
 * SetCookie
 */
function setCookie(res, option) {
  if (app.globalData.header.Cookie === "" && option.autoCookie) {
    if (res && res.header && res.header['Set-Cookie']) {
      var cookies = res.header['Set-Cookie'].split(";")[0] + ";";
      console.log("SetCookie:" + cookies);
      app.globalData.header.Cookie = cookies;
      wx.setStorageSync('cookies', app.globalData.header.Cookie);
    } else {
      console.log("Get Cookie From Cache");
      app.globalData.header.Cookie = wx.getStorageSync("cookies") || "";
    }
  }
}