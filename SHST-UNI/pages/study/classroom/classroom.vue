<template>
    <view>

        <layout title="空教室">
            <view class="top">
                <picker-view class="picker-con" indicator-style="height: 40px;" @change="bindPickerChange">
                    <picker-view-column>
                        <view v-for="(item,index) in queryData" :key="index" class="picker-item">{{item[1]}}</view>
                    </picker-view-column>
                    <picker-view-column>
                        <view v-for="(item,index) in queryTime" :key="index" class="picker-item">{{item[0]}}</view>
                    </picker-view-column>
                    <picker-view-column>
                        <view v-for="(item,index) in queryFloor" :key="index" class="picker-item">{{item[0]}}</view>
                    </picker-view-column>
                </picker-view>
                <view class="button-con">
                    <view class="a-btn search" @click="loadClassroom">搜索</view>
                </view>
            </view>
        </layout>

        <layout v-if="show" :title="qShow+'['+searchData+']'">
            <view class="floor-name">{{room[0].jxl}}</view>
            <view class="room-con">
                <view v-for="(inner,innerIndex) in room[0].jsList" :key="innerIndex">
                    <view class="unit">{{inner.jsmc}}</view>
                </view>
            </view>
        </layout>

    </view>
</template>

<script>
    import util from "@/modules/datetime";
    export default {
        data: function() {
            return {
                room: [],
                qShow: "",
                show: false,
                searchData: util.formatDate(),
                searchTime: "0102",
                searchFloor: 1,
                searchCampus: 1,
                index: [0, 0, 0],
                queryData: [],
                queryTime: [],
                queryFloor: []
            }
        },
        created: function() {
            uni.$app.onload(() => {
                var queryData = this.getTimeArr();
                var queryTime = [
                    ["12节", "0102", "12节(8:00-9:50)"],
                    ["34节", "0304", "34节(10:10-12:00)"],
                    ["56节", "0506", "56节(14:00-15:50)"],
                    ["78节", "0708", "78节(16:00-17:50)"],
                    ["9X节", "0910", "9X节(19:00-20:50)"],
                    ["上午", "am", "上午(8:00-12:00)"],
                    ["下午", "pm", "下午(14:00-17:50)"],
                    ["全天", "allday", "全天(8:00-20:50)"]
                ];
                var queryFloor = [
                    ["J1", "1", 1],
                    ["J3", "3", 1],
                    ["J5", "5", 1],
                    ["J7", "7", 1],
                    ["J14", "14", 1],
                    ["S1", "S1", 1],
                    ["济1", "0301", 3],
                    ["济2", "0302", 3],
                    ["济3", "0303", 3],
                ];
                this.queryData = queryData;
                this.queryTime = queryTime;
                this.queryFloor = queryFloor;
            })
        },
        methods: {
            loadClassroom:function (e) {
                uni.setNavigationBarTitle({title: "加载中..."})
                setTimeout(() => this.loadClassroomSetTime(e), 300);
            },
            loadClassroomSetTime: async function (e) {
                var res = await uni.$app.request({
                    load: 2,
                    throttle: true,
                    url: uni.$app.data.url + "/sw/classroom",
                    data: {
                        searchData: this.searchData,
                        searchTime: this.searchTime,
                        searchFloor: this.searchFloor,
                        searchCampus: this.searchCampus,
                    },
                })
                var data = res.data.data;
                if(!data) {
                    uni.$app.toast("加载失败，请重试");
                    return void 0;
                }
                if (res.data.data.flag) {
                    uni.$app.toast("该日期不在教学周期内");
                    return void 0;
                }
                if (!data[0]) data = [{
                    "jxl": this.searchFloor + "号楼",
                    jsList: [{jsmc: "无空教室"}]
                }];
                data[0].jsList.sort((a, b) => a.jsmc > b.jsmc ? 1 : -1);
                this.room = data;
                this.show = true;
                this.qShow = this.queryTime[this.index[1]][2];
                this.searchData = this.searchData;
            },
            getTimeArr: function() {
                var weekShow = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"];
                var date = new Date();
                var year = date.getFullYear();
                var queryDataArr = [];
                var week = new Date().getDay();
                console.log(week);
                for (var i = 0; i < 7; ++i) {
                    let monthTemp = date.getMonth() + 1;;
                    let dayTemp = date.getDate();
                    let weekTemp = week + i;
                    if (monthTemp < 10) monthTemp = "0" + monthTemp;
                    if (dayTemp < 10) dayTemp = "0" + dayTemp;
                    queryDataArr.push([year + "-" + monthTemp + "-" + dayTemp, weekShow[weekTemp % 7]]);
                    date.addDate(0, 0, 1);
                }
                return queryDataArr;
            },
            bindPickerChange: function(e) {
                this.index = e.detail.value;
                var [dataIndex, timeIndex, floorIndex] = e.detail.value;
                this.searchData = this.queryData[dataIndex][0];
                this.searchTime = this.queryTime[timeIndex][1];
                this.searchFloor = this.queryFloor[floorIndex][1];
                this.searchCampus = this.queryFloor[floorIndex][2];
            }
        }
    }
</script>

<style scoped>
    .top {
        display: flex;
        margin: 20px 0;
        text-align: center;
        justify-content: space-between;
    }

    .picker-con{
        width: 77%;
        height: 100px;
    }

    .picker-item{
        line-height: 40px;
     }

    .unit {
        display: flex;
        padding: 10px 7px;
        font-size: 13px;
        background: #eee;
        margin: 3px;
    }

    .floor-name{
        border-bottom: 1px solid #eee;
        padding: 10px 0 ;
        text-align: center;
        margin: 0 0 8px 0;
    }

    .room-con {
        display: flex;
        flex-wrap: wrap;
        align-content: center;
        align-items: center;
        justify-content: center;
    }

    .button-con {
        margin: 0;
        width: 20%;
        max-width: 78px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-sizing: content-box;
    }

    .search {
        height: auto;
        line-height: unset;
        padding: 10px 15px;
        background: #1e9fff;
        color: #fff;
        border-radius: 3px;
        word-break: break-all;
        transition: all 0.3s;
    }

</style>
