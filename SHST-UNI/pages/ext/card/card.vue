<template>
    <view>

        <layout title="校园卡查询">
            <view class="supple">
                <view class="info a-flex-space-between">
                    <view>姓名</view>
                    <rich-text :nodes="name"></rich-text>
                </view>
                <view class="info a-flex-space-between">
                    <view>卡号</view>
                    <rich-text :nodes="account"></rich-text>
                </view>
                <view class="info a-flex-space-between"> 
                    <view>卡余额</view>
                    <rich-text :nodes="banlance"></rich-text>
                </view>
                <view class="info a-flex-space-between">
                    <view>过渡余额</view>
                    <rich-text :nodes="balanceTemp"></rich-text>
                </view>
            </view>
        </layout>
        <layout>
            <view class="x-center">
                <view class="a-btn a-btn-blue a-btn-small" @click="todayQuery">当日流水查询</view>
                <view class="a-btn a-btn-blue a-btn-small a-lml" @click="historyQuery">历史流水查询</view>
            </view>
        </layout>
        <layout v-if="show">
            <view>
                <view class="table">
                    <view class="a-flex">
                        <view class="unit x-center y-center">时间</view>
                        <view class="unit x-center y-center">类型</view>
                        <view class="unit x-center y-center">商户</view>
                        <view class="unit x-center y-center">交易额</view>
                        <view class="unit x-center y-center">余额</view>
                        <view class="unit x-center y-center">流水号</view>
                    </view>
                    <view v-for="item in data" :key="item.time" class="a-flex">
                        <view class="unit x-center y-center">{{item.time}}</view>
                        <view class="unit x-center y-center">{{item.status}}</view>
                        <view class="unit x-center y-center">{{item.location}}</view>
                        <view class="unit x-center y-center">{{item.money}}</view>
                        <view class="unit x-center y-center">{{item.balance}}</view>
                        <view class="unit x-center y-center">{{item.serno}}</view>
                    </view>
                </view>
            </view>
        </layout>
        <layout v-if="show" title="Tips">
            <view class="tips-con">
                <view>1. 历史消费记录只显示一个月内消费的前17条记录</view>
                <view>2. 仅作参考，具体数据请于行政楼查阅</view>
            </view>
        </layout>

    </view>
</template>

<script>
    var cardLoad = true;
    export default {
        data: function() {
            return {
                name: "",
                account: "",
                banlance: "",
                balanceTemp: "",
                data: "",
                show: false
            }
        },
        created: function() {
            uni.$app.onload(async () => {
                var res = await uni.$app.request({
                    load: 2,
                    url: uni.$app.data.url + "/card/userInfo",
                })
                cardLoad = false;
                var info = res.data.info;
                var pregInfo = info.match(/<div align="left">[\S]*<\/div>/g);
                var balanceInfo = info.match(/<td class="neiwen">[\S]*<\/td>/g);
                var balance = balanceInfo[0].split("（")[0];
                var balanceTemp = balanceInfo[0].split("）")[1].split("(")[0];
                this.name = pregInfo[0];
                this.account = pregInfo[3];
                this.banlance = balance;
                this.balanceTemp = balanceTemp;
            })
        },
        methods: {
            todayQuery: async function() {
                if (cardLoad) {
                    uni.$app.toast("请稍后");
                    return void 0;
                }
                var res = await uni.$app.request({
                    load: 2,
                    url: uni.$app.data.url + "/card/today",
                })
                this.diposeCardData(res.data.info);
            },
            historyQuery: async function() {
                if (cardLoad) {
                    uni.$app.toast("请稍后");
                    return void 0;
                }
                var res = await uni.$app.request({
                    load: 2,
                    url: uni.$app.data.url + "/card/history"
                })
                this.diposeCardData(res.data.info);
            },
            diposeCardData: function(data) {
                var line = [];
                var lineData = data.match(/<tr class="listbg[2]?">[\s\S]*?<\/tr>/g);
                if (!lineData) {
                    uni.$app.toast("暂无数据");
                } else {
                    lineData.forEach((value) => {
                        var infoArr = value.match(/<td[\s\S]*?>[\s\S]*?<\/td>/g);
                        var infoObj = {};
                        infoObj.time = infoArr[0].replace(/<[\S]?td[\s\S]*?>/g, "").substring(5, 16);
                        infoObj.status = infoArr[3].replace(/<[\S]?td[\s\S]*?>/g, "");
                        infoObj.location = infoArr[4].replace(/<[\S]?td[\s\S]*?>/g, "");
                        infoObj.money = infoArr[5].replace(/<[\S]?td[\s\S]*?>/g, "");
                        infoObj.balance = infoArr[6].replace(/<[\S]?td[\s\S]*?>/g, "");
                        infoObj.serno = infoArr[7].replace(/<[\S]?td[\s\S]*?>/g, "");
                        line.push(infoObj);
                    })
                    console.log(line);
                }
                this.data = line;
                this.show = true;
            }
        }
    }
</script>

<style scoped>
    .supple {
        box-sizing: border-box;
        color: rgb(134, 134, 134);
        width: 100%;
    }

    .info {
        padding: 5px 0;
    }
    
    .table{
        border-left:1px solid #eee;
        border-top:1px solid #eee;
        width: 100%;
    }

    .unit {
        box-sizing: border-box;
        width: 30%;
        padding: 15px 5px;
        text-align: center;
        border-bottom: 1px solid #eee;
        border-right: 1px solid #eee;
        word-break: break-all;
    }
</style>
