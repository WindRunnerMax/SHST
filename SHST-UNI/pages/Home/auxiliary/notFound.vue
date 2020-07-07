<template>
    <view>
        
        <view class='status'>404</view>
        <view class='status'>NOT</view>
        <view class='status'>FOUND</view>
        <navigator url="/pages/Home/tips/tips" open-type="reLaunch" class='index' style='padding: 5px 0;' hover-class="none">返回首页</navigator>
        
    </view>
</template>

<script>
    export default {
        data() {
            return {
                
            }
        },
        methods: {
            
        }
    }
</script>

<style>
    page{
        background: #FFFFFF;
    }
    .status{
      text-align: center;
      margin-top: 10px;
      font-size: 80px;
      color: #aaa;
    }
    
    .index{
      margin-top:20px;
      font-size: 15px;
      color: #aaa;
      text-align: center;
    }
</style>
