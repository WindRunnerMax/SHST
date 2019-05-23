// pages/classroom/classroom.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    show:0
  },
  flagChange(e){
    var flagIndex = parseInt(e.currentTarget.dataset.index);
    this.data.flag[flagIndex] = this.data.flag[flagIndex] === 'none' ? "block" : "none";
    this.setData({
      flag: this.data.flag
    })
  },
  loadClassroom(e){
    var query = e.currentTarget.dataset.indexcurr;
    var qShow = e.currentTarget.dataset.show;
    var that = this;
    app.ajax({
      load:1,
      data:{
        query:query
      },
      url: app.globalData.url + 'funct/sw/classroom',
      fun:res=>{
        if (res.data.MESSAGE !== "Yes"){
          app.toast("ERROR");
          return ;
        }
        var data = res.data.data;
        var flagExp = [];
        data.map((index,value)=>{
          flagExp.push("none");
          return value;
        })
        console.log(data)
        that.setData({
          room:data,
          flag:flagExp,
          query:query,
          show:1,
          qShow: qShow
        })
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

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