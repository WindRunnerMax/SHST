// pages/lib/lib.js
const app = getApp();
const compareTimeInSameDay = (t1, t2) => {
  let d = new Date()
  let ft1 = d.setHours(t1.split(":")[0], t1.split(":")[1])
  let ft2 = d.setHours(t2.split(":")[0], t2.split(":")[1])
  return ft1 > ft2
}
Page({

  /**
   * 页面的初始数据
   */
  data: {
    book: "",
    page: 1,
    show: 0
  },
  bookInput(e) {
    this.setData({
      book: e.detail.value
    })
  },
  query(e) {
    var param = "?q=" + this.data.book;
    if (typeof(e) === "number") {
      param += "&page=" + e;
    }
    var that = this;
    app.ajax({
      load: 1,
      url: app.globalData.url + "funct/lib/signalBookquery" + param,
      fun: res => {
        if (res.data.Message === "Yes") {
          var bookList = [];
          var repx = /<li onclick.*?>[\s\S]*?<\/li>/g;
          var pageInfo = res.data.info.match(/第[\S]*页/);
          try{
            res.data.info.match(repx).forEach(function (value, index, array) {
              var listObject = {};
              var infoBookFour = [];
              repx = /<em>.*<\/em>/g;
              value.match(repx).map(function (valueBook, indexBook, arrayBook) {
                valueBook = valueBook.replace("<em>", "");
                valueBook = valueBook.replace("</em>", "");
                infoBookFour.push(valueBook);
                return value;
              })
              listObject.infoList = infoBookFour;
              repx = /javascript:bookDetail(.)*;/g;
              listObject.id = value.match(repx)[0].match(/[0-9]+/)[0];
              bookList.push(listObject);
            })
          }catch(e){}
          console.log(bookList)
          that.setData({
            info: bookList,
            page: res.data.page,
            pageInfo: pageInfo[0],
            show: 1
          })
        } else {
          app.toast("响应超时");
        }

      }
    })
  },
  pre() {
    var curPage = parseInt(this.data.page);
    if (curPage <= 0) return;
    this.query(curPage - 1);
  },
  next() {
    var curPage = parseInt(this.data.page);
    this.query(curPage + 1);
  },
  jump(e) {
    if(!e.currentTarget.dataset.id){
      app.toast("出错了");
      return ;
    }
    wx.navigateTo({
      url: "/pages/Lib/libDetail/libDetail?id=" + e.currentTarget.dataset.id
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var curData = new Date();
    var startTime = "7:00";
    var endTime = "22:30";
    var curTime = curData.getHours() + ":" + curData.getMinutes();
    if (compareTimeInSameDay(startTime, curTime) || compareTimeInSameDay(curTime, endTime)) {
      app.toast("当前时间图书馆服务已关闭");
      return;
    }
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