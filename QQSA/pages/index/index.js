Page({
  data: {
    list: [
      {
        id: 'form',
        name: '科大',
        open: true,
        pages: ['嵙地图','校历','放假安排'],
        url: ['/pages/Sdust/map/map', '/pages/Sdust/canlendar/calendar', '/pages/Sdust/vacation/vacation']
      },
      {
        id: 'widget',
        name: '学习',
        open: false,
        pages: ['常用链接', '转专业相关','社团一览表'],
        url: ['/pages/Study/link/link', '/pages/Study/major/major','/pages/Study/league/league']
      },
      {
        id: 'nav',
        name: '生活',
        open: false,
        pages: ['用电相关','机房相关','宿舍相关','网络相关','餐厅相关','洗浴相关','医疗相关','来校路线'],
        url: ['/pages/Life/power/power', '/pages/Life/computer/computer', '/pages/Life/living/living', '/pages/Life/network/network', '/pages/Life/canteen/canteen', '/pages/Life/shower/shower', '/pages/Life/medical/medical', '/pages/Life/traffic/traffic']
      }
    ],
    version: getApp().globalData.version
  },
  kindToggle: function (e) {
    var id = e.currentTarget.id, list = this.data.list;
    for (var i = 0, len = list.length; i < len; ++i) {
      if (list[i].id == id) {
        list[i].open = !list[i].open
      } else {
        list[i].open = false
      }
    }
    this.setData({
      list: list
    });
  },
  onLoad(e){
    wx.setNavigationBarColor({
      frontColor: '#000000',
      backgroundColor: '#f8f8f8',
      animation: {
        duration: 200,
        timingFunc: 'easeIn'
      }
    })
  },
  onShareAppMessage: function () {

  },
  addQQGroup(){
    wx.showModal({
      title: '提示',
      content: 'QQ交流群：722942376',
      confirmText:"复制",
      success(res) {
        if (res.confirm) {
          console.log('确定');
          wx.setClipboardData({
            data: "722942376"
          })
        } else if (res.cancel) {
          console.log('取消');
        }
      }
    })
  },
  toAbout(){
    wx.navigateTo({
      url: "/pages/User/about/about"
    })
  }
});
