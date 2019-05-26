// pages/tips/tips.js
const app = getApp()

Page({

  /**
   * 页面的初始数据
   */
  data: {
    todayWeather: ["", "CLEAR_DAY", 0, 0, "数据获取中"],
    tomorrowWeather: ["", "CLEAR_DAY", 0, 0],
    tdatomoWeather: ["", "CLEAR_DAY", 0, 0],
    tips:""
  },
  onLoad: function(options) {
    var that = this;
    if (app.globalData.userFlag === 0){
      that.setData({
        table: [],
        tips: "游客模式"
      })
    }else{
      app.ajax({
        load: 1,
        url: app.globalData.url + 'funct/sw/table',
        fun: function(res) {
          console.log(res.data)
          if (res.data.Message === "Yes") {
            that.setData({
              table: res.data.data[new Date().getDay() - 1] ? res.data.data[new Date().getDay() - 1] : [],
              tips:"No class today"
            })
          } else {
            app.toast("ERROR");
          }
        }
      })
    }
    var ran = parseInt(Math.random() * 100000000000);
    app.ajax({
      url: "https://api.caiyunapp.com/v2/Y2FpeXVuIGFuZHJpb2QgYXBp/120.127164,36.000129/weather?lang=zh_CN&device_id=" + ran,
      fun: function(res) {
        if (res.data.status === "ok") {
          var weatherData = res.data.result.daily;
          that.setData({
            todayWeather: [weatherData.skycon[0].date, weatherData.skycon[0].value, weatherData.temperature[0].min, weatherData.temperature[0].max, res.data.result.hourly.description],
            tomorrowWeather: [weatherData.skycon[1].date, weatherData.skycon[1].value, weatherData.temperature[1].min, weatherData.temperature[1].max, ],
            tdatomoWeather: [weatherData.skycon[2].date, weatherData.skycon[2].value, weatherData.temperature[2].min, weatherData.temperature[2].max, ]
          })
        } else {
          app.toast("WEATHER ERROR");
        }
      }
    })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function() {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function() {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function() {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function() {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function() {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function() {

  }
})