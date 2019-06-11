// pages/event/event.js
const app = getApp()
const md5 = require('../../vector/md5.js');
const colorList = ["#EAA78C", "#F9CD82", "#9ADEAD", "#9CB6E9", "#E49D9B", "#97D7D7", "#ABA0CA", "#9F8BEC", "#ACA4D5", "#6495ED", "#7BCDA5", "#76B4EF"];
const colorN = colorList.length;

function getNowFormatDate(id) {
  var date = new Date();
  var hour = date.getHours();
  var minutes = date.getMinutes();
  var year = date.getFullYear();
  var month = date.getMonth() + 1;
  var day = date.getDate();
  if (id == 2) year++;
  if (month < 10) month = "0" + month;
  if (day < 10) day = "0" + day;
  return year + "-" + month + "-" + day;
}

function dateDiff(startDateString, endDateString, content) {
  var separator = "-"; //日期分隔符
  var startDates = startDateString.split(separator);
  var endDates = endDateString.split(separator);
  var startDate = new Date(startDates[0], startDates[1] - 1, startDates[2]);
  var endDate = new Date(endDates[0], endDates[1] - 1, endDates[2]);
  var color = colorList[md5.hexMD5(content)[0].charCodeAt() % colorN]
  var diff = parseInt((endDate - startDate) / 1000 / 60 / 60 / 24); //把相差的毫秒数转换为天数
  if (diff === 0) diff = "今";
  else if (diff < 0) diff = "超期" + Math.abs(diff);
  else diff = "距今" + Math.abs(diff);
  return [diff, color];
}
Page({

  /**
   * 页面的初始数据
   */
  data: {
    todayWeather: ["", "CLEAR_DAY", 0, 0, "数据获取中"],
    tomorrowWeather: ["", "CLEAR_DAY", 0, 0],
    tdatomoWeather: ["", "CLEAR_DAY", 0, 0],
    tips:"数据加载中",
    todoList: [],
    tips2: "数据加载中"
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
        url: app.globalData.url + 'funct/sw/tableToday',
        fun: function(res) {
          console.log(res.data)
          if (res.data.Message === "Yes") {
            that.setData({
              table: res.data.data ? res.data.data : [],
              tips: res.data.data ? "" : "No Class Today"
            })
          } else {
            app.toast("ERROR");
            that.setData({
              tips: "加载失败"
            })
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

    if (app.globalData.openid === "") {
      that.setData({
        tips2: "未正常获取用户信息"
      })
    } else {
      app.ajax({
        url: app.globalData.url + "funct/todo/getevent",
        fun: res => {
          if (res.data.data) {
            if (res.data.data.length === 0) {
              that.setData({
                tips2: "暂没有待办事项"
              })
              return;
            }else{
              that.setData({
                tips2: ""
              })
            }
            var curData = getNowFormatDate(1);
            res.data.data.map(function (value) {
              var diff_color = dateDiff(curData, value.todo_time, value.event_content);
              value.diff = diff_color[0];
              value.color = diff_color[1];
              return value;
            })
            res.data.data.sort((a, b) => { return a.todo_time > b.todo_time ? 1 : -1 ;});
            console.log(res.data.data);
            that.setData({
              todoList: res.data.data,
              count: res.data.data.length
            })
          }else{
            that.setData({
              tips2: "加载失败"
            })
          }
        }
      })
    }
  }, 
  setStatus(e) {
    var that = this;
    wx.showModal({
      title: '提示',
      content: '确定标记为已完成吗',
      success: function (choice) {
        if (choice.confirm) {
          var index = e.currentTarget.dataset.index;
          var id = e.currentTarget.dataset.id;
          app.ajax({
            url: app.globalData.url + "funct/todo/setStatus",
            method: "POST",
            data: {
              id: id
            },
            fun: res => {
              app.toast("标记成功");
              that.data.todoList.splice(index, 1);
              that.setData({
                todoList: that.data.todoList,
                tips2: that.data.todoList.length === 0 ? "暂没有待办事项" : "",
                count: that.data.count - 1
              })
            }
          })
        }
      }
    })
  },
  // onPullDownRefresh() {
  //   this.onLoad();
  //   wx.stopPullDownRefresh();
  // },
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