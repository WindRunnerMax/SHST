<template>
    <view>

        <layout :topSpace="true">
            <weather></weather>
        </layout>


        <layout :title="today">
            <view slot="headslot">
                <view class="y-center">
                    <view class="iconfont icon-shuaxin icon refresh" @click="refresh"></view>
                    <button open-type="share" class="iconfont icon-fenxiang icon btn"></button>
                </view>
            </view>
            <view class="artical-con text-ellipsis" @click="articalJump">
                <i class="iconfont icon-gonggao icon"></i>
                <rich-text class="a-link" :nodes="artical"></rich-text>
            </view>
            <navigator url="/pages/user/announce/announce" open-type="navigate" class="artical-con text-ellipsis" hover-class="none">
                <i class="iconfont icon-gonggao icon"></i>
                <text class="a-link">更多公告...</text>
            </navigator>

        </layout>


        <layout title="今日课程" >
            <view v-for="(item,index) in table" :key="index">
                <view class="unit-table" v-if="item" v-for="(classObj,classIndex) in item.table" :key="classIndex">
                    <view class="y-center a-mr a-mt">
                        <view class="a-dot" :style="{'background':classObj.background}"></view>
                        <view class="a-lmr">第{{2*(classObj.knot + 1) - 1}}{{2*(classObj.knot + 1)}}节</view>
                        <view>{{classObj.teacher}}</view>
                    </view>
                    <view class="y-center a-lmt a-mb">
                        <view class="a-ml a-lmr">{{classObj.className}}</view>
                        <view>{{classObj.classroom}}</view>
                    </view>
                </view>
            </view>
            <view class="unit-table" v-if="tips" @click="bindSW">
                <view class="y-center a-mt a-mr">
                    <view class="a-dot" style="background:#eee;"></view>
                    <view>{{tips}}</view>
                </view>
                <view class="a-lmt a-mb a-ml a-mr">{{tipsInfo}}</view>
            </view>
        </layout>

        <!-- #ifndef MP-WEIXIN -->
        <layout title="待办事项">
            <view v-for="(item,index) in todoList" :key="item.id">
                <view class="y-center unit-todo a-flex-space-between">
                    <view>
                        <view class="y-center a-mt">
                            <view class="a-dot" :style="{'background':item.color}"></view>
                            <view>{{item.event_content}}</view>
                        </view>
                        <view class="y-center a-lmt">
                            <view class="a-mb a-mt a-lmr">{{item.todo_time}}</view>
                            <view>{{item.diff}}天</view>
                        </view>
                    </view>
                    <view>
                        <i class="iconfont icon-banner set-status" @click="setStatus(item.id, index)"></i>
                    </view>
                </view>
            </view>
            <view class="unit-table" v-if="tips2">
                <view class="y-center a-mt a-mb">
                    <view class="a-dot" style="background:#eee;"></view>
                    <view>{{tips2}}</view>
                </view>
                <view class="a-lmt a-mb a-ml a-mr">快去添加一个想做的事吧</view>
            </view>
        </layout>
        <!-- #endif -->


        <layout title="每日一句" >
            <sentence></sentence>
        </layout>

    </view>
</template>

