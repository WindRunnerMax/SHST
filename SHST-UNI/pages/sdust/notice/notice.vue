<template>
    <view>

        <headslot title="校内公告"></headslot>
        <view class="a-lmt"></view>
        <layout v-for="(item,index) in notice" :key="item.id">
            <view class="a-flex-space-between"  @click="jump(item.id)" >
                <view class="a-full x-center intro text-ellipsis">
                    <view class="text-ellipsis">{{item.title}}</view>
                    <view class="time">{{item.create_time}}</view>
                </view>
                <view class="x-center y-center arrow">
                    <view class="iconfont icon-arrow-right"></view>
                </view>
            </view>
        </layout>
        <layout>
            <loading :loading="loading" @click="loadNext(page+1)"></loading>
        </layout>

    </view>
</template>

<script>
    export default {
        components: {},
        data: function() {
            return {
                page: 0,
                notice: [],
                loading: "loadmore"
            }
        },
        beforeCreate: function() {},
        created: function() {
            uni.$app.onload(() => this.loadNext(0));
        },
        filters: {},
        computed: {},
        methods: {
            loadNext: function(page){
                uni.$app.throttle(500, async () => {
                    this.loading = "loading";
                    var res = await uni.$app.request({
                        load: 2,
                        url: uni.$app.data.url + `/notice/getnotice/${page}`,
                    })
                    this.notice = this.notice.concat(res.data.info);
                    this.page = page;
                    if(res.data.info.length < 20) this.loading = "nomore";
                    else this.loading = "loadmore";
                })
            },
            jump: function(id) {
                uni.navigateTo({url: "detail?id=" + id})
            }
        }
    }
</script>

<style scoped>
    .intro{
        line-height: 27px;
        flex-direction: column;
    }
    .time{
        color: #aaa;
    }
    .arrow{
        width: 30px;
    }
</style>
