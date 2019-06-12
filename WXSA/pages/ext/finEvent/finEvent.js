// pages/event/event.js
const app = getApp()
const md5 = require('../../../vector/md5.js');
const colorList = app.globalData.colorList;
const colorN = app.globalData.colorN;

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
  else if (diff < 0) diff = "已过" + Math.abs(diff);
  else diff = "距今" + Math.abs(diff);
  return [diff, color];
}

Page({

  /**
   * 页面的初始数据
   */
  data: {
    addContent: "",
    dataDo: getNowFormatDate(1), //默认起始时间  
    dataEnd: getNowFormatDate(2), //默认结束时间 
    todoList: [],
    clickFlag: 1,
    tips: "",
    count: 0
  },
  addInput: function (e) {
    this.data.addContent = e.detail.value;
  },
  dateChange(e) {
    this.setData({
      dataDo: e.detail.value
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    if (app.globalData.openid === "") {
      this.setData({
        tips: "未正常获取用户信息"
      })
    } else {
      app.ajax({
        url: app.globalData.url + "funct/todo/getFinEvent",
        fun: res => {
          if (res.data.data) {
            if (res.data.data.length === 0) {
              this.setData({
                tips: "暂没有已完成事项"
              })
              return;
            }
            var curData = getNowFormatDate(1);
            res.data.data.map(function (value) {
              var diff_color = dateDiff(curData, value.todo_time, value.event_content);
              value.diff = diff_color[0];
              value.color = diff_color[1];
              return value;
            })
            console.log(res.data.data);
            that.setData({
              todoList: res.data.data,
              count: res.data.data.length
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
      content: '确定标记为未完成吗',
      success: function (choice) {
        if (choice.confirm) {
          var index = e.currentTarget.dataset.index;
          var id = e.currentTarget.dataset.id;
          app.ajax({
            url: app.globalData.url + "funct/todo/setNoFinStatus",
            method: "POST",
            data: {
              id: id
            },
            fun: res => {
              app.toast("标记成功");
              that.data.todoList.splice(index, 1);
              that.setData({
                todoList: that.data.todoList,
                tips: that.data.todoList.length === 0 ? "暂没有已完成事项" : "",
                count: that.data.count - 1
              })
            }
          })
        }
      }
    })
  },
  deleteUnit(e) {
    var that = this;
    wx.showModal({
      title: '提示',
      content: '确定删除吗',
      success: function (choice) {
        if (choice.confirm) {
          var index = e.currentTarget.dataset.index;
          var id = e.currentTarget.dataset.id;
          app.ajax({
            url: app.globalData.url + "funct/todo/deleteUnit",
            method: "POST",
            data: {
              id: id
            },
            fun: res => {
              app.toast("删除成功");
              that.data.todoList.splice(index, 1);
              that.setData({
                todoList: that.data.todoList,
                tips: that.data.todoList.length === 0 ? "暂没有已完成事项" : "",
                count: that.data.count - 1
              })
            }
          })
        }
      }
    })
  },
  jump() {
    wx.navigateTo({
      url: "/pages/finEvent/finEvent"
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