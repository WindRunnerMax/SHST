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
                <view class="amount">{{item.amount}}</view>
            </view>
        </layout>
        <layout>
            <loading :loading="loading" @click="loadReward(page+1)"></loading>
        </layout>
    </view>
</template>

<script>
    import headslot from "@/components/headslot/headslot.vue";
    import loading from "@/components/loading/loading.vue";
    export default {
        components: {
            headslot, loading
        },
        data: () => ({
            page: 1,
            data: [],
            loading: "loadmore"
        }),
        created: function(options) {
            uni.$app.onload(() => this.loadReward(1));
        },
        methods: {
            loadReward: function(page){
                uni.$app.throttle(500, async () => {
                    this.loading = "loading";
                    var res = await uni.$app.request({
                        load: 2,
                        url: uni.$app.data.url + `/ext/rewardlist/${page}`,
                    })
                    this.data = this.data.concat(res.data.info);
                    this.page = this.page + 1;
                    if(res.data.info.length < 10) this.loading = "nomore";
                    else this.loading = "loadmore";
                })
            }
        }
    }
</script>

<style scoped lang="scss">
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
        color: $a-blue;
        font-size: 17px;
        margin-right: 5px;
    }
</style>