<script>
    import weather from "@/components/weather.vue";
    import sentence from "@/components/sentence.vue";
    import util from "@/modules/datetime";
    import pubFct from"@/vector/pubFct.js";
    export default {
        components: {
            weather,
            sentence
        },
        data: function() {
            return {
                today: util.formatDate("yyyy-MM-dd K"),
                table: [],
                todoList: [],
                tips: "数据加载中",
                tipsInfo: "数据加载中",
                tips2: "数据加载中",
                artical: "数据加载中"
            }
        },
        created: function() {
            uni.$app.onload(() => {
                console.log("Login EventBus Execute");
                this.getArtical();
                this.getTable();
                // #ifndef MP-WEIXIN
                this.getEvent();
                // #endif
                if (!uni.$app.data.userFlag) {
                    this.tips = "点我前去绑定教务系统账号";
                    this.tipsInfo = "绑定强智教务系统就可以使用山科小站咯";
                }
            })
        },
        methods: {
            /**
             * 课表处理
             */
            getTable: function() {
                var tableCache = uni.getStorageSync("table") || {};
                if (tableCache.term === uni.$app.data.curTerm
                    && tableCache.classTable
                    && tableCache.classTable[uni.$app.data.curWeek]) {
                    console.log("GET TABLE FROM CACHE");
                    var showTableArr = pubFct.tableDispose(tableCache.classTable[uni.$app.data.curWeek], 1);
                    this.tipsDispose(showTableArr);
                } else {
                    this.getRemoteTable();
                }
            },
            getRemoteTable: async function(load = 1, limit = false) {
                if (uni.$app.data.userFlag === 1) {
                    console.log("GET TABLE FROM REMOTE");
                    var res = await uni.$app.request({
                        load: load,
                        throttle: limit,
                        url: uni.$app.data.url + "/sw/table",
                        data: {
                            week: uni.$app.data.curWeek,
                            term: uni.$app.data.curTerm
                        }
                    })
                    if (res.data.status === 1) {
                        var showTableArr = pubFct.tableDispose(res.data.data, 1);
                        this.tipsDispose(showTableArr);
                        var tableCache = uni.getStorageSync("table") || {
                            term: uni.$app.data.curTerm,
                            classTable: []
                        };
                        tableCache.term = uni.$app.data.curTerm;
                        tableCache.classTable[uni.$app.data.curWeek] = res.data.data;
                        uni.setStorage({key: "table", data: tableCache})
                    } else {
                        uni.$app.toast("ERROR");
                        this.tips = "加载失败";
                        this.tipsInfo = "加载失败了，重新登录试一下";
                    }
                }
            },
            tipsDispose: function(info) {
                if (!uni.$app.data.userFlag) return void 0;
                this.table = info ? info : [];
                this.tips = info ? "" : "No Class Today";
                this.tipsInfo = info ? "" : "今天没有课，快去自习室学习吧";
            },
            refresh: function() {
                uni.setStorageSync("table", {term: uni.$app.data.curTerm, classTable: []});
                this.getRemoteTable(2, true);
            },

            // #ifndef MP-WEIXIN
            /**
             * 待办处理
             */
            getEvent: async function() {
                var eventDipose = (data) => { /* 部署数据 */
                    uni.setStorageSync("event", data);
                    if (data.length === 0) {
                        this.tips2 = "暂无待办事项";
                        return void 0;
                    } else {
                        this.tips2 = "";
                    }
                    var curData = util.formatDate();
                    data.map(function(value) {
                        [value.diff, value.color] = pubFct.todoDateDiff(curData, value.todo_time, value.event_content);
                        return value;
                    })
                    data.sort((a, b) => a.todo_time > b.todo_time ? 1 : -1);
                    this.todoList = data;
                }
                var eventCache = uni.getStorageSync("event") || "";
                if (eventCache !== "") {
                    console.log("GET EVENT FROM CACHE");
                    eventDipose(eventCache);
                } else {
                    if (uni.$app.data.userFlag !== 1) {
                        this.tips2 = "暂无待办事项";
                        return void 0;
                    }
                    console.log("GET EVENT FROM REMOTE");
                    var res = await uni.$app.request({
                        url: uni.$app.data.url + "/todo/getEvent",
                    })
                    if (res.data.data && res.data.data != 3) eventDipose(res.data.data);
                    else this.tips2 = "加载失败"
                }
            },
            setStatus: async function(id, index) {
                var [err,choice] = await uni.showModal({
                    title: "提示",
                    content: "确定标记为已完成吗",
                })
                if (choice.confirm) {
                    var res = await uni.$app.request({
                        url: uni.$app.data.url + "/todo/setStatus",
                        method: "POST",
                        data: {id}
                    })
                    uni.$app.toast("标记成功");
                    this.todoList.splice(index, 1);
                    uni.setStorageSync("event", this.todoList);
                    this.tips2 = this.todoList.length === 0 ? "暂没有待办事项" : "";
                }
            },

            // #endif
            getArtical: function() {
                if (uni.$app.data.initData && uni.$app.data.initData.articalName) {
                    // #ifdef MP-ALIPAY
                    this.artical = [{
                        type: "text",
                        text: uni.$app.data.initData.articalName
                    }]
                    // #endif
                    // #ifndef MP-ALIPAY
                    this.artical = uni.$app.data.initData.articalName
                    // #endif
                }
            },
            articalJump: function() {
                if (uni.$app.data.initData && uni.$app.data.initData.articleUrl) {
                    var url = encodeURIComponent(uni.$app.data.initData.articleUrl);
                    // #ifdef MP-WEIXIN
                    uni.navigateTo({url: "/pages/home/auxiliary/webview?url=" + url})
                    // #endif
                    // #ifndef MP-WEIXIN
                    uni.setClipboardData({data: uni.$app.data.initData.articleUrl})
                    // #endif
                }
            },
            bindSW: function() {
                if (uni.$app.data.userFlag === 0) uni.navigateTo({url: "/pages/home/auxiliary/login"})
            },
            onShareAppMessage: function() {}
        }
    }
</script>

<style scoped>
    .artical-con {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #EEEEEE;
    }

    .icon {
        padding: 0 5px;
        align-self: flex-end;
        color: #aaa;
        margin-right: 5px;
    }

    .unit-todo,.unit-table {
        border-bottom: 1px solid #EEEEEE;
        padding: 5px;
        color: #555555;
    }

    .refresh {
        font-size: 15px;
        padding-bottom: 1px;
        padding-right: 4px;
    }

    .set-status {
        color: #555555;
        border: 1px solid #EEEEEE;
        padding: 7px;
        border-radius: 20px;
    }
</style>
