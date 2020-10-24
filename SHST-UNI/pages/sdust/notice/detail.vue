<template>
    <view>

        <layout title="公告详情" :top-space="true">
            <view class="x-center title a-lmb">
                <view>{{title}}</view>
            </view>
            <rich-text :nodes="info"></rich-text>
        </layout>

    </view>
</template>

<script>
    export default {
        components: {},
        data: function() {
            return {
                title: "",
                info: ""
            }
        },
        onLoad: function(option){
            uni.$app.onload(async () => {
                var res = await uni.$app.request({
                    load: 2,
                    url: uni.$app.data.url + `/notice/getdetail/${option.id}`,
                    throttle: true,
                })
                this.title = res.data.info.title;
                this.info = res.data.info.content
                    .replace(/font-size: \d+px;/g, "font-size: 13px;")
                    .replace(/width="\d+"/g, "")
                    .replace(/height="\d+"/g, "")
                    .replace(/<img/g, "<img width=\"100%\"");
            })
        },
        filters: {},
        computed: {},
        methods: {}
    }
</script>

<style scoped>
    .title{
        font-weight: bold;
    }
</style>
