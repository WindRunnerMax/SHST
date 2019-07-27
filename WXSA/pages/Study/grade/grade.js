// pages/grade/grade.js
"use strict";
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    show: 0,
    defaultOpt: app.globalData.curTerm,
    point: 0,
    pointN: 0,
    pointW: 0
  },
  bindPickerChange(e) {
    var that = this;
    console.log(this.data.array[e.detail.value].value);
    this.setData({
      defaultOpt: this.data.array[e.detail.value].show
    })
    var stuYear = this.data.array[e.detail.value].value;
    var query = (stuYear === "" ? "" : "/" + stuYear);
    app.ajax({
      load: 2,
      url: app.globalData.url + 'funct/sw/grade' + query,
      fun: res => {
        if (res.data.MESSAGE !== "Yes") {
          app.toast("ERROR");
          return;
        }
        if (res.data.data[0]) {
          try {
            var info = res.data.data;
            var point = 0;
            var pointN = 0;
            var pointW = 0;
            var n = 0;
            info.forEach(function(value) {
              if (value.kclbmc !== "公选") {
                n++;
                point += value.xf;
                if (value.zcj === "优") {
                  pointN += 4.5;
                  pointW += (4.5 * value.xf);
                } else if (value.zcj === "良") {
                  pointN += 3.5;
                  pointW += (3.5 * value.xf);
                } else if (value.zcj === "中") {
                  pointN += 2.5;
                  pointW += (2.5 * value.xf);
                } else if (value.zcj === "及格") {
                  pointN += 1.5;
                  pointW += (1.5 * value.xf);
                } else if (value.zcj === "不及格") {} else {
                  var s = parseInt(value.zcj);
                  if (s >= 60) {
                    pointN += ((s - 50) / 10);
                    pointW += (((s - 50) / 10) * value.xf);
                  }
                }
              }
            })
            that.setData({
              point: point,
              pointN: (pointN / n).toFixed(2),
              pointW: (pointW / point).toFixed(2)
            })
          } catch (err) {console.log(err);}
          
        }
        that.setData({
          show: 1,
          grade: !res.data.data[0] ? [] : res.data.data
        })
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    // 处理学期
    var year = parseInt(app.globalData.curTerm.split("-")[1]);
    var yearArr = [{
      show: '全部学期',
      value: ""
    }];
    for (var i = 1; i <= 4; ++i) {
      yearArr.push({
        show: (year - i) + "-" + (year - i + 1) + "-1",
        value: (year - i) + "-" + (year - i + 1) + "-1"
      })
      yearArr.push({
        show: (year - i) + "-" + (year - i + 1) + "-2",
        value: (year - i) + "-" + (year - i + 1) + "-2"
      })
    }
    this.setData({
      array: yearArr
    })
    this.initGrade();
  },
  initGrade(){
    var that = this;
    this.setData({
      defaultOpt: app.globalData.curTerm
    })
    var stuYear = app.globalData.curTerm;
    var query = (stuYear === "" ? "" : "/" + stuYear);
    app.ajax({
      load: 2,
      url: app.globalData.url + 'funct/sw/grade' + query,
      fun: res => {
        if (res.data.MESSAGE !== "Yes") {
          app.toast("ERROR");
          return;
        }
        if (res.data.data[0]) {
          try {
            var info = res.data.data;
            var point = 0;
            var pointN = 0;
            var pointW = 0;
            var n = 0;
            info.forEach(function (value) {
              if (value.kclbmc !== "公选") {
                n++;
                point += value.xf;
                if (value.zcj === "优") {
                  pointN += 4.5;
                  pointW += (4.5 * value.xf);
                } else if (value.zcj === "良") {
                  pointN += 3.5;
                  pointW += (3.5 * value.xf);
                } else if (value.zcj === "中") {
                  pointN += 2.5;
                  pointW += (2.5 * value.xf);
                } else if (value.zcj === "及格") {
                  pointN += 1.5;
                  pointW += (1.5 * value.xf);
                } else if (value.zcj === "不及格") { } else {
                  var s = parseInt(value.zcj);
                  if (s >= 60) {
                    pointN += ((s - 50) / 10);
                    pointW += (((s - 50) / 10) * value.xf);
                  }
                }
              }
            })
            that.setData({
              point: point,
              pointN: (pointN / n).toFixed(2),
              pointW: (pointW / point).toFixed(2)
            })
          } catch (err) { console.log(err); }

        }
        that.setData({
          show: 1,
          grade: !res.data.data[0] ? [] : res.data.data
        })
      }
    })
  }
})