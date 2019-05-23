//app.js
App({
  globalData: {
    userInfo: null,
    url: 'https://www.liyanzuisha.cn/sdust/index.php/',
    header: {
      'Cookie': '', //PHPSESSID
      'content-type': 'application/x-www-form-urlencoded'
    },
  },
  toast: function(e, time = 2000, icon = 'none') {
    wx.showToast({
      title: e,
      icon: icon,
      duration: time
    })
  },
  ajax: function(requestInfo) {
    var option = {
      load: 1,
      url: "",
      method: "GET",
      data: {},
      fun: function(res) {}
    };
    this.extend(option, requestInfo);
    if (option.load === 1) {
      wx.showNavigationBarLoading();
    } else if (option.load === 2) {
      wx.showLoading({
        title: '请求中',
      })
    }
    var that = this;
    wx.request({
      url: option.url,
      data: option.data,
      method: option.method,
      header: that.globalData.header,
      success: option.fun,
      fail: res => {
        that.toast("服务器错误");
      },
      complete: function() {
        if (option.load === 1) wx.hideNavigationBarLoading();
        else if (option.load === 2) {
          wx.hideLoading();
        }
      }
    })
  },
  extend: function() {
    var aLength = arguments.length;
    var options = arguments[0];
    var target = {};
    var copy;
    var i = 1;
    if (typeof options === "boolean" && options === true) {
      //深拷贝 (仅递归处理对象)
      for (; i < aLength; i++) {
        if ((options = arguments[i]) != null) {
          if (typeof options !== 'object') {
            return options;
          }
          for (var name in options) {
            copy = options[name];
            if (target === copy) {
              continue;
            }
            target[name] = this.extend(true, options[name]);
          }
        }
      }
    } else {
      //浅拷贝
      target = options;
      if (aLength === i) {
        target = this;
        i--;
      } //如果是只有一个参数，拓展asse功能 如果两个以上参数，将后续对象加入到第一个对象
      for (; i < aLength; i++) {
        options = arguments[i];
        for (var name in options) {
          target[name] = options[name];
        }
      }
    }
    return target;
  }
})