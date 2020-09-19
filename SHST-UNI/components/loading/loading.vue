<template>
    <view @click="loadmore">
        <view class="load-con">
            <view class="line"></view>
            <view class="status">{{status}}</view>
            <view class="line"></view>
        </view>

    </view>
</template>
<script>
    export default {
        name: "loading",
        props: {
            loading: {
                type: String,
                default: ""
            }
        },
        computed: {
            status: function(){
                switch(this.loading){
                    case "loading": return "加载中";
                    case "loadmore": return "点击加载更多";
                    case "nomore": return "我也是有底线的";
                    default: return "我也是有底线的";
                }
            }
        },
        methods: {
            loadmore: function(){
                if(this.loading !== "loadmore") return void 0;
                this.$emit("click");
            }
        }
    }
</script>
<style scoped>
    .load-con{
        display: flex;
        align-items: center;
        overflow: hidden;
        justify-content: center;
    }
    .line{
        width: 100px;
        height: 1px;
        background-color: #aaa;
    }
    .status{
        color: #aaa;
        font-size: 13px;
        padding: 5px 10px;
    }
</style>
