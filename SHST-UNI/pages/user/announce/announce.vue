<template>
    <view>
        <headslot title="公告"></headslot>
        <view class="a-lmt"></view>

        <layout v-for="(item,index) in data" :key="index">
            <rich-text :nodes="item.announce" class="announce"></rich-text>
        </layout>

        <!-- #ifdef MP-WEIXIN -->
        <official-account></official-account>
        <!-- #endif -->

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
            uni.setStorage({
                key: "point",
                data: uni.$app.data.point
            })
            var res = await uni.$app.request({
                load: 2,
                throttle: true,
                url: uni.$app.data.url + "/ext/announce",
            })
            if (res.data.info) {
                res.data.info.reverse();
                this.data = res.data.info;
            }
        },
        methods: {
            copy: function(str) {
                uni.setClipboardData({data: str})
            }
        }
    }
</script>

<style>
    .announce{
        line-height: 23px;
    }
</style>
