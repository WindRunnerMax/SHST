<template>
    <view>

        <headslot title="赞赏列表"></headslot>
        <view class="a-lmt"></view>

        <layout v-for="(item,index) in data" :key="index">
            <view class="info-con">
                <view class="left">
                    <view class="name">{{item.name}}</view>
                    <view class="time">{{item.reward_time}}</view>
                </view>
                <view class="amount" style="color: #4C98F7;">{{item.amount}}</view>
            </view>
        </layout>

    </view>
</template>

<script>
    import headslot from "@/components/headslot.vue";
    export default {
        components: {
            headslot
        },
        data: function() {
            return {
                data: []
            }
        },
        created: async function(options) {
            var res = await uni.$app.request({
                load: 2,
                throttle: true,
                url: uni.$app.data.url + "/ext/rewardlist",
            })
            if (res.data.info) {
                res.data.info.reverse();
                this.data = res.data.info;
            }
        },
        methods: {

        }
    }
</script>

<style scoped>
    .info-con {
        font-size: 13px;
        line-height: 23px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .name {
        font-size: 15px;
    }

    .time {
        font-size: 12px;
        color: #aaa;
        margin-top: 5px;
    }

    .amount {
        font-size: 17px;
        margin-right: 5px;
    }
</style>
