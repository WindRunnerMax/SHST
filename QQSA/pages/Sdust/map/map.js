// pages/map/map.js
"use strict";
Page({

  /**
   * Page initial data
   */
  data: {
    longitude: 120.12487,
    latitude: 35.99940,
    speed: 0,
    accuracy: 0,
    info:"定位中",
    point: "#FFB800",
    showLongitude: 120.124870,
    showLatitude: 35.999400
  },
  //事件处理函数
  bindViewTap: function() {

  },
  onLoad: function() {
      var that = this
      // wx.getLocation({
      //   type: 'gcj02',
      //   // altitude: true, //高精度定位
      //   //定位成功，更新定位结果
      //   success: function(res) {
      //     var latitude = res.latitude
      //     var longitude = res.longitude
      //     var speed = res.speed
      //     var accuracy = res.accuracy

      //     that.setData({
      //       longitude: longitude,
      //       latitude: latitude,
      //       speed: speed,
      //       accuracy: accuracy, 
      //       info: "定位成功",
      //       point: "#009688",
      //       showLongitude: longitude.toFixed(6),
      //       showLatitude: latitude.toFixed(6)
      //     })
      //   },
      //   //定位失败回调
      //   fail: function() {
      //     that.setData({
      //       info: "定位失败",
      //       point: "#FF5722"
      //     })
      //   }
      // })
    }

    ,
  viewImg(e) {
    var current = e.currentTarget.dataset.viewimgurl;
    wx.previewImage({
      current: current,
      urls: [current]
    })
  },


  /**
   * Lifecycle function--Called when page is initially rendered
   */
  onReady: function() {

  },

  /**
   * Lifecycle function--Called when page show
   */
  onShow: function() {

  },

  /**
   * Lifecycle function--Called when page hide
   */
  onHide: function() {

  },

  /**
   * Lifecycle function--Called when page unload
   */
  onUnload: function() {

  },

  /**
   * Page event handler function--Called when user drop down
   */
  onPullDownRefresh: function() {

  },

  /**
   * Called when page reach bottom
   */
  onReachBottom: function() {

  },

  /**
   * Called when user click on the top right corner to share
   */
  onShareAppMessage: function() {

  }
})