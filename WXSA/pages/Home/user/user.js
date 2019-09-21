"use strict";
const app = getApp()

Page({

  /**
   * 页面的初始数据
   */
  data: {
    academy: " ",
    name: " ",
    username: " ",
    point: "none"
  },
  copy(e) {
    wx.setClipboardData({
      data: e.currentTarget.dataset.copy
    })
  },
  jump(e) {
    wx.navigateTo({
      url: e.currentTarget.dataset.jumpurl
    })
  },
  jumpUpdate(e) {
    this.setData({
      point: "none"
    })
	if(wx.hideTabBarRedDot){
		wx.hideTabBarRedDot({
		  index:2
		})
	}
    wx.navigateTo({
      url: e.currentTarget.dataset.jumpurl
    })
  },
  logout(e) {
    wx.navigateTo({
      url: '/pages/Home/Login/login?status=E'
    })
  },
  onReady: function(options) {
    var that = this;
    wx.getStorage({
      key: 'point',
      complete: (res) => {
        if (res.data !== app.globalData.tips) {
          that.setData({
            point: "block"
          })
        }
      }
    })
    if (app.globalData.userFlag === 0 || app.globalData.userFlag === 2) {
      var tipsInfo = "游客";
      that.setData({
        academy: tipsInfo,
        name: tipsInfo,
        username: tipsInfo
        })
      return;
    }
    wx.getStorage({
      key: 'userInfo',
      success: res => {
        console.log("GET USERINFO FROM CACHE");
        that.setData({
          academy: res.data.academy,
          name: res.data.name,
          username: res.data.username,
        })
      },
      fail : res => {
        console.log("GET USERINFO FROM REMOTE");
        app.ajax({
          load: 1,
          url: app.globalData.url + 'funct/user/getuserinfo',
          fun: res => {
            if (res.data.info) {
              wx.setStorage({
                key: 'userInfo',
                data: res.data.info
              })
              that.setData({
                academy: res.data.info.academy,
                name: res.data.info.name,
                username: res.data.info.username,
              })
            } else {
              app.toast("服务器错误");
            }

          }
        })
      }
    })
    
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function() {

  }
})