<template>
    <view>
        <headslot title="公告"></headslot>
        <view class="a-lmt"></view>

        <layout v-for="(item,index) in data" :key="index">
            <rich-text :nodes="item.announce" class="announce"></rich-text>
        </layout>
    </view>
</template>

<script>
    import storage from "@/modules/storage.js";
    import headslot from "@/components/headslot/headslot.vue";
    export default {
        components: {
            headslot
        },
        data: () => ({
            data: []
        }),
        created: async function(options) {
            storage.setPromise("point", uni.$app.data.point);
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
        methods: {}
    }
</script>

<style>
    .announce{
        line-height: 23px;
    }
</style>
