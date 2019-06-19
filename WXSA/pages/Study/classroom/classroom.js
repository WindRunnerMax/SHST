// pages/classroom/classroom.js
const app = getApp();
const time = require('../../../vector/time.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    show: 0,
    searchData: time.getNowFormatDate(),
    searchTime: '0102',
    index : [0,0]
  },
  flagChange(e) {
    var flagIndex = parseInt(e.currentTarget.dataset.index);
    this.data.flag[flagIndex] = this.data.flag[flagIndex] === 'none' ? "flex" : "none";
    this.setData({
      flag: this.data.flag
    })
  },
  loadClassroom(e) {
    var that = this;
    app.ajax({
      load: 1,
      data: {
        searchData: that.data.searchData,
        searchTime: that.data.searchTime
      },
      url: app.globalData.url + 'funct/sw/classroomExt',
      fun: res => {
        if (res.data.MESSAGE !== "Yes") {
          app.toast("ERROR");
          return;
        }
        var data = res.data.data;
        var flagExp = [];
        data.map((index, value) => {
          flagExp.push("none");
          return value;
        })
        console.log(data)
        that.setData({
          room: data,
          flag: flagExp,
          show: 1,
          qShow: that.data.queryTime[that.data.index[1]][2],
          searchData: that.data.searchData
        })
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var queryData = this.getTimeArr();
    var queryTime = [
      ['12节', '0102', '12节(8:00-9:50)'],
      ['34节', '0304', '34节(10:10-12:00)'],
      ['56节', '0506', '56节(14:00-15:50)'],
      ['78节', '0708', '78节(16:00-17:50)'],
      ['9X节', '0910', '9X节(19:00-20:50)'],
      ['上午', 'am', '上午(8:00-12:00)'],
      ['下午', 'pm', '下午(14:00-17:50)'],
      ['全天', 'allday', '全天(8:00-20:50)']
    ];
    this.setData({
      queryData: queryData,
      queryTime: queryTime
    })
  },
  getTimeArr() {
    var date = new Date();
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var queryDataArr = [];
    for (var i = 0; i < 7; ++i) {
      let monthTemp = month;
      let dayTemp = day + i;
      if (monthTemp < 10) monthTemp = "0" + monthTemp;
      if (dayTemp < 10) dayTemp = "0" + dayTemp;
      queryDataArr.push(year + "-" + monthTemp + "-" + dayTemp);
    }
    return queryDataArr;
  },
  bindPickerChange(e) {
    var that = this;
    this.data.index = e.detail.value;
    this.data.searchData = that.data.queryData[e.detail.value[0]];
    this.data.searchTime = that.data.queryTime[e.detail.value[1]][1]
  },
  resetInfo(){
    this.setData({
      searchData: time.getNowFormatDate(),
      searchTime: '0102',
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