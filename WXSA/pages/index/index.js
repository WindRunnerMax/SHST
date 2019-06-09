//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
    account: "",
    password: "",
    status:""
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
          "openid": app.globalData.openid
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
            if (data.data.openid && data.data.PHPSESSID){
              console.log(data.data.openid);
              app.globalData.openid = data.data.openid;
              app.globalData.header.Cookie = 'PHPSESSID=' + data.data.PHPSESSID;
            }
            if (data.data.Message === "Yes") {
              that.setData({
                tips:1
              })
              wx.setStorage({
                key: 'flag',
                data: '0'
              })
            } else if (data.data.Message === "Ex") {
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
              that.setData({
                tips: 1,
                status: data.data.info
              })
            } else {
              app.toast("获取用户信息失败");
            }
          }
        })
      },
      fail: () => {
        wx.showModal({
          title: '用户未授权',
          content: '用户未授权，无法正常使用小程序的功能，点击确定重新设置授权',
          showCancel: false,
          success: function (res) {
            if (res.confirm) {
              wx.openSetting({
                success: function (res) {
                  if (!res.authSetting["scope.userInfo"] || !res.authSetting["scope.userLocation"]) {
                    // that.onLoad();
                    console.log("SUCCESS");
                  }
                }
              })
            }
          }
        })
      },
      complete : () => {
        wx.hideToast();
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