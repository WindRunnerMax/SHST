// pages/lib/lib.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    book:"",
    page : 1,
    show:0
  },
  bookInput(e){
    this.setData({
      book: e.detail.value
    })
  },
  query(e){
    var param = "?q=" + this.data.book;
    if(typeof(e) === "number"){
      param += "&page=" + e;
    }
    var that = this;
    app.ajax({
      load:1,
      url:app.globalData.url + "funct/lib/bookquery" + param,
      fun:res =>{
        that.setData({
          info : res.data.libInfo,
          page:res.data.page,
          pageInfo:res.data.pageInfo,
          show:1
        })
      }
    })
  },
  pre(){
    var curPage = parseInt(this.data.page);
    if (curPage<= 0) return ;
    this.query(curPage - 1);
  },
  next(){
    var curPage = parseInt(this.data.page);
    this.query(curPage + 1);
  },
  jump(e) {
    wx.navigateTo({
      url: "/pages/libDetail/libDetail?id=" + e.currentTarget.dataset.id
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