<template>
    <view>

        <view class="x-center">
            <image  @click="viewImg('http://windrunner_max.gitee.io/imgpath/SHST/Static/Reward.jpg')" 
                class="reward-img" src="/static/img/Reward.jpg"></image>
        </view>

        <view class="tips-reward">
            
            <!-- #ifdef MP-WEIXIN -->
            <view>点击二维码后长按识别赞赏码</view>
            <!-- #endif -->
            <!-- #ifdef MP-QQ -->
            <view>点击保存赞赏码微信扫一扫</view>
            <!-- #endif -->
            <view>记得写上您的名字哦</view>
            <view>感谢您的支持!</view>

            <navigator url="reward-list" open-type="navigate" class="reward-list a-link" hover-class="none">赞赏记录</navigator>
        </view>

    </view>
</template>

<script>
    export default {
        data: function() {
            return {

            }
        },
        methods: {
            viewImg:function(e) {
                var current = e.currentTarget.dataset.viewimgurl;
                uni.previewImage({
                    current: current,
                    urls: [current]
                })
            }
        }
    }
</script>

<style>
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
        margin-top: 30px;
        font-size: 17px;
    }
</style>
