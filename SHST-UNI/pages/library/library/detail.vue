<template>
    <view>

        <layout title="图书信息">
            <view class="tips-con">
                <view class="strong">{{data.name}}</view>
                <view>{{data.bookInfoArray[0]}}</view>
                <view>{{data.bookInfoArray[1]}}</view>
                <view>{{data.bookInfoArray[2]}}</view>
            </view>

        </layout>

        <layout title="馆藏信息">
            <view class="tips-con">
                <view v-for="(item,index) in data.bookStroageArray" :key="index">
                    <view v-if="index % 4 === 0 && index !== 0" class="split-blank"></view>
                    <view>{{item}}</view>
                </view>
            </view>
        </layout>

    </view>
</template>

<script>
    import {formatDate} from "@/modules/datetime";
    import {regMatch} from "@/modules/regex";
    export default {
        data: () => ({
            data: {
                bookInfoArray: []
            }
        }),
        onLoad: async function(option) {
            uni.$app.onload(async ()=>{
                var res = await uni.$app.request({
                    load: 2,
                    url: uni.$app.data.url + "/lib/detail",
                    throttle: true,
                    data: {
                        id: option.id
                    },
                })
                var bookInfo = {};
                bookInfo.name = regMatch(/<h2>(.*?)<\/h2>/g,res.data.info)[0];
                var bookInfoArray = [];
                regMatch(/<tr><td>([\S]*?)<\/?td><\/tr>/g,res.data.info).forEach(value => bookInfoArray.push(value));
                var liBookInfo = regMatch(/<ul data-role="listview">[\s\S]*?<\/ul>/g,res.data.info)[0];
                var bookStroageArray = regMatch(/<p.*?>(.*?)<\/p>/g,liBookInfo);
                bookInfo.bookInfoArray = bookInfoArray;
                bookInfo.bookStroageArray = bookStroageArray;
                this.data = bookInfo;
            })
        },
        methods: {

        }
    }
</script>

<style scoped> 
    .strong {
        font-size: 23px;
        line-height: 30px;
        margin-top: 10px;
    }
    
    .split-blank{
        width:100%;
        height:20px;
    }
</style>
