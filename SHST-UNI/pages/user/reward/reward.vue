<template>
    <view>
        
        <!-- #ifdef MP-WEIXIN -->
        
        <view class="x-center">
            <image  @click="viewImg('http://dev.shst.touchczy.top/public/static/img/Reward.jpg')"
                class="reward-img" src="/static/img/Reward.jpg"></image>
        </view>

        <view class="tips-reward tips-con">

            <view>点击二维码后长按识别赞赏码</view>
            <view>记得写上您的名字哦</view>
            <view>感谢您的支持!</view>

            <navigator url="reward-list" open-type="navigate" class="reward-list" hover-class="none">赞赏记录</navigator>
        </view>
        
        <!-- #endif -->

    </view>
</template>

<script>
    export default {
        methods: {
            viewImg: function(url) {
                this.viewImage(url, [url]);
            }
        }
    }
</script>

<style lang="scss">
    page {
        padding: 0;
        background: #FFFFFF;
    }

    .reward-img {
        width: 497px;
        height: 563px;
        zoom: 0.6;
    }

    .tips-reward {
        margin-top: 20px;
        font-size: 20px;
        text-align: center;
        color: #8f8f8f;
    }

    .reward-list {
        color: $a-blue;
        margin-top: 30px;
        font-size: 17px;
        text-decoration: underline;
    }
</style>
