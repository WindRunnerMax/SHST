//logs.js
const util = require('../../utils/util.js')
const app = getApp()

Page({
  data: {
    next : '>',
    pre : '<',
    week: 0
  },
  onLoad: function (e) {
    var urlTemp = "";
    if (typeof (e) === "number") urlTemp += ("/" + e);
    var that = this;
    app.ajax({
      load: 1,
      url: app.globalData.url + 'funct/sw/signalTable' + urlTemp,
      fun: function (res) {
        res.data.data = app.tableDispose(res.data.data);
        if (res.data.Message === "Yes") {
          that.setData({
            table: res.data.data,
            week:res.data.week
          })
        } else {
          app.toast("ERROR");
        }
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
