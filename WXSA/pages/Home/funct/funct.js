// pages/funct/funct.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    adShow : 1
  },
  adError(e){
    console.log(e);
    this.setData({
      adShow : 0
    })
  },
  jump(e){
    if (!app.globalData.userFlag && e.currentTarget.dataset.checkuser === "0"){
      app.toast("游客模式下无法提供此功能");
      return ;
    }
    wx.navigateTo({
      url: e.currentTarget.dataset.jumpurl
    })
  },
  viewImg(e){
      var current = e.currentTarget.dataset.viewimgurl;
      wx.previewImage({
        current: current,
        urls: [current]
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