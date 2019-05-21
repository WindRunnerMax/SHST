// pages/tips/tips.js
const app = getApp()
var toast = (e, time = 2000) => {
  wx.showToast({
    title: e,
    icon: 'none',
    duration: time
  })
}

Page({

  /**
   * 页面的初始数据
   */
  data: {
    table:[],
    todayWeather: ["", "CLEAR_DAY",0,0,""],
    tomorrowWeather: ["", "CLEAR_DAY",0,0],
    tdatomoWeather: ["", "CLEAR_DAY",0,0]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    wx.request({
      url: app.globalData.url + 'index.php/funct/sw/table',
      method: 'GET',
      header: app.globalData.header,
      success: res => {
        console.log(res.data)
        if (res.data.Message === "Yes"){
          that.setData({
            table: res.data.data[new Date().getDay()-1]
        })
        }else{
          toast("ERROR");
        }
      },
      fail: res => {
        console.log("失败");
        toast("服务器错误");
      }
    })
    var ran = Math.random() * 100000000000;
    wx.request({
      url: "https://api.caiyunapp.com/v2/Y2FpeXVuIGFuZHJpb2QgYXBp/120.127164,36.000129/weather?lang=zh_CN&device_id=" + ran,
      method: 'GET',
      header: app.globalData.header,
      success: res => {
        if (res.data.status === "ok") {
          var weatherData = res.data.result.daily;
          that.setData({
            todayWeather: [weatherData.skycon[0].date, weatherData.skycon[0].value, weatherData.temperature[0].min, weatherData.temperature[0].max,res.data.result.hourly.description],
            tomorrowWeather: [weatherData.skycon[1].date, weatherData.skycon[1].value,weatherData.temperature[1].min, weatherData.temperature[1].max,],
            tdatomoWeather: [weatherData.skycon[2].date, weatherData.skycon[2].value, weatherData.temperature[2].min, weatherData.temperature[2].max,]
          })
        } else {
          toast("WEATHER ERROR");
        }

      },
      fail: res => {
        console.log("失败");
        toast("服务器错误");
      }
    })
    
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})