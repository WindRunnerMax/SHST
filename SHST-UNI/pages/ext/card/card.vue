<template>
    <view>

        <layout title="校园卡查询">
            <view class="a-color-grey">
                <view class="a-pt-5 a-pb-3 a-flex-space-between">
                    <view>姓名</view>
                    <rich-text :nodes="name"></rich-text>
                </view>
                <view class="a-pt-5 a-pb-3 a-flex-space-between">
                    <view>卡号</view>
                    <rich-text :nodes="account"></rich-text>
                </view>
                <view class="a-pt-5 a-pb-3 a-flex-space-between">
                    <view>卡余额</view>
                    <rich-text :nodes="banlance"></rich-text>
                </view>
                <view class="a-pt-5 a-pb-3 a-flex-space-between">
                    <view>过渡余额</view>
                    <rich-text :nodes="balanceTemp"></rich-text>
                </view>
            </view>
            <view class="a-hr"></view>
            <view class="a-flex-space-between">
                <view ></view>
                <view>
                    <view class="a-btn a-btn-blue" @click="todayQuery">当日流水</view>
                    <view class="a-btn a-btn-blue a-lml" @click="historyQuery">历史流水</view>
                </view>
            </view>
        </layout>

        <view>
            <view v-for="item in list" :key="item.time">
                <layout>
                    <view class="y-center a-color-grey">
                        <view class="a-dot" :style="{'background': item.background}"></view>
                        <view class="y-center a-ml">{{item.location}}</view>
                        <view class="y-center a-lml">金额：{{item.money}}</view>
                    </view>
                    <view class="y-center a-lmt a-color-grey">
                        <view class="y-center a-ml-5">余额：{{item.balance}}</view>
                        <view class="y-center a-lml">{{item.status}}</view>
                    </view>
                    <view class="y-center a-lmt a-color-grey">
                        <view class="y-center a-ml-5">{{item.time}}</view>
                        <view class="y-center a-lml">流水：{{item.serno}}</view>
                    </view>
                </layout>
            </view>
        </view>

        <layout v-if="show" title="Tips">
            <view class="tips-con a-ml a-color-grey">
                <view>1. 历史消费记录只显示一个月内消费的前17条记录</view>
                <view>2. 仅作参考，具体数据请于行政楼查阅</view>
            </view>
        </layout>

    </view>
</template>

<script>
    export default {
        data: () => ({
            name: "加载中",
            account: "加载中",
            banlance: "0.00",
            balanceTemp: "0.00",
            list: "",
            show: false
        }),
        beforeCreate:function(){
            this.cardLoading = true;
        },
        created: function() {
            uni.$app.onload(async () => {
                const res = await uni.$app.request({
                    load: 2,
                    url: uni.$app.data.url + "/card/userInfo",
                })
                const info = res.data.info;
                if(!info) {
                    uni.$app.toast("加载失败，请稍后重试");
                    return void 0;
                }
                this.cardLoading = false;
                const pregInfo = info.match(/<div align="left">[\S]*<\/div>/g);
                const balanceInfo = info.match(/<td class="neiwen">[\S]*<\/td>/g);
                const balance = balanceInfo[0].split("（")[0];
                const balanceTemp = balanceInfo[0].split("）")[1].split("(")[0];
                this.name = pregInfo[0];
                this.account = pregInfo[3];
                this.banlance = balance;
                this.balanceTemp = balanceTemp;
            })
        },
        methods: {
            todayQuery: async function() {
                if (this.cardLoading) {
                    uni.$app.toast("请稍后");
                    return void 0;
                }
                const res = await uni.$app.request({
                    load: 2,
                    url: uni.$app.data.url + "/card/today",
                })
                this.diposeCardData(res.data.info);
            },
            historyQuery: async function() {
                if (this.cardLoading) {
                    uni.$app.toast("请稍后");
                    return void 0;
                }
                const res = await uni.$app.request({
                    load: 2,
                    url: uni.$app.data.url + "/card/history"
                })
                this.diposeCardData(res.data.info);
            },
            diposeCardData: function(data) {
                const line = [];
                const lineData = data.match(/<tr class="listbg[2]?">[\s\S]*?<\/tr>/g);
                if (!lineData) {
                    uni.$app.toast("暂无数据");
                } else {
                    lineData.forEach((value, index) => {
                        const infoArr = value.match(/<td[\s\S]*?>[\s\S]*?<\/td>/g);
                        const infoObj = {};
                        infoObj.time = infoArr[0].replace(/<[\s\S]*?>/g, "");
                        infoObj.status = infoArr[3].replace(/<[\s\S]*?>/g, "");
                        infoObj.location = infoArr[4].replace(/<[\s\S]*?>/g, "");
                        infoObj.money = infoArr[5].replace(/<[\s\S]*?>/g, "");
                        infoObj.balance = infoArr[6].replace(/<[\s\S]*?>/g, "");
                        infoObj.serno = infoArr[7].replace(/<[\s\S]*?>/g, "");
                        infoObj.background = uni.$app.data.colorList[index % uni.$app.data.colorN];
                        line.push(infoObj);
                    })
                    console.log(line);
                }
                this.list = line;
                this.show = true;
            }
        }
    }
</script>

<style scoped lang="scss">
</style>
