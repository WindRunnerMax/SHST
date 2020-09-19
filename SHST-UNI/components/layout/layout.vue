<template>
    <view>

        <view class="lay-line" v-show="title">
            <view class="lay-left-con">
                <view class="lay-verline" :style="{background: color}"></view>
                <view class="title">{{title}}</view>
            </view>
            <view>
                <slot name="headslot"></slot>
            </view>
        </view>
        <view class="lay-card" :style="{color: computedColor}" :class="{'lay-min-top': computedTopSpace}">
            <slot></slot>
        </view>

    </view>
</template>
<script>
    export default {
        name: "layout",
        props: {
            title: {
                type: String,
            },
            color: {
                type: String,
                default: "#79B2F9"
            },
            topSpace: {
                type: null,
            },
            inheritColor: {
                type: Boolean,
                default: false
            }
        },
        computed:{
            computedColor: function(){
                return this.inheritColor ? this.color : "unset";
            },
            computedTopSpace: function(){
                return this.topSpace === null ? !!this.title : !this.topSpace;
            }
        },
        methods: {}
    }
</script>
<style>
    .lay-line {
        background-color: #FFFFFF;
        padding: 10px 5px 8px 5px;
        box-sizing: border-box;
        display: flex;
        border-bottom: 1px solid #EEEEEE;
        justify-content: space-between;
        align-items: center;
    }

    .lay-verline {
        width: 2px;
        margin: 2px 5px;
    }

    .lay-card {
        font-size: 13px;
        background-color: #FFFFFF;
        padding: 11px;
        box-sizing: border-box;
        margin-bottom: 10px;
    }

    .title{
        align-self: center;
    }

    .lay-min-top {
        padding-top: 3px;
    }

    .lay-left-con {
        display: flex;
    }
</style>
