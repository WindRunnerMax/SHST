<template>
    <view>

        <layout title="共享课表">
            <view class="x-center a-flex-warp" v-if="data.status === 1">
                <view class="x-center" style="flex-direction: column;">
                    <input class="a-input" placeholder="对方学号" v-model="account" type="number"></input>
                    <input class="a-input a-mt"  placeholder="对方姓名" v-model="name"></input>
                    <view class="a-btn a-btn-blue" @click="req">发起请求</view>
                </view>
            </view>

            <view class="x-center a-flex-warp" v-if="data.status === 2">
                <view class="y-center">
                    <view>{{data.pair_user[0]}}</view>
                    <view class="a-ml">{{data.pair_user[1]}}</view>
                    <view class="a-btn a-btn-blue a-btn-mini" @click="cancelreq">撤销请求</view>
                </view>
            </view>

            <view class="a-hr a-mb" v-if="data.status !== 0"></view>

            <view class="x-center a-flex-warp" v-if="data.status !== 0">
                <view v-for="(item,index) in data.data" class="y-center" :key="index">
                    <view>{{item.account}}</view>
                    <view class="a-ml">{{item.name}}</view>
                    <view class="a-btn a-btn-blue a-btn-mini" @click="agree(item.id)">同意</view>
                    <view class="a-btn a-btn-blue a-btn-mini" @click="refuse(item.id)">拒绝</view>
                </view>
            </view>

            <view v-if="data.status === 0">
                <view v-for="item in 5" :key="item">
                    <view class="a-flex">
                        <view v-for="inner in 7" :key="inner" class="division a-ml">
                            <view class="table-unit-con" v-if="(data.succ.timeTable1[inner]
                                && data.succ.timeTable1[inner][item]) || (data.succ.timeTable2[inner] && data.succ.timeTable2[inner][item])">

                                <view v-if="(data.succ.timeTable2[inner] && data.succ.timeTable2[inner][item])"
                                    class="timetable-hide-bot" style="background:rgb(100, 149, 237);">
                                    <view v-for="(classObj,classIndex) in data.succ.timeTable2[inner][item].table" :key="classIndex">
                                        <view>{{classObj.className}}</view>
                                        <view>{{classObj.classroom}}</view>
                                        <view v-if="classIndex !== data.succ.timeTable2[inner][item].table.length-1">---</view>
                                    </view>
                                </view>
                                <view v-else>
                                    <view class="timetable-hide-bot"></view>
                                </view>


                                <view v-if="(data.succ.timeTable1[inner] && data.succ.timeTable1[inner][item])"
                                    class="timetable-hide-top" style="background:rgb(234, 167, 140);}}">
                                    <view v-for="(classObj,classIndex) in data.succ.timeTable1[inner][item].table" :key="classIndex">
                                        <view>{{classObj.className}}</view>
                                        <view>{{classObj.classroom}}</view>
                                        <view v-if="classIndex !== data.succ.timeTable1[inner][item].table.length-1">---</view>
                                    </view>
                                </view>
                                <view v-else>
                                    <view class="timetable-hide-top"></view>
                                </view>

                            </view>
                            <view v-else>
                                <view class="timetable-hide"></view>
                            </view>
                        </view>
                    </view>
                    <view class="a-hr a-mb a-mt"></view>
                </view>
                <view class="a-hr a-mb"></view>
                <view class="y-center">
                    <view>{{data.user}} -</view>
                    <view> {{data.succ.pair}}</view>
                    <view class="a-btn a-btn-blue a-btn-mini" @click="lifting(data.succ.id)">解除关系</view>
                </view>
            </view>
        </layout>

        <layout title="Tips" v-if="data.status === 1">
            <view class="tips-con">
                <view>1.向对方发起请求(对方必须是正常登陆过软件或者小程序才可以)，对方通过后，你们将能够在此看到自己与对方的课表</view>
            </view>
        </layout>
    </view>
</template>

<script>
    import {tableDispose} from "@/vector/pub-fct.js";
    export default {
        data: () => ({
                data: [],
                account: "",
                name: ""
        }),
        created: function() {
            uni.$app.onload(() => this.onloadData());
        },
        methods: {
            onloadData: async function(){
                var res = await uni.$app.request({
                    load: 2,
                    url: uni.$app.data.url + "/share/tableShare",
                    data: {
                        week: uni.$app.data.curWeek,
                        term: uni.$app.data.curTerm
                    },
                })
                if (res.data.info.succ) {
                    var succData = res.data.info.succ;
                    if(!succData.timetable1 || !succData.timetable2){
                        uni.$app.toast("加载失败，请重试");
                        return void 0;
                    }
                    res.data.info.succ.timeTable1 = tableDispose(succData.timetable1);
                    res.data.info.succ.timeTable2 = tableDispose(succData.timetable2);
                }
                console.log(res.data.info);
                this.data = res.data.info;
            },
            req: async function() {
                if (this.account === "" || this.name === "") {
                    uni.$app.toast("请输入完整信息");
                    return false;
                }
                var res = await uni.$app.request({
                    url: uni.$app.data.url + "/share/startReq",
                    throttle: true,
                    method: "POST",
                    data: {
                        account: this.account,
                        user: this.name
                    },
                })
                uni.$app.toast(res.data.msg);
                console.log(this.onloadData());
            },
            cancelreq: async function() {
                var res = await uni.$app.request({
                    throttle: true,
                    method: "POST",
                    url: uni.$app.data.url + "/share/cancelReq",
                })
                uni.$app.toast(res.data.msg);
                this.onloadData();
            },
            agree: async function(id) {
                var res = await uni.$app.request({
                    load: 2,
                    data: {id},
                    throttle: true,
                    method: "POST",
                    url: uni.$app.data.url + "/share/agreereq",
                })
                uni.$app.toast(res.data.msg);
                this.onloadData();
            },
            lifting: async function(id) {
                var res = await uni.$app.request({
                    load: 2,
                    data: {id},
                    throttle: true,
                    method: "POST",
                    url: uni.$app.data.url + "/share/lifting",
                })
                uni.$app.toast("成功");
                this.onloadData();
            },
            refuse: async function(id) {
                var res = await uni.$app.request({
                    load: 2,
                    data: {id},
                    throttle: true,
                    method: "POST",
                    url: uni.$app.data.url + "/share/refusereq",
                })
                uni.$app.toast(res.data.msg);
                this.onloadData();
            }
        }
    }
</script>

<style scoped>

    .a-input {
        align-self: center;
        border: 1px solid #eee;
        width: 200px;
        border-radius: 3px;
    }

    .timetable-hide {
        min-height: 230px;
        border-radius: 3px;
        margin-left: 3px;
        text-align: center;
        word-break: break-all;
        color: #fff;
        padding: 1px;
        background: #eee;
        font-size: 13px;
    }

    .table-unit-con {
        display: flex;
        flex-wrap: wrap;
        height: 100%;
    }

    .timetable-hide-top {
        min-height: 115px;
        text-align: center;
        word-break: break-all;
        color: #fff;
        padding: 1px;
        background: #eee;
        font-size: 13px;
        border-bottom: 1px solid #eee;
    }

    .timetable-hide-bot {
        min-height: 115px;
        text-align: center;
        word-break: break-all;
        color: #fff;
        padding: 1px;
        background: #eee;
        font-size: 13px;
    }

    .division{
        width:calc(100% / 7);
        background:#eee;
    }
</style>
