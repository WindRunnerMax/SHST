<template>
    <view>

        <layout title="常用链接">
            <view v-for="(item,index) in data" :key="index">
                <view class="line">
                    <view>{{item.name}}：</view>
                    <view class="a-link" @tap="copy" :data-copy="item.url">{{item.url}}</view>
                </view>
            </view>
        </layout>

    </view>
</template>

<script>
    const app = getApp();
    export default {
        data() {
            return {
                data: []
            }
        },
        onLoad: async function() {
            var res = await app.request({
                load: 2,
                url: app.globalData.url + 'ext/urlshare',
            })
            this.data = res.data.url
        },
        methods: {
            copy(e) {
                uni.setClipboardData({
                    data: e.currentTarget.dataset.copy
                })
            }
        }
    }
</script>

<style>
    .line {
        display: flex;
        padding: 20px 5px;
        border-bottom: 1px solid #EEEEEE;
        flex-wrap: wrap;
    }
</style>
