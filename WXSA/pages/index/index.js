//index.js
//获取应用实例
const app = getApp()
var toast = (e,time=2000) => {
  wx.showToast({
    title: e,
    icon: 'none',
    duration: time
  })
}

Page({
  data: {
    account: "",
    password: "",
    openid: ""
  },
  accountInput: function(e) {
    this.setData({
      account: e.detail.value
    })
  },
  passwordInput: function(e) {
    this.setData({
      password: e.detail.value
    })
  },
  enter: function(e) {
    var that = this;
    if (this.data.account.length == 0 || this.data.password.length == 0) {
      toast("用户名和密码不能为空");
    } else {
      // 这里修改成跳转的页面 
      wx.request({
        url: app.globalData.url + 'index.php/funct/user/login',
        data: {
          "account": that.data.account,
          "password": that.data.password,
          "openid": that.data.openid
        },
        method: 'POST',
        header: app.globalData.header,
        success: res => {
          console.log(res.data)
          if (res.data.Message == "No") {
            toast(res.data.info);            
          } else if (res.data.Message == "Yes") {
            wx.setStorage({
              key: 'flag',
              data: '1',
              success:function(){
                wx.switchTab({
                  url: '/pages/tips/tips'
                })
              }
            })
          } else {
            toast("请求错误");            
          }
        },
        fail: res => {
          console.log("失败");
            toast("服务器错误");            
        }
      })
    }
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function() {
    wx.getStorage({
      key: 'flag',
      success(res) {
        if(res.data === '1'){
          toast("登录中", 5000);          
        }
      }
    })
    var that = this;
    wx.login({
      success: res => {
        wx.request({
          url: app.globalData.url + 'index.php/funct/user/getOpenid',
          data: {
            "code": res.code
          },
          method: 'POST',
          header: app.globalData.header,
          success: data => {
            if (data.data.Message === "Yes") {
              var openid = data.data.openid;
              that.setData({
                openid:openid
              })
              app.globalData.header.Cookie = 'PHPSESSID=' + data.data.PHPSESSID;
            } else if (data.data.Message === "Ex"){
              app.globalData.header.Cookie = 'PHPSESSID=' + data.data.PHPSESSID;   
              wx.setStorage({
                key: 'flag',
                data: '1',
                success: function () {
                  wx.switchTab({
                    url: '/pages/tips/tips'
                  })
                }
              })           
            } else if (data.data.Message === "Non"){
              toast(data.data.info);            
            }else{
              toast("获取用户信息失败");
            }
          },
          fail: function(res) {
            toast("请检查网络");            
          }
        })
      }
    })
  },
  getUserInfo: function(e) {
    console.log(e)
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  }
})