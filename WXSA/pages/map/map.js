// pages/map/map.js
Page({

  /**
   * Page initial data
   */
  data: {
    longitude: 116.4965075,
    latitude: 40.006103,
    speed: 0,
    accuracy: 0
  },
  //事件处理函数
  bindViewTap: function() {

  },
  onLoad: function() {
      var that = this
      wx.showLoading({
        title: "定位中"
      })
      wx.getLocation({
        type: 'gcj02',
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
            accuracy: accuracy
          })
        },
        //定位失败回调
        fail: function() {
          wx.showToast({
            title: "定位失败",
            icon: "none"
          })
        },

        complete: function() {
          //隐藏定位中信息进度
          wx.hideLoading();
        },
      })
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