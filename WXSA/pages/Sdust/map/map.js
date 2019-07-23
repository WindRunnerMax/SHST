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
      wx.getLocation({
        type: 'wgs84',
        // altitude: true, //高精度定位
        //定位成功，更新定位结果
        success: function(res) {
          var latitude = res.latitude
          var longitude = res.longitude
          var speed = res.speed
          var accuracy = res.accuracy

          that.setData({
            longitude: longitude,
            latitude: latitude,
            speed: speed,
            accuracy: accuracy, 
            info: "定位成功",
            point: "#009688",
            showLongitude: longitude.toFixed(6),
            showLatitude: latitude.toFixed(6)
          })
        },
        //定位失败回调
        fail: function() {
          that.setData({
            info: "定位失败",
            point: "#FF5722"
          })
        }
      })
    }

    ,
  viewImg(e) {
    var current = e.currentTarget.dataset.viewimgurl;
    wx.previewImage({
      current: current,
      urls: [current]
    })
  }
})