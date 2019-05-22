//logs.js
const util = require('../../utils/util.js')
const app = getApp()

Page({
  data: {
    next : '>',
    pre : '<',
    week: 1
  },
  onLoad: function (e) {
    wx.showNavigationBarLoading();
    var urlTemp = "";
    if (typeof (e) === "number") urlTemp += ("/" + e);
    var that = this;
    app.ajax({
      url: app.globalData.url + 'funct/sw/table' + urlTemp,
      fun: function (res) {
        console.log(res.data)
        if (res.data.Message === "Yes") {
          that.setData({
            table: res.data.data,
            week:res.data.week
          })
        } else {
          app.toast("ERROR");
        }
        wx.hideNavigationBarLoading();
      }
    })
  },
  pre(e){
    console.log(e.target.dataset.week)
    if (e.target.dataset.week <= 1) return ;
    var week = parseInt(e.currentTarget.dataset.week) - 1;
    this.onLoad(week);
  },
  next(e){
    var week = parseInt(e.currentTarget.dataset.week) + 1;
    this.onLoad(week);
  }
})
