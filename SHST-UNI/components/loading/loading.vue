<template>
    <view @click="loadmore">
        <view class="load-con">
            <view class="line" v-show="loading !=='loading'"></view>
            <view v-show="loading ==='loading'">
                <view class="loader"></view>
            </view>
            <view class="status">{{status}}</view>
            <view class="line" v-show="loading !=='loading'"></view>
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
        padding: 5px;
    }
    .loader-box{
        position: relative;
    }
    .loader{
        margin-left: 5px;
        position: relative;
        height: 12px;
        width: 12px;
        border-radius: 80px;
        border: 1px solid #aaa;
        transform-origin: 50% 50%;
        animation: loader 1s linear infinite;
    }

    .loader:after{
        content: "";
        position: absolute;
        top: -5px;
        left: 7px;
        width: 7px;
        height: 7px;
        background-color: #fff;
        border-radius: 30px;
    }

    @keyframes loader{
        0%{transform:rotate(0deg);}
        100%{transform:rotate(360deg);}
    }
</style>
