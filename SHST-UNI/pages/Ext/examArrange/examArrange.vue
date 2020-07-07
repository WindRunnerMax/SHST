<template>
    <view>

        <headslot title="考试安排"></headslot>

        <view style="margin-top: 10px;"></view>

        <layout v-if="tips">
            <view class="y-CenterCon">
                <view class="a-dot" style="background: #EEEEEE;margin-right: 6px;"></view>
                <view>{{tips}}</view>
            </view>
        </layout>
        <layout v-for="(item,index) in exam" :key="index">
            <view class="unit">
                <view class="infoCon">
                    <view class='cName'>{{item.kcmc}}</view>
                    <view style='color:#aaa;'>{{item.startTime}}-{{item.endTimeSplit}}</view>
                </view>
                <view class="infoCon">
                    <view class='cgrade'>{{item.jsmc}}</view>
                    <view style='color:#aaa;'>{{item.vksjc}}</view>
                </view>
            </view>
        </layout>

    </view>
</template>

<script>
    const app = getApp()
    import headslot from "@/components/headslot.vue"
    export default {
        components: {
            headslot
        },
        data() {
            return {
                tips: "",
                exam: []
            }
        },
        onLoad: async function(options) {
            var res = await app.request({
                load: 2,
                url: app.globalData.url + 'sw/exam',
            })
            if (res.data.MESSAGE === "Yes") {
                if (!res.data.data[0]) res.data.data = [];
                res.data.data.map((value) => {
                    if (!value) return;
                    var gap = value.ksqssj.split("~");
                    value.startTime = gap[0];
                    value.endTime = gap[1];
                    value.endTimeSplit = value.endTime.split(" ")[1];
                    return value;
                })
                console.log(res.data.data)
                this.exam = res.data.data.length !== 0 ? res.data.data : []
                this.tips = res.data.data.length !== 0 ? "" : "暂无考试信息"
            } else {
                app.toast("数据拉取失败");
            }
        },
        methods: {

        }
    }
</script>

<style>
    .unit {
        display: flex;
        justify-content: space-between;
    }

    .unit view {
        margin: 5px;
    }

    .cName {
        font-size: 15px;
    }


    .cgrade {
        font-size: 16px;
        color: #569FD1;
    }


    .infoCon {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
</style>
