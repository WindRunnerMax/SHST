// pages/grade/grade.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    show: 0,
    defaultOpt: "请选择学期"
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
      load: 1,
      url: app.globalData.url + 'funct/sw/grade' + query,
      fun: res => {
        if (res.data.MESSAGE !== "Yes") {
          app.toast("ERROR");
          return;
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
    var date = new Date();
    var year = date.getFullYear();
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
      array:yearArr
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