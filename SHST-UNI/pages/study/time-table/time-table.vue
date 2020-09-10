<template>
    <view>

        <layout title="查课表">
            <view class="table-top">
                <view class="week">第{{week}}周</view>
                <view class="a-flex">
                    <view class="a-btn a-btn-white a-btn-mini refresh" @click="refresh(week)">
                        <view class="operate iconfont icon-shuaxin1"></view>
                    </view>
                    <view class="a-btn a-btn-white a-btn-mini pre" style="font-size: 14px;" @click="pre(week)">
                        <view class="operate iconfont icon-arrow-lift"></view>
                    </view>
                    <view class="a-btn a-btn-white a-btn-mini next" style="font-size: 14px;" @click="next(week)">
                        <view class="operate iconfont icon-arrow-right"></view>
                    </view>
                </view>
            </view>
            <view class="a-hr timetablehr"></view>
            <view class="a-flex">
                <view v-for="item in 7" :key="item" class="week-unit">
                    <view>{{date[item].n}}</view>
                    <view :class="date[item].s">{{date[item].d ? date[item].d : "00/00"}}</view>
                </view>
            </view>
            <view class="a-hr timetablehr"></view>
            <view v-for="item in 5" :key="item">
                <view class="a-flex">
                    <view v-for="inner in 7" :key="inner" class="a-full">
                        <view v-if="table[inner] && table[inner][item]" class="timetable-hide"
                            :style="{'background':table[inner][item].background}">
                            <view v-for="(classObj,classIndex) in table[inner][item].table" :key="classIndex">
                                <view>{{classObj.className}}</view>
                                <view>{{classObj.classroom}}</view>
                                <view>{{classObj.teacher}}</view>
                                <view v-if="classIndex !== table[inner][item].table.length-1">---</view>
                            </view>
                        </view>
                        <view v-else class="timetable-hide"></view>
                    </view>
                </view>
                <view class="a-hr timetablehr"></view>
            </view>
        </layout>

<!--        <view class="a-hide" :class="{'a-show':today > '2020-03-26'}">
            <layout>
                <view class="y-center">
                    <view class="a-dot"></view>
                    <navigator url="/pages/home/auxiliary/webview?url=https%3A%2F%2Fmp.weixin.qq.com%2Fs%2F9L3kFI0jdHajnPm83jRbwA"
                        open-type="navigate" class="a-link" hover-class="none">自定义课表配色</navigator>
                </view>
            </layout>
        </view> -->

        <layout v-if="ad">
            <!-- #ifdef MP-WEIXIN -->
            <ad unit-id="adunit-ce81890e6ff0b2a7" class="adapt" @error="adError"></ad>
            <!-- #endif -->
            <!-- #ifdef MP-QQ -->
            <ad unit-id="98766bd6a7f4cc14e978058a3a365551" class="adapt" @error="adError"></ad>
            <!-- #endif -->
        </layout>

    </view>
</template>

<script>
    import {tableDispose} from "@/vector/pubFct.js";
    import { formatDate, extDate } from "@/modules/datetime.js";
    export default {
        data: function() {
            return {
                week: 1,
                ad: true,
                date: [],
                table: [],
                today: formatDate()
            }
        },
        created: function(e) {
            uni.$app.onload(() => {
                this.week = uni.$app.data.curWeek;
                this.getDate();
                this.getCache(uni.$app.data.curWeek);
            })
        },
        methods: {
            getCache: function(week) {
                var tableCache = uni.getStorageSync("table") || {};
                if (tableCache.term === uni.$app.data.curTerm && tableCache.classTable && tableCache.classTable[week]) {
                    console.log("GET TABLE FROM CACHE");
                    var showTableArr = tableDispose(tableCache.classTable[week]);
                    this.table = showTableArr;
                    this.week = week;
                    this.getDate();
                } else {
                    this.getRemoteTable(week);
                }
            },
            getRemoteTable: async function(week = null, throttle=false) {
                var urlTemp = "";
                if (typeof(week) === "number") urlTemp += ("/" + week);
                var res = await uni.$app.request({
                    load: 2,
                    throttle: throttle,
                    url: uni.$app.data.url + "/sw/table" + urlTemp,
                    data: {
                        week: uni.$app.data.curWeek,
                        term: uni.$app.data.curTerm
                    },
                })
                console.log("GET TABLE FROM REMOTE WEEK " + week);
                var showTableArr = tableDispose(res.data.data);
                this.table = showTableArr;
                this.week = res.data.week
                var tableCache = uni.getStorageSync("table") || {
                    term: uni.$app.data.curTerm,
                    classTable: []
                };
                tableCache.term = uni.$app.data.curTerm;
                tableCache.classTable[week] = res.data.data;
                uni.setStorage({
                    key: "table",
                    data: tableCache
                })
                this.getDate();
            },
            pre: function(week) {
                if (week <= 1) return;
                --week;
                this.week = week;
                this.getCache(week);
            },
            next: function(week) {
                ++week;
                this.week = week;
                this.getCache(week);
            },
            adError: function(e) {
                this.ad = false;
            },
            refresh: function(week) {
                uni.setStorageSync("table", {term: uni.$app.data.curTerm,classTable: []});
                this.getRemoteTable(Number(week), true);
            },
            getDate: function() {
                var week = this.week;
                var today = new Date();
                var curWeekDate = new Date(uni.$app.data.curTermStart);
                curWeekDate.addDate(0, 0, week * 7 - 8);
                var allWeekDay = [];
                var week = ["Mon", "Tue", "Wed", "Thur", "Fri", "Sat", "Sun"];
                for (let i = 0; i < 7; ++i) {
                    curWeekDate.addDate(0, 0, 1);
                    allWeekDay.push({
                        n: week[i],
                        d: formatDate("MM/dd", curWeekDate),
                        s: curWeekDate.getDay() === today.getDay() ? "today" : "none"
                    });
                }
                this.date = allWeekDay;
            }
        }
    }
</script>

<style scoped>
    .table-top {
        display: flex;
        padding: 5px;
        justify-content: space-between;
        height: 30px;
    }

    .week {
        align-self: center;
        margin-left: 10px;
    }

    .pre,
    .next,
    .refresh {
        height: 100%;
        margin-left: 10px;
        align-self: center;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 30px;
    }

    .timetable-hide {
        min-height: 130px;
        margin: 0 1.5px;
        /* text-align: center; */
        word-break: break-all;
        color: #fff;
        padding: 3px;
        background: #fff;
        font-size: 12px;
        border-radius: 2px;
    }

    .timetable-hide > view{
        margin-bottom: 3px;
    }

    .timetablehr {
        margin: 3px 0;
        background-color: #eee !important;
        height: 1px;
        border: none;
    }

    .week-unit {
        text-align: center;
        padding: 5px 0 1px 0;
        font-size: 10px;
        width: 100%;
    }

    .week-unit>view {
        padding: 3px 0;
        font-size: 8px;
    }

    .today {
        border-bottom: 3px solid #eee;
    }

    .operate {
        align-self: center;
    }

    .none {
        border: none;
        font-size: 8px;
    }
</style>
