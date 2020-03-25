<template>
  <div>

    <layout>
      <div class="x-CenterCon">
        <div style="display: flex;flex-direction: column;">
          <div class="x-CenterCon">
            <select v-model="v1" style="margin-left: 0;">
              <option v-for="(item,index) in queryData" :value="item[0]" :key="index">{{ item[1] }}</option>
            </select>
            <select v-model="v2">
              <option v-for="(item,index) in queryTime" :value="item[1]" :key="index">{{ item[0] }}</option>
            </select>
            <select v-model="v3" style="margin-right: 0;">
              <option v-for="(item,index) in queryFloor" :value="item[1]" :key="index">{{ item[0] }}</option>
            </select>
          </div>
          <el-button style="margin-top: 5px;width: 100%;" icon="el-icon-search" size="medium" type="primary"
            @click="getClassroom">{{btnStatus ? "查询" : "链接已超时，请重新回复获取链接"}}</el-button>
        </div>
      </div>
    </layout>

    <layout v-if="show" :title="room.jxl" >
      <div class="x-CenterCon" style="flex-wrap: wrap;">
        <div class="unitClassroom" v-for="(item,index) in room.jsList" :key="index">{{item.jsmc}}</div>
      </div>
    </layout>

  </div>
</template>
<script>
  export default {
    data() {
      return {
        queryData: [],
        queryTime: [],
        queryFloor: [],
        v1: "",
        v2: "",
        v3: "",
        room: {},
        show: 0
      }
    },
    created: function() {
      var weekShow = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"];
      var date = new Date();
      var year = date.getFullYear();
      var queryDataArr = [];
      var week = new Date().getDay();
      for (var i = 0; i < 7; ++i) {
        let monthTemp = date.getMonth() + 1;;
        let dayTemp = date.getDate();
        let weekTemp = week + i;
        if (monthTemp < 10) monthTemp = "0" + monthTemp;
        if (dayTemp < 10) dayTemp = "0" + dayTemp;
        queryDataArr.push([year + "-" + monthTemp + "-" + dayTemp, weekShow[weekTemp % 7]]);
        date.addDate(0, 0, 1);
      }
      this.queryData = queryDataArr;
      this.queryTime = [
        ['12节', '0102', '12节(8:00-9:50)'],
        ['34节', '0304', '34节(10:10-12:00)'],
        ['56节', '0506', '56节(14:00-15:50)'],
        ['78节', '0708', '78节(16:00-17:50)'],
        ['9X节', '0910', '9X节(19:00-20:50)'],
        ['上午', 'am', '上午(8:00-12:00)'],
        ['下午', 'pm', '下午(14:00-17:50)'],
        ['全天', 'allday', '全天(8:00-20:50)']
      ];
      this.queryFloor = [
        ["J1", "1"],
        ["J3", "3"],
        ["J5", "5"],
        ["J7", "7"],
        ["J14", "14"],
        ["S1", "S1"]
      ];
      this.v1 = this.queryData[0][0];
      this.v2 = this.queryTime[0][1];
      this.v3 = this.queryFloor[0][1];
    },
    computed: {
      btnStatus: function() {
        return Math.round(new Date().getTime() / 1000 - 28800) > (parseInt(this.$route.params.t) + 3600) ? false :
          true;
      }
    },
    methods: {
      getClassroom: function() {
        var that = this;
        custApp.ajax({
          url: `http://dev.touchczy.top/mp/scl/${that.v1}/${that.v2}/${that.v3}`,
          headers:{
            Refer: window.location.href
          },
          success: function(res) {
            if (res.data.MESSAGE !== "Yes") {
              custApp.toast("链接超时，请于公众号重新回复");
              return;
            }
            if (res.data.data.flag) {
              custApp.toast("未生成教学周历");
              return;
            }
            var data = res.data.data;
            if (!data[0]) data = [{
              "jxl": "青岛校区-" + v3 + "号楼",
              jsList: [{
                jsmc: "无空教室"
              }]
            }];
            data[0].jsList.sort((a, b) => {
              return a.jsmc > b.jsmc ? 1 : -1;
            });
            console.log(data[0]);
            that.room = data[0];
            that.show = 1;
          }
        })
  }

  },
  }
</script>

<style>
  select {
    padding: 5px;
    border-radius: 3px;
    margin: 10px 7px;
    min-width: 80px;
    background: #fff;
  }
  .unitClassroom{
      display: flex;
      padding: 10px;
      font-size: 13px;
      background: #eee;
      border-radius: 3px;
      margin: 3px;
    }
</style>
