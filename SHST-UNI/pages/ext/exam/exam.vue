<template>
    <view>

        <headslot title="考试安排"></headslot>

        <view class="gap"></view>

        <layout v-if="tips">
            <view class="y-center">
                <view class="a-dot" style="background: #eee;"></view>
                <view>{{tips}}</view>
            </view>
        </layout>
        <layout v-for="item in exam" :key="item.kcmc">
            <view class="unit">
                <view class="x-center y-center">
                    <view class="c-name">{{item.kcmc}}</view>
                    <view style="color:#aaa;">{{item.startTime}}-{{item.endTimeSplit}}</view>
                </view>
                <view class="x-center y-center">
                    <view class="c-grade">{{item.jsmc}}</view>
                    <view style="color:#aaa;">{{item.vksjc}}</view>
                </view>
            </view>
        </layout>

    </view>
</template>

<script>
    import headslot from "@/components/headslot/headslot.vue";
    export default {
        components: { headslot },
        data: () => ({
            tips: "",
            exam: []
        }),
        created: function() {
            uni.$app.onload(async ()=>{
                var res = await uni.$app.request({
                    load: 2,
                    url: uni.$app.data.url + "/sw/exam",
                })
                if (!res.data.data[0]) res.data.data = [];
                res.data.data.map((value) => {
                    if (!value) return;
                    [value.startTime, value.endTime] = value.ksqssj.split("~");
                    value.endTimeSplit = value.endTime.split(" ")[1];
                    return value;
                })
                console.log(res.data.data);
                this.exam = res.data.data.length !== 0 ? res.data.data : [];
                this.tips = res.data.data.length !== 0 ? "" : "暂无考试信息";
            })

        },
        methods: {

        }
    }
</script>

<style scoped>
    .unit {
        display: flex;
        justify-content: space-between;
    }

    .unit view {
        margin: 5px;
    }

    .c-name {
        font-size: 15px;
    }

    .gap{
        height: 10px;
    }

    .c-grade {
        font-size: 16px;
        color: #569FD1;
    }

</style>
