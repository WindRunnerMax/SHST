<template functional>
    <view>
        
        <view class="status">404</view>
        <view class="status">NOT</view>
        <view class="status">FOUND</view>
        <navigator url="/pages/home/tips/tips" open-type="reLaunch" class="index a-pt a-pb" hover-class="none">返回首页</navigator>
        
    </view>
</template>

<script>
    export default {}
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
