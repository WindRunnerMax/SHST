//index.js
//获取应用实例
"use strict";
const app = getApp()

Page({
  data: {
    account: "",
    password: "",
    status: "",
    hidePassword : true
  },
  accountInput: function(e) {
    this.data.account = e.detail.value;
    // this.setData({
    //   account: e.detail.value
    // })
  },
  passwordInput: function(e) {
    this.data.password = e.detail.value;
    // this.setData({
    //   password: e.detail.value
    // })
  },
  enter: function(e) {
    this.data.account = e.detail.value.account;
    this.data.password = e.detail.value.password;
    var that = this;
    if (this.data.account.length == 0 || this.data.password.length == 0) {
      app.toast("用户名和密码不能为空");
    } else {
      app.ajax({
        load: 3,
        url: app.globalData.url + 'funct/user/login',
        method: 'POST',
        data: {
          "account": that.data.account,
          "password": encodeURIComponent(that.data.password),
          "openid": app.globalData.openid
        },
        fun: function(res) {
          console.log(res.data)
          if (res.data.Message == "No") {
            that.setData({
              status: res.data.info
            })
            this.completeLoad = (res) => { app.toast(res.data.info);}
          } else if (res.data.Message == "Yes") {
            wx.setStorage({
              key: 'user',
              data: {
                "account": that.data.account,
                "password": that.data.password,
                "openid": app.globalData.openid
              },
              success: function() {
                app.globalData.userFlag = 1;
                wx.reLaunch({
                  url: '/pages/index/index'
                })
              }
            })
          } else {
            that.setData({
              status: "ERROR"
            })
            this.completeLoad = (res) => { app.toast("请求错误"); }
          }
        }
      });
    }
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function(e) {
    wx.getStorage({
      key: 'user',
      success: res => {
        if (res.data.account !== "" && res.data.password !== "") {
          this.setData({
            account: res.data.account,
            password: decodeURIComponent(res.data.password)
          })
          app.globalData.openid = res.data.openid
        }

      }
    })
  },
  switchChange(e) {
    this.setData({
      hidePassword: !e.detail.value
    })
  },
  viewInfo() {
    wx.switchTab({
      url: '/pages/index/index'
    })
  },
  getUserInfo: function(e) {
    console.log(e)
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  }
})

/**
   fail: () => {
    wx.hideToast();
    wx.showModal({
      title: '用户未授权',
      content: '用户未授权，无法正常使用小程序的功能，点击确定重新设置授权',
      showCancel: false,
      success: function(res) {
        if (res.confirm) {
          wx.openSetting({
            success: function(res) {
              if (!res.authSetting["scope.userInfo"] || !res.authSetting["scope.userLocation"]) {
                // that.onLoad();
                console.log("SUCCESS");
              }
            }
          })
        }
      }
    })
  }
 */