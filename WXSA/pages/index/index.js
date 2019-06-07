//index.js
//获取应用实例
const app = getApp()
var openid = "";

Page({
  data: {
    account: "",
    password: "",
    status:""
  },
  accountInput: function(e) {
    this.setData({
      account: e.detail.value
    })
  },
  passwordInput: function(e) {
    this.setData({
      password: e.detail.value
    })
  },
  enter: function(e) {
    var that = this;
    if (this.data.account.length == 0 || this.data.password.length == 0) {
      app.toast("用户名和密码不能为空");
    } else {
      app.ajax({
        load: 2,
        url: app.globalData.url + 'funct/user/login',
        method: 'POST',
        data: {
          "account": that.data.account,
          "password": encodeURIComponent(that.data.password),
          "openid": openid
        },
        fun: function(res) {
          wx.hideLoading();
          console.log(res.data)
          if (res.data.Message == "No") {
            that.setData({
              status: res.data.info
            })
            app.toast(res.data.info);
          } else if (res.data.Message == "Yes") {
            wx.setStorage({
              key: 'flag',
              data: '1',
              success: function() {
                app.globalData.userFlag = 1;
                wx.switchTab({
                  url: '/pages/tips/tips'
                })
              }
            })
          } else {
            that.setData({
              status: "ERROR"
            })
            app.toast("请求错误");
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
      key: 'flag',
      success(res) {
        if (res.data === '1') {
          app.toast("登录中", 5000, 'loading');
        }
      }
    })
    var that = this;
    wx.login({
      success: res => {
        app.ajax({
          load: 1,
          url: app.globalData.url + 'funct/user/getOpenid',
          method: 'POST',
          data: {
            "code": res.code
          },
          fun: function(data) {
            wx.hideToast();
            if (data.data.Message === "Yes") {
              openid = data.data.openid;
              that.setData({
                tips:1
              })
              wx.setStorage({
                key: 'flag',
                data: '0'
              })
              app.globalData.header.Cookie = 'PHPSESSID=' + data.data.PHPSESSID;
            } else if (data.data.Message === "Ex") {
              openid = data.data.openid;
              app.globalData.header.Cookie = 'PHPSESSID=' + data.data.PHPSESSID;
              if (!e.status) {
                wx.setStorage({
                  key: 'flag',
                  data: '1',
                  success: function() {
                    app.globalData.userFlag = 1;
                    wx.switchTab({
                      url: '/pages/tips/tips'
                    })
                  }
                })
              }
            } else if (data.data.Message === "NoN") {
              app.toast(data.data.info);
              openid = data.data.openid;
              app.globalData.header.Cookie = 'PHPSESSID=' + data.data.PHPSESSID;
              that.setData({
                tips: 1,
                status: data.data.info
              })
            } else {
              app.toast("获取用户信息失败");
            }
          }
        })
      }
    })
  },
  viewInfo(){
    wx.switchTab({
      url: '/pages/tips/tips'
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