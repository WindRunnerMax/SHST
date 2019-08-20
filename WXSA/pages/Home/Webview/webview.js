// pages/Home/Webview/webview.js
Page({
  onLoad: function (options) {
    var url = decodeURIComponent(options.url);
    this.setData({
      url: url
    })
  }
})