<template>
    <view>
        
        <view class="tips">{{sentence}}</view>
        <view class="tips">{{content}}</view>
        <image class="sent-image" :src="url" mode="aspectFill"></image>

    </view>
</template>
<script>
    export default {
        name: "sentence",
        props: {},
        methods: {},
        data:() => ({
            url: "",
            sentence: "",
            content: ""
        }),
        created: function() {
            var that = this;
            uni.request({
                url: "https://open.iciba.com/dsapi/",
                success: function(res) {
                    that.url = res.data.picture2;
                    that.sentence = res.data.note;
                    that.content = res.data.content;
                }
            })
        }
    }
</script>
<style>
    .tips{
        margin: 6px 0 8px 3px;
    }
    .sent-image {
        width: 100%;
    }
</style>
