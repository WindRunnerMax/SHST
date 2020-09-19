<template>
    <view>

        <view>
            <headslot title="待办事项"></headslot>
            <view class="a-lmt"></view>
            <layout v-for="(item,index) in todoList" :key="item.id">
                <view class="y-center unit-todo a-flex-space-between">
                    <view>
                        <view class="y-center a-mt a-mb">
                            <view class="a-dot" :style="{'background':item.color}"></view>
                            <view>{{item.event_content}}</view>
                        </view>
                        <view class="y-center">
                            <view class="a-mt a-mb a-ml a-mr">{{item.todo_time}}</view>
                        </view>
                    </view>
                    <view class="y-center">
                        <i class="iconfont icon-banner set-status" @click="setStatus(item.id, index)"></i>
                        <i class="iconfont icon-x set-status" @click="deleteUnit(item.id, index)"></i>
                    </view>
                </view>
            </layout>
            <layout v-if="tips">
                <view class="y-center">
                    <view class="a-dot" style="background: #eee;"></view>
                    <view>{{tips}}</view>
                </view>
            </layout>
        </view>

    </view>
</template>

<script>
    import headslot from "@/components/headslot/headslot.vue";
    import {todoDateDiff} from "@/vector/pubFct.js";
    import {formatDate} from "@/modules/datetime.js";
    export default {
        components: {
            headslot
        },
        data() {
            return {
                addContent: "",
                dataDo: formatDate(), //默认起始时间  
                dataEnd: formatDate(), //默认结束时间 
                todoList: [],
                clickFlag: 1,
                tips: "",
                count: 0
            }
        },
        created: function() {
            uni.$app.onload(async ()=>{
                var endTime = new Date();
                endTime.addDate(1);
                this.dataEnd = formatDate("yyyy-MM-dd", endTime);
                if (uni.$app.data.openid === "") {
                    this.tips = "未正常获取用户信息";
                } else {
                    var res = await uni.$app.request({
                        load: 2,
                        url: uni.$app.data.url + "/todo/getFinEvent",
                    })
                    if (res.data.data) {
                        if (res.data.data.length === 0) {
                            this.tips = "暂无已完成事项"
                            return void 0;
                        }
                        var curData = formatDate();
                        res.data.data.map(function(value) {
                            [value.diff, value.color] = todoDateDiff(curData, value.todo_time, value.event_content);
                            return value;
                        })
                        console.log(res.data.data);
                        this.todoList = res.data.data
                        this.count = res.data.data.length
                    }
                }
            })
        },
        methods: {
            setStatus: async function(id, index) {
                var [err, choice] = await uni.showModal({
                    title: "提示",
                    content: "确定标记为未完成吗",
                })
                var res = await uni.$app.request({
                    url: uni.$app.data.url + "/todo/setNoFinStatus",
                    method: "POST",
                    data: {
                        id: id
                    },
                })
                uni.$app.toast("标记成功");
                this.todoList.splice(index, 1);
                this.todoList = this.todoList
                this.tips = this.todoList.length === 0 ? "暂无已完成事项" : ""
                this.count = this.count - 1
            },
            deleteUnit: async function(id, index) {
                var [err, choice] = await uni.showModal({
                    title: "提示",
                    content: "确定标记为未完成吗",
                })
                if (choice.confirm) {
                    var res = await uni.$app.request({
                        url: uni.$app.data.url + "/todo/deleteUnit",
                        method: "POST",
                        data: {
                            id: id
                        },
                    })
                    uni.$app.toast("删除成功");
                    this.todoList.splice(index, 1);
                    this.todoList = this.todoList
                    this.tips = this.todoList.length === 0 ? "暂无已完成事项" : ""
                    this.count = this.count - 1
                }
            }
        },
    }
</script>

<style scoped>
    .a-dot {
        margin: 0 3px;
    }

    .unit-todo {
        color: #555555;
    }

    .set-status {
        color: #555555;
        border: 1px solid #EEEEEE;
        padding: 7px;
        border-radius: 20px;
        margin: 0 3px;
    }
</style>
